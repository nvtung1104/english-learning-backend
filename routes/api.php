<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserRoleController;
use App\Http\Controllers\Api\CourseLevelController;
use App\Http\Controllers\Api\LessonTypeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\QuizAnswerController;
use App\Http\Controllers\Api\VocabularyController;
use App\Http\Controllers\Api\UserAnswerController;
use App\Http\Controllers\Api\GrammarTopicController;
use App\Http\Controllers\Api\CourseCategoryController;
use App\Http\Controllers\Api\CourseSectionController;
use App\Http\Controllers\Api\LessonMaterialController;
use App\Http\Controllers\Api\ListeningLessonController;
use App\Http\Controllers\Api\QuizQuestionController;
use App\Http\Controllers\Api\QuizAttemptController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\SpeakingAttemptController;
use App\Http\Controllers\Api\CourseEnrollmentController;
use App\Http\Controllers\Api\LessonProgressController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    */
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    /*
    |--------------------------------------------------------------------------
    | Core catalog
    |--------------------------------------------------------------------------
    */
    Route::apiResource('categories', CourseCategoryController::class);
    Route::apiResource('levels', CourseLevelController::class);
    Route::apiResource('lesson-types', LessonTypeController::class);

    /*
    |--------------------------------------------------------------------------
    | Courses / Sections / Lessons
    |--------------------------------------------------------------------------
    */
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('sections', CourseSectionController::class);
    Route::apiResource('lessons', LessonController::class);
    Route::apiResource('lesson-materials', LessonMaterialController::class);

    /*
    |--------------------------------------------------------------------------
    | Lesson contents
    |--------------------------------------------------------------------------
    */
    Route::apiResource('vocabularies', VocabularyController::class);
    Route::apiResource('grammar-topics', GrammarTopicController::class);
    Route::apiResource('listening-lessons', ListeningLessonController::class);
    Route::apiResource('speaking-attempts', SpeakingAttemptController::class);

    /*
    |--------------------------------------------------------------------------
    | Quizzes
    |--------------------------------------------------------------------------
    */
    Route::apiResource('quizzes', QuizController::class);
    Route::apiResource('quiz-questions', QuizQuestionController::class);
    Route::apiResource('quiz-answers', QuizAnswerController::class);
    Route::apiResource('user-answers', UserAnswerController::class);

    Route::get('/quiz-attempts', [QuizAttemptController::class, 'index']);
    Route::post('/quizzes/{quiz}/submit', [QuizAttemptController::class, 'submit']);

    /*
    |--------------------------------------------------------------------------
    | Enrollments / Progress
    |--------------------------------------------------------------------------
    */
    Route::get('/enrollments', [CourseEnrollmentController::class, 'index']);
    Route::post('/enrollments', [CourseEnrollmentController::class, 'enroll']);

    Route::get('/lesson-progress', [LessonProgressController::class, 'index']);
    Route::post('/lesson-progress/complete', [LessonProgressController::class, 'complete']);
    Route::get('/courses/{course}/progress', [LessonProgressController::class, 'courseProgress']);

    /*
    |--------------------------------------------------------------------------
    | Reviews / Notifications
    |--------------------------------------------------------------------------
    */
    Route::apiResource('reviews', ReviewController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);

    /*
    |--------------------------------------------------------------------------
    | Roles / Permissions
    |--------------------------------------------------------------------------
    */
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);

    Route::apiResource('user-roles', UserRoleController::class);
    Route::post('/user-roles/remove', [UserRoleController::class, 'remove']);

    Route::apiResource('role-permissions', RolePermissionController::class);
    Route::post('/role-permissions/remove', [RolePermissionController::class, 'remove']);
});