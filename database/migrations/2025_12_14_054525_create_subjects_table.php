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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم المادة');
            $table->string('name_en')->nullable();
            $table->string('code')->unique()->nullable()->comment('رمز المادة');
            $table->enum('type', ['required', 'optional'])->default('required')->comment('نوع المادة (إجباري، اختياري)');
            $table->integer('weekly_hours')->default(0)->comment('عدد الحصص الأسبوعية');
            $table->decimal('full_marks', 5, 2)->default(100)->comment('الدرجة الكاملة');
            $table->decimal('pass_marks', 5, 2)->default(50)->comment('درجة النجاح');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
