<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'invoice', 'financialAccount']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $students = Student::with('user')->where('status', 'active')->get();
        $invoices = Invoice::where('status', '!=', 'paid')->where('status', '!=', 'cancelled')->get();

        return view('admin.pages.payments.index', compact('payments', 'students', 'invoices'));
    }

    public function create(Request $request)
    {
        $students = Student::with('user')->where('status', 'active')->get();
        $invoices = Invoice::where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->with('student.user')
            ->get();

        $selectedInvoice = null;
        if ($request->filled('invoice_id')) {
            $selectedInvoice = Invoice::with('student.user')->find($request->invoice_id);
        }

        return view('admin.pages.payments.create', compact('students', 'invoices', 'selectedInvoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,card,check,online,other',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::findOrFail($validated['student_id']);
            
            // إنشاء أو الحصول على الحساب المالي
            $financialAccount = $student->financialAccount;
            if (!$financialAccount) {
                $financialAccount = FinancialAccount::create([
                    'student_id' => $student->id,
                    'account_number' => 'ACC-' . str_pad($student->id, 6, '0', STR_PAD_LEFT),
                    'balance' => 0,
                    'total_invoiced' => 0,
                    'total_paid' => 0,
                    'total_due' => 0,
                    'is_active' => true,
                ]);
            }

            // التحقق من المبلغ إذا كانت هناك فاتورة
            if ($validated['invoice_id']) {
                $invoice = Invoice::findOrFail($validated['invoice_id']);
                if ($validated['amount'] > $invoice->remaining_amount) {
                    return back()->withErrors(['amount' => 'المبلغ المدفوع أكبر من المبلغ المتبقي في الفاتورة.'])->withInput();
                }
            }

            // إنشاء الدفعة
            $payment = Payment::create([
                'student_id' => $student->id,
                'invoice_id' => $validated['invoice_id'] ?? null,
                'financial_account_id' => $financialAccount->id,
                'payment_number' => $this->generatePaymentNumber(),
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
                'received_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // تحديث الفاتورة إذا كانت موجودة
            if ($validated['invoice_id']) {
                $invoice->updateStatus();
            }

            // تحديث الحساب المالي
            $financialAccount->updateBalance();

            DB::commit();

            return redirect()->route('admin.payments.show', $payment->id)
                ->with('success', 'تم تسجيل الدفعة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء تسجيل الدفعة: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(string $id)
    {
        $payment = Payment::with(['student.user', 'invoice', 'financialAccount', 'receiver'])->findOrFail($id);
        return view('admin.pages.payments.show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->status == 'refunded') {
            return redirect()->route('admin.payments.show', $payment->id)
                ->with('error', 'لا يمكن تعديل دفعة مستردة.');
        }

        $students = Student::with('user')->where('status', 'active')->get();
        $invoices = Invoice::where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->with('student.user')
            ->get();

        return view('admin.pages.payments.edit', compact('payment', 'students', 'invoices'));
    }

    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status == 'refunded') {
            return back()->withErrors(['error' => 'لا يمكن تعديل دفعة مستردة.']);
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,card,check,online,other',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        DB::beginTransaction();
        try {
            $oldAmount = $payment->amount;
            $oldInvoice = $payment->invoice;

            $payment->update($validated);

            // تحديث الفاتورة القديمة
            if ($oldInvoice) {
                $oldInvoice->updateStatus();
            }

            // تحديث الفاتورة الجديدة
            if ($payment->invoice) {
                $payment->invoice->updateStatus();
            }

            // تحديث الحساب المالي
            if ($payment->financialAccount) {
                $payment->financialAccount->updateBalance();
            }

            DB::commit();

            return redirect()->route('admin.payments.show', $payment->id)
                ->with('success', 'تم تحديث الدفعة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الدفعة: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status == 'completed') {
            return redirect()->route('admin.payments.index')
                ->with('error', 'لا يمكن حذف دفعة مكتملة. يرجى استردادها بدلاً من ذلك.');
        }

        DB::beginTransaction();
        try {
            $invoice = $payment->invoice;
            $financialAccount = $payment->financialAccount;

            $payment->delete();

            if ($invoice) {
                $invoice->updateStatus();
            }

            if ($financialAccount) {
                $financialAccount->updateBalance();
            }

            DB::commit();

            return redirect()->route('admin.payments.index')
                ->with('success', 'تم حذف الدفعة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.payments.index')
                ->with('error', 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage());
        }
    }

    private function generatePaymentNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        
        $lastPayment = Payment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->payment_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'PAY-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
