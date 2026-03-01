<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض لوحة تحكم الطالب
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        // حساب معدل الحضور لهذا الشهر
        $currentMonth = now()->startOfMonth();
        $attendanceRecords = $student->attendances()
            ->where('date', '>=', $currentMonth)
            ->get();
        
        $totalDays = $attendanceRecords->count();
        $presentDays = $attendanceRecords->where('status', 'present')->count();
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        // حساب المعدل التراكمي
        $gradeRecords = $student->gradeRecords()
            ->where('is_published', true)
            ->get();
        
        $averageGrade = 0;
        if ($gradeRecords->count() > 0) {
            $totalPercentage = $gradeRecords->sum('percentage');
            $averageGrade = round($totalPercentage / $gradeRecords->count(), 2);
        }

        // عدد الفواتير المعلقة
        $pendingInvoices = $student->invoices()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->count();

        // عدد الاستعارات النشطة
        $activeBorrowings = $student->bookBorrowings()
            ->whereIn('status', ['borrowed', 'overdue'])
            ->count();

        // عدد المحاضرات القادمة
        $upcomingLectures = \App\Models\OnlineLecture::whereHas('attendance', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->where('scheduled_at', '>=', now())
            ->where('is_active', true)
            ->count();

        // إحصائيات سريعة
        $stats = [
            'attendance_rate' => $attendanceRate,
            'average_grade' => $averageGrade,
            'pending_invoices' => $pendingInvoices,
            'active_borrowings' => $activeBorrowings,
            'upcoming_lectures' => $upcomingLectures,
        ];

        // آخر الحضور
        $recentAttendances = $student->attendances()
            ->with('section')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // آخر الدرجات
        $recentGrades = $student->gradeRecords()
            ->with('subject', 'teacher.user')
            ->where('is_published', true)
            ->orderBy('exam_date', 'desc')
            ->limit(5)
            ->get();

        return view('student.pages.dashboard', compact('student', 'stats', 'recentAttendances', 'recentGrades'));
    }
}
