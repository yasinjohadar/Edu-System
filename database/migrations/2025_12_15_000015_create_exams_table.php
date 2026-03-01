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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'exam', 'midterm', 'final'])->default('quiz');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('set null');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->integer('duration')->default(30); // بالدقائق
            $table->decimal('total_marks', 5, 2)->default(100);
            $table->decimal('passing_marks', 5, 2)->default(60);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_review')->default(false);
            $table->boolean('show_results')->default(false);
            $table->boolean('show_answers')->default(false);
            $table->boolean('randomize_questions')->default(false);
            $table->timestamps();
            
            $table->index('subject_id');
            $table->index('grade_id');
            $table->index('section_id');
            $table->index('teacher_id');
            $table->index(['is_published', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
