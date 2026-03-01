<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');
            $table->string('verification_code')->unique()->comment('رمز التحقق');
            $table->string('ip_address')->nullable()->comment('عنوان IP');
            $table->timestamp('verified_at')->nullable()->comment('تاريخ التحقق');
            $table->timestamps();
            
            $table->index('verification_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_verifications');
    }
};
