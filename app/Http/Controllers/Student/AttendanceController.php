<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض سجل الحضور والغياب
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $query = $student->attendances()->with('section')->orderBy('date', 'desc');

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(20);

        // إحصائيات
        $totalDays = $student->attendances()->count();
        $presentDays = $student->attendances()->where('status', 'present')->count();
        $absentDays = $student->attendances()->where('status', 'absent')->count();
        $lateDays = $student->attendances()->where('status', 'late')->count();
        $excusedDays = $student->attendances()->where('status', 'excused')->count();
        
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        $stats = [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'excused_days' => $excusedDays,
            'attendance_rate' => $attendanceRate,
        ];

        return view('student.pages.attendance.index', compact('attendances', 'stats'));
    }

    /**
     * عرض تفاصيل الحضور
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $attendance = Attendance::where('student_id', $student->id)
            ->with(['section', 'markedBy'])
            ->findOrFail($id);

        return view('student.pages.attendance.show', compact('attendance'));
    }
}

