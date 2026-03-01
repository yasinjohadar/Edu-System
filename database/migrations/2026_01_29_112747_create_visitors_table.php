<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('visitor_name')->comment('اسم الزائر');
            $table->string('relationship')->nullable()->comment('العلاقة');
            $table->string('phone')->nullable()->comment('الهاتف');
            $table->string('id_number')->nullable()->comment('رقم الهوية');
            $table->dateTime('visit_date')->comment('تاريخ الزيارة');
            $table->dateTime('check_in_time')->nullable()->comment('وقت الدخول');
            $table->dateTime('check_out_time')->nullable()->comment('وقت الخروج');
            $table->text('purpose')->nullable()->comment('الغرض');
            $table->foreignId('registered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
