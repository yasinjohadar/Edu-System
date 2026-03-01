<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->comment('اسم المسار');
            $table->string('route_number')->unique()->comment('رقم المسار');
            $table->text('description')->nullable()->comment('الوصف');
            $table->decimal('distance', 8, 2)->nullable()->comment('المسافة بالكيلومتر');
            $table->time('start_time')->comment('وقت البدء');
            $table->time('end_time')->comment('وقت الانتهاء');
            $table->decimal('fee', 10, 2)->default(0)->comment('الرسوم');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_routes');
    }
};
