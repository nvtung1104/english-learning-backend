<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quizId = DB::table('quizzes')
            ->where('title', 'Quiz: Hello and Greetings')
            ->value('id');

        if (!$quizId) {
            return;
        }

        $questions = [
            [
                'quiz_id' => $quizId,
                'question' => 'Cách chào phổ biến vào buổi sáng là gì?',
                'question_type' => 'multiple_choice',
                'image_url' => null,
                'audio_url' => null,
            ],
            [
                'quiz_id' => $quizId,
                'question' => 'Khi gặp bạn bè, câu nào tự nhiên nhất?',
                'question_type' => 'multiple_choice',
                'image_url' => null,
                'audio_url' => null,
            ],
        ];

        foreach ($questions as $question) {
            DB::table('quiz_questions')->updateOrInsert(
                [
                    'quiz_id' => $question['quiz_id'],
                    'question' => $question['question'],
                ],
                $question
            );
        }
    }
}   