<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\GradeRecord;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeRecordController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('grade');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $gradeRecords = $this->buildGradeRecordsQuery($request)
            ->paginate(50)
            ->withQueryString();

        $examTypes = $this->examTypes();
        $semesters = $this->semesters();

        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $sections = Section::with('class')->where('is_active', true)->orderBy('name')->get();
        $academicYears = GradeRecord::distinct()->pluck('academic_year')->filter()->sort()->reverse();

        if ($request->ajax()) {
            return response()->json([
                'body' => view('admin.partials.grade-records-table-body', compact('gradeRecords'))->render(),
                'extra' => view('admin.partials.grade-records-table-footer', compact('gradeRecords'))->render(),
                'from' => $gradeRecords->firstItem(),
                'to' => $gradeRecords->lastItem(),
                'total' => $gradeRecords->total(),
            ]);
        }

        return view('admin.pages.grade-records.index', compact(
            'gradeRecords',
            'subjects',
            'sections',
            'examTypes',
            'semesters',
            'academicYears'
        ));
    }

    private function buildGradeRecordsQuery(Request $request)
    {
        $query = GradeRecord::with(['student.user', 'subject', 'teacher.user']);

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($sq) use ($search) {
                    $sq->where('student_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('section_id')) {
            $sectionId = $request->section_id;
            $query->whereHas('student', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            });
        }

        return $query->orderBy('exam_date', 'desc')->orderBy('created_at', 'desc');
    }

    private function examTypes(): array
    {
        return [
            'quiz' => 'اختبار قصير',
            'assignment' => 'واجب',
            'midterm' => 'امتحان نصفي',
            'final' => 'امتحان نهائي',
            'project' => 'مشروع',
            'participation' => 'مشاركة',
            'homework' => 'واجب منزلي',
            'other' => 'أخرى',
        ];
    }

    private function semesters(): array
    {
        return [
            'first' => 'الفصل الأول',
            'second' => 'الفصل الثاني',
            'summer' => 'الفصل الصيفي',
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $subjects = Subject::where('is_active', true)->get();
        $sections = Section::with('class')->where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();

        $selectedSection = null;
        $selectedSubject = null;
        $students = collect();

        if ($request->filled('section_id') && $request->filled('subject_id')) {
            $selectedSection = Section::with('class')->find($request->section_id);
            $selectedSubject = Subject::find($request->subject_id);
            
            if ($selectedSection) {
                $students = $selectedSection->students()
                    ->with('user')
                    ->where('status', 'active')
                    ->orderBy('student_code')
                    ->get();
            }
        }

        $examTypes = $this->examTypes();
        $semesters = $this->semesters();

        // السنة الدراسية الحالية (يمكن تحسينها لاحقاً)
        $currentYear = date('Y');
        $academicYear = ($currentYear - 1) . '-' . $currentYear;

        return view('admin.pages.grade-records.create', compact(
            'subjects',
            'sections',
            'teachers',
            'selectedSection',
            'selectedSubject',
            'students',
            'examTypes',
            'semesters',
            'academicYear'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'exam_type' => 'required|in:quiz,assignment,midterm,final,project,participation,homework,other',
            'exam_name' => 'required|string|max:255',
            'total_marks' => 'required|numeric|min:0',
            'exam_date' => 'required|date',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:first,second,summer',
            'notes' => 'nullable|string',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.marks_obtained' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $created = 0;
            
            foreach ($validated['grades'] as $gradeData) {
                $marksObtained = $gradeData['marks_obtained'];
                $totalMarks = $validated['total_marks'];
                $percentage = ($marksObtained / $totalMarks) * 100;
                
                // حساب الدرجة الحرفية
                $grade = 'F';
                if ($percentage >= 90) $grade = 'A+';
                elseif ($percentage >= 85) $grade = 'A';
                elseif ($percentage >= 80) $grade = 'B+';
                elseif ($percentage >= 75) $grade = 'B';
                elseif ($percentage >= 70) $grade = 'C+';
                elseif ($percentage >= 65) $grade = 'C';
                elseif ($percentage >= 60) $grade = 'D+';
                elseif ($percentage >= 50) $grade = 'D';

                GradeRecord::create([
                    'student_id' => $gradeData['student_id'],
                    'subject_id' => $validated['subject_id'],
                    'teacher_id' => $validated['teacher_id'] ?? auth()->user()->teacher?->id,
                    'exam_type' => $validated['exam_type'],
                    'exam_name' => $validated['exam_name'],
                    'marks_obtained' => $marksObtained,
                    'total_marks' => $totalMarks,
                    'percentage' => round($percentage, 2),
                    'grade' => $grade,
                    'exam_date' => $validated['exam_date'],
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                    'notes' => $validated['notes'] ?? null,
                    'is_published' => false,
                ]);
                
                $created++;
            }

            DB::commit();

            return redirect()->route('admin.grade-records.index')
                ->with('success', "تم إدخال {$created} درجة بنجاح.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ الدرجات: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gradeRecord = GradeRecord::with(['student.user', 'subject', 'teacher.user'])->findOrFail($id);
        return view('admin.pages.grade-records.show', compact('gradeRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gradeRecord = GradeRecord::with(['student.user', 'subject', 'teacher'])->findOrFail($id);
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();

        $examTypes = $this->examTypes();
        $semesters = $this->semesters();

        return view('admin.pages.grade-records.edit', compact('gradeRecord', 'subjects', 'teachers', 'examTypes', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gradeRecord = GradeRecord::findOrFail($id);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'exam_type' => 'required|in:quiz,assignment,midterm,final,project,participation,homework,other',
            'exam_name' => 'required|string|max:255',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'exam_date' => 'required|date',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:first,second,summer',
            'notes' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        // حساب النسبة المئوية والدرجة الحرفية
        $percentage = ($validated['marks_obtained'] / $validated['total_marks']) * 100;
        
        $grade = 'F';
        if ($percentage >= 90) $grade = 'A+';
        elseif ($percentage >= 85) $grade = 'A';
        elseif ($percentage >= 80) $grade = 'B+';
        elseif ($percentage >= 75) $grade = 'B';
        elseif ($percentage >= 70) $grade = 'C+';
        elseif ($percentage >= 65) $grade = 'C';
        elseif ($percentage >= 60) $grade = 'D+';
        elseif ($percentage >= 50) $grade = 'D';

        $validated['percentage'] = round($percentage, 2);
        $validated['grade'] = $grade;
        $validated['is_published'] = (bool) $request->input('is_published', 0);

        $gradeRecord->update($validated);

        return redirect()->route('admin.grade-records.index')
            ->with('success', 'تم تحديث الدرجة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gradeRecord = GradeRecord::findOrFail($id);
        $gradeRecord->delete();

        return redirect()->route('admin.grade-records.index')
            ->with('success', 'تم حذف الدرجة بنجاح.');
    }
}
