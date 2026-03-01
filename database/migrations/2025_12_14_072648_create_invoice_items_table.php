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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade')->comment('الفاتورة');
            $table->foreignId('fee_type_id')->nullable()->constrained('fee_types')->onDelete('set null')->comment('نوع الرسوم');
            $table->string('item_name')->comment('اسم البند');
            $table->text('description')->nullable()->comment('وصف البند');
            $table->integer('quantity')->default(1)->comment('الكمية');
            $table->decimal('unit_price', 10, 2)->comment('سعر الوحدة');
            $table->decimal('discount', 10, 2)->default(0)->comment('الخصم');
            $table->decimal('tax', 10, 2)->default(0)->comment('الضريبة');
            $table->decimal('total', 12, 2)->comment('الإجمالي');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index('invoice_id');
            $table->index('fee_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
