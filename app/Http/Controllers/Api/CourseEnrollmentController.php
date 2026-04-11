<?php

namespace App\Http\Controllers\Api;

use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseEnrollmentResource;

class CourseEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $items = CourseEnrollment::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return CourseEnrollmentResource::collection($items);
    }

    public function teacherIndex(Request $request)
    {
        $courseIds = \App\Models\Course::where('created_by', $request->user()->id)->pluck('id');

        $items = CourseEnrollment::with(['user', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->get();

        return CourseEnrollmentResource::collection($items);
    }

    public function enroll(Request $request, \App\Models\Course $course)
    {
        $exists = CourseEnrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đăng ký khóa học này rồi.',
            ], 422);
        }

        $item = CourseEnrollment::create([
            'user_id'     => $request->user()->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký khóa học thành công.',
            'data'    => new CourseEnrollmentResource($item->load('course')),
        ], 201);
    }
}