<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdminUserController;
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
use App\Http\Controllers\Api\QuizController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Public catalog & content
Route::get('/categories',        [CourseCategoryController::class, 'index']);
Route::get('/categories/{courseCategory}', [CourseCategoryController::class, 'show']);
Route::get('/levels',            [CourseLevelController::class, 'index']);
Route::get('/levels/{courseLevel}', [CourseLevelController::class, 'show']);
Route::get('/lesson-types',      [LessonTypeController::class, 'index']);
Route::get('/courses',           [CourseController::class, 'index']);
Route::get('/courses/{course}',  [CourseController::class, 'show']);
Route::get('/courses/{course}/reviews', [ReviewController::class, 'index']);
Route::get('/lessons/{lesson}',  [LessonController::class, 'show']);
Route::get('/quizzes/{quiz}',    [QuizController::class, 'show']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me',          [AuthController::class, 'me']);
    Route::post('/logout',     [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    // Profile (all roles)
    Route::get('/profile',                  [ProfileController::class, 'show']);
    Route::put('/profile',                  [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // User management
        Route::apiResource('users', AdminUserController::class);
        Route::post('/users/{user}/toggle-status',  [AdminUserController::class, 'toggleStatus']);
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword']);

        // Catalog management
        Route::apiResource('categories',   CourseCategoryController::class)->except(['index', 'show']);
        Route::apiResource('levels',       CourseLevelController::class)->except(['index', 'show']);
        Route::apiResource('lesson-types', LessonTypeController::class)->except(['index', 'show']);

        // Roles & Permissions
        Route::apiResource('roles',            RoleController::class);
        Route::apiResource('permissions',      PermissionController::class);
        Route::apiResource('role-permissions', RolePermissionController::class);
        Route::apiResource('user-roles',       UserRoleController::class);
        Route::post('/user-roles/remove',       [UserRoleController::class, 'remove']);
        Route::post('/role-permissions/remove', [RolePermissionController::class, 'remove']);

        // Notifications
        Route::apiResource('notifications', NotificationController::class);

        // Reviews moderation
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | TEACHER
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,teacher')->prefix('teacher')->group(function () {

        Route::apiResource('courses',          CourseController::class)->except(['index', 'show']);
        Route::apiResource('sections',         CourseSectionController::class);
        Route::apiResource('lessons',          LessonController::class)->except(['show']);
        Route::apiResource('lesson-materials', LessonMaterialController::class);

        Route::apiResource('vocabularies',      VocabularyController::class);
        Route::apiResource('grammar-topics',    GrammarTopicController::class);
        Route::apiResource('listening-lessons', ListeningLessonController::class);

        Route::apiResource('speaking-attempts', SpeakingAttemptController::class);

        Route::apiResource('quizzes',        QuizController::class)->except(['show']);
        Route::apiResource('quiz-questions', QuizQuestionController::class);
        Route::apiResource('quiz-answers',   QuizAnswerController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:student')->prefix('student')->group(function () {

        // Enrollment
        Route::post('/courses/{course}/enroll', [CourseEnrollmentController::class, 'enroll']);
        Route::get('/my-courses',               [CourseEnrollmentController::class, 'index']);

        // Progress
        Route::post('/lessons/{lesson}/complete',    [LessonProgressController::class, 'complete']);
        Route::get('/lesson-progress',               [LessonProgressController::class, 'index']);
        Route::get('/courses/{course}/progress',     [LessonProgressController::class, 'courseProgress']);

        // Quiz
        Route::post('/quizzes/{quiz}/submit', [QuizAttemptController::class, 'submit']);
        Route::get('/quiz-attempts',          [QuizAttemptController::class, 'index']);
        Route::apiResource('user-answers',    UserAnswerController::class);

        // Reviews
        Route::post('/reviews',              [ReviewController::class, 'store']);
        Route::put('/reviews/{review}',      [ReviewController::class, 'update']);
        Route::delete('/reviews/{review}',   [ReviewController::class, 'destroy']);

        // Notifications
        Route::get('/notifications',                          [NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read',     [NotificationController::class, 'markAsRead']);
    });

    /*
    |--------------------------------------------------------------------------
    | SYSTEM (dev / internal tool - no role restriction)
    |--------------------------------------------------------------------------
    */
    Route::prefix('system')->group(function () {
        Route::apiResource('courses',           CourseController::class);
        Route::apiResource('sections',          CourseSectionController::class);
        Route::apiResource('lessons',           LessonController::class);
        Route::apiResource('lesson-materials',  LessonMaterialController::class);
        Route::apiResource('vocabularies',      VocabularyController::class);
        Route::apiResource('grammar-topics',    GrammarTopicController::class);
        Route::apiResource('listening-lessons', ListeningLessonController::class);
        Route::apiResource('quizzes',           QuizController::class);
        Route::apiResource('quiz-questions',    QuizQuestionController::class);
        Route::apiResource('quiz-answers',      QuizAnswerController::class);
    });
});
