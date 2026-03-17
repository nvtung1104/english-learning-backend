<?php

namespace App\Http\Controllers\Api;

use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizAnswerController extends Controller
{
    public function index(Request $request)
    {
        $items = QuizAnswer::with('question')
            ->when($request->question_id, fn($q) => $q->where('question_id', $request->question_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'answer_text' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
        ]);

        $item = QuizAnswer::create($validated);

        return response()->json([
            'message' => 'Tạo đáp án thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            QuizAnswer::with('question')->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $item = QuizAnswer::findOrFail($id);

        $validated = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'answer_text' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật đáp án thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        QuizAnswer::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa đáp án thành công',
        ]);
    }
}