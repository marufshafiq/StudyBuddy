<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherDashboardController;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Routes for teacher dashboard and related functionality.
| All routes are protected by the 'auth' and 'teacher' middleware.
|
*/

Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    
    // Teacher Dashboard
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Students Management
    Route::get('/students', [TeacherDashboardController::class, 'students'])->name('students');
    
    // Assignments Management
    Route::get('/assignments', [TeacherDashboardController::class, 'assignments'])->name('assignments');
    Route::post('/assignments', [TeacherDashboardController::class, 'storeTask'])->name('assignments.store');
    Route::delete('/assignments/{task}', [TeacherDashboardController::class, 'deleteTask'])->name('assignments.delete');
    
});
