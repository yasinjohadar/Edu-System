<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('attendance');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attendances = $this->buildAttendancesQuery($request)->paginate(20)->withQueryString();
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $students = Student::with('user')->where('status', 'active')->get();

        if ($request->ajax()) {
            return response()->json([
                'body' => view('admin.partials.attendances-table-body', compact('attendances'))->render(),
                'extra' => view('admin.partials.attendances-table-footer', compact('attendances'))->render(),
                'from' => $attendances->firstItem(),
                'to' => $attendances->lastItem(),
                'total' => $attendances->total(),
            ]);
        }

        return view('admin.pages.attendances.index', compact('attendances', 'sections', 'students'));
    }

    private function buildAttendancesQuery(Request $request)
    {
        $query = Attendance::with(['student.user', 'section.class.grade', 'markedBy']);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhereHas('student', function ($sq) use ($search) {
                    $sq->where('student_code', 'like', "%{$search}%");
                });
            });
        }

        return $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $selectedSection = null;
        $students = collect();

        if ($request->filled('section_id')) {
            $selectedSection = Section::with('class.grade')->findOrFail($request->section_id);
            
            // جلب الطلاب الذين ينتمون لهذا الفصل
            $students = Student::with('user')
                ->where('section_id', $selectedSection->id)
                ->where('status', 'active')
                ->orderBy('student_code')
                ->get();
        }

        return view('admin.pages.attendances.create', compact('sections', 'selectedSection', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.check_in_time' => 'nullable|date_format:H:i',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $date = $request->date;
            $sectionId = $request->section_id;
            $markedBy = auth()->id();

            foreach ($request->attendances as $attendanceData) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $attendanceData['student_id'],
                        'section_id' => $sectionId,
                        'date' => $date,
                    ],
                    [
                        'status' => $attendanceData['status'],
                        'check_in_time' => $attendanceData['check_in_time'] ?? null,
                        'notes' => $attendanceData['notes'] ?? null,
                        'marked_by' => $markedBy,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('admin.attendances.index', [
                'section_id' => $sectionId,
                'date' => $date
            ])->with('success', 'تم تسجيل الحضور بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تسجيل الحضور: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::with(['student.user', 'section.class.grade', 'markedBy'])
            ->findOrFail($id);
        
        return view('admin.pages.attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::with(['student.user', 'section'])->findOrFail($id);
        return view('admin.pages.attendances.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance->update([
            'status' => $request->status,
            'check_in_time' => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.attendances.index', [
            'section_id' => $attendance->section_id,
            'date' => $attendance->date->format('Y-m-d')
        ])->with('success', 'تم تحديث سجل الحضور بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('admin.attendances.index')->with('success', 'تم حذف سجل الحضور بنجاح');
    }
}
