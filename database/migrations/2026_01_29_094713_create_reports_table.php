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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم التقرير');
            $table->string('type')->comment('نوع التقرير');
            $table->text('description')->nullable()->comment('وصف التقرير');
            $table->json('filters')->nullable()->comment('الفلاتر المستخدمة');
            $table->json('data')->nullable()->comment('البيانات المحفوظة');
            $table->string('format')->default('pdf')->comment('صيغة التقرير (pdf, excel, csv)');
            $table->string('file_path')->nullable()->comment('مسار الملف المحفوظ');
            $table->enum('status', ['pending', 'generating', 'completed', 'failed'])->default('pending')->comment('حالة التقرير');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('أنشئ بواسطة');
            $table->timestamp('generated_at')->nullable()->comment('تاريخ الإنشاء');
            $table->timestamps();
            
            $table->index('type');
            $table->index('status');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
