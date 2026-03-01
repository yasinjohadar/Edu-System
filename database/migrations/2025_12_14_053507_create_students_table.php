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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('student_code')->unique()->comment('رقم القيد الفريد');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->date('enrollment_date')->nullable()->comment('تاريخ التسجيل');
            $table->enum('status', ['active', 'graduated', 'transferred', 'suspended'])->default('active');
            $table->unsignedBigInteger('class_id')->nullable()->comment('سيتم إضافة foreign key لاحقاً');
            $table->unsignedBigInteger('section_id')->nullable()->comment('سيتم إضافة foreign key لاحقاً');
            $table->string('parent_guardian')->nullable()->comment('اسم ولي الأمر الأساسي');
            $table->string('emergency_contact')->nullable()->comment('جهة الاتصال في الطوارئ');
            $table->text('medical_notes')->nullable()->comment('ملاحظات طبية');
            $table->string('photo')->nullable()->comment('صورة الطالب');
            $table->string('birth_certificate')->nullable()->comment('شهادة الميلاد');
            $table->string('health_certificate')->nullable()->comment('الشهادة الصحية');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
