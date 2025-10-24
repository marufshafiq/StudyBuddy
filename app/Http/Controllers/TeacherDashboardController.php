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

    /**
     * Display all students.
     */
    public function students()
    {
        $teacher = Auth::user();
        
        // Get only students (exclude teachers and admins)
        $students = User::where('role', 'student')
            ->where('id', '!=', $teacher->id) // Exclude current teacher if somehow they have student role
            ->withCount(['tasks as total_tasks' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }])
            ->withCount(['tasks as completed_tasks' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)
                      ->where('status', 'completed');
            }])
            ->withCount(['tasks as pending_tasks' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)
                      ->whereIn('status', ['pending', 'in_progress']);
            }])
            ->latest()
            ->get();

        return view('teacher.students', compact('students'));
    }

    /**
     * Display assignments page with task creation form.
     */
    public function assignments()
    {
        $teacher = Auth::user();
        $teacherId = $teacher->id;

        // Get only students (exclude teachers and admins) for assignment dropdown
        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();

        // Get all tasks assigned by this teacher
        $tasks = Task::where('teacher_id', $teacherId)
            ->with('student')
            ->latest()
            ->paginate(15);

        return view('teacher.assignments', compact('students', 'tasks'));
    }

    /**
     * Store a new task.
     */
    public function storeTask(Request $request)
    {
        $teacher = Auth::user();

        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || $user->role !== 'student') {
                        $fail('The selected user must be a student.');
                    }
                },
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $task = Task::create([
            'teacher_id' => $teacher->id,
            'student_id' => $validated['student_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        // Create notification for the student
        Notification::create([
            'user_id' => $validated['student_id'],
            'sender_id' => $teacher->id,
            'title' => 'New Assignment: ' . $validated['title'],
            'message' => 'You have been assigned a new task by ' . $teacher->name . '. Due date: ' . ($validated['deadline'] ? date('M d, Y', strtotime($validated['deadline'])) : 'No deadline'),
            'type' => 'task',
            'is_read' => false,
        ]);

        return back()->with('success', 'Task assigned successfully!');
    }

    /**
     * Delete a task.
     */
    public function deleteTask(Task $task)
    {
        // Ensure the task belongs to the authenticated teacher
        if ($task->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }
}

