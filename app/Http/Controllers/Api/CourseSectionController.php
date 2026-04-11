<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseSectionResource;
use App\Http\Requests\CourseSection\StoreCourseSectionRequest;
use App\Http\Requests\CourseSection\UpdateCourseSectionRequest;

class CourseSectionController extends Controller
{
    public function byCourse(Request $request, \App\Models\Course $course)
    {
        $items = CourseSection::with('lessons')
            ->where('course_id', $course->id)
            ->orderBy('order_number')
            ->get();

        return CourseSectionResource::collection($items);
    }

    public function index(Request $request)
    {
        $query = CourseSection::with('lessons');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $items = $query->orderBy('order_number')->paginate(10);

        return CourseSectionResource::collection($items);
    }

    public function store(StoreCourseSectionRequest $request)
    {
        $item = CourseSection::create($request->validated());
        $item->load('lessons');

        return response()->json([
            'success' => true,
            'message' => 'Tạo phần học thành công.',
            'data' => new CourseSectionResource($item),
        ], 201);
    }

    public function show(CourseSection $courseSection)
    {
        $courseSection->load('lessons');

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết phần học thành công.',
            'data' => new CourseSectionResource($courseSection),
        ]);
    }

    public function update(UpdateCourseSectionRequest $request, CourseSection $courseSection)
    {
        $courseSection->update($request->validated());
        $courseSection->load('lessons');

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật phần học thành công.',
            'data' => new CourseSectionResource($courseSection),
        ]);
    }

    public function destroy(CourseSection $courseSection)
    {
        $courseSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa phần học thành công.',
        ]);
    }
}