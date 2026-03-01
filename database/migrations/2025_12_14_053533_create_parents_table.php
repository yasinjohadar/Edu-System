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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('parent_code')->unique()->comment('رقم ولي الأمر الفريد');
            $table->enum('relationship', ['father', 'mother', 'guardian'])->default('guardian');
            $table->string('occupation')->nullable()->comment('المهنة');
            $table->string('workplace')->nullable()->comment('مكان العمل');
            $table->string('work_phone')->nullable()->comment('هاتف العمل');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
