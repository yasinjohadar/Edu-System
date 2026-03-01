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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->onDelete('set null')->comment('فئة الحدث');
            $table->string('title')->comment('عنوان الحدث');
            $table->text('description')->nullable()->comment('وصف الحدث');
            $table->date('start_date')->comment('تاريخ البدء');
            $table->date('end_date')->nullable()->comment('تاريخ الانتهاء');
            $table->time('start_time')->nullable()->comment('وقت البدء');
            $table->time('end_time')->nullable()->comment('وقت الانتهاء');
            $table->string('location')->nullable()->comment('المكان');
            $table->enum('type', ['holiday', 'exam', 'activity', 'meeting', 'other'])->default('other')->comment('نوع الحدث');
            $table->enum('recurrence', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none')->comment('التكرار');
            $table->date('recurrence_end_date')->nullable()->comment('تاريخ انتهاء التكرار');
            $table->boolean('is_all_day')->default(false)->comment('طوال اليوم');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->json('target_audience')->nullable()->comment('الجمهور المستهدف (classes, sections, students)');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('أنشئ بواسطة');
            $table->timestamps();
            
            $table->index('start_date');
            $table->index('end_date');
            $table->index('type');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
