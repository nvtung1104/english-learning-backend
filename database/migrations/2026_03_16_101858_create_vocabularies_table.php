<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('word');
            $table->string('meaning');
            $table->text('example')->nullable();
            $table->string('audio')->nullable()->comment('audio phát âm');
            $table->timestamps();

            $table->index('word');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};