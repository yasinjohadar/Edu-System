<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller
{
    /**
     * Show questions for specific exam
     */
    public function index(Exam $exam)
    {
        $questions = $exam->examQuestions()
            ->with('question')
            ->orderBy('order')
            ->get();
        
        return view('admin.exam-questions.index', compact('exam', 'questions'));
    }

    /**
     * Show form to add questions to exam
     */
    public function create(Exam $exam)
    {
        $availableQuestions = Question::active()
            ->where('subject_id', $exam->subject_id)
            ->where('grade_id', $exam->grade_id)
            ->whereDoesntHave('examQuestions', function ($query) use ($exam) {
                $query->where('exam_id', $exam->id);
            })
            ->get();
        
        return view('admin.exam-questions.create', compact('exam', 'availableQuestions'));
    }

    /**
     * Store questions to exam
     */
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.points' => 'required|numeric|min:0',
            'questions.*.order' => 'required|integer|min:0',
            'questions.*.is_mandatory' => 'boolean',
        ]);

        foreach ($validated['questions'] as $questionData) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $questionData['id'],
                'points' => $questionData['points'],
                'order' => $questionData['order'],
                'is_mandatory' => $questionData['is_mandatory'] ?? false,
            ]);
        }

        return back()->with('success', 'تم إضافة الأسئلة بنجاح');
    }

    /**
     * Update question order and points
     */
    public function update(Request $request, ExamQuestion $examQuestion)
    {
        $validated = $request->validate([
            'points' => 'required|numeric|min:0',
            'order' => 'required|integer|min:0',
            'is_mandatory' => 'boolean',
        ]);

        $examQuestion->update($validated);

        return back()->with('success', 'تم تحديث السؤال بنجاح');
    }

    /**
     * Remove question from exam
     */
    public function destroy(ExamQuestion $examQuestion)
    {
        $examQuestion->delete();
        
        return back()->with('success', 'تم إزالة السؤال بنجاح');
    }

    /**
     * Reorder questions in exam
     */
    public function reorder(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:exam_questions,id',
            'questions.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['questions'] as $questionData) {
            $examQuestion = ExamQuestion::find($questionData['id']);
            if ($examQuestion) {
                $examQuestion->update(['order' => $questionData['order']]);
            }
        }

        return back()->with('success', 'تم إعادة ترتيب الأسئلة بنجاح');
    }
}
