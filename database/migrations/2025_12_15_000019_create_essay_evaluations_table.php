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
        Schema::create('essay_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_answer_id')->constrained('exam_answers')->onDelete('cascade');
            $table->foreignId('rubric_id')->nullable()->constrained('rubrics')->onDelete('cascade');
            $table->json('criteria_scores')->nullable(); // درجات كل معيار
            $table->decimal('total_score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            
            $table->index('exam_answer_id');
            $table->index('evaluated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essay_evaluations');
    }
};
