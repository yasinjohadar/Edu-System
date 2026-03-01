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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('teacher_code')->unique()->comment('رقم المعلم الفريد');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date')->nullable()->comment('تاريخ التعيين');
            $table->string('qualification')->nullable()->comment('المؤهل العلمي');
            $table->string('specialization')->nullable()->comment('التخصص');
            $table->string('experience_years')->nullable()->comment('سنوات الخبرة');
            $table->decimal('salary', 10, 2)->nullable()->comment('الراتب');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'resigned'])->default('active');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->string('photo')->nullable()->comment('صورة المعلم');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
