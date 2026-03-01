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
        Schema::create('video_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('video_url');
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration')->nullable(); // بالثواني
            $table->boolean('auto_play')->default(false);
            $table->boolean('allow_download')->default(false);
            $table->text('transcript')->nullable(); // النص الكامل للفيديو
            $table->integer('start_time')->nullable(); // وقت البدء بالثواني
            $table->integer('end_time')->nullable(); // وقت الانتهاء بالثواني
            $table->timestamps();
            
            $table->index('question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_questions');
    }
};
