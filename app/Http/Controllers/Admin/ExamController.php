<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::with(['subject', 'grade', 'section', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,exam,midterm,final',
            'subject_id' => 'nullable|exists:subjects,id',
            'grade_id' => 'nullable|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0|max:total_marks',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
            'allow_review' => 'boolean',
            'show_results' => 'boolean',
            'show_answers' => 'boolean',
            'randomize_questions' => 'boolean',
        ]);

        // Generate unique exam code
        $validated['exam_code'] = $this->generateExamCode();

        $exam = Exam::create($validated);

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'تم إنشاء الاختبار بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        $exam->load(['subject', 'grade', 'section', 'teacher']);
        
        return view('admin.exams.edit', compact('exam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,exam,midterm,final',
            'subject_id' => 'nullable|exists:subjects,id',
            'grade_id' => 'nullable|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0|max:total_marks',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
            'allow_review' => 'boolean',
            'show_results' => 'boolean',
            'show_answers' => 'boolean',
            'randomize_questions' => 'boolean',
        ]);

        $exam->update($validated);

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'تم تحديث الاختبار بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        // Check if exam has results
        $hasResults = $exam->examResults()->exists();
        
        if ($hasResults) {
            return back()->with('error', 'لا يمكن حذف اختبار له نتائج');
        }

        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'تم حذف الاختبار بنجاح');
    }

    /**
     * Publish exam
     */
    public function publish(Exam $exam)
    {
        $exam->update(['is_published' => true]);
        
        return back()->with('success', 'تم نشر الاختبار بنجاح');
    }

    /**
     * Unpublish exam
     */
    public function unpublish(Exam $exam)
    {
        $exam->update(['is_published' => false]);
        
        return back()->with('success', 'تم إلغاء نشر الاختبار');
    }

    /**
     * Get exam statistics
     */
    public function statistics(Exam $exam)
    {
        $exam->load(['examResults', 'examQuestions']);
        
        $totalStudents = $exam->examResults()->count();
        $completedStudents = $exam->examResults()->where('status', '!=', 'absent')->count();
        $passedStudents = $exam->examResults()->where('status', 'passed')->count();
        $failedStudents = $exam->examResults()->where('status', 'failed')->count();
        $absentStudents = $exam->examResults()->where('status', 'absent')->count();
        
        $averageScore = $exam->examResults()->where('status', '!=', 'absent')->avg('percentage') ?? 0;
        $highestScore = $exam->examResults()->where('status', '!=', 'absent')->max('percentage') ?? 0;
        $lowestScore = $exam->examResults()->where('status', '!=', 'absent')->min('percentage') ?? 0;
        
        return view('admin.exams.statistics', compact(
            'exam', 
            'totalStudents',
            'completedStudents',
            'passedStudents',
            'failedStudents',
            'absentStudents',
            'averageScore',
            'highestScore',
            'lowestScore'
        ));
    }

    /**
     * Generate unique exam code
     */
    private function generateExamCode()
    {
        return 'EXAM-' . strtoupper(uniqid());
    }
}
