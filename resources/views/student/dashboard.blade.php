@extends('layouts.student')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Section with Student Info -->
        <div class="mb-6">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ $studentInfo['name'] }}! 👋</h1>
                        <p class="text-indigo-100">{{ $studentInfo['role'] }} • Last login: {{ $studentInfo['last_login']->diffForHumans() }}</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-center bg-white/20 backdrop-blur rounded-lg px-4 py-2">
                            <div class="text-2xl font-bold">{{ $completionPercentage }}%</div>
                            <div class="text-xs text-indigo-100">Progress</div>
                        </div>
                        <div class="text-center bg-white/20 backdrop-blur rounded-lg px-4 py-2">
                            <div class="text-2xl font-bold">{{ $todayMeetings }}</div>
                            <div class="text-xs text-indigo-100">Today's Meetings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Total Tasks -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $totalTasks }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $completedTasks }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $pendingTasks }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $overdueTasks }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-medium text-gray-900">Task Completion Progress</h3>
                <span class="text-sm font-medium text-indigo-600">{{ $completionPercentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $completionPercentage }}%">
                </div>
            </div>
            <div class="mt-2 flex justify-between text-xs text-gray-500">
                <span>{{ $completedTasks }} of {{ $totalTasks }} tasks completed</span>
                <span>{{ $pendingTasks }} remaining</span>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Tasks -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">📝 My Tasks</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tasks as $task)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-start">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                                    @if($task->description)
                                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($task->description, 50) }}</div>
                                                    @endif
                                                    @if($task->teacher)
                                                        <div class="text-xs text-gray-400 mt-1">By: {{ $task->teacher->name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($task->deadline)
                                                <div class="text-sm {{ $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                                    {{ $task->deadline->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $task->deadline->diffForHumans() }}</div>
                                            @else
                                                <span class="text-sm text-gray-400">No deadline</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($task->priority === 'high') bg-red-100 text-red-800
                                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($task->status === 'completed') bg-green-100 text-green-800
                                                @elseif($task->status === 'submitted') bg-blue-100 text-blue-800
                                                @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($task->status !== 'completed')
                                                <form method="POST" action="{{ route('student.task.complete', $task) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        Mark Done
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-green-600">✓ Done</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p>No tasks assigned yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Meetings & Notifications -->
            <div class="space-y-6">
                
                <!-- Upcoming Meetings -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">📅 Upcoming Meetings</h2>
                    </div>
                    <div class="p-6">
                        @forelse($upcomingMeetings as $meeting)
                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold text-sm">
                                                {{ $meeting->scheduled_at->format('d') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $meeting->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $meeting->scheduled_at->format('M d, Y • h:i A') }}
                                        </p>
                                        @if($meeting->teacher)
                                            <p class="text-xs text-gray-500 mt-1">With: {{ $meeting->teacher->name }}</p>
                                        @endif
                                        @if($meeting->meeting_link)
                                            <a href="{{ $meeting->meeting_link }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-800 mt-1 inline-block">
                                                Join Meeting →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No upcoming meetings</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Notifications -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">🔔 Notifications</h2>
                        @if($unreadNotificationsCount > 0)
                            <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">
                                {{ $unreadNotificationsCount }}
                            </span>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($notifications as $notification)
                            <div class="p-4 hover:bg-gray-50 transition {{ !$notification->is_read ? 'bg-indigo-50' : '' }}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 text-2xl">
                                        {{ $notification->type_icon }}
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($notification->message, 80) }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                            @if(!$notification->is_read)
                                                <form method="POST" action="{{ route('student.notification.read', $notification) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800">
                                                        Mark as read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No notifications</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
