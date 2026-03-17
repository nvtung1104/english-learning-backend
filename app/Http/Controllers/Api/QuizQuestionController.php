<?php

namespace App\Http\Controllers\Api;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizQuestionController extends Controller
{
    public function index(Request $request)
    {
        $items = QuizQuestion::with('answers')
            ->when($request->quiz_id, fn($q) => $q->where('quiz_id', $request->quiz_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string',
            'question_type' => 'nullable|string|max:50',
            'image_url' => 'nullable|string|max:255',
            'audio_url' => 'nullable|string|max:255',
            'answers' => 'required|array|min:2',
            'answers.*.answer_text' => 'required|string|max:255',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        $question = QuizQuestion::create([
            'quiz_id' => $validated['quiz_id'],
            'question' => $validated['question'],
            'question_type' => $validated['question_type'] ?? 'multiple_choice',
            'image_url' => $validated['image_url'] ?? null,
            'audio_url' => $validated['audio_url'] ?? null,
        ]);

        foreach ($validated['answers'] as $answer) {
            $question->answers()->create($answer);
        }

        return response()->json([
            'message' => 'Tạo câu hỏi thành công',
            'data' => $question->load('answers'),
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            QuizQuestion::with('answers')->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $question = QuizQuestion::findOrFail($id);

        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string',
            'question_type' => 'nullable|string|max:50',
            'image_url' => 'nullable|string|max:255',
            'audio_url' => 'nullable|string|max:255',
        ]);

        $question->update([
            'quiz_id' => $validated['quiz_id'],
            'question' => $validated['question'],
            'question_type' => $validated['question_type'] ?? 'multiple_choice',
            'image_url' => $validated['image_url'] ?? null,
            'audio_url' => $validated['audio_url'] ?? null,
        ]);

        return response()->json([
            'message' => 'Cập nhật câu hỏi thành công',
            'data' => $question->load('answers'),
        ]);
    }

    public function destroy(string $id)
    {
        QuizQuestion::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa câu hỏi thành công',
        ]);
    }
}