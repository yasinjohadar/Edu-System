<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('bed_id')->constrained('beds')->onDelete('cascade');
            $table->date('check_in_date')->comment('تاريخ الدخول');
            $table->date('check_out_date')->nullable()->comment('تاريخ الخروج');
            $table->enum('status', ['active', 'checked_out', 'suspended'])->default('active')->comment('الحالة');
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('hostel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_accommodations');
    }
};
