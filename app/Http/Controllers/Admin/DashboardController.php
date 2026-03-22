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
use App\Models\FinancialAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            ->groupBy('students.id')
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
            'last7Days'
        ));
    }
}

