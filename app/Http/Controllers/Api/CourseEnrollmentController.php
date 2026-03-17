<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $items = CourseEnrollment::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $exists = CourseEnrollment::where('user_id', $request->user()->id)
            ->where('course_id', $validated['course_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Bạn đã đăng ký khóa học này rồi',
            ], 422);
        }

        $item = CourseEnrollment::create([
            'user_id' => $request->user()->id,
            'course_id' => $validated['course_id'],
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'message' => 'Đăng ký khóa học thành công',
            'data' => $item->load('course'),
        ], 201);
    }
}