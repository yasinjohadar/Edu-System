<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('bus_routes')->onDelete('cascade');
            $table->string('stop_name')->comment('اسم المحطة');
            $table->string('address')->nullable()->comment('العنوان');
            $table->decimal('latitude', 10, 8)->nullable()->comment('خط العرض');
            $table->decimal('longitude', 11, 8)->nullable()->comment('خط الطول');
            $table->integer('order')->default(0)->comment('الترتيب');
            $table->time('arrival_time')->nullable()->comment('وقت الوصول');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_stops');
    }
};
