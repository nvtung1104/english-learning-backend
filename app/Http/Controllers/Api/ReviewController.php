<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $items = Review::with('user')
            ->when($request->course_id, fn($q) => $q->where('course_id', $request->course_id))
            ->latest()
            ->paginate(15);

        return ReviewResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string',
        ]);

        $exists = Review::where('user_id', $request->user()->id)
            ->where('course_id', $validated['course_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá khóa học này rồi.',
            ], 422);
        }

        $item = Review::create([
            'user_id'   => $request->user()->id,
            'course_id' => $validated['course_id'],
            'rating'    => $validated['rating'],
            'comment'   => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá khóa học thành công.',
            'data'    => new ReviewResource($item->load('user')),
        ], 201);
    }

    public function show(Review $review)
    {
        return response()->json([
            'success' => true,
            'data'    => new ReviewResource($review->load('user')),
        ]);
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền sửa đánh giá này.'], 403);
        }

        $validated = $request->validate([
            'rating'  => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật đánh giá thành công.',
            'data'    => new ReviewResource($review->load('user')),
        ]);
    }

    public function destroy(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền xóa đánh giá này.'], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa đánh giá thành công.',
        ]);
    }
}