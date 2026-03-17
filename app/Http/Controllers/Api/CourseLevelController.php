<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseLevelController extends Controller
{
    public function index()
    {
        return response()->json(CourseLevel::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_levels,name',
        ]);

        $level = CourseLevel::create($validated);

        return response()->json([
            'message' => 'Tạo cấp độ thành công',
            'data' => $level,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(CourseLevel::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $level = CourseLevel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_levels,name,' . $level->id,
        ]);

        $level->update($validated);

        return response()->json([
            'message' => 'Cập nhật cấp độ thành công',
            'data' => $level,
        ]);
    }

    public function destroy(string $id)
    {
        $level = CourseLevel::findOrFail($id);
        $level->delete();

        return response()->json([
            'message' => 'Xóa cấp độ thành công',
        ]);
    }
}