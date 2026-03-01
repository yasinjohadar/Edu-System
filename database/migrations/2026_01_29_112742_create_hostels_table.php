<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم النزل');
            $table->string('address')->nullable()->comment('العنوان');
            $table->string('phone')->nullable()->comment('الهاتف');
            $table->integer('total_rooms')->default(0)->comment('إجمالي الغرف');
            $table->integer('total_beds')->default(0)->comment('إجمالي الأسرة');
            $table->text('description')->nullable()->comment('الوصف');
            $table->enum('gender', ['male', 'female', 'mixed'])->default('mixed')->comment('الجنس');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostels');
    }
};
