<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseSectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = CourseSection::with('lessons')
            ->when($request->course_id, fn($q) => $q->where('course_id', $request->course_id))
            ->orderBy('order_number')
            ->get();

        return response()->json($sections);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'order_number' => 'nullable|integer|min:1',
        ]);

        $section = CourseSection::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'order_number' => $validated['order_number'] ?? 1,
        ]);

        return response()->json([
            'message' => 'Tạo section thành công',
            'data' => $section,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            CourseSection::with('lessons')->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $section = CourseSection::findOrFail($id);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'order_number' => 'nullable|integer|min:1',
        ]);

        $section->update($validated);

        return response()->json([
            'message' => 'Cập nhật section thành công',
            'data' => $section,
        ]);
    }

    public function destroy(string $id)
    {
        $section = CourseSection::findOrFail($id);
        $section->delete();

        return response()->json([
            'message' => 'Xóa section thành công',
        ]);
    }
}