<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\User;
use App\Services\ZoomService;

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

    /**
     * Display meetings page with requests and scheduled meetings.
     */
    public function meetings()
    {
        $teacher = Auth::user();
        
        // Get pending meeting requests from students
        $pendingRequests = Meeting::forTeacher($teacher->id)
            ->pendingRequests()
            ->with('student')
            ->get();
            
        // Get approved upcoming meetings
        $upcomingMeetings = Meeting::forTeacher($teacher->id)
            ->approved()
            ->upcoming()
            ->with('student')
            ->get();
            
        // Get past meetings
        $pastMeetings = Meeting::forTeacher($teacher->id)
            ->approved()
            ->where('scheduled_at', '<', now())
            ->with('student')
            ->orderBy('scheduled_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get all students for creating meetings
        $students = User::where('role', 'student')->get();
        
        return view('teacher.meetings', compact(
            'pendingRequests',
            'upcomingMeetings',
            'pastMeetings',
            'students'
        ));
    }

    /**
     * Create a new meeting with a student.
     */
    public function createMeeting(Request $request, ZoomService $zoomService)
    {
        $teacher = Auth::user();
        
        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:180'],
        ]);
        
        // Verify the student_id is actually a student
        $student = User::findOrFail($request->student_id);
        if ($student->role !== 'student') {
            return back()->withErrors(['student_id' => 'Selected user must be a student.']);
        }
        
        // Create meeting
        $meeting = Meeting::create([
            'student_id' => $request->student_id,
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'duration_minutes' => $request->duration_minutes,
            'status' => 'scheduled',
            'request_status' => 'approved', // Teacher created, so auto-approved
        ]);
        
        // Create Zoom meeting link automatically
        $meetLink = $zoomService->createMeetingLink($meeting);
        
        // Create notification for student
        Notification::create([
            'user_id' => $request->student_id,
            'sender_id' => $teacher->id,
            'type' => 'meeting',
            'title' => 'Meeting Scheduled',
            'message' => $teacher->name . ' has scheduled a meeting with you: ' . $request->title,
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Meeting created successfully with Zoom link!');
    }

    /**
     * Approve a meeting request from a student.
     */
    public function approveMeeting(Meeting $meeting, ZoomService $zoomService)
    {
        $teacher = Auth::user();
        
        // Ensure the meeting is for this teacher
        if ($meeting->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Update meeting status
        $meeting->update([
            'request_status' => 'approved',
        ]);
        
        // Create Zoom meeting link automatically
        $meetLink = $zoomService->createMeetingLink($meeting);
        
        // Notify student
        Notification::create([
            'user_id' => $meeting->student_id,
            'sender_id' => $teacher->id,
            'type' => 'meeting',
            'title' => 'Meeting Request Approved',
            'message' => 'Your meeting request "' . $meeting->title . '" has been approved! Zoom link is ready.',
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Meeting request approved and Zoom link created!');
    }

    /**
     * Reject a meeting request from a student.
     */
    public function rejectMeeting(Request $request, Meeting $meeting)
    {
        $teacher = Auth::user();
        
        // Ensure the meeting is for this teacher
        if ($meeting->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);
        
        // Update meeting status
        $meeting->update([
            'request_status' => 'rejected',
            'status' => 'cancelled',
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        // Notify student
        Notification::create([
            'user_id' => $meeting->student_id,
            'sender_id' => $teacher->id,
            'type' => 'meeting',
            'title' => 'Meeting Request Declined',
            'message' => 'Your meeting request "' . $meeting->title . '" has been declined.' . ($request->rejection_reason ? ' Reason: ' . $request->rejection_reason : ''),
            'is_read' => false,
        ]);
        
        return back()->with('success', 'Meeting request rejected.');
    }

    /**
     * Cancel a meeting.
     */
    public function cancelMeeting(Meeting $meeting, ZoomService $zoomService)
    {
        $teacher = Auth::user();
        
        // Ensure the meeting belongs to this teacher
        if ($meeting->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete Zoom meeting via API
        $zoomService->cancelMeeting($meeting);
        
        // Notify student if meeting was approved
        if ($meeting->request_status === 'approved') {
            Notification::create([
                'user_id' => $meeting->student_id,
                'sender_id' => $teacher->id,
                'type' => 'meeting',
                'title' => 'Meeting Cancelled',
                'message' => $teacher->name . ' has cancelled the meeting: ' . $meeting->title,
                'is_read' => false,
            ]);
        }
        
        return back()->with('success', 'Meeting cancelled successfully.');
    }
}

