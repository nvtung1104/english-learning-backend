<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Vocabulary'],
            ['name' => 'Grammar'],
            ['name' => 'Listening'],
            ['name' => 'Speaking'],
            ['name' => 'Reading'],
        ];

        foreach ($items as $item) {
            DB::table('lesson_types')->updateOrInsert(
                ['name' => $item['name']],
                $item
            );
        }
    }
}