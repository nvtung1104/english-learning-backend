<?php

namespace App\Http\Controllers\Api;

use App\Models\ListeningLesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ListeningLessonResource;

class ListeningLessonController extends Controller
{
    public function index(Request $request)
    {
        $items = ListeningLesson::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->orderBy('id')
            ->get();

        return ListeningLessonResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id'  => 'required|exists:lessons,id|unique:listening_lessons,lesson_id',
            'audio_url'  => 'nullable|string|max:255',
            'transcript' => 'nullable|string',
        ]);

        $item = ListeningLesson::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bài nghe thành công.',
            'data'    => new ListeningLessonResource($item),
        ], 201);
    }

    public function show(ListeningLesson $listeningLesson)
    {
        return response()->json([
            'success' => true,
            'data'    => new ListeningLessonResource($listeningLesson),
        ]);
    }

    public function update(Request $request, ListeningLesson $listeningLesson)
    {
        $validated = $request->validate([
            'lesson_id'  => 'sometimes|required|exists:lessons,id|unique:listening_lessons,lesson_id,' . $listeningLesson->id,
            'audio_url'  => 'nullable|string|max:255',
            'transcript' => 'nullable|string',
        ]);

        $listeningLesson->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài nghe thành công.',
            'data'    => new ListeningLessonResource($listeningLesson),
        ]);
    }

    public function destroy(ListeningLesson $listeningLesson)
    {
        $listeningLesson->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bài nghe thành công.',
        ]);
    }
}