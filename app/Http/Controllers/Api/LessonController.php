<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $lessons = Lesson::with(['section', 'lessonType'])
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->orderBy('order_number')
            ->get();

        return response()->json($lessons);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:course_sections,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'lesson_type_id' => 'nullable|exists:lesson_types,id',
            'video_url' => 'nullable|string|max:255',
            'order_number' => 'nullable|integer|min:1',
        ]);

        $lesson = Lesson::create([
            'section_id' => $validated['section_id'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'lesson_type_id' => $validated['lesson_type_id'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'order_number' => $validated['order_number'] ?? 1,
        ]);

        return response()->json([
            'message' => 'Tạo bài học thành công',
            'data' => $lesson->load(['section', 'lessonType']),
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            Lesson::with([
                'section.course',
                'lessonType',
                'materials',
                'vocabularies',
                'grammarTopics',
                'listeningLesson',
                'quizzes.questions.answers'
            ])->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'section_id' => 'required|exists:course_sections,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'lesson_type_id' => 'nullable|exists:lesson_types,id',
            'video_url' => 'nullable|string|max:255',
            'order_number' => 'nullable|integer|min:1',
        ]);

        $lesson->update($validated);

        return response()->json([
            'message' => 'Cập nhật bài học thành công',
            'data' => $lesson->load(['section', 'lessonType']),
        ]);
    }

    public function destroy(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return response()->json([
            'message' => 'Xóa bài học thành công',
        ]);
    }
}