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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->comment('الطالب');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade')->comment('الفصل الدراسي');
            $table->date('date')->comment('تاريخ الحضور');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent')->comment('حالة الحضور');
            $table->time('check_in_time')->nullable()->comment('وقت الحضور');
            $table->time('check_out_time')->nullable()->comment('وقت الانصراف');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete()->comment('المعلم الذي سجل الحضور');
            $table->timestamps();
            
            // منع تسجيل حضور مرتين لنفس الطالب في نفس اليوم
            $table->unique(['student_id', 'section_id', 'date'], 'unique_attendance');
            
            // فهارس للأداء
            $table->index(['section_id', 'date']);
            $table->index(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
