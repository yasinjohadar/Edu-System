<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض الجدول الدراسي
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        if (!$student->section_id) {
            return redirect()->route('student.dashboard')->with('error', 'لم يتم تعيين فصل دراسي لك');
        }

        // جلب الجدول الدراسي للفصل
        $schedules = Schedule::where('section_id', $student->section_id)
            ->where('is_active', true)
            ->with(['subject', 'teacher.user'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // تجميع الجدول حسب اليوم
        $scheduleByDay = $schedules->groupBy('day_of_week');

        // أيام الأسبوع
        $days = [
            1 => 'السبت',
            2 => 'الأحد',
            3 => 'الاثنين',
            4 => 'الثلاثاء',
            5 => 'الأربعاء',
            6 => 'الخميس',
            7 => 'الجمعة',
        ];

        return view('student.pages.schedule.index', compact('scheduleByDay', 'days', 'student'));
    }
}

