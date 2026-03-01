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
        Schema::create('assignment_submission_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade')->comment('التسليم');
            $table->string('url')->comment('الرابط');
            $table->string('title')->nullable()->comment('عنوان الرابط');
            $table->text('description')->nullable()->comment('وصف الرابط');
            $table->enum('link_type', ['google_drive', 'dropbox', 'youtube', 'onedrive', 'other'])->default('other')->comment('نوع الرابط');
            $table->integer('sort_order')->default(0)->comment('ترتيب الروابط');
            $table->timestamps();
            
            $table->index(['submission_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submission_links');
    }
};
