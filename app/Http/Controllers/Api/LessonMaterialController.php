<?php

namespace App\Http\Controllers\Api;

use App\Models\LessonMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonMaterialController extends Controller
{
    public function index(Request $request)
    {
        $items = LessonMaterial::with('lesson')
            ->when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'file_url' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
        ]);

        $item = LessonMaterial::create($validated);

        return response()->json([
            'message' => 'Tạo tài liệu bài học thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            LessonMaterial::with('lesson')->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $item = LessonMaterial::findOrFail($id);

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'file_url' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật tài liệu bài học thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        LessonMaterial::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa tài liệu bài học thành công',
        ]);
    }
}