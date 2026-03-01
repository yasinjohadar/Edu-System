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
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم نوع الرسوم');
            $table->string('name_en')->nullable()->comment('اسم نوع الرسوم بالإنجليزية');
            $table->string('code')->unique()->comment('رمز نوع الرسوم');
            $table->text('description')->nullable()->comment('وصف نوع الرسوم');
            $table->enum('category', ['tuition', 'registration', 'activity', 'book', 'uniform', 'transport', 'other'])->default('other')->comment('فئة الرسوم');
            $table->decimal('default_amount', 10, 2)->default(0)->comment('المبلغ الافتراضي');
            $table->boolean('is_recurring')->default(false)->comment('هل الرسوم متكررة');
            $table->enum('recurring_period', ['monthly', 'quarterly', 'semester', 'yearly'])->nullable()->comment('فترة التكرار');
            $table->boolean('is_active')->default(true)->comment('هل النوع نشط');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index('code');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_types');
    }
};
