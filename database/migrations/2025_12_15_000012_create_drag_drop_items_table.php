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
        Schema::create('drag_drop_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('item_text');
            $table->foreignId('target_zone_id')->constrained('hotspot_zones')->onDelete('cascade');
            $table->integer('item_order');
            $table->timestamps();
            
            $table->unique(['question_id', 'item_order']);
            $table->index('question_id');
            $table->index('target_zone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drag_drop_items');
    }
};
