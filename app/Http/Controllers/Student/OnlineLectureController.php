<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\OnlineLecture;
use App\Models\LectureAttendance;

class OnlineLectureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض المحاضرات الإلكترونية
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        if (!$student->section_id) {
            return redirect()->route('student.dashboard')->with('error', 'لم يتم تعيين فصل دراسي لك');
        }

        // جلب المحاضرات للفصل الدراسي
        $query = OnlineLecture::where('section_id', $student->section_id)
            ->with(['subject', 'teacher.user', 'materials'])
            ->where('is_active', true);

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $lectures = $query->orderBy('scheduled_at', 'desc')->paginate(20);

        // المحاضرات القادمة
        $upcomingLectures = OnlineLecture::where('section_id', $student->section_id)
            ->where('scheduled_at', '>=', now())
            ->where('is_active', true)
            ->with(['subject', 'teacher.user'])
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();

        // المواد الدراسية للفلترة
        $subjects = \App\Models\Subject::whereHas('onlineLectures', function($q) use ($student) {
            $q->where('section_id', $student->section_id);
        })->get();

        return view('student.pages.lectures.index', compact('lectures', 'upcomingLectures', 'subjects'));
    }

    /**
     * عرض تفاصيل المحاضرة
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $lecture = OnlineLecture::where('section_id', $student->section_id)
            ->with(['subject', 'teacher.user', 'materials', 'attendance' => function($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->findOrFail($id);

        // تحديث عدد المشاهدات
        $lecture->incrementViews();

        return view('student.pages.lectures.show', compact('lecture'));
    }
}

