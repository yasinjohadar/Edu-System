<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\GradeRecord;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض الدرجات
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $query = $student->gradeRecords()
            ->where('is_published', true)
            ->with(['subject', 'teacher.user'])
            ->orderBy('exam_date', 'desc');

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // فلترة حسب نوع الامتحان
        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        // فلترة حسب الفصل الدراسي
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $grades = $query->paginate(20);

        // إحصائيات
        $allGrades = $student->gradeRecords()
            ->where('is_published', true)
            ->get();

        $averageGrade = 0;
        if ($allGrades->count() > 0) {
            $totalPercentage = $allGrades->sum('percentage');
            $averageGrade = round($totalPercentage / $allGrades->count(), 2);
        }

        // الدرجات حسب المادة
        $gradesBySubject = $allGrades->groupBy('subject_id')->map(function($group) {
            $subject = $group->first()->subject;
            $totalPercentage = $group->sum('percentage');
            $count = $group->count();
            return [
                'subject' => $subject,
                'average' => round($totalPercentage / $count, 2),
                'count' => $count,
            ];
        });

        $stats = [
            'total_exams' => $allGrades->count(),
            'average_grade' => $averageGrade,
            'grades_by_subject' => $gradesBySubject,
        ];

        // المواد الدراسية للفلترة
        $subjects = \App\Models\Subject::whereHas('gradeRecords', function($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->where('is_published', true);
        })->get();

        return view('student.pages.grades.index', compact('grades', 'stats', 'subjects'));
    }

    /**
     * عرض تفاصيل الدرجة
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $grade = GradeRecord::where('student_id', $student->id)
            ->where('is_published', true)
            ->with(['subject', 'teacher.user'])
            ->findOrFail($id);

        return view('student.pages.grades.show', compact('grade'));
    }
}

