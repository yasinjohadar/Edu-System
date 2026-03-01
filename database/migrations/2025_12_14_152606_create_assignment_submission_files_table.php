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
        Schema::create('assignment_submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade')->comment('التسليم');
            $table->string('file_path')->comment('مسار الملف');
            $table->string('file_name')->comment('اسم الملف الأصلي');
            $table->string('file_size')->nullable()->comment('حجم الملف');
            $table->string('file_type')->nullable()->comment('نوع الملف (mime type)');
            $table->enum('file_category', ['answer', 'attachment', 'other'])->default('answer')->comment('فئة الملف');
            $table->text('description')->nullable()->comment('وصف الملف');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index(['submission_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submission_files');
    }
};
