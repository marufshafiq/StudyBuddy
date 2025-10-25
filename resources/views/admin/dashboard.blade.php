@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('subtitle', 'Complete system statistics and recent activities')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Users Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Users</p>
                <h3 class="text-3xl font-bold mt-1">{{ $totalUsers }}</h3>
            </div>
            <span class="text-4xl">👥</span>
        </div>
        <div class="flex justify-between text-xs text-blue-100">
            <span>👨‍🏫 {{ $totalTeachers }} Teachers</span>
            <span>👨‍🎓 {{ $totalStudents }} Students</span>
        </div>
    </div>

    <!-- Tasks Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Tasks</p>
                <h3 class="text-3xl font-bold mt-1">{{ $totalTasks }}</h3>
            </div>
            <span class="text-4xl">📝</span>
        </div>
        <div class="flex justify-between text-xs text-green-100">
            <span>⏳ {{ $pendingTasks }} Pending</span>
            <span>✅ {{ $completedTasks }} Completed</span>
        </div>
    </div>

    <!-- Meetings Card -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Meetings</p>
                <h3 class="text-3xl font-bold mt-1">{{ $totalMeetings }}</h3>
            </div>
            <span class="text-4xl">📅</span>
        </div>
        <div class="flex justify-between text-xs text-purple-100">
            <span>⏰ {{ $upcomingMeetings }} Upcoming</span>
            <span>⏳ {{ $pendingMeetingRequests }} Pending</span>
        </div>
    </div>

    <!-- Notifications Card -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-orange-100 text-sm font-medium">Notifications</p>
                <h3 class="text-3xl font-bold mt-1">{{ $totalNotifications }}</h3>
            </div>
            <span class="text-4xl">🔔</span>
        </div>
        <div class="flex justify-between text-xs text-orange-100">
            <span>📬 {{ $unreadNotifications }} Unread</span>
            <span>📚 {{ $totalResources }} Resources</span>
        </div>
    </div>
</div>

<!-- Alert Cards -->
@if($pendingMeetingRequests > 0 || $rejectedMeetings > 0)
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    @if($pendingMeetingRequests > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-center">
            <span class="text-yellow-500 text-2xl mr-3">⏳</span>
            <div class="flex-1">
                <h4 class="font-semibold text-yellow-800">Pending Meeting Requests</h4>
                <p class="text-sm text-yellow-700 mt-1">{{ $pendingMeetingRequests }} meeting(s) awaiting approval</p>
            </div>
            <a href="{{ route('admin.meetings.pending') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                View
            </a>
        </div>
    </div>
    @endif

    @if($rejectedMeetings > 0)
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <div class="flex items-center">
            <span class="text-red-500 text-2xl mr-3">❌</span>
            <div class="flex-1">
                <h4 class="font-semibold text-red-800">Rejected Meetings</h4>
                <p class="text-sm text-red-700 mt-1">{{ $rejectedMeetings }} meeting(s) have been rejected</p>
            </div>
            <a href="{{ route('admin.meetings.rejected') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                View
            </a>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">👥 Recent Users</h3>
                <a href="{{ route('admin.users') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All →</a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentUsers as $user)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($user->role === 'admin') bg-red-100 text-red-700
                            @elseif($user->role === 'teacher') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <p>No users found</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">📝 Recent Tasks</h3>
                <a href="{{ route('admin.tasks') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All →</a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentTasks as $task)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-gray-800">{{ $task->title }}</h4>
                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                        @if($task->status === 'completed') bg-green-100 text-green-700
                        @elseif($task->status === 'submitted') bg-blue-100 text-blue-700
                        @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center space-x-4">
                        <span>👨‍🏫 {{ $task->teacher->name }}</span>
                        <span>→</span>
                        <span>👨‍🎓 {{ $task->student->name }}</span>
                    </div>
                    <span>{{ $task->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <p>No tasks found</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Meetings -->
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">📅 Recent Meetings</h3>
            <a href="{{ route('admin.meetings') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All →</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheduled</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentMeetings as $meeting)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $meeting->title }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting->teacher->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting->student->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting->scheduled_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($meeting->request_status === 'approved') bg-green-100 text-green-700
                            @elseif($meeting->request_status === 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ ucfirst($meeting->request_status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                        No meetings found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
