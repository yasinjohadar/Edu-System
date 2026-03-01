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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade')->comment('الواجب');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->comment('الطالب');
            $table->string('submission_number')->unique()->comment('رقم التسليم الفريد');
            $table->integer('attempt_number')->default(1)->comment('رقم المحاولة');
            $table->boolean('is_resubmission')->default(false)->comment('هل هي إعادة تسليم');
            $table->foreignId('previous_submission_id')->nullable()->constrained('assignment_submissions')->onDelete('set null')->comment('التسليم السابق');
            $table->dateTime('submitted_at')->comment('تاريخ ووقت التسليم');
            $table->enum('status', ['submitted', 'late', 'graded', 'returned', 'resubmitted'])->default('submitted')->comment('حالة التسليم');
            $table->decimal('marks_obtained', 5, 2)->nullable()->comment('الدرجة المحصل عليها');
            $table->text('feedback')->nullable()->comment('ملاحظات المعلم');
            $table->text('teacher_notes')->nullable()->comment('ملاحظات المعلم - نص طويل');
            $table->text('student_notes')->nullable()->comment('ملاحظات الطالب عند التسليم');
            $table->boolean('requires_resubmission')->default(false)->comment('المعلم طلب إعادة التسليم');
            $table->text('resubmission_reason')->nullable()->comment('سبب طلب الإعادة');
            $table->dateTime('graded_at')->nullable()->comment('تاريخ التصحيح');
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null')->comment('المعلم الذي صحح');
            $table->boolean('is_late')->default(false)->comment('هل تم التسليم متأخراً');
            $table->integer('days_late')->default(0)->comment('عدد أيام التأخير');
            $table->decimal('late_penalty', 5, 2)->default(0)->comment('غرامة التأخير');
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['assignment_id', 'student_id']);
            $table->index(['assignment_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index(['attempt_number', 'assignment_id']);
            $table->index(['submitted_at']);
            $table->unique(['assignment_id', 'student_id', 'attempt_number'], 'unique_attempt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
