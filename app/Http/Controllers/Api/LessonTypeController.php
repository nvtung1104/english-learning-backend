<?php

namespace App\Http\Controllers\Api;

use App\Models\LessonType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonTypeResource;
use App\Http\Requests\LessonType\StoreLessonTypeRequest;
use App\Http\Requests\LessonType\UpdateLessonTypeRequest;

class LessonTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = LessonType::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('all')) {
            return LessonTypeResource::collection(
                $query->orderByRaw("CASE WHEN category IS NOT NULL THEN 0 ELSE 1 END")->orderBy('id')->get()
            );
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);

        return LessonTypeResource::collection($items);
    }

    public function store(StoreLessonTypeRequest $request)
    {
        $item = LessonType::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tạo loại bài học thành công.',
            'data' => new LessonTypeResource($item),
        ], 201);
    }

    public function show(LessonType $lessonType)
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết loại bài học thành công.',
            'data' => new LessonTypeResource($lessonType),
        ]);
    }

    public function update(UpdateLessonTypeRequest $request, LessonType $lessonType)
    {
        $lessonType->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật loại bài học thành công.',
            'data' => new LessonTypeResource($lessonType),
        ]);
    }

    public function destroy(LessonType $lessonType)
    {
        $lessonType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa loại bài học thành công.',
        ]);
    }
}