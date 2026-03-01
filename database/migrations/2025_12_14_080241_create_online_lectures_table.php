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
        Schema::create('online_lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable(); // محتوى المحاضرة
            $table->enum('type', ['live', 'recorded', 'material'])->default('recorded');
            $table->string('video_url')->nullable(); // رابط الفيديو
            $table->string('audio_url')->nullable(); // رابط الصوت
            $table->datetime('scheduled_at')->nullable(); // للمحاضرات المباشرة
            $table->datetime('started_at')->nullable(); // وقت بدء المحاضرة المباشرة
            $table->datetime('ended_at')->nullable(); // وقت انتهاء المحاضرة المباشرة
            $table->integer('duration')->nullable(); // المدة بالدقائق
            $table->string('meeting_link')->nullable(); // رابط الاجتماع للمحاضرات المباشرة
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_lectures');
    }
};
