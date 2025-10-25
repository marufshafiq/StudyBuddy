@extends('layouts.admin')

@section('title', 'User Details')
@section('subtitle', 'Complete user profile and activity')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users') }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm">
        ← Back to Users
    </a>
</div>

<!-- User Profile Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
    <div class="p-8">
        <div class="flex items-start space-x-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h2 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                        @if($user->role === 'admin') bg-red-100 text-red-700
                        @elseif($user->role === 'teacher') bg-blue-100 text-blue-700
                        @else bg-green-100 text-green-700
                        @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <p class="text-gray-600 mb-4">{{ $user->email }}</p>
                <div class="flex items-center space-x-6 text-sm text-gray-500">
                    <span>📅 Joined: {{ $user->created_at->format('M d, Y') }}</span>
                    <span>⏰ Last Updated: {{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Tasks</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $user->assignedTasks->count() }}</p>
            </div>
            <span class="text-3xl">📝</span>
        </div>
    </div>

    @if($user->role === 'teacher')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Created Tasks</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $user->createdTasks->count() }}</p>
            </div>
            <span class="text-3xl">✏️</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Meetings</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $user->teacherMeetings->count() }}</p>
            </div>
            <span class="text-3xl">📅</span>
        </div>
    </div>
    @endif

    @if($user->role === 'student')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Meetings</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $user->studentMeetings->count() }}</p>
            </div>
            <span class="text-3xl">📅</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Saved Books</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $user->savedResources->count() }}</p>
            </div>
            <span class="text-3xl">📚</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Notifications</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">{{ $user->notifications->count() }}</p>
            </div>
            <span class="text-3xl">🔔</span>
        </div>
    </div>
</div>

<!-- Recent Tasks -->
@if($user->assignedTasks->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">📝 Recent Tasks</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Task</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                        @if($user->role === 'teacher') Assigned To @else Assigned By @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($user->assignedTasks->take(10) as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $task->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($user->role === 'teacher')
                            {{ $task->student->name }}
                        @else
                            {{ $task->teacher->name }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $task->deadline ? $task->deadline->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($task->status === 'completed') bg-green-100 text-green-700
                            @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $task->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Recent Notifications -->
@if($user->notifications->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">🔔 Recent Notifications</h3>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($user->notifications->take(10) as $notification)
        <div class="p-4 hover:bg-gray-50 transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($notification->type === 'task') bg-green-100 text-green-700
                            @elseif($notification->type === 'meeting') bg-purple-100 text-purple-700
                            @else bg-blue-100 text-blue-700
                            @endif">
                            {{ ucfirst($notification->type) }}
                        </span>
                        @if($notification->is_read)
                            <span class="text-xs text-gray-400">Read</span>
                        @else
                            <span class="text-xs text-yellow-600 font-semibold">● Unread</span>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-800">{{ $notification->title }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                </div>
                <span class="text-xs text-gray-400 ml-4">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
