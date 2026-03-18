<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $section1 = DB::table('course_sections')
            ->where('title', 'Phần 1: Chào hỏi cơ bản')
            ->value('id');

        $section2 = DB::table('course_sections')
            ->where('title', 'Phần 2: Giao tiếp hằng ngày')
            ->value('id');

        $vocabType = DB::table('lesson_types')->where('name', 'Vocabulary')->value('id');
        $grammarType = DB::table('lesson_types')->where('name', 'Grammar')->value('id');
        $listeningType = DB::table('lesson_types')->where('name', 'Listening')->value('id');

        $items = [];

        if ($section1 && $vocabType) {
            $items[] = [
                'section_id' => $section1,
                'order_number' => 1,
                'title' => 'Hello and Greetings',
                'content' => 'Học các mẫu câu chào hỏi cơ bản trong tiếng Anh.',
                'lesson_type_id' => $vocabType,
                'video_url' => null,
                'created_at' => now(),
            ];
        }

        if ($section1 && $grammarType) {
            $items[] = [
                'section_id' => $section1,
                'order_number' => 2,
                'title' => 'To Be Basic',
                'content' => 'Cấu trúc động từ to be trong câu đơn giản.',
                'lesson_type_id' => $grammarType,
                'video_url' => null,
                'created_at' => now(),
            ];
        }

        if ($section2 && $listeningType) {
            $items[] = [
                'section_id' => $section2,
                'order_number' => 1,
                'title' => 'Daily Conversation Listening',
                'content' => 'Nghe hội thoại cơ bản trong cuộc sống hằng ngày.',
                'lesson_type_id' => $listeningType,
                'video_url' => null,
                'created_at' => now(),
            ];
        }

        foreach ($items as $item) {
            DB::table('lessons')->updateOrInsert(
                [
                    'section_id' => $item['section_id'],
                    'order_number' => $item['order_number'],
                ],
                [
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'lesson_type_id' => $item['lesson_type_id'],
                    'video_url' => $item['video_url'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['created_at'],
                ]
            );
        }
    }
}