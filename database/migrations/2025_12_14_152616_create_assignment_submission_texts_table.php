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
        Schema::create('assignment_submission_texts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade')->comment('التسليم');
            $table->longText('content')->comment('نص الإجابة');
            $table->integer('sort_order')->default(0)->comment('ترتيب النصوص إذا كان متعدد');
            $table->timestamps();
            
            $table->index(['submission_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submission_texts');
    }
};
