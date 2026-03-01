<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            $table->string('name')->comment('الاسم');
            $table->string('email')->unique()->comment('البريد الإلكتروني');
            $table->string('phone')->nullable()->comment('الهاتف');
            $table->date('graduation_date')->comment('تاريخ التخرج');
            $table->string('degree')->nullable()->comment('الدرجة');
            $table->string('major')->nullable()->comment('التخصص');
            $table->string('current_job')->nullable()->comment('الوظيفة الحالية');
            $table->string('company')->nullable()->comment('الشركة');
            $table->text('address')->nullable()->comment('العنوان');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
            
            $table->index('graduation_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
