<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('route_id')->constrained('bus_routes')->onDelete('cascade');
            $table->foreignId('stop_id')->nullable()->constrained('bus_stops')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->onDelete('set null');
            $table->date('start_date')->comment('تاريخ البدء');
            $table->date('end_date')->nullable()->comment('تاريخ الانتهاء');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->comment('الحالة');
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('route_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transports');
    }
};
