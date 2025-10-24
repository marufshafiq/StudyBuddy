<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\User;

class TeacherDashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $teacher = Auth::user();
        $teacherId = $teacher->id;

        // Get students assigned to this teacher (students with tasks from this teacher)
        $studentIds = Task::where('teacher_id', $teacherId)->distinct()->pluck('student_id');
        $students = User::whereIn('id', $studentIds)->where('role', 'student')->get();
        $totalStudents = $students->count();

        // Get all tasks assigned by this teacher
        $allTasks = Task::where('teacher_id', $teacherId)->get();
        $totalTasks = $allTasks->count();
        $pendingTasks = $allTasks->whereIn('status', ['pending', 'in_progress'])->count();
        $submittedTasks = $allTasks->where('status', 'submitted')->count();
        $completedTasks = $allTasks->where('status', 'completed')->count();

        // Get recent tasks (latest 10)
        $recentTasks = Task::where('teacher_id', $teacherId)
            ->with('student')
            ->latest()
            ->limit(10)
            ->get();

        // Get upcoming meetings
        $upcomingMeetings = Meeting::where('teacher_id', $teacherId)
            ->upcoming()
            ->with('student')
            ->limit(5)
            ->get();

        // Get today's meetings
        $todayMeetings = Meeting::where('teacher_id', $teacherId)
            ->whereDate('scheduled_at', today())
            ->where('status', 'scheduled')
            ->count();

        // Prepare teacher info
        $teacherInfo = [
            'name' => $teacher->name,
            'email' => $teacher->email,
            'role' => 'Teacher',
            'last_login' => $teacher->updated_at ?? $teacher->created_at,
        ];

        return view('teacher.dashboard', compact(
            'teacherInfo',
            'totalStudents',
            'totalTasks',
            'pendingTasks',
            'submittedTasks',
            'completedTasks',
            'recentTasks',
            'upcomingMeetings',
            'todayMeetings',
            'students'
        ));
    }
}
