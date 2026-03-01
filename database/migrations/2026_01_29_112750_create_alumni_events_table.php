<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان الحدث');
            $table->text('description')->nullable()->comment('الوصف');
            $table->date('event_date')->comment('تاريخ الحدث');
            $table->time('event_time')->nullable()->comment('وقت الحدث');
            $table->string('location')->nullable()->comment('المكان');
            $table->enum('type', ['reunion', 'networking', 'workshop', 'seminar', 'other'])->default('other')->comment('النوع');
            $table->integer('max_attendees')->nullable()->comment('الحد الأقصى للحضور');
            $table->decimal('fee', 10, 2)->default(0)->comment('الرسوم');
            $table->boolean('is_active')->default(true)->comment('نشط');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_events');
    }
};
