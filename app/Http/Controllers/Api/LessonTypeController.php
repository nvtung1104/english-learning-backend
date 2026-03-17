<?php

namespace App\Http\Controllers\Api;

use App\Models\LessonType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonTypeController extends Controller
{
    public function index()
    {
        return response()->json(LessonType::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:lesson_types,name',
        ]);

        $type = LessonType::create($validated);

        return response()->json([
            'message' => 'Tạo loại bài học thành công',
            'data' => $type,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(LessonType::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $type = LessonType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:lesson_types,name,' . $type->id,
        ]);

        $type->update($validated);

        return response()->json([
            'message' => 'Cập nhật loại bài học thành công',
            'data' => $type,
        ]);
    }

    public function destroy(string $id)
    {
        $type = LessonType::findOrFail($id);
        $type->delete();

        return response()->json([
            'message' => 'Xóa loại bài học thành công',
        ]);
    }
}