<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSectionSeeder extends Seeder
{
    public function run(): void
    {
        $courseId = DB::table('courses')
            ->where('title', 'English Communication For Beginners')
            ->value('id');

        if (!$courseId) {
            return;
        }

        $items = [
            [
                'course_id' => $courseId,
                'title' => 'Phần 1: Chào hỏi cơ bản',
                'order_number' => 1,
            ],
            [
                'course_id' => $courseId,
                'title' => 'Phần 2: Giao tiếp hằng ngày',
                'order_number' => 2,
            ],
        ];

        foreach ($items as $item) {
            DB::table('course_sections')->updateOrInsert(
                [
                    'course_id' => $item['course_id'],
                    'title' => $item['title'],
                ],
                $item
            );
        }
    }
}