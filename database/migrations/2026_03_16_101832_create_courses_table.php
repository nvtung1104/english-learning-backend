<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('course_levels')->nullOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('thumbnail')->nullable()->comment('ảnh khóa học');
            $table->integer('duration')->nullable()->comment('thời lượng khóa học (phút)');
            $table->tinyInteger('status')->default(1)->comment('1=published,0=draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category_id', 'level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};