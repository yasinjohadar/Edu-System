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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->comment('الطالب');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null')->comment('الفاتورة');
            $table->foreignId('financial_account_id')->nullable()->constrained('financial_accounts')->onDelete('set null')->comment('الحساب المالي');
            $table->string('payment_number')->unique()->comment('رقم الدفعة');
            $table->date('payment_date')->comment('تاريخ الدفع');
            $table->decimal('amount', 12, 2)->comment('مبلغ الدفع');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'card', 'check', 'online', 'other'])->default('cash')->comment('طريقة الدفع');
            $table->string('reference_number')->nullable()->comment('رقم المرجع (رقم الشيك، رقم التحويل، إلخ)');
            $table->string('bank_name')->nullable()->comment('اسم البنك');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed')->comment('حالة الدفع');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null')->comment('استلم بواسطة');
            $table->timestamp('processed_at')->nullable()->comment('تاريخ المعالجة');
            $table->timestamps();
            
            $table->index('payment_number');
            $table->index('student_id');
            $table->index('invoice_id');
            $table->index('payment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
