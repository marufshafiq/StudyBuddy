<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\User;
use App\Services\ZoomService;

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
     * Display all tasks for the student.
     */
    public function tasks()
    {
        $student = Auth::user();
        $studentId = $student->id;

        // Fetch all tasks for the student with pagination
        $tasks = Task::forStudent($studentId)
            ->with('teacher')
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'in_progress' THEN 2 
                WHEN status = 'submitted' THEN 3 
                WHEN status = 'completed' THEN 4 
                ELSE 5 END")
            ->orderBy('deadline', 'asc')
            ->paginate(15);

        // Calculate task statistics
        $allTasks = Task::forStudent($studentId)->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'completed')->count();
        $pendingTasks = $allTasks->whereIn('status', ['pending', 'in_progress'])->count();
        $submittedTasks = $allTasks->where('status', 'submitted')->count();
        $overdueTasks = $allTasks->filter(fn($task) => $task->isOverdue())->count();

        return view('student.tasks', compact(
            'tasks',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'submittedTasks',
            'overdueTasks'
        ));
    }

    /**
     * Display all meetings for the student.
     */
    public function meetings()
    {
        $student = Auth::user();
        
        // Get all meetings (upcoming, past, and requests)
        $upcomingMeetings = Meeting::forStudent($student->id)
            ->approved()
            ->upcoming()
            ->with('teacher')
            ->get();
            
        $pendingRequests = Meeting::forStudent($student->id)
            ->pendingRequests()
            ->with('teacher')
            ->get();
            
        $pastMeetings = Meeting::forStudent($student->id)
            ->approved()
            ->where('scheduled_at', '<', now())
            ->with('teacher')
            ->orderBy('scheduled_at', 'desc')
            ->limit(10)
            ->get();
            
        $rejectedRequests = Meeting::forStudent($student->id)
            ->where('request_status', 'rejected')
            ->with('teacher')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get all teachers for the request form
        $teachers = User::where('role', 'teacher')->get();
        
        return view('student.meetings', compact(
            'upcomingMeetings',
            'pendingRequests',
            'pastMeetings',
            'rejectedRequests',
            'teachers'
        ));
    }

    /**
     * Request a meeting with a teacher.
     */
    public function requestMeeting(Request $request)
    {
        $request->validate([
            'teacher_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:180'],
        ]);
        
        // Verify the teacher_id is actually a teacher
        $teacher = User::findOrFail($request->teacher_id);
        if ($teacher->role !== 'teacher' && $teacher->role !== 'admin') {
            return back()->withErrors(['teacher_id' => 'Selected user must be a teacher.']);
        }
        
        // Create meeting request
        $meeting = Meeting::create([
            'student_id' => Auth::id(),
            'teacher_id' => $request->teacher_id,
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'status' => 'scheduled',
            'request_status' => 'pending',
        ]);
        
        // Create notification for teacher
        Notification::create([
            'user_id' => $request->teacher_id,
            'sender_id' => Auth::id(),
            'type' => 'meeting',
            'title' => 'New Meeting Request',
            'message' => Auth::user()->name . ' has requested a meeting: ' . $request->title,
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Meeting request sent successfully! Waiting for teacher approval.');
    }

    /**
     * Cancel a meeting.
     */
    public function cancelMeeting(Meeting $meeting)
    {
        // Ensure the meeting belongs to the authenticated student
        if ($meeting->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Notify teacher if meeting was approved
        if ($meeting->request_status === 'approved' && $meeting->teacher_id) {
            Notification::create([
                'user_id' => $meeting->teacher_id,
                'sender_id' => Auth::id(),
                'type' => 'meeting',
                'title' => 'Meeting Cancelled',
                'message' => Auth::user()->name . ' has cancelled the meeting: ' . $meeting->title,
                'is_read' => false,
            ]);
        }
        
        // Delete the meeting completely
        $meeting->delete();
        
        return back()->with('success', 'Meeting request cancelled and removed.');
    }

    /**
     * Delete a rejected meeting request.
     */
    public function deleteMeeting(Meeting $meeting)
    {
        // Ensure the meeting belongs to the authenticated student
        if ($meeting->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow deletion of rejected meetings
        if ($meeting->request_status !== 'rejected') {
            return back()->withErrors(['error' => 'Only rejected meeting requests can be deleted.']);
        }
        
        $meeting->delete();
        
        return back()->with('success', 'Rejected meeting request deleted.');
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
