<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض الفواتير
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $query = $student->invoices()->with('items.feeType')->orderBy('invoice_date', 'desc');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->where('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->paginate(20);

        // إحصائيات
        $totalInvoices = $student->invoices()->count();
        $paidInvoices = $student->invoices()->where('status', 'paid')->count();
        $pendingInvoices = $student->invoices()->whereIn('status', ['pending', 'partial', 'overdue'])->count();
        $totalAmount = $student->invoices()->sum('total_amount');
        $paidAmount = $student->invoices()->sum('paid_amount');
        $remainingAmount = $totalAmount - $paidAmount;

        $stats = [
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'pending_invoices' => $pendingInvoices,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remainingAmount,
        ];

        return view('student.pages.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * عرض تفاصيل الفاتورة
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $invoice = Invoice::where('student_id', $student->id)
            ->with(['items.feeType', 'payments'])
            ->findOrFail($id);

        return view('student.pages.invoices.show', compact('invoice'));
    }
}

