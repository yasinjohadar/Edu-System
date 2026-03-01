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
        Schema::create('essay_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('min_words')->default(0);
            $table->integer('max_words')->default(0);
            $table->boolean('allow_attachments')->default(true);
            $table->foreignId('rubric_id')->nullable()->constrained('rubrics')->onDelete('set null');
            $table->timestamps();
            
            $table->index('question_id');
            $table->index('rubric_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essay_questions');
    }
};
