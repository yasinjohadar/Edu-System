<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->comment('المبلغ');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'card', 'online', 'other'])->default('bank_transfer')->comment('طريقة الدفع');
            $table->date('donation_date')->comment('تاريخ التبرع');
            $table->text('purpose')->nullable()->comment('الغرض');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->comment('الحالة');
            $table->string('reference_number')->nullable()->comment('رقم المرجع');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
            
            $table->index('alumni_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_donations');
    }
};
