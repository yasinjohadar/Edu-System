<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $calendars = AcademicCalendar::orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->paginate(15);

        return view('admin.pages.academic-calendars.index', compact('calendars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.academic-calendars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string|max:255',
            'semester' => 'required|in:first,second,summer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'exams_start' => 'nullable|date',
            'exams_end' => 'nullable|date|after:exams_start',
            'results_publish_date' => 'nullable|date',
            'holidays' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        AcademicCalendar::create([
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'exams_start' => $request->exams_start,
            'exams_end' => $request->exams_end,
            'results_publish_date' => $request->results_publish_date,
            'holidays' => $request->holidays,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.academic-calendars.index')->with('success', 'تم إنشاء التقويم الأكاديمي بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicCalendar $academicCalendar)
    {
        return view('admin.pages.academic-calendars.show', compact('academicCalendar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicCalendar $academicCalendar)
    {
        return view('admin.pages.academic-calendars.edit', compact('academicCalendar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicCalendar $academicCalendar)
    {
        $request->validate([
            'academic_year' => 'required|string|max:255',
            'semester' => 'required|in:first,second,summer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'exams_start' => 'nullable|date',
            'exams_end' => 'nullable|date|after:exams_start',
            'results_publish_date' => 'nullable|date',
            'holidays' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $academicCalendar->update([
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'exams_start' => $request->exams_start,
            'exams_end' => $request->exams_end,
            'results_publish_date' => $request->results_publish_date,
            'holidays' => $request->holidays,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.academic-calendars.index')->with('success', 'تم تحديث التقويم الأكاديمي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->delete();
        return redirect()->route('admin.academic-calendars.index')->with('success', 'تم حذف التقويم الأكاديمي بنجاح');
    }
}
