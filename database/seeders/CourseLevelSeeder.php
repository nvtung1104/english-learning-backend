<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseLevelSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Beginner'],
            ['name' => 'Intermediate'],
            ['name' => 'Advanced'],
        ];

        foreach ($items as $item) {
            DB::table('course_levels')->updateOrInsert(
                ['name' => $item['name']],
                $item
            );
        }
    }
}