<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\GradeRecord;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BookBorrowing;
use App\Models\Fine;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض صفحة التقارير الرئيسية
     */
    public function index()
    {
        $reports = Report::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pages.reports.index', compact('reports'));
    }

    /**
     * عرض صفحة اختيار نوع التقرير
     */
    public function create()
    {
        return view('admin.pages.reports.create');
    }

    /**
     * عرض صفحة تقرير أداء الطلاب
     */
    public function studentPerformance(Request $request)
    {
        $classes = ClassModel::with('grade')->get();
        $sections = Section::with('class.grade')->get();
        $subjects = Subject::all();

        // تطبيق الفلاتر
        $query = Student::with(['user', 'class.grade', 'section', 'gradeRecords.subject']);

        if ($request->filled('student_id')) {
            $query->where('id', $request->student_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('gradeRecords', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereHas('gradeRecords', function($q) use ($request) {
                $q->whereDate('record_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('gradeRecords', function($q) use ($request) {
                $q->whereDate('record_date', '<=', $request->date_to);
            });
        }

        $students = $query->get();

        // حساب الإحصائيات
        $stats = [];
        foreach ($students as $student) {
            $gradeRecords = $student->gradeRecords;
            if ($request->filled('subject_id')) {
                $gradeRecords = $gradeRecords->where('subject_id', $request->subject_id);
            }
            if ($request->filled('date_from')) {
                $gradeRecords = $gradeRecords->where('record_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $gradeRecords = $gradeRecords->where('record_date', '<=', $request->date_to);
            }

            $stats[$student->id] = [
                'total_records' => $gradeRecords->count(),
                'average_percentage' => $gradeRecords->avg('percentage') ?? 0,
                'highest_grade' => $gradeRecords->max('percentage') ?? 0,
                'lowest_grade' => $gradeRecords->min('percentage') ?? 100,
                'excellent_count' => $gradeRecords->where('percentage', '>=', 90)->count(),
                'good_count' => $gradeRecords->whereBetween('percentage', [75, 89])->count(),
                'pass_count' => $gradeRecords->whereBetween('percentage', [50, 74])->count(),
                'fail_count' => $gradeRecords->where('percentage', '<', 50)->count(),
            ];
        }

        return view('admin.pages.reports.student-performance', compact(
            'students', 'stats', 'classes', 'sections', 'subjects'
        ));
    }

    /**
     * عرض صفحة تقرير أداء الفصول
     */
    public function classPerformance(Request $request)
    {
        $classes = ClassModel::with('grade')->get();
        $grades = Grade::all();

        $query = ClassModel::with(['grade', 'students.user', 'students.gradeRecords']);

        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->filled('class_id')) {
            $query->where('id', $request->class_id);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('students.gradeRecords', function($q) use ($request) {
                $q->whereDate('record_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('students.gradeRecords', function($q) use ($request) {
                $q->whereDate('record_date', '<=', $request->date_to);
            });
        }

        $classesData = $query->get();

        // حساب الإحصائيات لكل فصل
        $stats = [];
        foreach ($classesData as $class) {
            $allGrades = collect();
            foreach ($class->students as $student) {
                $grades = $student->gradeRecords;
                if ($request->filled('date_from')) {
                    $grades = $grades->where('record_date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $grades = $grades->where('record_date', '<=', $request->date_to);
                }
                $allGrades = $allGrades->merge($grades);
            }

            $stats[$class->id] = [
                'total_students' => $class->students->count(),
                'total_records' => $allGrades->count(),
                'average_percentage' => $allGrades->avg('percentage') ?? 0,
                'excellent_students' => $class->students->filter(function($student) use ($allGrades) {
                    $studentGrades = $allGrades->where('student_id', $student->id);
                    return $studentGrades->avg('percentage') >= 90;
                })->count(),
                'failing_students' => $class->students->filter(function($student) use ($allGrades) {
                    $studentGrades = $allGrades->where('student_id', $student->id);
                    return $studentGrades->avg('percentage') < 50;
                })->count(),
            ];
        }

        return view('admin.pages.reports.class-performance', compact(
            'classesData', 'stats', 'classes', 'grades'
        ));
    }

    /**
     * عرض صفحة تقرير أداء المعلمين
     */
    public function teacherPerformance(Request $request)
    {
        $teachers = Teacher::with(['user', 'subjects', 'sections.class.grade']);

        if ($request->filled('teacher_id')) {
            $teachers->where('id', $request->teacher_id);
        }

        if ($request->filled('subject_id')) {
            $teachers->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject_id);
            });
        }

        $teachersData = $teachers->get();
        $subjects = Subject::all();

        // حساب الإحصائيات
        $stats = [];
        foreach ($teachersData as $teacher) {
            $students = collect();
            foreach ($teacher->sections as $section) {
                $students = $students->merge($section->students);
            }

            $allGrades = collect();
            foreach ($students as $student) {
                $grades = $student->gradeRecords;
                if ($request->filled('subject_id')) {
                    $grades = $grades->where('subject_id', $request->subject_id);
                }
                $allGrades = $allGrades->merge($grades);
            }

            $stats[$teacher->id] = [
                'total_students' => $students->unique('id')->count(),
                'total_sections' => $teacher->sections->count(),
                'total_subjects' => $teacher->subjects->count(),
                'total_grades' => $allGrades->count(),
                'average_percentage' => $allGrades->avg('percentage') ?? 0,
            ];
        }

        return view('admin.pages.reports.teacher-performance', compact(
            'teachersData', 'stats', 'subjects'
        ));
    }

    /**
     * عرض صفحة تقرير الحضور
     */
    public function attendance(Request $request)
    {
        $classes = ClassModel::with('grade')->get();
        $sections = Section::with('class.grade')->get();

        $query = Attendance::with(['student.user', 'student.class.grade', 'student.section']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('section_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // حساب الإحصائيات
        $stats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
        ];

        if ($stats['total'] > 0) {
            $stats['present_rate'] = round(($stats['present'] / $stats['total']) * 100, 2);
            $stats['absent_rate'] = round(($stats['absent'] / $stats['total']) * 100, 2);
            $stats['late_rate'] = round(($stats['late'] / $stats['total']) * 100, 2);
            $stats['excused_rate'] = round(($stats['excused'] / $stats['total']) * 100, 2);
        } else {
            $stats['present_rate'] = 0;
            $stats['absent_rate'] = 0;
            $stats['late_rate'] = 0;
            $stats['excused_rate'] = 0;
        }

        // إحصائيات حسب الطالب
        $studentStats = [];
        $students = $attendances->pluck('student')->unique('id');
        foreach ($students as $student) {
            $studentAttendances = $attendances->where('student_id', $student->id);
            $studentStats[$student->id] = [
                'total' => $studentAttendances->count(),
                'present' => $studentAttendances->where('status', 'present')->count(),
                'absent' => $studentAttendances->where('status', 'absent')->count(),
                'late' => $studentAttendances->where('status', 'late')->count(),
                'excused' => $studentAttendances->where('status', 'excused')->count(),
                'attendance_rate' => $studentAttendances->count() > 0 
                    ? round(($studentAttendances->where('status', 'present')->count() / $studentAttendances->count()) * 100, 2)
                    : 0,
            ];
        }

        return view('admin.pages.reports.attendance', compact(
            'attendances', 'stats', 'studentStats', 'classes', 'sections'
        ));
    }

    /**
     * عرض صفحة التقارير المالية
     */
    public function financial(Request $request)
    {
        $query = Invoice::with(['student.user', 'student.class.grade', 'payments']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        // حساب الإحصائيات
        $stats = [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('total_amount'),
            'paid_amount' => $invoices->sum('paid_amount'),
            'remaining_amount' => $invoices->sum('remaining_amount'),
            'pending_count' => $invoices->where('status', 'pending')->count(),
            'paid_count' => $invoices->where('status', 'paid')->count(),
            'partial_count' => $invoices->where('status', 'partial')->count(),
            'overdue_count' => $invoices->where('status', 'overdue')->count(),
        ];

        // إحصائيات المدفوعات
        $paymentsQuery = Payment::with(['student.user', 'invoice']);

        if ($request->filled('student_id')) {
            $paymentsQuery->where('student_id', $request->student_id);
        }

        if ($request->filled('date_from')) {
            $paymentsQuery->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $paymentsQuery->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $paymentsQuery->where('status', 'completed')
            ->orderBy('payment_date', 'desc')
            ->get();

        $paymentStats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
        ];

        return view('admin.pages.reports.financial', compact(
            'invoices', 'payments', 'stats', 'paymentStats'
        ));
    }

    /**
     * عرض صفحة تقرير المكتبة
     */
    public function library(Request $request)
    {
        $query = BookBorrowing::with(['student.user', 'book.category']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('borrowed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('borrowed_at', '<=', $request->date_to);
        }

        $borrowings = $query->orderBy('borrowed_at', 'desc')->get();

        // حساب الإحصائيات
        $stats = [
            'total_borrowings' => $borrowings->count(),
            'active_borrowings' => $borrowings->where('status', 'borrowed')->count(),
            'returned_borrowings' => $borrowings->where('status', 'returned')->count(),
            'overdue_borrowings' => $borrowings->where('status', 'overdue')->count(),
        ];

        // إحصائيات الغرامات
        $finesQuery = Fine::with(['bookBorrowing.student.user', 'bookBorrowing.book']);

        if ($request->filled('student_id')) {
            $finesQuery->whereHas('bookBorrowing', function($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        if ($request->filled('status')) {
            $finesQuery->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $finesQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $finesQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $fines = $finesQuery->orderBy('created_at', 'desc')->get();

        $fineStats = [
            'total_fines' => $fines->count(),
            'total_amount' => $fines->sum('amount'),
            'paid_amount' => $fines->where('status', 'paid')->sum('amount'),
            'unpaid_amount' => $fines->where('status', 'unpaid')->sum('amount'),
        ];

        return view('admin.pages.reports.library', compact(
            'borrowings', 'fines', 'stats', 'fineStats'
        ));
    }

    /**
     * عرض صفحة تقرير الاختبارات
     */
    public function exams(Request $request)
    {
        $query = Exam::with(['subject', 'section.class.grade', 'examResults.student.user']);

        if ($request->filled('exam_id')) {
            $query->where('id', $request->exam_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('exam_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('exam_date', '<=', $request->date_to);
        }

        $exams = $query->orderBy('exam_date', 'desc')->get();

        // حساب الإحصائيات
        $stats = [];
        foreach ($exams as $exam) {
            $results = $exam->examResults;
            $stats[$exam->id] = [
                'total_students' => $results->count(),
                'average_score' => $results->avg('total_score') ?? 0,
                'average_percentage' => $results->avg('percentage') ?? 0,
                'highest_score' => $results->max('total_score') ?? 0,
                'lowest_score' => $results->min('total_score') ?? 100,
                'passed_count' => $results->where('percentage', '>=', $exam->pass_percentage ?? 50)->count(),
                'failed_count' => $results->where('percentage', '<', $exam->pass_percentage ?? 50)->count(),
            ];
        }

        $subjects = Subject::all();
        $sections = Section::with('class.grade')->get();

        return view('admin.pages.reports.exams', compact(
            'exams', 'stats', 'subjects', 'sections'
        ));
    }

    /**
     * عرض صفحة تقرير الواجبات
     */
    public function assignments(Request $request)
    {
        $query = Assignment::with(['subject', 'section.class.grade', 'teacher.user', 'submissions.student.user']);

        if ($request->filled('assignment_id')) {
            $query->where('id', $request->assignment_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $assignments = $query->orderBy('created_at', 'desc')->get();

        // حساب الإحصائيات
        $stats = [];
        foreach ($assignments as $assignment) {
            $submissions = $assignment->submissions;
            $stats[$assignment->id] = [
                'total_students' => $assignment->section->students->count() ?? 0,
                'submitted_count' => $submissions->count(),
                'pending_count' => ($assignment->section->students->count() ?? 0) - $submissions->count(),
                'graded_count' => $submissions->whereNotNull('grade')->count(),
                'average_grade' => $submissions->whereNotNull('grade')->avg('grade') ?? 0,
                'on_time_count' => $submissions->where('is_late', false)->count(),
                'late_count' => $submissions->where('is_late', true)->count(),
            ];
        }

        $subjects = Subject::all();
        $sections = Section::with('class.grade')->get();

        return view('admin.pages.reports.assignments', compact(
            'assignments', 'stats', 'subjects', 'sections'
        ));
    }

    /**
     * عرض صفحة تقرير الدرجات
     */
    public function grades(Request $request)
    {
        $classes = ClassModel::with('grade')->get();
        $sections = Section::with('class.grade')->get();
        $subjects = Subject::all();

        $query = GradeRecord::with(['student.user', 'student.class.grade', 'student.section', 'subject']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('section_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('record_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('record_date', '<=', $request->date_to);
        }

        $gradeRecords = $query->orderBy('record_date', 'desc')->get();

        // حساب الإحصائيات
        $stats = [
            'total_records' => $gradeRecords->count(),
            'average_percentage' => $gradeRecords->avg('percentage') ?? 0,
            'highest_percentage' => $gradeRecords->max('percentage') ?? 0,
            'lowest_percentage' => $gradeRecords->min('percentage') ?? 100,
            'excellent_count' => $gradeRecords->where('percentage', '>=', 90)->count(),
            'good_count' => $gradeRecords->whereBetween('percentage', [75, 89])->count(),
            'pass_count' => $gradeRecords->whereBetween('percentage', [50, 74])->count(),
            'fail_count' => $gradeRecords->where('percentage', '<', 50)->count(),
        ];

        return view('admin.pages.reports.grades', compact(
            'gradeRecords', 'stats', 'classes', 'sections', 'subjects'
        ));
    }

    /**
     * تصدير التقرير
     */
    public function export(Request $request)
    {
        $type = $request->input('type');
        $format = $request->input('format', 'pdf');
        $filters = $request->except(['type', 'format', '_token']);

        // حفظ التقرير في قاعدة البيانات
        $report = Report::create([
            'name' => $this->getReportName($type),
            'type' => $type,
            'description' => 'تقرير ' . $this->getReportName($type),
            'filters' => $filters,
            'format' => $format,
            'status' => 'generating',
            'created_by' => Auth::id(),
        ]);

        // هنا يمكن إضافة منطق التصدير الفعلي (PDF, Excel, CSV)
        // حالياً سنعيد البيانات فقط

        $report->update([
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'تم إنشاء التقرير بنجاح');
    }

    /**
     * الحصول على اسم التقرير
     */
    private function getReportName($type)
    {
        $names = [
            'student_performance' => 'أداء الطلاب',
            'class_performance' => 'أداء الفصول',
            'teacher_performance' => 'أداء المعلمين',
            'attendance' => 'الحضور والغياب',
            'financial' => 'التقارير المالية',
            'library' => 'المكتبة',
            'exams' => 'الاختبارات',
            'assignments' => 'الواجبات',
            'grades' => 'الدرجات',
        ];

        return $names[$type] ?? $type;
    }
}
