<?php

namespace App\Http\Controllers\Api;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $items = Quiz::with('lesson')
            ->when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'time_limit' => 'nullable|integer|min:1',
            'pass_score' => 'nullable|integer|min:0|max:100',
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $validated['lesson_id'],
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'pass_score' => $validated['pass_score'] ?? 50,
        ]);

        return response()->json([
            'message' => 'Tạo quiz thành công',
            'data' => $quiz,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            Quiz::with('questions.answers')->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'time_limit' => 'nullable|integer|min:1',
            'pass_score' => 'nullable|integer|min:0|max:100',
        ]);

        $quiz->update($validated);

        return response()->json([
            'message' => 'Cập nhật quiz thành công',
            'data' => $quiz,
        ]);
    }

    public function destroy(string $id)
    {
        Quiz::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa quiz thành công',
        ]);
    }
}