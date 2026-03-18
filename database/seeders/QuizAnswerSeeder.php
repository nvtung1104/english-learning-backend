<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $q1 = DB::table('quiz_questions')
            ->where('question', 'Cách chào phổ biến vào buổi sáng là gì?')
            ->value('id');

        $q2 = DB::table('quiz_questions')
            ->where('question', 'Khi gặp bạn bè, câu nào tự nhiên nhất?')
            ->value('id');

        if ($q1) {
            $answers1 = [
                ['question_id' => $q1, 'answer_text' => 'Good morning', 'is_correct' => 1],
                ['question_id' => $q1, 'answer_text' => 'Good night', 'is_correct' => 0],
                ['question_id' => $q1, 'answer_text' => 'Bye bye', 'is_correct' => 0],
                ['question_id' => $q1, 'answer_text' => 'See you', 'is_correct' => 0],
            ];

            foreach ($answers1 as $answer) {
                DB::table('quiz_answers')->updateOrInsert(
                    [
                        'question_id' => $answer['question_id'],
                        'answer_text' => $answer['answer_text'],
                    ],
                    $answer
                );
            }
        }

        if ($q2) {
            $answers2 = [
                ['question_id' => $q2, 'answer_text' => 'Hi', 'is_correct' => 1],
                ['question_id' => $q2, 'answer_text' => 'Goodbye forever', 'is_correct' => 0],
                ['question_id' => $q2, 'answer_text' => 'Good night', 'is_correct' => 0],
                ['question_id' => $q2, 'answer_text' => 'Take care tomorrow morning only', 'is_correct' => 0],
            ];

            foreach ($answers2 as $answer) {
                DB::table('quiz_answers')->updateOrInsert(
                    [
                        'question_id' => $answer['question_id'],
                        'answer_text' => $answer['answer_text'],
                    ],
                    $answer
                );
            }
        }
    }
}