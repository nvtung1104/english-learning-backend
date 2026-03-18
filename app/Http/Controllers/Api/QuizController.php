<?php

namespace App\Http\Controllers\Api;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::query();

        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);

        return QuizResource::collection($items);
    }

    public function store(StoreQuizRequest $request)
    {
        $item = Quiz::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tạo quiz thành công.',
            'data' => new QuizResource($item),
        ], 201);
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.answers');

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết quiz thành công.',
            'data' => new QuizResource($quiz),
        ]);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        $quiz->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật quiz thành công.',
            'data' => new QuizResource($quiz),
        ]);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa quiz thành công.',
        ]);
    }
}