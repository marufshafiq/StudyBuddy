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

        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📅 My Meetings</h1>
                <p class="mt-2 text-sm text-gray-600">Request and manage meetings with your teachers</p>
            </div>
            <button onclick="document.getElementById('requestMeetingModal').classList.remove('hidden')" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                + Request Meeting
            </button>
        </div>

        <!-- Pending Requests -->
        @if($pendingRequests->count() > 0)
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">⏳ Pending Requests</h2>
            <div class="grid grid-cols-1 gap-4">
                @foreach($pendingRequests as $meeting)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $meeting->title }}</h3>
                            @if($meeting->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $meeting->description }}</p>
                            @endif
                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                <span>📍 With: {{ $meeting->teacher->name ?? 'N/A' }}</span>
                                <span>🕐 {{ $meeting->scheduled_at->format('M d, Y • h:i A') }}</span>
                                <span>⏱️ {{ $meeting->duration_minutes }} min</span>
                            </div>
                            <p class="text-xs text-yellow-600 mt-2">Waiting for teacher approval...</p>
                        </div>
                        <form action="{{ route('student.meetings.cancel', $meeting) }}" method="POST" onsubmit="return confirm('Cancel this meeting request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Cancel & Remove
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Rejected Requests -->
        @if($rejectedRequests->count() > 0)
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">❌ Rejected Requests</h2>
            <div class="grid grid-cols-1 gap-4">
                @foreach($rejectedRequests as $meeting)
                <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $meeting->title }}</h3>
                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                <span>📍 With: {{ $meeting->teacher->name ?? 'N/A' }}</span>
                                <span>🕐 {{ $meeting->scheduled_at->format('M d, Y • h:i A') }}</span>
                            </div>
                            @if($meeting->rejection_reason)
                                <p class="text-sm text-red-700 mt-2 bg-red-100 p-2 rounded">
                                    <strong>Reason:</strong> {{ $meeting->rejection_reason }}
                                </p>
                            @endif
                        </div>
                        <form action="{{ route('student.meetings.delete', $meeting) }}" method="POST" onsubmit="return confirm('Delete this rejected request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Upcoming Meetings -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">✅ Upcoming Meetings</h2>
            @if($upcomingMeetings->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($upcomingMeetings as $meeting)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $meeting->title }}</h3>
                                    @if($meeting->isToday())
                                        <span class="ml-3 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            Today
                                        </span>
                                    @endif
                                </div>
                                @if($meeting->description)
                                    <p class="text-sm text-gray-600 mt-2">{{ $meeting->description }}</p>
                                @endif
                                <div class="mt-3 flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $meeting->teacher->name ?? 'N/A' }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $meeting->scheduled_at->format('M d, Y • h:i A') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $meeting->duration_minutes }} minutes
                                    </span>
                                </div>
                                @if($meeting->meeting_link)
                                    <!-- Zoom Meeting Link -->
                                    <div class="mt-4">
                                        <a href="{{ $meeting->meeting_link }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M15.5 2.25a.75.75 0 01.75-.75h5.5a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0V4.06l-6.22 6.22a.75.75 0 11-1.06-1.06L19.94 3h-3.69a.75.75 0 01-.75-.75z"/>
                                                <path d="M2.5 4.25c0-.966.784-1.75 1.75-1.75h8.5a.75.75 0 010 1.5h-8.5a.25.25 0 00-.25.25v15.5c0 .138.112.25.25.25h15.5a.25.25 0 00.25-.25v-8.5a.75.75 0 011.5 0v8.5a1.75 1.75 0 01-1.75 1.75H4.25a1.75 1.75 0 01-1.75-1.75V4.25z"/>
                                            </svg>
                                            Join Zoom Meeting
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <p class="text-sm text-yellow-800">
                                            ⏳ Waiting for teacher to create the Zoom meeting link.
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <form action="{{ route('student.meetings.cancel', $meeting) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel and remove this meeting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Cancel & Remove
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500">No upcoming meetings</p>
                    <p class="text-sm text-gray-400 mt-1">Request a meeting with your teacher to get started</p>
                </div>
            @endif
        </div>

        <!-- Past Meetings -->
        @if($pastMeetings->count() > 0)
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">📚 Past Meetings</h2>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pastMeetings as $meeting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $meeting->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting->teacher->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $meeting->scheduled_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($meeting->status === 'completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($meeting->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Request Meeting Modal -->
<div id="requestMeetingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-900">Request a Meeting</h3>
            <button onclick="document.getElementById('requestMeetingModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('student.meetings.request') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Teacher *</label>
                <select name="teacher_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Choose a teacher...</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Title *</label>
                <input type="text" name="title" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       placeholder="e.g., Discussion about Assignment 1">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                          placeholder="What would you like to discuss?"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Date & Time *</label>
                    <input type="datetime-local" name="scheduled_at" required 
                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('scheduled_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                    <select name="duration_minutes" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="90">1.5 hours</option>
                        <option value="120">2 hours</option>
                    </select>
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" 
                        onclick="document.getElementById('requestMeetingModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">
                    Send Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
