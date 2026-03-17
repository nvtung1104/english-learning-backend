<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonProgressController extends Controller
{
    public function index(Request $request)
    {
        $items = LessonProgress::with('lesson')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($items);
    }

    public function complete(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'lesson_id' => $validated['lesson_id'],
            ],
            [
                'status' => 1,
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Đánh dấu hoàn thành bài học thành công',
            'data' => $progress,
        ]);
    }

    public function courseProgress(Request $request, string $courseId)
    {
        $lessonIds = Lesson::whereHas('section', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->pluck('id');

        $total = $lessonIds->count();

        $completed = LessonProgress::where('user_id', $request->user()->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 1)
            ->count();

        $percent = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

        return response()->json([
            'course_id' => (int) $courseId,
            'total_lessons' => $total,
            'completed_lessons' => $completed,
            'progress_percent' => $percent,
        ]);
    }
}