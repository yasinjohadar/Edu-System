<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('driver_code')->unique()->comment('رقم السائق');
            $table->string('license_number')->unique()->comment('رقم الرخصة');
            $table->date('license_expiry')->nullable()->comment('انتهاء الرخصة');
            $table->string('phone')->nullable()->comment('الهاتف');
            $table->string('address')->nullable()->comment('العنوان');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active')->comment('الحالة');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
