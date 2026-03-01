<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamQuestion;
use App\Models\ExamAnswer;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    /**
     * Display a listing of available exams.
     */
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        $query = Exam::query()
            ->where('is_published', true)
            ->where('end_at', '>', now())
            ->with(['subject', 'grade']);
        
        // Filter by subject
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'available':
                    $query->where('start_at', '<=', now())
                          ->whereDoesntHave('results', function ($q) use ($student) {
                              $q->where('student_id', $student->id);
                          });
                    break;
                case 'completed':
                    $query->whereHas('results', function ($q) use ($student) {
                        $q->where('student_id', $student->id);
                    });
                    break;
                case 'expired':
                    $query->where('end_at', '<', now());
                    break;
            }
        }
        
        $exams = $query->orderBy('start_at', 'asc')
            ->paginate(12);
        
        // Add attempt status to each exam
        $exams->getCollection()->transform(function ($exam) use ($student) {
            $result = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();
            
            $exam->has_attempted = !is_null($result);
            $exam->result_id = $result ? $result->id : null;
            
            return $exam;
        });
        
        $subjects = \App\Models\Subject::all();
        
        return view('student.exams.index', compact('exams', 'subjects'));
    }

    /**
     * Show the form for taking an exam.
     */
    public function take(Exam $exam)
    {
        $student = Auth::user()->student;
        
        // Check if exam is published
        if (!$exam->is_published) {
            return redirect()->route('student.exams.index')
                ->with('error', 'هذا الاختبار غير منشور حالياً');
        }
        
        // Check if exam is available
        if ($exam->start_at > now()) {
            return redirect()->route('student.exams.index')
                ->with('error', 'هذا الاختبار لم يبدأ بعد');
        }
        
        // Check if exam has ended
        if ($exam->end_at < now()) {
            return redirect()->route('student.exams.index')
                ->with('error', 'هذا الاختبار انتهى');
        }
        
        // Check if student has already attempted the exam
        $existingResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();
        
        if ($existingResult) {
            return redirect()->route('student.exams.result', $existingResult)
                ->with('info', 'لقد قمت بهذا الاختبار بالفعل');
        }
        
        // Get exam questions
        $questionsQuery = ExamQuestion::where('exam_id', $exam->id)
            ->with(['question' => function ($q) {
                $q->with(['options', 'blanks', 'matchingPairs', 'orderingItems', 'categories', 'classificationItems', 'hotspotZones', 'dragDropItems', 'essayQuestion', 'booleanAnswer']);
            }]);
        
        // Shuffle questions if required
        if ($exam->shuffle_questions) {
            $questionsQuery->inRandomOrder();
        }
        
        $examQuestions = $questionsQuery->get();
        
        if ($examQuestions->isEmpty()) {
            return redirect()->route('student.exams.index')
                ->with('error', 'لا توجد أسئلة في هذا الاختبار');
        }
        
        // Create exam result
        $result = ExamResult::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'score' => 0,
            'percentage' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        $questions = $examQuestions->map(function ($examQuestion) {
            $question = $examQuestion->question;
            $question->question_order = $examQuestion->question_order;
            $question->points = $examQuestion->points;
            return $question;
        });
        
        return view('student.exams.take', compact('exam', 'questions', 'result'));
    }

    /**
     * Submit exam answers.
     */
    public function submit(Request $request, Exam $exam)
    {
        $student = Auth::user()->student;
        
        // Validate request
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Get exam result
            $result = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->where('status', 'in_progress')
                ->firstOrFail();
            
            // Get exam questions
            $examQuestions = ExamQuestion::where('exam_id', $exam->id)
                ->with('question')
                ->get();
            
            $totalScore = 0;
            $totalPoints = 0;
            
            foreach ($examQuestions as $examQuestion) {
                $question = $examQuestion->question;
                $questionPoints = $examQuestion->points;
                $totalPoints += $questionPoints;
                
                $answer = new ExamAnswer();
                $answer->exam_result_id = $result->id;
                $answer->question_id = $question->id;
                $answer->question_order = $examQuestion->question_order;
                
                // Process answer based on question type
                switch ($question->type) {
                    case 'multiple_choice':
                        $selectedOptionId = $validated['answers'][$question->id] ?? null;
                        $answer->selected_option_id = $selectedOptionId;
                        
                        // Auto-grade
                        if ($selectedOptionId) {
                            $selectedOption = \App\Models\QuestionOption::find($selectedOptionId);
                            if ($selectedOption && $selectedOption->is_correct) {
                                $answer->is_correct = true;
                                $answer->score = $questionPoints;
                                $totalScore += $questionPoints;
                            } else {
                                $answer->is_correct = false;
                                $answer->score = 0;
                            }
                        } else {
                            $answer->is_correct = false;
                            $answer->score = 0;
                        }
                        break;
                    
                    case 'true_false':
                        $isTrue = $validated['answers'][$question->id] ?? null;
                        $answer->is_correct = ($isTrue === 'true');
                        
                        // Auto-grade
                        $booleanAnswer = $question->booleanAnswer;
                        if ($booleanAnswer) {
                            $correctValue = $booleanAnswer->is_correct;
                            $studentValue = ($isTrue === 'true');
                            
                            if ($correctValue === $studentValue) {
                                $answer->score = $questionPoints;
                                $totalScore += $questionPoints;
                            } else {
                                $answer->score = 0;
                            }
                        } else {
                            $answer->score = 0;
                        }
                        break;
                    
                    case 'essay':
                        $answer->text_answer = $validated['answers'][$question->id] ?? null;
                        $answer->score = 0; // Will be graded manually
                        break;
                    
                    case 'fill_blanks':
                        $blanks = $validated['answers'][$question->id] ?? [];
                        $answer->text_answer = json_encode($blanks);
                        
                        // Auto-grade
                        $questionBlanks = $question->blanks;
                        $correctBlanks = 0;
                        $totalBlanks = $questionBlanks->count();
                        
                        foreach ($questionBlanks as $blank) {
                            $studentAnswer = $blanks[$blank->blank_order] ?? '';
                            $correctAnswer = $blank->answer;
                            
                            if (!$blank->case_sensitive) {
                                $studentAnswer = strtolower($studentAnswer);
                                $correctAnswer = strtolower($correctAnswer);
                            }
                            
                            if ($studentAnswer === $correctAnswer) {
                                $correctBlanks++;
                            }
                        }
                        
                        if ($totalBlanks > 0) {
                            $answer->score = ($correctBlanks / $totalBlanks) * $questionPoints;
                            $totalScore += $answer->score;
                        } else {
                            $answer->score = 0;
                        }
                        break;
                    
                    case 'matching':
                        $matches = $validated['answers'][$question->id] ?? [];
                        $answer->text_answer = json_encode($matches);
                        
                        // Auto-grade
                        $matchingPairs = $question->matchingPairs;
                        $correctMatches = 0;
                        $totalMatches = $matchingPairs->count();
                        
                        foreach ($matchingPairs as $pair) {
                            $studentMatch = $matches[$pair->id] ?? null;
                            if ($studentMatch == $pair->id) {
                                $correctMatches++;
                            }
                        }
                        
                        if ($totalMatches > 0) {
                            $answer->score = ($correctMatches / $totalMatches) * $questionPoints;
                            $totalScore += $answer->score;
                        } else {
                            $answer->score = 0;
                        }
                        break;
                    
                    case 'ordering':
                        $order = $validated['answers'][$question->id] ?? [];
                        $answer->text_answer = json_encode($order);
                        
                        // Auto-grade
                        $orderingItems = $question->orderingItems;
                        $correctItems = 0;
                        $totalItems = $orderingItems->count();
                        
                        foreach ($orderingItems as $item) {
                            $studentOrder = $order[$item->id] ?? null;
                            if ($studentOrder == $item->correct_order) {
                                $correctItems++;
                            }
                        }
                        
                        if ($totalItems > 0) {
                            $answer->score = ($correctItems / $totalItems) * $questionPoints;
                            $totalScore += $answer->score;
                        } else {
                            $answer->score = 0;
                        }
                        break;
                    
                    default:
                        $answer->text_answer = $validated['answers'][$question->id] ?? null;
                        $answer->score = 0;
                        break;
                }
                
                $answer->save();
            }
            
            // Update exam result
            $percentage = $totalPoints > 0 ? ($totalScore / $totalPoints) * 100 : 0;
            $status = 'passed';
            
            if ($percentage < $exam->passing_score) {
                $status = 'failed';
            }
            
            $result->update([
                'score' => $totalScore,
                'percentage' => $percentage,
                'status' => $status,
                'completed_at' => now(),
                'time_spent' => now()->diffInSeconds($result->started_at),
            ]);
            
            DB::commit();
            
            return redirect()->route('student.exams.result', $result)
                ->with('success', 'تم إرسال إجاباتك بنجاح');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting exam: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال الإجابات. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display exam result.
     */
    public function result(ExamResult $result)
    {
        $student = Auth::user()->student;
        
        // Check if result belongs to student
        if ($result->student_id !== $student->id) {
            return redirect()->route('student.exams.index')
                ->with('error', 'غير مصرح لك بعرض هذه النتيجة');
        }
        
        $result->load(['exam', 'exam.subject', 'student', 'answers', 'answers.question']);
        
        return view('student.exams.result', compact('result'));
    }

    /**
     * Review exam answers.
     */
    public function review(ExamResult $result)
    {
        $student = Auth::user()->student;
        
        // Check if result belongs to student
        if ($result->student_id !== $student->id) {
            return redirect()->route('student.exams.index')
                ->with('error', 'غير مصرح لك بمراجعة هذا الاختبار');
        }
        
        // Check if exam allows review
        if (!$result->exam->allow_review) {
            return redirect()->route('student.exams.result', $result)
                ->with('error', 'لا يُسمح بمراجعة هذا الاختبار');
        }
        
        $result->load(['exam', 'exam.subject', 'student', 'answers', 'answers.question']);
        
        return view('student.exams.review', compact('result'));
    }
}
