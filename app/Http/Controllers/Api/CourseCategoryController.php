<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseCategoryResource;
use App\Http\Requests\CourseCategory\StoreCourseCategoryRequest;
use App\Http\Requests\CourseCategory\UpdateCourseCategoryRequest;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);

        return CourseCategoryResource::collection($items);
    }

    public function store(StoreCourseCategoryRequest $request)
    {
        $item = CourseCategory::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tạo danh mục khóa học thành công.',
            'data' => new CourseCategoryResource($item),
        ], 201);
    }

    public function show(CourseCategory $courseCategory)
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết danh mục khóa học thành công.',
            'data' => new CourseCategoryResource($courseCategory),
        ]);
    }

    public function update(UpdateCourseCategoryRequest $request, CourseCategory $courseCategory)
    {
        $courseCategory->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật danh mục khóa học thành công.',
            'data' => new CourseCategoryResource($courseCategory),
        ]);
    }

    public function destroy(CourseCategory $courseCategory)
    {
        $courseCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục khóa học thành công.',
        ]);
    }
}