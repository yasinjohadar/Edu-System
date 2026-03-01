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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('name')->comment('اسم الفصل (أ، ب، ج، إلخ)');
            $table->string('name_en')->nullable();
            $table->integer('capacity')->default(30)->comment('السعة القصوى للفصل');
            $table->integer('current_students')->default(0)->comment('عدد الطلاب الحالي');
            $table->foreignId('class_teacher_id')->nullable()->constrained('users')->nullOnDelete()->comment('المعلم الرئيسي للفصل');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
