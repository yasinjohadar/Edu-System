<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\FeeType;
use App\Models\FinancialAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::where('status', 'active')->get();
        $feeTypes = FeeType::where('is_active', true)->get();

        if ($students->isEmpty() || $feeTypes->isEmpty()) {
            $this->command->warn('لا توجد طلاب أو أنواع رسوم. يرجى تشغيل seeders أخرى أولاً.');
            return;
        }

        $invoicesCreated = 0;
        $currentYear = date('Y');
        $academicYear = ($currentYear - 1) . '-' . $currentYear;

        foreach ($students as $student) {
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

            // إنشاء فاتورة تسجيل (مرة واحدة)
            $registrationFee = $feeTypes->where('category', 'registration')->first();
            if ($registrationFee) {
                $this->createInvoice($student, $financialAccount, [
                    ['fee_type' => $registrationFee, 'name' => 'رسوم التسجيل', 'amount' => $registrationFee->default_amount],
                ], Carbon::parse($student->enrollment_date ?? now()), 'registration');
                $invoicesCreated++;
            }

            // إنشاء فاتورة الفصل الأول
            $tuitionFirst = $feeTypes->where('code', 'TUI-001')->first();
            $booksFee = $feeTypes->where('category', 'book')->first();
            $uniformFee = $feeTypes->where('category', 'uniform')->first();
            
            if ($tuitionFirst) {
                $items = [
                    ['fee_type' => $tuitionFirst, 'name' => 'الرسوم الدراسية - الفصل الأول', 'amount' => $tuitionFirst->default_amount],
                ];
                
                if ($booksFee) {
                    $items[] = ['fee_type' => $booksFee, 'name' => 'رسوم الكتب والقرطاسية', 'amount' => $booksFee->default_amount];
                }
                
                if ($uniformFee && rand(1, 2) == 1) {
                    $items[] = ['fee_type' => $uniformFee, 'name' => 'رسوم الزي المدرسي', 'amount' => $uniformFee->default_amount];
                }

                $this->createInvoice($student, $financialAccount, $items, Carbon::now()->startOfYear()->addMonths(8), 'tuition-first');
                $invoicesCreated++;
            }

            // إنشاء فاتورة الفصل الثاني
            $tuitionSecond = $feeTypes->where('code', 'TUI-002')->first();
            if ($tuitionSecond) {
                $this->createInvoice($student, $financialAccount, [
                    ['fee_type' => $tuitionSecond, 'name' => 'الرسوم الدراسية - الفصل الثاني', 'amount' => $tuitionSecond->default_amount],
                ], Carbon::now()->startOfYear()->addMonths(1), 'tuition-second');
                $invoicesCreated++;
            }

            // إنشاء فاتورة نشاطات (احتمال 70%)
            if (rand(1, 10) <= 7) {
                $activityFee = $feeTypes->where('category', 'activity')->first();
                if ($activityFee) {
                    $this->createInvoice($student, $financialAccount, [
                        ['fee_type' => $activityFee, 'name' => 'رسوم النشاطات', 'amount' => $activityFee->default_amount],
                    ], Carbon::now()->subMonths(rand(1, 6)), 'activity');
                    $invoicesCreated++;
                }
            }

            // تحديث الحساب المالي
            $financialAccount->updateBalance();
        }

        $this->command->info("تم إنشاء {$invoicesCreated} فاتورة بنجاح!");
    }

    private function createInvoice($student, $financialAccount, $items, $invoiceDate, $type): void
    {
        $year = $invoiceDate->format('Y');
        $month = $invoiceDate->format('m');
        
        // البحث عن آخر رقم فاتورة في نفس الشهر
        $lastInvoice = Invoice::where('invoice_number', 'like', 'INV-' . $year . $month . '-%')
            ->orderByRaw('CAST(SUBSTRING(invoice_number, -4) AS UNSIGNED) DESC')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $invoiceNumber = 'INV-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        // التحقق من عدم التكرار
        while (Invoice::where('invoice_number', $invoiceNumber)->exists()) {
            $newNumber++;
            $invoiceNumber = 'INV-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }
        $dueDate = $invoiceDate->copy()->addDays(30);

        // حساب المبالغ
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['amount'];
        }

        $discountAmount = 0;
        $taxAmount = 0;
        $totalAmount = $subtotal - $discountAmount + $taxAmount;

        // إنشاء الفاتورة
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'financial_account_id' => $financialAccount->id,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'status' => rand(1, 10) <= 3 ? 'paid' : (rand(1, 10) <= 2 ? 'partial' : 'pending'),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'remaining_amount' => $totalAmount,
            'created_by' => 1,
        ]);

        // إضافة عناصر الفاتورة
        foreach ($items as $index => $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'fee_type_id' => $item['fee_type']->id,
                'item_name' => $item['name'],
                'description' => $item['fee_type']->description,
                'quantity' => 1,
                'unit_price' => $item['amount'],
                'discount' => 0,
                'tax' => 0,
                'total' => $item['amount'],
                'sort_order' => $index,
            ]);
        }

        // تحديث حالة الفاتورة
        $invoice->updateStatus();
    }
}
