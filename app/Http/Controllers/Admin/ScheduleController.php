<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:schedule-list|schedule-create|schedule-edit|schedule-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:schedule-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:schedule-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:schedule-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['section.class', 'subject', 'teacher.user']);

        // فلترة حسب الفصل
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // فلترة حسب اليوم
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $schedules = $query->orderBy('day_of_week')
            ->orderBy('order')
            ->orderBy('start_time')
            ->paginate(50);

        $sections = Section::with('class')->where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        return view('admin.pages.schedules.index', compact('schedules', 'sections', 'teachers', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::with('class')->where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        return view('admin.pages.schedules.create', compact('sections', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // التحقق من عدم تعارض الوقت مع جدول آخر لنفس الفصل
        $conflict = Schedule::where('section_id', $validated['section_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->where('is_active', true)
            ->first();

        if ($conflict) {
            return back()->withErrors(['time' => 'يوجد تعارض في الوقت مع جدول آخر لنفس الفصل في نفس اليوم.'])->withInput();
        }

        // التحقق من عدم تعارض الوقت مع جدول آخر لنفس المعلم
        $teacherConflict = Schedule::where('teacher_id', $validated['teacher_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->where('is_active', true)
            ->first();

        if ($teacherConflict) {
            return back()->withErrors(['time' => 'يوجد تعارض في الوقت مع جدول آخر لنفس المعلم في نفس اليوم.'])->withInput();
        }

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'تم إنشاء الجدول الدراسي بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::with(['section.class', 'subject', 'teacher.user'])->findOrFail($id);
        return view('admin.pages.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $sections = Section::with('class')->where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        return view('admin.pages.schedules.edit', compact('schedule', 'sections', 'subjects', 'teachers', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // التحقق من عدم تعارض الوقت مع جدول آخر لنفس الفصل (استثناء الجدول الحالي)
        $conflict = Schedule::where('section_id', $validated['section_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->where('is_active', true)
            ->first();

        if ($conflict) {
            return back()->withErrors(['time' => 'يوجد تعارض في الوقت مع جدول آخر لنفس الفصل في نفس اليوم.'])->withInput();
        }

        // التحقق من عدم تعارض الوقت مع جدول آخر لنفس المعلم (استثناء الجدول الحالي)
        $teacherConflict = Schedule::where('teacher_id', $validated['teacher_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->where('is_active', true)
            ->first();

        if ($teacherConflict) {
            return back()->withErrors(['time' => 'يوجد تعارض في الوقت مع جدول آخر لنفس المعلم في نفس اليوم.'])->withInput();
        }

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'تم تحديث الجدول الدراسي بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'تم حذف الجدول الدراسي بنجاح.');
    }
}
