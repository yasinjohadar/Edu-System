<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAnswer;
use App\Models\EssayEvaluation;
use App\Models\Rubric;
use Illuminate\Http\Request;

class EssayEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EssayEvaluation::query();
        
        // Filter by exam
        if ($request->has('exam_id')) {
            $query->whereHas('examAnswer', function ($q) use ($request) {
                $q->where('exam_id', $request->exam_id);
            });
        }
        
        // Filter by evaluated status
        if ($request->has('is_evaluated')) {
            if ($request->is_evaluated) {
                $query->whereNotNull('evaluated_at');
            } else {
                $query->whereNull('evaluated_at');
            }
        }
        
        $evaluations = $query->with(['examAnswer.exam', 'examAnswer.student', 'examAnswer.question', 'rubric', 'evaluator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.essay-evaluations.index', compact('evaluations'));
    }

    /**
     * Show the form for evaluating an essay answer.
     */
    public function evaluate(ExamAnswer $examAnswer)
    {
        $examAnswer->load(['exam', 'student', 'question.essayQuestion.rubric']);
        
        return view('admin.essay-evaluations.evaluate', compact('examAnswer'));
    }

    /**
     * Store the evaluation.
     */
    public function store(Request $request, ExamAnswer $examAnswer)
    {
        $validated = $request->validate([
            'rubric_id' => 'nullable|exists:rubrics,id',
            'criteria_scores' => 'required|array',
            'criteria_scores.*.score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        // Calculate total score
        $totalScore = array_sum(array_column($validated['criteria_scores'], 'score'));
        
        // Get rubric max points
        $rubric = null;
        if (isset($validated['rubric_id'])) {
            $rubric = Rubric::find($validated['rubric_id']);
        }
        
        $maxPoints = $rubric ? $rubric->max_points : 100;

        $evaluation = EssayEvaluation::create([
            'exam_answer_id' => $examAnswer->id,
            'rubric_id' => $validated['rubric_id'] ?? null,
            'criteria_scores' => $validated['criteria_scores'],
            'total_score' => $totalScore,
            'feedback' => $validated['feedback'] ?? null,
            'evaluated_by' => auth()->id(),
            'evaluated_at' => now(),
        ]);

        // Update exam answer marks
        $examAnswer->update([
            'marks_obtained' => $totalScore,
            'is_correct' => $totalScore > 0,
        ]);

        // Recalculate exam result
        $this->recalculateExamResult($examAnswer);

        return redirect()
            ->route('admin.essay-evaluations.index')
            ->with('success', 'تم تقييم الإجابة بنجاح');
    }

    /**
     * Update an evaluation.
     */
    public function update(Request $request, EssayEvaluation $evaluation)
    {
        $validated = $request->validate([
            'criteria_scores' => 'required|array',
            'criteria_scores.*.score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        // Calculate total score
        $totalScore = array_sum(array_column($validated['criteria_scores'], 'score'));
        
        $evaluation->update([
            'criteria_scores' => $validated['criteria_scores'],
            'total_score' => $totalScore,
            'feedback' => $validated['feedback'] ?? null,
            'evaluated_at' => now(),
        ]);

        // Update exam answer marks
        $examAnswer = $evaluation->examAnswer;
        $examAnswer->update([
            'marks_obtained' => $totalScore,
            'is_correct' => $totalScore > 0,
        ]);

        // Recalculate exam result
        $this->recalculateExamResult($examAnswer);

        return redirect()
            ->route('admin.essay-evaluations.index')
            ->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * Remove the specified evaluation.
     */
    public function destroy(EssayEvaluation $evaluation)
    {
        $examAnswer = $evaluation->examAnswer;
        
        $evaluation->delete();
        
        // Reset exam answer marks
        $examAnswer->update([
            'marks_obtained' => 0,
            'is_correct' => false,
        ]);

        // Recalculate exam result
        $this->recalculateExamResult($examAnswer);

        return redirect()
            ->route('admin.essay-evaluations.index')
            ->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * Recalculate exam result after evaluation
     */
    private function recalculateExamResult(ExamAnswer $examAnswer)
    {
        $examResult = $examAnswer->exam->examResults()->first();
        
        if ($examResult) {
            // Get all answers for this exam
            $allAnswers = $examAnswer->exam->examAnswers;
            
            $totalObtainedMarks = $allAnswers->sum('marks_obtained');
            $totalMarks = $examAnswer->exam->examQuestions->sum('points');
            
            $percentage = $totalMarks > 0 ? ($totalObtainedMarks / $totalMarks) * 100 : 0;
            
            // Determine status
            $status = 'failed';
            if ($percentage >= $examAnswer->exam->passing_marks) {
                $status = 'passed';
            }
            
            $examResult->update([
                'obtained_marks' => $totalObtainedMarks,
                'percentage' => $percentage,
                'status' => $status,
            ]);
        }
    }
}
