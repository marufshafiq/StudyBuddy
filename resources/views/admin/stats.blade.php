@extends('layouts.admin')

@section('title', 'System Statistics')
@section('subtitle', 'Comprehensive analytics and growth metrics')

@section('content')
<!-- Users Statistics -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">👥 User Statistics</h3>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Total Users</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['users']['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Admins</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['users']['admins'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Teachers</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['users']['teachers'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Students</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['users']['students'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">New (7 days)</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['users']['recent'] }}</p>
        </div>
    </div>
</div>

<!-- Tasks Statistics -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">📝 Task Statistics</h3>
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Total Tasks</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['tasks']['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Pending</p>
            <p class="text-2xl font-bold text-gray-600 mt-1">{{ $stats['tasks']['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">In Progress</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['tasks']['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Submitted</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['tasks']['submitted'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Completed</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['tasks']['completed'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Overdue</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['tasks']['overdue'] }}</p>
        </div>
    </div>
</div>

<!-- Meetings Statistics -->
<div class="mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">📅 Meeting Statistics</h3>
    <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Total</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['meetings']['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['meetings']['pending_requests'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Approved</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['meetings']['approved'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Rejected</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['meetings']['rejected'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Upcoming</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['meetings']['upcoming'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Completed</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['meetings']['completed'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Cancelled</p>
            <p class="text-2xl font-bold text-orange-600 mt-1">{{ $stats['meetings']['cancelled'] }}</p>
        </div>
    </div>
</div>

<!-- Resources & Notifications Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
    <!-- Resources -->
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-4">📚 Resource Statistics</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Total Resources</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['resources']['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Books</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['resources']['books'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Favorites</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['resources']['favorites'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">With Read URL</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['resources']['with_read_url'] }}</p>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-4">🔔 Notification Statistics</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Total Sent</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['notifications']['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Unread</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['notifications']['unread'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 col-span-2">
                <p class="text-xs text-gray-500 font-medium">Read</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['notifications']['read'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Growth Chart -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">📈 7-Day Growth Trend</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Users Growth -->
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">👥 New Users</h4>
            <div class="space-y-2">
                @forelse($growth['users'] as $day)
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('M d') }}</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($day->count / ($growth['users']->max('count') ?: 1)) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 w-8">{{ $day->count }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Tasks Growth -->
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">📝 New Tasks</h4>
            <div class="space-y-2">
                @forelse($growth['tasks'] as $day)
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('M d') }}</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($day->count / ($growth['tasks']->max('count') ?: 1)) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 w-8">{{ $day->count }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Meetings Growth -->
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">📅 New Meetings</h4>
            <div class="space-y-2">
                @forelse($growth['meetings'] as $day)
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('M d') }}</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($day->count / ($growth['meetings']->max('count') ?: 1)) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 w-8">{{ $day->count }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">No data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
