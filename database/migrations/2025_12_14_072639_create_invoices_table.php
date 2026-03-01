<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->comment('الطالب');
            $table->foreignId('financial_account_id')->nullable()->constrained('financial_accounts')->onDelete('set null')->comment('الحساب المالي');
            $table->string('invoice_number')->unique()->comment('رقم الفاتورة');
            $table->date('invoice_date')->comment('تاريخ الفاتورة');
            $table->date('due_date')->comment('تاريخ الاستحقاق');
            $table->enum('status', ['draft', 'pending', 'partial', 'paid', 'overdue', 'cancelled'])->default('pending')->comment('حالة الفاتورة');
            $table->decimal('subtotal', 12, 2)->default(0)->comment('المجموع الفرعي');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('مبلغ الخصم');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('مبلغ الضريبة');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('المبلغ الإجمالي');
            $table->decimal('paid_amount', 12, 2)->default(0)->comment('المبلغ المدفوع');
            $table->decimal('remaining_amount', 12, 2)->default(0)->comment('المبلغ المتبقي');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->text('terms')->nullable()->comment('شروط الدفع');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('أنشئ بواسطة');
            $table->timestamp('paid_at')->nullable()->comment('تاريخ الدفع الكامل');
            $table->timestamps();
            
            $table->index('invoice_number');
            $table->index('student_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('invoice_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
