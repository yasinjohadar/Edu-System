<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\FinancialAccount;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoices = Invoice::where('status', '!=', 'cancelled')
            ->where('remaining_amount', '>', 0)
            ->with('student')
            ->get();

        if ($invoices->isEmpty()) {
            $this->command->warn('لا توجد فواتير متاحة. يرجى تشغيل InvoiceSeeder أولاً.');
            return;
        }

        $paymentsCreated = 0;
        $paymentMethods = ['cash', 'bank_transfer', 'card', 'check', 'online'];

        foreach ($invoices as $invoice) {
            $financialAccount = $invoice->financialAccount;
            if (!$financialAccount) {
                continue;
            }

            // 60% من الفواتير لها مدفوعات
            if (rand(1, 10) <= 6) {
                $remainingAmount = $invoice->remaining_amount;
                
                // 70% دفع كامل، 30% دفع جزئي
                if (rand(1, 10) <= 7) {
                    $paymentAmount = $remainingAmount;
                } else {
                    $paymentAmount = $remainingAmount * (rand(30, 90) / 100);
                }

                $year = $invoice->invoice_date->format('Y');
                $month = $invoice->invoice_date->format('m');
                
                // البحث عن آخر رقم دفعة في نفس الشهر
                $lastPayment = Payment::where('payment_number', 'like', 'PAY-' . $year . $month . '-%')
                    ->orderByRaw('CAST(SUBSTRING(payment_number, -4) AS UNSIGNED) DESC')
                    ->first();

                if ($lastPayment) {
                    $lastNumber = (int) substr($lastPayment->payment_number, -4);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                $paymentNumber = 'PAY-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                
                // التحقق من عدم التكرار
                while (Payment::where('payment_number', $paymentNumber)->exists()) {
                    $newNumber++;
                    $paymentNumber = 'PAY-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                }
                $paymentDate = $invoice->invoice_date->copy()->addDays(rand(1, 60));

                Payment::create([
                    'student_id' => $invoice->student_id,
                    'invoice_id' => $invoice->id,
                    'financial_account_id' => $financialAccount->id,
                    'payment_number' => $paymentNumber,
                    'payment_date' => $paymentDate,
                    'amount' => round($paymentAmount, 2),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'reference_number' => rand(1, 10) <= 5 ? 'REF-' . rand(1000, 9999) : null,
                    'bank_name' => rand(1, 10) <= 3 ? ['البنك الأهلي', 'البنك السعودي الفرنسي', 'البنك العربي'][rand(0, 2)] : null,
                    'notes' => rand(1, 10) <= 3 ? 'دفعة شهرية' : null,
                    'status' => 'completed',
                    'received_by' => 1,
                    'processed_at' => $paymentDate,
                ]);

                // تحديث الفاتورة
                $invoice->updateStatus();
                $paymentsCreated++;
            }
        }

        // تحديث جميع الحسابات المالية
        FinancialAccount::chunk(50, function ($accounts) {
            foreach ($accounts as $account) {
                $account->updateBalance();
            }
        });

        $this->command->info("تم إنشاء {$paymentsCreated} دفعة بنجاح!");
    }
}
