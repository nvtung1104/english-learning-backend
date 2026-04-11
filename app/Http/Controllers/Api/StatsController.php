<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\GrammarTopic;
use App\Models\LessonProgress;
use App\Models\ListeningLesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Review;
use App\Models\User;
use App\Models\Vocabulary;

class StatsController extends Controller
{
    public function student()
    {
        $user = auth()->user();

        $enrollments = CourseEnrollment::where('user_id', $user->id)->count();

        $lessonsCompleted = LessonProgress::where('user_id', $user->id)
            ->where('status', 1)->count();

        $attempts = QuizAttempt::where('user_id', $user->id)->count();

        $avgScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('score')->avg('score');

        return response()->json([
            'enrollments'       => $enrollments,
            'lessons_completed' => $lessonsCompleted,
            'quiz_attempts'     => $attempts,
            'avg_score'         => $avgScore ? round($avgScore, 1) : null,
        ]);
    }

    public function teacher()
    {
        $user = auth()->user();

        $courses = Course::where('created_by', $user->id)->count();

        $students = CourseEnrollment::whereHas('course', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })->distinct('user_id')->count('user_id');

        $quizzes = Quiz::whereHas('lesson.section.course', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })->count();

        $avgRating = Review::whereHas('course', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })->avg('rating');

        return response()->json([
            'courses'    => $courses,
            'students'   => $students,
            'quizzes'    => $quizzes,
            'avg_rating' => $avgRating ? round($avgRating, 1) : null,
        ]);
    }

    public function public()
    {
        $totalAttempts  = QuizAttempt::count();
        $passedAttempts = QuizAttempt::where('score', '>=', 50)->count();
        $completionRate = $totalAttempts > 0
            ? round(($passedAttempts / $totalAttempts) * 100)
            : null;

        $avgScore  = QuizAttempt::whereNotNull('score')->avg('score');
        $avgRating = Review::avg('rating');

        $totalPractice = Quiz::count()
            + Vocabulary::count()
            + GrammarTopic::count()
            + ListeningLesson::count();

        return response()->json([
            'users'            => User::count(),
            'courses'          => Course::count(),
            'quizzes'          => Quiz::count(),
            'practice_total'   => $totalPractice,
            'enrollments'      => CourseEnrollment::count(),
            'attempts'         => $totalAttempts,
            'lessons_completed'=> LessonProgress::where('status', 1)->count(),
            'avg_score'        => $avgScore  ? round($avgScore, 1)  : null,
            'avg_rating'       => $avgRating ? round($avgRating, 1) : null,
            'completion_rate'  => $completionRate,
        ]);
    }
}
