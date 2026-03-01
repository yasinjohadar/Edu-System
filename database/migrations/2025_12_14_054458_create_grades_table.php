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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم المرحلة (ابتدائي، متوسط، ثانوي، روضة)');
            $table->string('name_en')->nullable()->comment('اسم المرحلة بالإنجليزية');
            $table->integer('min_age')->nullable()->comment('الحد الأدنى للعمر');
            $table->integer('max_age')->nullable()->comment('الحد الأقصى للعمر');
            $table->decimal('fees', 10, 2)->nullable()->comment('الرسوم الدراسية');
            $table->integer('order')->default(0)->comment('ترتيب العرض');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
