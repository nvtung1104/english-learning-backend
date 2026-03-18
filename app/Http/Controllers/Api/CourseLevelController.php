<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseLevelResource;
use App\Http\Requests\CourseLevel\StoreCourseLevelRequest;
use App\Http\Requests\CourseLevel\UpdateCourseLevelRequest;

class CourseLevelController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseLevel::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);

        return CourseLevelResource::collection($items);
    }

    public function store(StoreCourseLevelRequest $request)
    {
        $item = CourseLevel::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tạo cấp độ khóa học thành công.',
            'data' => new CourseLevelResource($item),
        ], 201);
    }

    public function show(CourseLevel $courseLevel)
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết cấp độ khóa học thành công.',
            'data' => new CourseLevelResource($courseLevel),
        ]);
    }

    public function update(UpdateCourseLevelRequest $request, CourseLevel $courseLevel)
    {
        $courseLevel->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật cấp độ khóa học thành công.',
            'data' => new CourseLevelResource($courseLevel),
        ]);
    }

    public function destroy(CourseLevel $courseLevel)
    {
        $courseLevel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa cấp độ khóa học thành công.',
        ]);
    }
}