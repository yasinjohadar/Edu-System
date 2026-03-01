<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAnswer;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Exam $exam)
    {
        $answers = $exam->examAnswers()
            ->with(['student', 'question'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.exam-answers.index', compact('exam', 'answers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamAnswer $examAnswer)
    {
        $examAnswer->load(['exam', 'student', 'question']);
        
        return view('admin.exam-answers.show', compact('examAnswer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamAnswer $examAnswer)
    {
        $validated = $request->validate([
            'answer' => 'required|string',
            'is_correct' => 'boolean',
            'marks_obtained' => 'required|numeric|min:0',
        ]);

        $examAnswer->update($validated);

        return back()->with('success', 'تم تحديث الإجابة بنجاح');
    }

    /**
     * Auto-grade answers for specific exam
     */
    public function autoGrade(Exam $exam)
    {
        $exam->load(['examQuestions.question']);
        $totalQuestions = $exam->examQuestions->count();
        
        foreach ($exam->examQuestions as $examQuestion) {
            $question = $examQuestion->question;
            $answers = ExamAnswer::where('exam_id', $exam->id)
                ->where('question_id', $question->id)
                ->get();
            
            foreach ($answers as $answer) {
                $this->gradeAnswer($answer, $question, $examQuestion->points);
            }
        }

        return back()->with('success', 'تم تصحيح الإجابات بنجاح');
    }

    /**
     * Grade a single answer
     */
    private function gradeAnswer(ExamAnswer $answer, Question $question, $points)
    {
        $isCorrect = false;
        $marksObtained = 0;

        switch ($question->type) {
            case 'multiple_choice':
                $correctOption = $question->options()->where('is_correct', true)->first();
                $isCorrect = $answer->answer == $correctOption->id;
                $marksObtained = $isCorrect ? $points : 0;
                break;

            case 'true_false':
                $booleanAnswer = $question->booleanAnswer;
                $isCorrect = $answer->answer == $booleanAnswer->is_correct;
                $marksObtained = $isCorrect ? $points : 0;
                break;

            case 'fill_blanks':
                $blanks = $question->blanks;
                $userAnswers = json_decode($answer->answer, true);
                $correctCount = 0;
                
                foreach ($blanks as $index => $blank) {
                    if (isset($userAnswers[$index])) {
                        $userAnswer = $userAnswers[$index];
                        $correctAnswer = $blank->answer;
                        
                        if ($blank->case_sensitive) {
                            if ($userAnswer == $correctAnswer) {
                                $correctCount++;
                            }
                        } else {
                            if (strtolower($userAnswer) == strtolower($correctAnswer)) {
                                $correctCount++;
                            }
                        }
                    }
                }
                
                $isCorrect = $correctCount == $blanks->count();
                $marksObtained = ($correctCount / $blanks->count()) * $points;
                break;

            case 'matching':
                $pairs = $question->matchingPairs;
                $userAnswers = json_decode($answer->answer, true);
                $correctCount = 0;
                
                foreach ($pairs as $pair) {
                    if (isset($userAnswers[$pair->left_item])) {
                        if ($userAnswers[$pair->left_item] == $pair->right_item) {
                            $correctCount++;
                        }
                    }
                }
                
                $isCorrect = $correctCount == $pairs->count();
                $marksObtained = ($correctCount / $pairs->count()) * $points;
                break;

            case 'ordering':
                $items = $question->orderingItems;
                $userAnswers = json_decode($answer->answer, true);
                $correctCount = 0;
                
                foreach ($items as $index => $item) {
                    if (isset($userAnswers[$index])) {
                        if ($userAnswers[$index] == $item->correct_order) {
                            $correctCount++;
                        }
                    }
                }
                
                $isCorrect = $correctCount == $items->count();
                $marksObtained = ($correctCount / $items->count()) * $points;
                break;

            case 'classification':
                $items = $question->classificationItems;
                $userAnswers = json_decode($answer->answer, true);
                $correctCount = 0;
                
                foreach ($items as $item) {
                    if (isset($userAnswers[$item->item_text])) {
                        if ($userAnswers[$item->item_text] == $item->category_id) {
                            $correctCount++;
                        }
                    }
                }
                
                $isCorrect = $correctCount == $items->count();
                $marksObtained = ($correctCount / $items->count()) * $points;
                break;

            case 'drag_drop':
            case 'hotspot':
                // Manual grading required for these types
                $marksObtained = 0;
                break;

            case 'essay':
            case 'audio':
            case 'video':
                // Manual grading required for these types
                $marksObtained = 0;
                break;
        }

        $answer->update([
            'is_correct' => $isCorrect,
            'marks_obtained' => $marksObtained,
        ]);
    }
}
