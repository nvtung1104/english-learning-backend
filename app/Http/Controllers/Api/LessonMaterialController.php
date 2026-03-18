<?php

namespace App\Http\Controllers\Api;

use App\Models\LessonMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonMaterialResource;

class LessonMaterialController extends Controller
{
    public function index(Request $request)
    {
        $items = LessonMaterial::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->latest()
            ->get();

        return LessonMaterialResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'file_url'  => 'required|string|max:255',
            'type'      => 'nullable|in:pdf,doc,audio,video',
        ]);

        $item = LessonMaterial::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tạo tài liệu bài học thành công.',
            'data'    => new LessonMaterialResource($item),
        ], 201);
    }

    public function show(LessonMaterial $lessonMaterial)
    {
        return response()->json([
            'success' => true,
            'data'    => new LessonMaterialResource($lessonMaterial),
        ]);
    }

    public function update(Request $request, LessonMaterial $lessonMaterial)
    {
        $validated = $request->validate([
            'lesson_id' => 'sometimes|required|exists:lessons,id',
            'file_url'  => 'sometimes|required|string|max:255',
            'type'      => 'nullable|in:pdf,doc,audio,video',
        ]);

        $lessonMaterial->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tài liệu bài học thành công.',
            'data'    => new LessonMaterialResource($lessonMaterial),
        ]);
    }

    public function destroy(LessonMaterial $lessonMaterial)
    {
        $lessonMaterial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa tài liệu bài học thành công.',
        ]);
    }
}