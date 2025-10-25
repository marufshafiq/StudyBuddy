@extends('layouts.admin')

@section('title', 'All Notifications')
@section('subtitle', 'System-wide notification history')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Notifications</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <span class="text-3xl">🔔</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Unread</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['unread'] }}</p>
            </div>
            <span class="text-3xl">📬</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Types</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ count($stats['by_type']) }}</p>
            </div>
            <span class="text-3xl">📊</span>
        </div>
    </div>
</div>

<!-- Notifications Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Notification History</h3>
        <p class="text-sm text-gray-500 mt-1">All notifications sent across the system</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Recipient</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sender</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($notification->type === 'task') bg-green-100 text-green-700
                            @elseif($notification->type === 'meeting') bg-purple-100 text-purple-700
                            @elseif($notification->type === 'reminder') bg-yellow-100 text-yellow-700
                            @else bg-blue-100 text-blue-700
                            @endif">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $notification->title }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600">{{ Str::limit($notification->message, 50) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">
                            <p class="font-medium">{{ $notification->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $notification->user->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $notification->sender ? $notification->sender->name : 'System' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($notification->is_read)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                ✓ Read
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                ● Unread
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $notification->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">🔔</span>
                        <p>No notifications found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($notifications->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
