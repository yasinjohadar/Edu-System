<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\FeeType;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:invoice-list|invoice-create|invoice-edit|invoice-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:invoice-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:invoice-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:invoice-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['student.user', 'financialAccount']);

        // فلترة حسب الطالب
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // فلترة حسب رقم الفاتورة
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        $invoices = $query->orderBy('invoice_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $students = Student::with('user')->where('status', 'active')->get();
        $statuses = [
            'draft' => 'مسودة',
            'pending' => 'معلقة',
            'partial' => 'مدفوعة جزئياً',
            'paid' => 'مدفوعة',
            'overdue' => 'متأخرة',
            'cancelled' => 'ملغاة',
        ];

        return view('admin.pages.invoices.index', compact('invoices', 'students', 'statuses'));
    }

    public function create()
    {
        $students = Student::with('user')->where('status', 'active')->get();
        $feeTypes = FeeType::where('is_active', true)->orderBy('sort_order')->get();
        
        return view('admin.pages.invoices.create', compact('students', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.fee_type_id' => 'nullable|exists:fee_types,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
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

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'student_id' => $student->id,
                'financial_account_id' => $financialAccount->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'status' => 'pending',
                'subtotal' => 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'remaining_amount' => 0,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // إضافة عناصر الفاتورة
            $subtotal = 0;
            foreach ($validated['items'] as $index => $item) {
                $itemSubtotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
                $itemTotal = $itemSubtotal + ($item['tax'] ?? 0);
                $subtotal += $itemSubtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_type_id' => $item['fee_type_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $itemTotal,
                    'sort_order' => $index,
                ]);
            }

            // حساب المبالغ النهائية
            $totalAmount = $subtotal - ($validated['discount_amount'] ?? 0) + ($validated['tax_amount'] ?? 0);

            $invoice->update([
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'remaining_amount' => $totalAmount,
            ]);

            // تحديث الحساب المالي
            $financialAccount->updateBalance();

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'تم إنشاء الفاتورة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(string $id)
    {
        $invoice = Invoice::with(['student.user', 'items.feeType', 'payments', 'financialAccount', 'creator'])->findOrFail($id);
        return view('admin.pages.invoices.show', compact('invoice'));
    }

    public function edit(string $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        if ($invoice->status == 'paid' || $invoice->status == 'cancelled') {
            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('error', 'لا يمكن تعديل فاتورة مدفوعة أو ملغاة.');
        }

        $students = Student::with('user')->where('status', 'active')->get();
        $feeTypes = FeeType::where('is_active', true)->orderBy('sort_order')->get();
        
        return view('admin.pages.invoices.edit', compact('invoice', 'students', 'feeTypes'));
    }

    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status == 'paid' || $invoice->status == 'cancelled') {
            return back()->withErrors(['error' => 'لا يمكن تعديل فاتورة مدفوعة أو ملغاة.']);
        }

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.fee_type_id' => 'nullable|exists:fee_types,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // حذف العناصر القديمة
            $invoice->items()->delete();

            // إضافة العناصر الجديدة
            $subtotal = 0;
            foreach ($validated['items'] as $index => $item) {
                $itemSubtotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
                $itemTotal = $itemSubtotal + ($item['tax'] ?? 0);
                $subtotal += $itemSubtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_type_id' => $item['fee_type_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $itemTotal,
                    'sort_order' => $index,
                ]);
            }

            // حساب المبالغ النهائية
            $totalAmount = $subtotal - ($validated['discount_amount'] ?? 0) + ($validated['tax_amount'] ?? 0);
            $remainingAmount = $totalAmount - $invoice->paid_amount;

            $invoice->update([
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'total_amount' => $totalAmount,
                'remaining_amount' => $remainingAmount,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
            ]);

            // تحديث حالة الفاتورة
            $invoice->updateStatus();

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'تم تحديث الفاتورة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الفاتورة: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status == 'paid') {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'لا يمكن حذف فاتورة مدفوعة.');
        }

        if ($invoice->payments()->count() > 0) {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'لا يمكن حذف فاتورة لها مدفوعات.');
        }

        DB::beginTransaction();
        try {
            $financialAccount = $invoice->financialAccount;
            
            $invoice->items()->delete();
            $invoice->delete();

            if ($financialAccount) {
                $financialAccount->updateBalance();
            }

            DB::commit();

            return redirect()->route('admin.invoices.index')
                ->with('success', 'تم حذف الفاتورة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.invoices.index')
                ->with('error', 'حدث خطأ أثناء حذف الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * الحصول على الفواتير كـ JSON
     */
    public function getInvoicesJson(Request $request)
    {
        $query = Invoice::with('student.user')
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $invoices = $query->get()->map(function($invoice) {
            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'remaining_amount' => number_format($invoice->remaining_amount, 2),
            ];
        });

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * توليد رقم فاتورة فريد
     */
    private function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'INV-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
