<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::query();

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('lesson_type_id')) {
            $query->where('lesson_type_id', $request->lesson_type_id);
        }

        $items = $query->orderBy('order_number')->paginate(10);

        return LessonResource::collection($items);
    }

    public function store(StoreLessonRequest $request)
    {
        $data = $request->validated();
        $data['created_at'] = now();

        $item = Lesson::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bài học thành công.',
            'data' => new LessonResource($item),
        ], 201);
    }

    public function show(Lesson $lesson)
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết bài học thành công.',
            'data' => new LessonResource($lesson),
        ]);
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $lesson->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài học thành công.',
            'data' => new LessonResource($lesson),
        ]);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bài học thành công.',
        ]);
    }
}