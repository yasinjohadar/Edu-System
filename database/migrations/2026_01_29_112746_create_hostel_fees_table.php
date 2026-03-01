<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            $table->string('fee_month')->comment('الشهر');
            $table->decimal('amount', 10, 2)->comment('المبلغ');
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending')->comment('الحالة');
            $table->date('due_date')->nullable()->comment('تاريخ الاستحقاق');
            $table->date('paid_date')->nullable()->comment('تاريخ الدفع');
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('hostel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_fees');
    }
};
