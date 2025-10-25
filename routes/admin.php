<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for admin dashboard and related functionality.
| All routes are protected by the 'auth' and 'admin' middleware.
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminDashboardController::class, 'userDetails'])->name('users.details');
    
    // Teachers Management
    Route::get('/teachers', [AdminDashboardController::class, 'teachers'])->name('teachers');
    
    // Students Management
    Route::get('/students', [AdminDashboardController::class, 'students'])->name('students');
    
    // Tasks Management
    Route::get('/tasks', [AdminDashboardController::class, 'tasks'])->name('tasks');
    
    // Meetings Management
    Route::get('/meetings', [AdminDashboardController::class, 'meetings'])->name('meetings');
    Route::get('/meetings/pending', [AdminDashboardController::class, 'pendingMeetings'])->name('meetings.pending');
    Route::get('/meetings/rejected', [AdminDashboardController::class, 'rejectedMeetings'])->name('meetings.rejected');
    
    // Notifications
    Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('notifications');
    
    // Resources
    Route::get('/resources', [AdminDashboardController::class, 'resources'])->name('resources');
    
    // System Stats
    Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
    
});
