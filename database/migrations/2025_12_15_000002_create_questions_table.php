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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_code')->unique();
            $table->enum('type', [
                'multiple_choice', 'true_false', 'essay', 'fill_blanks', 
                'matching', 'ordering', 'classification', 'drag_drop', 
                'hotspot', 'audio', 'video'
            ]);
            $table->text('content');
            $table->text('explanation')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('set null');
            $table->string('tags')->nullable(); // للتصنيف
            $table->decimal('points', 5, 2)->default(1);
            $table->integer('time_limit')->default(0); // بالثواني، 0 يعني بدون حد
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('type');
            $table->index('subject_id');
            $table->index('grade_id');
            $table->index('difficulty');
            $table->index('tags');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
