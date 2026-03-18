<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $lessonId = DB::table('lessons')
            ->where('title', 'Hello and Greetings')
            ->value('id');

        if (!$lessonId) {
            return;
        }

        DB::table('quizzes')->updateOrInsert(
            ['lesson_id' => $lessonId],
            [
                'lesson_id' => $lessonId,
                'title' => 'Quiz: Hello and Greetings',
                'time_limit' => 15,
            ]
        );
    }
}