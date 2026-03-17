<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['category', 'level', 'creator'])
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%');
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->level_id, function ($query) use ($request) {
                $query->where('level_id', $request->level_id);
            })
            ->latest()
            ->paginate(10);

        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:course_categories,id',
            'level_id' => 'required|exists:course_levels,id',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:1',
            'status' => 'nullable|integer|in:0,1',
        ]);

        $course = Course::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'level_id' => $validated['level_id'],
            'price' => $validated['price'] ?? 0,
            'thumbnail' => $validated['thumbnail'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'status' => $validated['status'] ?? 1,
            'created_by' => $request->user()->id,
        ]);

        $course->load(['category', 'level', 'creator']);

        return response()->json([
            'message' => 'Tạo khóa học thành công',
            'data' => $course,
        ], 201);
    }

    public function show(string $id)
    {
        $course = Course::with([
            'category',
            'level',
            'creator',
            'sections.lessons',
            'reviews.user',
        ])->findOrFail($id);

        return response()->json($course);
    }

    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:course_categories,id',
            'level_id' => 'required|exists:course_levels,id',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:1',
            'status' => 'nullable|integer|in:0,1',
        ]);

        $course->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'level_id' => $validated['level_id'],
            'price' => $validated['price'] ?? 0,
            'thumbnail' => $validated['thumbnail'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'status' => $validated['status'] ?? 1,
        ]);

        $course->load(['category', 'level', 'creator']);

        return response()->json([
            'message' => 'Cập nhật khóa học thành công',
            'data' => $course,
        ]);
    }

    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json([
            'message' => 'Xóa khóa học thành công',
        ]);
    }
}