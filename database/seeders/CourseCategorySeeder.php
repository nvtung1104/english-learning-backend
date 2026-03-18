<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Giao tiếp tiếng Anh',
                'description' => 'Khóa học giao tiếp tiếng Anh cơ bản và thực hành.',
            ],
            [
                'name' => 'Ngữ pháp tiếng Anh',
                'description' => 'Khóa học ngữ pháp từ cơ bản đến nâng cao.',
            ],
            [
                'name' => 'Luyện nghe tiếng Anh',
                'description' => 'Khóa học giúp cải thiện kỹ năng nghe tiếng Anh.',
            ],
        ];

        foreach ($items as $item) {
            DB::table('course_categories')->updateOrInsert(
                ['name' => $item['name']],
                $item
            );
        }
    }
}