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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_number')->unique()->comment('رقم الواجب الفريد');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->comment('المادة الدراسية');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade')->comment('المعلم الذي أنشأ الواجب');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade')->comment('الفصل الدراسي');
            $table->string('title')->comment('عنوان الواجب');
            $table->text('description')->nullable()->comment('وصف الواجب');
            $table->text('instructions')->nullable()->comment('التعليمات');
            $table->decimal('total_marks', 5, 2)->comment('الدرجة الكلية');
            $table->date('due_date')->comment('تاريخ الاستحقاق');
            $table->time('due_time')->default('23:59:59')->comment('وقت الاستحقاق');
            $table->boolean('allow_late_submission')->default(true)->comment('السماح بالتسليم المتأخر');
            $table->decimal('late_penalty_per_day', 5, 2)->default(0)->comment('غرامة التأخير لكل يوم');
            $table->integer('max_late_days')->nullable()->comment('أقصى أيام تأخير مسموحة');
            $table->integer('max_attempts')->nullable()->comment('عدد المحاولات المسموحة (null = غير محدود)');
            $table->boolean('allow_resubmission')->default(false)->comment('السماح بإعادة التسليم بعد التصحيح');
            $table->date('resubmission_deadline')->nullable()->comment('آخر موعد لإعادة التسليم');
            $table->json('submission_types')->comment('أنواع التسليم المسموحة');
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft')->comment('حالة الواجب');
            $table->boolean('is_active')->default(true)->comment('هل الواجب نشط');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('أنشأه من');
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['subject_id', 'status']);
            $table->index(['teacher_id', 'status']);
            $table->index(['section_id', 'status']);
            $table->index(['due_date']);
            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
