<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Resource;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with overview statistics
     */
    public function index()
    {
        // Get counts
        $totalUsers = User::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        
        $totalMeetings = Meeting::count();
        $upcomingMeetings = Meeting::where('scheduled_at', '>', now())->count();
        $pendingMeetingRequests = Meeting::where('request_status', 'pending')->count();
        $rejectedMeetings = Meeting::where('request_status', 'rejected')->count();
        
        $totalNotifications = Notification::count();
        $unreadNotifications = Notification::where('is_read', false)->count();
        
        $totalResources = Resource::count();
        
        // Recent activities
        $recentUsers = User::latest()->limit(5)->get();
        $recentTasks = Task::with(['teacher', 'student'])->latest()->limit(5)->get();
        $recentMeetings = Meeting::with(['teacher', 'student'])->latest()->limit(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalTeachers', 'totalStudents', 'totalAdmins',
            'totalTasks', 'pendingTasks', 'completedTasks',
            'totalMeetings', 'upcomingMeetings', 'pendingMeetingRequests', 'rejectedMeetings',
            'totalNotifications', 'unreadNotifications', 'totalResources',
            'recentUsers', 'recentTasks', 'recentMeetings'
        ));
    }

    /**
     * Display all users
     */
    public function users()
    {
        $users = User::withCount(['assignedTasks', 'notifications'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Display user details
     */
    public function userDetails(User $user)
    {
        $user->load(['assignedTasks', 'notifications', 'savedResources']);
        
        if ($user->role === 'teacher') {
            $user->load('createdTasks', 'teacherMeetings');
        } elseif ($user->role === 'student') {
            $user->load('studentMeetings');
        }
        
        return view('admin.user-details', compact('user'));
    }

    /**
     * Display all teachers with their statistics
     */
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')
            ->withCount([
                'createdTasks',
                'teacherMeetings',
                'teacherMeetings as upcoming_meetings_count' => function ($query) {
                    $query->where('scheduled_at', '>', now());
                },
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.teachers', compact('teachers'));
    }

    /**
     * Display all students with their statistics
     */
    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount([
                'assignedTasks',
                'assignedTasks as completed_tasks_count' => function ($query) {
                    $query->where('status', 'completed');
                },
                'studentMeetings',
                'savedResources',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.students', compact('students'));
    }

    /**
     * Display all tasks with details
     */
    public function tasks()
    {
        $tasks = Task::with(['teacher', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistics
        $statusStats = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        
        return view('admin.tasks', compact('tasks', 'statusStats'));
    }

    /**
     * Display all meetings
     */
    public function meetings()
    {
        $meetings = Meeting::with(['teacher', 'student'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(20);
        
        // Statistics
        $statusStats = [
            'total' => Meeting::count(),
            'pending' => Meeting::where('request_status', 'pending')->count(),
            'approved' => Meeting::where('request_status', 'approved')->count(),
            'rejected' => Meeting::where('request_status', 'rejected')->count(),
            'upcoming' => Meeting::where('scheduled_at', '>', now())->count(),
            'past' => Meeting::where('scheduled_at', '<=', now())->count(),
        ];
        
        return view('admin.meetings', compact('meetings', 'statusStats'));
    }

    /**
     * Display pending meeting requests
     */
    public function pendingMeetings()
    {
        $meetings = Meeting::with(['teacher', 'student'])
            ->where('request_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.pending-meetings', compact('meetings'));
    }

    /**
     * Display rejected meetings
     */
    public function rejectedMeetings()
    {
        $meetings = Meeting::with(['teacher', 'student'])
            ->where('request_status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        
        return view('admin.rejected-meetings', compact('meetings'));
    }

    /**
     * Display all notifications
     */
    public function notifications()
    {
        $notifications = Notification::with(['user', 'sender'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::where('is_read', false)->count(),
            'by_type' => Notification::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type'),
        ];
        
        return view('admin.notifications', compact('notifications', 'stats'));
    }

    /**
     * Display all saved resources
     */
    public function resources()
    {
        $resources = Resource::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $stats = [
            'total' => Resource::count(),
            'favorites' => Resource::where('is_favorite', true)->count(),
            'by_type' => Resource::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type'),
        ];
        
        return view('admin.resources', compact('resources', 'stats'));
    }

    /**
     * Display system statistics
     */
    public function stats()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'teachers' => User::where('role', 'teacher')->count(),
                'students' => User::where('role', 'student')->count(),
                'recent' => User::where('created_at', '>=', now()->subDays(7))->count(),
            ],
            'tasks' => [
                'total' => Task::count(),
                'pending' => Task::where('status', 'pending')->count(),
                'in_progress' => Task::where('status', 'in_progress')->count(),
                'submitted' => Task::where('status', 'submitted')->count(),
                'completed' => Task::where('status', 'completed')->count(),
                'overdue' => Task::where('deadline', '<', now())
                    ->whereIn('status', ['pending', 'in_progress'])->count(),
            ],
            'meetings' => [
                'total' => Meeting::count(),
                'pending_requests' => Meeting::where('request_status', 'pending')->count(),
                'approved' => Meeting::where('request_status', 'approved')->count(),
                'rejected' => Meeting::where('request_status', 'rejected')->count(),
                'upcoming' => Meeting::where('scheduled_at', '>', now())->count(),
                'completed' => Meeting::where('status', 'completed')->count(),
                'cancelled' => Meeting::where('status', 'cancelled')->count(),
            ],
            'resources' => [
                'total' => Resource::count(),
                'books' => Resource::where('type', 'book')->count(),
                'favorites' => Resource::where('is_favorite', true)->count(),
                'with_read_url' => Resource::whereNotNull('read_url')->count(),
            ],
            'notifications' => [
                'total' => Notification::count(),
                'unread' => Notification::where('is_read', false)->count(),
                'read' => Notification::where('is_read', true)->count(),
            ],
        ];
        
        // Growth data (last 7 days)
        $growth = [
            'users' => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'tasks' => Task::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'meetings' => Meeting::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
        
        return view('admin.stats', compact('stats', 'growth'));
    }
}
