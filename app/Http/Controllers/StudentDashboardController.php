<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Notification;

class StudentDashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $student = Auth::user();
        $studentId = $student->id;

        // Fetch all tasks for the student
        $allTasks = Task::forStudent($studentId)
            ->orderBy('deadline', 'asc')
            ->get();

        // Calculate task statistics
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'completed')->count();
        $pendingTasks = $allTasks->whereIn('status', ['pending', 'in_progress'])->count();
        $submittedTasks = $allTasks->where('status', 'submitted')->count();
        
        // Calculate completion percentage
        $completionPercentage = $totalTasks > 0 
            ? round(($completedTasks / $totalTasks) * 100, 1) 
            : 0;

        // Get tasks by status
        $tasks = Task::forStudent($studentId)
            ->with('teacher')
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'in_progress' THEN 2 
                WHEN status = 'submitted' THEN 3 
                WHEN status = 'completed' THEN 4 
                ELSE 5 END")
            ->orderBy('deadline', 'asc')
            ->limit(10)
            ->get();

        // Get upcoming meetings (next 3)
        $upcomingMeetings = Meeting::forStudent($studentId)
            ->upcoming()
            ->with('teacher')
            ->limit(3)
            ->get();

        // Get recent notifications (latest 5)
        $notifications = Notification::forUser($studentId)
            ->with('sender')
            ->latest()
            ->limit(5)
            ->get();

        // Get unread notification count
        $unreadNotificationsCount = Notification::forUser($studentId)
            ->unread()
            ->count();

        // Count overdue tasks
        $overdueTasks = $allTasks->filter(fn($task) => $task->isOverdue())->count();

        // Get today's meetings
        $todayMeetings = Meeting::forStudent($studentId)
            ->whereDate('scheduled_at', today())
            ->where('status', 'scheduled')
            ->count();

        // Prepare student info
        $studentInfo = [
            'name' => $student->name,
            'email' => $student->email,
            'role' => 'Student',
            'last_login' => $student->updated_at ?? $student->created_at,
            'member_since' => $student->created_at,
        ];

        // Prepare dashboard data
        return view('student.dashboard', compact(
            'studentInfo',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'submittedTasks',
            'completionPercentage',
            'tasks',
            'upcomingMeetings',
            'notifications',
            'unreadNotificationsCount',
            'overdueTasks',
            'todayMeetings'
        ));
    }

    /**
     * Mark a task as completed.
     */
    public function completeTask(Request $request, Task $task)
    {
        // Ensure the task belongs to the authenticated student
        if ($task->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Task marked as completed!');
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read!');
    }
}
