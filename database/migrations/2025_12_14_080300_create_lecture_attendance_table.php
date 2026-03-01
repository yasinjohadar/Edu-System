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
        Schema::create('lecture_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained('online_lectures')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->datetime('joined_at')->nullable();
            $table->datetime('left_at')->nullable();
            $table->integer('duration_minutes')->nullable(); // مدة الحضور بالدقائق
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['lecture_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_attendance');
    }
};
