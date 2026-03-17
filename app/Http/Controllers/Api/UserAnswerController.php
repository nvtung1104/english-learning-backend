<?php

namespace App\Http\Controllers\Api;

use App\Models\UserAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAnswerController extends Controller
{
    public function index(Request $request)
    {
        $items = UserAnswer::with(['attempt', 'question', 'answer'])
            ->when($request->attempt_id, fn($q) => $q->where('attempt_id', $request->attempt_id))
            ->when($request->question_id, fn($q) => $q->where('question_id', $request->question_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'question_id' => 'required|exists:quiz_questions,id',
            'answer_id' => 'nullable|exists:quiz_answers,id',
        ]);

        $item = UserAnswer::create($validated);

        return response()->json([
            'message' => 'Lưu câu trả lời người dùng thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            UserAnswer::with(['attempt', 'question', 'answer'])->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $item = UserAnswer::findOrFail($id);

        $validated = $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'question_id' => 'required|exists:quiz_questions,id',
            'answer_id' => 'nullable|exists:quiz_answers,id',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật câu trả lời người dùng thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        UserAnswer::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa câu trả lời người dùng thành công',
        ]);
    }
}