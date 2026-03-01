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
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year')->comment('السنة الأكاديمية');
            $table->string('semester')->comment('الفصل الدراسي');
            $table->date('start_date')->comment('تاريخ البدء');
            $table->date('end_date')->comment('تاريخ الانتهاء');
            $table->date('registration_start')->nullable()->comment('بداية التسجيل');
            $table->date('registration_end')->nullable()->comment('نهاية التسجيل');
            $table->date('exams_start')->nullable()->comment('بداية الامتحانات');
            $table->date('exams_end')->nullable()->comment('نهاية الامتحانات');
            $table->date('results_publish_date')->nullable()->comment('تاريخ نشر النتائج');
            $table->json('holidays')->nullable()->comment('العطل الرسمية');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('أنشئ بواسطة');
            $table->timestamps();
            
            $table->index('academic_year');
            $table->index('semester');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
