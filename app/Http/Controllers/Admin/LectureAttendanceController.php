<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LectureAttendance;
use App\Models\OnlineLecture;
use App\Models\Student;
use Illuminate\Http\Request;

class LectureAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lecture-attendance-list')->only('index', 'show');
        $this->middleware('permission:lecture-attendance-create')->only('create', 'store');
        $this->middleware('permission:lecture-attendance-edit')->only('edit', 'update');
        $this->middleware('permission:lecture-attendance-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $attendanceQuery = LectureAttendance::with(['lecture.subject', 'student.user'])->orderBy('created_at', 'desc');

        if ($request->filled('lecture_id')) {
            $attendanceQuery->where('lecture_id', $request->input('lecture_id'));
        }

        if ($request->filled('student_id')) {
            $attendanceQuery->where('student_id', $request->input('student_id'));
        }

        if ($request->filled('status')) {
            $attendanceQuery->where('status', $request->input('status'));
        }

        $attendance = $attendanceQuery->paginate(20);
        $lectures = OnlineLecture::where('is_published', true)->get();
        $students = Student::with('user')->where('status', 'active')->get();

        return view('admin.pages.lecture-attendance.index', compact('attendance', 'lectures', 'students'));
    }

    public function create()
    {
        $lectures = OnlineLecture::where('is_published', true)->get();
        $students = Student::with('user')->where('status', 'active')->get();
        return view('admin.pages.lecture-attendance.create', compact('lectures', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:online_lectures,id',
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:present,absent,late,excused',
            'joined_at' => 'nullable|date',
            'left_at' => 'nullable|date|after:joined_at',
            'duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // التحقق من عدم وجود سجل مسبق
        $existing = LectureAttendance::where('lecture_id', $request->lecture_id)
            ->where('student_id', $request->student_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'يوجد سجل حضور مسبق لهذا الطالب في هذه المحاضرة');
        }

        LectureAttendance::create($request->all());

        return redirect()->route('admin.lecture-attendance.index')->with('success', 'تم تسجيل الحضور بنجاح');
    }

    public function show(string $id)
    {
        $attendance = LectureAttendance::with(['lecture.subject', 'student.user'])->findOrFail($id);
        return view('admin.pages.lecture-attendance.show', compact('attendance'));
    }

    public function edit(string $id)
    {
        $attendance = LectureAttendance::findOrFail($id);
        $lectures = OnlineLecture::where('is_published', true)->get();
        $students = Student::with('user')->where('status', 'active')->get();
        return view('admin.pages.lecture-attendance.edit', compact('attendance', 'lectures', 'students'));
    }

    public function update(Request $request, string $id)
    {
        $attendance = LectureAttendance::findOrFail($id);

        $request->validate([
            'lecture_id' => 'required|exists:online_lectures,id',
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:present,absent,late,excused',
            'joined_at' => 'nullable|date',
            'left_at' => 'nullable|date|after:joined_at',
            'duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($request->all());

        return redirect()->route('admin.lecture-attendance.index')->with('success', 'تم تحديث الحضور بنجاح');
    }

    public function destroy(string $id)
    {
        $attendance = LectureAttendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('admin.lecture-attendance.index')->with('success', 'تم حذف سجل الحضور بنجاح');
    }
}
