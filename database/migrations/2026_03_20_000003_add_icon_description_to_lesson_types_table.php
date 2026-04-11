<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lesson_types', function (Blueprint $table) {
            $table->string('icon', 10)->default('📄')->after('name');
            $table->string('description', 255)->nullable()->after('icon');
            $table->string('category', 50)->nullable()->after('description')->comment('vocabulary,grammar,pronunciation,listening,speaking,writing,conversation,advanced');
            $table->json('sub_topics')->nullable()->after('category')->comment('JSON array of sub-topic options');
        });
    }

    public function down(): void
    {
        Schema::table('lesson_types', function (Blueprint $table) {
            $table->dropColumn(['icon', 'description', 'category', 'sub_topics']);
        });
    }
};
