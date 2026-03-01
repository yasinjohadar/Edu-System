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
        Schema::create('grade_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->comment('الطالب');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->comment('المادة الدراسية');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null')->comment('المعلم الذي سجل الدرجة');
            $table->enum('exam_type', ['quiz', 'assignment', 'midterm', 'final', 'project', 'participation', 'homework', 'other'])->comment('نوع التقييم');
            $table->string('exam_name')->comment('اسم التقييم/الامتحان');
            $table->decimal('marks_obtained', 5, 2)->default(0)->comment('الدرجة المحصل عليها');
            $table->decimal('total_marks', 5, 2)->comment('الدرجة الكلية');
            $table->decimal('percentage', 5, 2)->nullable()->comment('النسبة المئوية');
            $table->string('grade', 2)->nullable()->comment('الدرجة الحرفية (A, B, C, D, F)');
            $table->date('exam_date')->comment('تاريخ التقييم');
            $table->string('academic_year')->comment('السنة الدراسية');
            $table->enum('semester', ['first', 'second', 'summer'])->default('first')->comment('الفصل الدراسي');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->boolean('is_published')->default(false)->comment('هل تم نشر الدرجة للطالب');
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['student_id', 'subject_id']);
            $table->index(['exam_date']);
            $table->index(['academic_year', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_records');
    }
};
