<?php

namespace App\Http\Controllers\Api;

use App\Models\ListeningLesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListeningLessonController extends Controller
{
    public function index(Request $request)
    {
        $items = ListeningLesson::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id|unique:listening_lessons,lesson_id',
            'audio_url' => 'nullable|string|max:255',
            'transcript' => 'nullable|string',
        ]);

        $item = ListeningLesson::create($validated);

        return response()->json([
            'message' => 'Tạo bài nghe thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(ListeningLesson::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $item = ListeningLesson::findOrFail($id);

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id|unique:listening_lessons,lesson_id,' . $item->id,
            'audio_url' => 'nullable|string|max:255',
            'transcript' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật bài nghe thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        ListeningLesson::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa bài nghe thành công',
        ]);
    }
}