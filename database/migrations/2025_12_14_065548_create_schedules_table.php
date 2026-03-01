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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade')->comment('الفصل الدراسي');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->comment('المادة الدراسية');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade')->comment('المعلم');
            $table->enum('day_of_week', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->comment('يوم الأسبوع');
            $table->time('start_time')->comment('وقت بداية الحصة');
            $table->time('end_time')->comment('وقت نهاية الحصة');
            $table->string('room')->nullable()->comment('القاعة/الفصل الدراسي');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->integer('order')->default(0)->comment('ترتيب الحصة في اليوم');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['section_id', 'day_of_week']);
            $table->index(['teacher_id', 'day_of_week']);
            $table->index(['subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
