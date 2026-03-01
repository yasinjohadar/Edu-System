<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            $table->string('room_number')->comment('رقم الغرفة');
            $table->integer('capacity')->default(1)->comment('السعة');
            $table->integer('available_beds')->default(0)->comment('الأسرة المتاحة');
            $table->enum('type', ['single', 'double', 'triple', 'quad'])->default('double')->comment('النوع');
            $table->decimal('fee', 10, 2)->default(0)->comment('الرسوم');
            $table->text('description')->nullable()->comment('الوصف');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
            
            $table->index('hostel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
