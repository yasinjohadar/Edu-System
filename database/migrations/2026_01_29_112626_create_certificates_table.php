<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('certificate_templates')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->string('certificate_number')->unique()->comment('رقم الشهادة');
            $table->string('verification_code')->unique()->comment('رمز التحقق');
            $table->enum('type', ['completion', 'achievement', 'attendance', 'grade'])->comment('نوع الشهادة');
            $table->date('issue_date')->comment('تاريخ الإصدار');
            $table->json('data')->nullable()->comment('بيانات الشهادة');
            $table->string('file_path')->nullable()->comment('مسار ملف PDF');
            $table->boolean('is_verified')->default(false)->comment('تم التحقق');
            $table->foreignId('issued_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('certificate_number');
            $table->index('verification_code');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
