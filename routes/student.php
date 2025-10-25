<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Routes for student dashboard and related functionality.
| All routes are protected by the 'auth' and 'student' middleware.
|
*/

Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    
    // Student Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // My Tasks
    Route::get('/tasks', [StudentDashboardController::class, 'tasks'])->name('tasks');
    
    // Task Management
    Route::patch('/task/{task}/complete', [StudentDashboardController::class, 'completeTask'])->name('task.complete');
    
    // Notifications
    Route::patch('/notification/{notification}/read', [StudentDashboardController::class, 'markNotificationRead'])->name('notification.read');
    
});
