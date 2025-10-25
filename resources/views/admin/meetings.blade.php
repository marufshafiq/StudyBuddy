@extends('layouts.admin')

@section('title', 'All Meetings')
@section('subtitle', 'View all meeting requests and schedules')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs text-gray-500 font-medium">Total</p>
        <p class="text-xl font-bold text-gray-800 mt-1">{{ $statusStats['total'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs text-gray-500 font-medium">Pending</p>
        <p class="text-xl font-bold text-yellow-600 mt-1">{{ $statusStats['pending'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs text-gray-500 font-medium">Approved</p>
        <p class="text-xl font-bold text-green-600 mt-1">{{ $statusStats['approved'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs text-gray-500 font-medium">Rejected</p>
        <p class="text-xl font-bold text-red-600 mt-1">{{ $statusStats['rejected'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs text-gray-500 font-medium">Upcoming</p>
        <p class="text-xl font-bold text-blue-600 mt-1">{{ $statusStats['upcoming'] }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <p class="text-xs text-gray-500 font-medium">Past</p>
        <p class="text-xl font-bold text-gray-600 mt-1">{{ $statusStats['past'] }}</p>
    </div>
</div>

<!-- Meetings Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">All Meetings</h3>
        <p class="text-sm text-gray-500 mt-1">Complete meeting history and details</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Meeting</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheduled</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Request Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Meeting Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Zoom Link</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($meetings as $meeting)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $meeting->title }}</p>
                            @if($meeting->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($meeting->description, 40) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <span>👨‍🏫</span>
                            <span class="text-sm text-gray-700">{{ $meeting->teacher->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <span>👨‍🎓</span>
                            <span class="text-sm text-gray-700">{{ $meeting->student->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $meeting->scheduled_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($meeting->request_status === 'approved') bg-green-100 text-green-700
                            @elseif($meeting->request_status === 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ ucfirst($meeting->request_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($meeting->status === 'completed') bg-blue-100 text-blue-700
                            @elseif($meeting->status === 'cancelled') bg-gray-100 text-gray-700
                            @else bg-purple-100 text-purple-700
                            @endif">
                            {{ ucfirst($meeting->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($meeting->zoom_meeting_link)
                            <a href="{{ $meeting->zoom_meeting_link }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                🔗 View Link
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">📅</span>
                        <p>No meetings found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($meetings->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $meetings->links() }}
    </div>
    @endif
</div>
@endsection
