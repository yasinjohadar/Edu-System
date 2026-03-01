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
        Schema::create('assignment_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade')->comment('الواجب');
            $table->string('file_path')->comment('مسار الملف');
            $table->string('file_name')->comment('اسم الملف الأصلي');
            $table->string('file_size')->nullable()->comment('حجم الملف');
            $table->string('file_type')->nullable()->comment('نوع الملف (mime type)');
            $table->text('description')->nullable()->comment('وصف المرفق');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index(['assignment_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_attachments');
    }
};
