<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\InstructorController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', [FrontController::class , 'home'])
    ->name('home');




Route::controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register')
            ->name('api.register');

        Route::post('/login', 'login')
            ->name('api.login');

        Route::post('/logout', 'logout')
            ->name('api.logout');

});


// Admin Login Route:
Route::prefix('/admin')->group(function () {
    // Step 1: Login with email + password
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');

    // Step 2: Confirm secret passcode to complete login
    Route::post('/verify', [AdminAuthController::class, 'verify'])->name('admin.verify');
});



// admin Dashboards
Route::middleware(['admin', 'auth:sanctum'])->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [FrontController::class, 'adminDashboard'])
        ->name('dashboard');

    // CRUD: students
    // Route::apiResource('users', Admin\UserController::class);


    // Categories & Subcategories
    // Route::apiResource('categories', Admin\CategoryController::class);

    // Courses Management
    // Route::apiResource('courses', Admin\CourseController::class);
    // Route::get('courses/{id}/students', [Admin\CourseController::class, 'students']);


    // Lessons inside a course
    // Route::apiResource('courses.lessons', Admin\LessonController::class);


    // Quizzes & Assignments
    // Route::apiResource('quizzes', Admin\QuizController::class);
    // Route::apiResource('assignments', Admin\AssignmentController::class);


    // Enrollments
    // Route::get('enrollments', [Admin\EnrollmentController::class, 'index']);
    // Route::delete('enrollments/{id}', [Admin\EnrollmentController::class, 'destroy']);


    // Subscriptions & Payments (if any)
    // Route::apiResource('subscriptions', Admin\SubscriptionController::class);


    // Settings & Statistics
    // Route::get('dashboard/overview', [Admin\DashboardController::class, 'overview']);
    // Route::get('dashboard/statistics', [Admin\DashboardController::class, 'statistics']);


Route::apiResource('/instructors', InstructorController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('instructors');

    // Add more admin routes here
});






// 1|mLm7aqOHJs965R4UECIK0LcOA6fcfJuvD7HyfurH29859065    => admin token
