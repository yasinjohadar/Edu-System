<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\GradeRecord;
use App\Models\Certificate;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Assignment;
use App\Models\FinancialAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dashboard-view');
    }

    /**
     * عرض لوحة تحكم الأدمن
     */
    public function index()
    {
        // إحصائيات الطلاب
        $studentsStats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'new_this_month' => Student::whereMonth('enrollment_date', Carbon::now()->month)
                ->whereYear('enrollment_date', Carbon::now()->year)
                ->count(),
        ];

        // إحصائيات المعلمين
        $teachersStats = [
            'total' => Teacher::count(),
            'active' => Teacher::whereHas('user', function($query) {
                $query->where('status', 'active');
            })->count(),
        ];

        // إحصائيات أولياء الأمور
        $parentsStats = [
            'total' => ParentModel::count(),
        ];

        // إحصائيات الحضور
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $attendanceStats = [
            'today_present' => Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->count(),
            'today_absent' => Attendance::whereDate('date', $today)
                ->where('status', 'absent')
                ->count(),
            'today_late' => Attendance::whereDate('date', $today)
                ->where('status', 'late')
                ->count(),
            'week_present' => Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->where('status', 'present')
                ->count(),
            'week_total' => Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->count(),
        ];

        // حساب نسبة الحضور لهذا الأسبوع
        $attendanceStats['week_rate'] = $attendanceStats['week_total'] > 0 
            ? round(($attendanceStats['week_present'] / $attendanceStats['week_total']) * 100, 1)
            : 0;

        // إحصائيات مالية
        $financialStats = [
            'total_invoices' => Invoice::count(),
            'total_invoices_amount' => Invoice::sum('total_amount'),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
            'pending_amount' => Invoice::where('status', 'pending')->sum('total_amount'),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'overdue_amount' => Invoice::where('status', 'overdue')->sum('remaining_amount'),
            'partial_invoices' => Invoice::where('status', 'partial')->count(),
            'partial_amount' => Invoice::where('status', 'partial')->sum('remaining_amount'),
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'this_month_payments' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', Carbon::now()->month)
                ->whereYear('payment_date', Carbon::now()->year)
                ->sum('amount'),
            'total_balance' => FinancialAccount::sum('balance'),
        ];

        // إحصائيات الدرجات
        $gradeStats = [
            'total_records' => GradeRecord::count(),
            'average_percentage' => GradeRecord::avg('percentage') ?? 0,
            'excellent_students' => GradeRecord::select('student_id')
                ->groupBy('student_id')
                ->havingRaw('AVG(percentage) >= ?', [90])
                ->count(),
            'failing_students' => GradeRecord::select('student_id')
                ->groupBy('student_id')
                ->havingRaw('AVG(percentage) < ?', [50])
                ->count(),
        ];

        // آخر الطلاب المسجلين
        $recentStudents = Student::with(['user', 'class', 'section'])
            ->orderBy('enrollment_date', 'desc')
            ->limit(5)
            ->get();

        // آخر الفواتير
        $recentInvoices = Invoice::with(['student.user', 'student.class'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // آخر المدفوعات
        $recentPayments = Payment::with(['student.user', 'invoice'])
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // الفواتير المتأخرة
        $overdueInvoices = Invoice::with(['student.user', 'student.class'])
            ->where('status', 'overdue')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // الطلاب الأكثر غياباً (آخر 30 يوم)
        $mostAbsentStudents = Student::with(['user', 'class', 'section'])
            ->select('students.id', 'students.user_id', 'students.class_id', 'students.section_id', 'students.status', 'students.student_code', 'students.date_of_birth', 'students.address', 'students.created_at', 'students.updated_at')
            ->selectRaw('COUNT(attendances.id) as absent_count')
            ->leftJoin('attendances', 'students.id', '=', 'attendances.student_id')
            ->where('attendances.status', 'absent')
            ->where('attendances.date', '>=', Carbon::now()->subDays(30))
            ->groupBy('students.id', 'students.user_id', 'students.class_id', 'students.section_id', 'students.status', 'students.student_code', 'students.date_of_birth', 'students.address', 'students.created_at', 'students.updated_at')
            ->orderBy('absent_count', 'desc')
            ->limit(5)
            ->get();

        // إحصائيات حسب الصف
        $classStats = DB::table('students')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name as class_name', DB::raw('COUNT(students.id) as student_count'))
            ->where('students.status', 'active')
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('student_count', 'desc')
            ->get();

        // إحصائيات المدفوعات لهذا الشهر (آخر 7 أيام)
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'amount' => Payment::whereDate('payment_date', $date)
                    ->where('status', 'completed')
                    ->sum('amount'),
            ];
        }

        // إحصائيات المدفوعات لهذا الشهر (آخر 7 أيام)
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'amount' => Payment::whereDate('payment_date', $date)
                    ->where('status', 'completed')
                    ->sum('amount'),
            ];
        }

        $totalSubjects = Subject::count();
        $activeSubjects = Subject::where('is_active', true)->count();
        $certificatesToday = Certificate::whereDate('issue_date', $today)->count();

        $dashboardWidgets = [
            [
                'title' => 'إجمالي الطلاب',
                'value' => $studentsStats['total'],
                'subtext' => $studentsStats['active'] . ' نشط · ' . $studentsStats['new_this_month'] . ' جديد هذا الشهر',
                'icon' => 'ri-group-line',
                'theme' => 'purple',
                'route' => 'admin.students.index',
            ],
            [
                'title' => 'الالتحاقات النشطة',
                'value' => $studentsStats['active'],
                'subtext' => 'إجمالي ' . number_format($studentsStats['total']) . ' التحاق',
                'icon' => 'ri-user-add-line',
                'theme' => 'green',
                'route' => 'admin.students.index',
            ],
            [
                'title' => 'المواد النشطة',
                'value' => $activeSubjects,
                'subtext' => 'من أصل ' . $totalSubjects . ' مادة',
                'icon' => 'ri-book-open-line',
                'theme' => 'blue',
                'route' => 'admin.subjects.index',
            ],
            [
                'title' => 'الشهادات الصادرة',
                'value' => Certificate::count(),
                'subtext' => $certificatesToday . ' اليوم',
                'icon' => 'ri-award-line',
                'theme' => 'orange',
                'route' => 'admin.certificates.index',
            ],
        ];

        $enrollmentChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $enrollmentChart[] = [
                'label' => $month->translatedFormat('M'),
                'count' => Student::whereMonth('enrollment_date', $month->month)
                    ->whereYear('enrollment_date', $month->year)
                    ->count(),
            ];
        }

        $todaySummary = [
            [
                'label' => 'حضور اليوم',
                'value' => $attendanceStats['today_present'],
                'icon' => 'ri-user-follow-line',
                'color' => 'green',
            ],
            [
                'label' => 'غياب اليوم',
                'value' => $attendanceStats['today_absent'],
                'icon' => 'ri-user-unfollow-line',
                'color' => 'red',
            ],
            [
                'label' => 'مدفوعات اليوم',
                'value' => number_format(
                    Payment::whereDate('payment_date', $today)->where('status', 'completed')->sum('amount'),
                    0
                ) . ' ر.س',
                'icon' => 'ri-wallet-3-line',
                'color' => 'blue',
            ],
            [
                'label' => 'فواتير جديدة',
                'value' => Invoice::whereDate('created_at', $today)->count(),
                'icon' => 'ri-file-list-3-line',
                'color' => 'orange',
            ],
            [
                'label' => 'اختبارات نشطة',
                'value' => Exam::where('is_published', true)->where('is_active', true)->count(),
                'icon' => 'ri-survey-line',
                'color' => 'purple',
            ],
            [
                'label' => 'واجبات مفتوحة',
                'value' => Assignment::where('status', 'published')->where('is_active', true)->count(),
                'icon' => 'ri-file-text-line',
                'color' => 'teal',
            ],
        ];

        $quickShortcuts = array_values(array_filter([
            ['title' => 'الواجبات', 'desc' => 'إدارة الواجبات', 'route' => 'admin.assignments.index', 'icon' => 'ri-file-text-line', 'color' => 'orange'],
            ['title' => 'الاختبارات', 'desc' => 'إدارة الاختبارات', 'route' => 'admin.exams.index', 'icon' => 'ri-survey-line', 'color' => 'pink'],
            ['title' => 'الشهادات', 'desc' => 'إصدار الشهادات', 'route' => 'admin.certificates.index', 'icon' => 'ri-award-line', 'color' => 'gold'],
            ['title' => 'الطلاب', 'desc' => 'إدارة الطلاب', 'route' => 'admin.students.index', 'icon' => 'ri-graduation-cap-line', 'color' => 'orange'],
            ['title' => 'المواد الدراسية', 'desc' => 'إدارة المواد', 'route' => 'admin.subjects.index', 'icon' => 'ri-book-open-line', 'color' => 'blue'],
            ['title' => 'المستخدمون', 'desc' => 'إدارة المستخدمين', 'route' => 'users.index', 'icon' => 'ri-group-line', 'color' => 'teal'],
            ['title' => 'الصلاحيات', 'desc' => 'إدارة الصلاحيات', 'route' => 'roles.index', 'icon' => 'ri-shield-keyhole-line', 'color' => 'pink'],
            ['title' => 'الحضور', 'desc' => 'سجل الحضور', 'route' => 'admin.attendances.index', 'icon' => 'ri-calendar-check-line', 'color' => 'green'],
            ['title' => 'المدفوعات', 'desc' => 'إدارة المدفوعات', 'route' => 'admin.payments.index', 'icon' => 'ri-wallet-3-line', 'color' => 'green'],
            ['title' => 'الفواتير', 'desc' => 'إدارة الفواتير', 'route' => 'admin.invoices.index', 'icon' => 'ri-file-list-3-line', 'color' => 'blue'],
            ['title' => 'بنك الأسئلة', 'desc' => 'إدارة الأسئلة', 'route' => 'admin.questions.index', 'icon' => 'ri-questionnaire-line', 'color' => 'purple'],
            ['title' => 'التقارير', 'desc' => 'عرض التقارير', 'route' => 'admin.reports.index', 'icon' => 'ri-file-chart-line', 'color' => 'indigo'],
            ['title' => 'المحاضرات', 'desc' => 'المحاضرات الإلكترونية', 'route' => 'admin.online-lectures.index', 'icon' => 'ri-live-line', 'color' => 'purple'],
            ['title' => 'المكتبة', 'desc' => 'إدارة الكتب', 'route' => 'admin.books.index', 'icon' => 'ri-book-2-line', 'color' => 'brown'],
            ['title' => 'المعلمون', 'desc' => 'إدارة المعلمين', 'route' => 'admin.teachers.index', 'icon' => 'ri-user-star-line', 'color' => 'cyan'],
            ['title' => 'الأحداث', 'desc' => 'التقويم والأحداث', 'route' => 'admin.events.index', 'icon' => 'ri-calendar-event-line', 'color' => 'red'],
        ], fn ($item) => Route::has($item['route'])));

        return view('admin.dashboard', compact(
            'studentsStats',
            'teachersStats',
            'parentsStats',
            'attendanceStats',
            'financialStats',
            'gradeStats',
            'recentStudents',
            'recentInvoices',
            'recentPayments',
            'overdueInvoices',
            'mostAbsentStudents',
            'classStats',
            'last7Days',
            'dashboardWidgets',
            'enrollmentChart',
            'todaySummary',
            'quickShortcuts'
        ));
    }
}

