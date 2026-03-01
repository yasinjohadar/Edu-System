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
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->onDelete('cascade')->comment('الطالب');
            $table->string('account_number')->unique()->comment('رقم الحساب');
            $table->decimal('balance', 12, 2)->default(0)->comment('رصيد الحساب');
            $table->decimal('total_invoiced', 12, 2)->default(0)->comment('إجمالي الفواتير');
            $table->decimal('total_paid', 12, 2)->default(0)->comment('إجمالي المدفوعات');
            $table->decimal('total_due', 12, 2)->default(0)->comment('إجمالي المستحقات');
            $table->date('last_transaction_date')->nullable()->comment('تاريخ آخر معاملة');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->boolean('is_active')->default(true)->comment('هل الحساب نشط');
            $table->timestamps();
            
            $table->index('account_number');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
