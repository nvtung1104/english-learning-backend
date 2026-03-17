<?php

namespace App\Http\Controllers\Api;

use App\Models\SpeakingAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpeakingAttemptController extends Controller
{
    public function index(Request $request)
    {
        $items = SpeakingAttempt::with(['user', 'lesson'])
            ->when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
            'audio_url' => 'nullable|string|max:255',
            'score' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $item = SpeakingAttempt::create($validated);

        return response()->json([
            'message' => 'Tạo bài speaking thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            SpeakingAttempt::with(['user', 'lesson'])->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $item = SpeakingAttempt::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
            'audio_url' => 'nullable|string|max:255',
            'score' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật bài speaking thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        SpeakingAttempt::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa bài speaking thành công',
        ]);
    }
}