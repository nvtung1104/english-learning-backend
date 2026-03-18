<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['category', 'level', 'creator']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allowedSorts = ['id', 'title', 'price', 'created_at'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'id';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';

        $items = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return CourseResource::collection($items);
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $data['created_by'] = auth()->id();

        $course = Course::create($data);
        $course->load(['category', 'level', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Tạo khóa học thành công.',
            'data' => new CourseResource($course),
        ], 201);
    }

    public function show(Course $course)
    {
        $course->load(['category', 'level', 'creator', 'sections.lessons']);

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết khóa học thành công.',
            'data' => new CourseResource($course),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($data);
        $course->load(['category', 'level', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật khóa học thành công.',
            'data' => new CourseResource($course),
        ]);
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa khóa học thành công.',
        ]);
    }
}