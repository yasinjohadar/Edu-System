<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamAnswer;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ExamResult::query();
        
        // Filter by exam
        if ($request->has('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by grade
        if ($request->has('grade_id')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }
        
        $results = $query->with(['exam', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.exam-results.index', compact('results'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamResult $examResult)
    {
        $examResult->load(['exam', 'student', 'answers.question']);
        
        return view('admin.exam-results.show', compact('examResult'));
    }

    /**
     * Show exam statistics
     */
    public function statistics(Exam $exam)
    {
        $results = $exam->examResults()->get();
        
        $totalStudents = $results->count();
        $completedStudents = $results->where('status', '!=', 'absent')->count();
        $passedStudents = $results->where('status', 'passed')->count();
        $failedStudents = $results->where('status', 'failed')->count();
        $absentStudents = $results->where('status', 'absent')->count();
        
        $averageScore = $results->where('status', '!=', 'absent')->avg('percentage') ?? 0;
        $highestScore = $results->where('status', '!=', 'absent')->max('percentage') ?? 0;
        $lowestScore = $results->where('status', '!=', 'absent')->min('percentage') ?? 0;
        
        $averageTime = $results->where('status', '!=', 'absent')->avg('time_taken') ?? 0;
        
        return view('admin.exam-results.statistics', compact(
            'exam',
            'totalStudents',
            'completedStudents',
            'passedStudents',
            'failedStudents',
            'absentStudents',
            'averageScore',
            'highestScore',
            'lowestScore',
            'averageTime'
        ));
    }

    /**
     * Export results to CSV
     */
    public function export(Exam $exam)
    {
        $results = $exam->examResults()
            ->with(['student', 'answers'])
            ->get();
        
        $headers = [
            'Student ID',
            'Student Name',
            'Total Marks',
            'Obtained Marks',
            'Percentage',
            'Status',
            'Time Taken (seconds)',
            'Started At',
            'Submitted At',
            'Attempts',
        ];
        
        $rows = [];
        foreach ($results as $result) {
            $rows[] = [
                $result->student_id,
                $result->student->name ?? 'N/A',
                $result->total_marks,
                $result->obtained_marks,
                $result->percentage,
                $result->status,
                $result->time_taken,
                $result->started_at,
                $result->submitted_at,
                $result->attempts,
            ];
        }
        
        $filename = 'exam_results_' . $exam->exam_code . '_' . date('Y-m-d_His') . '.csv';
        
        // Return CSV download
        return response()->stream(function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamResult $examResult)
    {
        $validated = $request->validate([
            'status' => 'required|in:passed,failed,absent',
            'total_marks' => 'required|numeric|min:0',
            'obtained_marks' => 'required|numeric|min:0|max:total_marks',
        ]);

        $examResult->update($validated);
        
        // Recalculate percentage
        if ($examResult->total_marks > 0) {
            $examResult->update([
                'percentage' => ($examResult->obtained_marks / $examResult->total_marks) * 100,
            ]);
        }

        return back()->with('success', 'تم تحديث النتيجة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamResult $examResult)
    {
        $examResult->delete();
        
        return back()->with('success', 'تم حذف النتيجة بنجاح');
    }
}
