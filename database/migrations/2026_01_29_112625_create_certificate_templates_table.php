<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم القالب');
            $table->enum('type', ['completion', 'achievement', 'attendance', 'grade'])->comment('نوع الشهادة');
            $table->text('html_template')->comment('قالب HTML');
            $table->json('fields')->nullable()->comment('الحقول المتاحة');
            $table->string('background_image')->nullable()->comment('صورة الخلفية');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
