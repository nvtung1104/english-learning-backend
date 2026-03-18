<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacherId = DB::table('users')->where('email', 'teacher@gmail.com')->value('id');
        $categoryId = DB::table('course_categories')->where('name', 'Giao tiếp tiếng Anh')->value('id');
        $levelId = DB::table('course_levels')->where('name', 'Beginner')->value('id');

        if (!$teacherId || !$categoryId || !$levelId) {
            return;
        }

        $items = [
            [
                'title' => 'English Communication For Beginners',
                'description' => 'Khóa học giao tiếp cơ bản cho người mới bắt đầu.',
                'category_id' => $categoryId,
                'level_id' => $levelId,
                'price' => 0,
                'thumbnail' => null,
                'duration' => 120,
                'status' => 1,
                'created_by' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Basic Daily English',
                'description' => 'Học tiếng Anh giao tiếp hằng ngày theo tình huống.',
                'category_id' => $categoryId,
                'level_id' => $levelId,
                'price' => 0,
                'thumbnail' => null,
                'duration' => 90,
                'status' => 1,
                'created_by' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($items as $item) {
            DB::table('courses')->updateOrInsert(
                ['title' => $item['title']],
                $item
            );
        }
    }
}