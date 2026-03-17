<?php

namespace App\Http\Controllers\Api;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizAttemptController extends Controller
{
    public function index(Request $request)
    {
        $items = QuizAttempt::with(['quiz', 'user'])
            ->where('user_id', $request->user()->id)
            ->when($request->quiz_id, fn($q) => $q->where('quiz_id', $request->quiz_id))
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function submit(Request $request, string $quizId)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($quizId);

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.answer_id' => 'nullable|exists:quiz_answers,id',
        ]);

        $attempt = QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'score' => 0,
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        $totalQuestions = $quiz->questions->count();
        $correctCount = 0;

        foreach ($validated['answers'] as $row) {
            $question = $quiz->questions->firstWhere('id', $row['question_id']);
            if (!$question) {
                continue;
            }

            $answer = $question->answers->firstWhere('id', $row['answer_id'] ?? null);

            $attempt->userAnswers()->create([
                'question_id' => $question->id,
                'answer_id' => $answer?->id,
            ]);

            if ($answer && $answer->is_correct) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        $attempt->update([
            'score' => $score,
        ]);

        return response()->json([
            'message' => 'Nộp bài thành công',
            'data' => $attempt->load('userAnswers'),
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'passed' => $score >= $quiz->pass_score,
        ]);
    }
}