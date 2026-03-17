<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->foreignId('lesson_type_id')->nullable()->constrained('lesson_types')->nullOnDelete();
            $table->string('video_url')->nullable();
            $table->integer('order_number')->default(1);
            $table->timestamps();

            $table->unique(['section_id', 'order_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};