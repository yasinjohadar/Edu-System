<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان الوظيفة');
            $table->text('description')->comment('الوصف');
            $table->string('company')->comment('الشركة');
            $table->string('location')->nullable()->comment('المكان');
            $table->string('salary_range')->nullable()->comment('نطاق الراتب');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship'])->default('full_time')->comment('نوع التوظيف');
            $table->date('application_deadline')->nullable()->comment('آخر موعد للتقديم');
            $table->string('contact_email')->nullable()->comment('البريد الإلكتروني للتواصل');
            $table->string('contact_phone')->nullable()->comment('الهاتف للتواصل');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->foreignId('posted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
