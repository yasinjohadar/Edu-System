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
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->text('answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->decimal('marks_obtained', 5, 2)->default(0);
            $table->integer('time_taken')->default(0); // بالثواني
            $table->timestamps();
            
            $table->unique(['student_id', 'exam_id', 'question_id']);
            $table->index(['student_id', 'exam_id']);
            $table->index(['exam_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
