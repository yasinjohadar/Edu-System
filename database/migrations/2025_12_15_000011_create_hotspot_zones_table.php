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
        Schema::create('hotspot_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('zone_name');
            $table->integer('coordinates_x')->nullable();
            $table->integer('coordinates_y')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->enum('shape', ['circle', 'rect', 'polygon'])->default('rect');
            $table->timestamps();
            
            $table->unique(['question_id', 'zone_name']);
            $table->index('question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_zones');
    }
};
