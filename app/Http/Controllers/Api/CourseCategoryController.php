<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $categories = CourseCategory::latest()->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name',
            'description' => 'nullable|string',
        ]);

        $category = CourseCategory::create($validated);

        return response()->json([
            'message' => 'Tạo danh mục thành công',
            'data' => $category,
        ], 201);
    }

    public function show(string $id)
    {
        $category = CourseCategory::findOrFail($id);

        return response()->json($category);
    }

    public function update(Request $request, string $id)
    {
        $category = CourseCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Cập nhật danh mục thành công',
            'data' => $category,
        ]);
    }

    public function destroy(string $id)
    {
        $category = CourseCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Xóa danh mục thành công',
        ]);
    }
}