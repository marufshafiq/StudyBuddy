@extends('layouts.admin')

@section('title', 'Pending Meeting Requests')
@section('subtitle', 'Meetings awaiting teacher approval')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <span class="text-3xl">⏳</span>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pending Meeting Requests</h3>
                <p class="text-sm text-gray-500 mt-1">Total: {{ $meetings->total() }} pending requests</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Meeting</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Requested By</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheduled For</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Requested On</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($meetings as $meeting)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $meeting->title }}</p>
                            @if($meeting->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($meeting->description, 60) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <span>👨‍🎓</span>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">{{ $meeting->student->name }}</p>
                                <p class="text-xs text-gray-500">{{ $meeting->student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <span>👨‍🏫</span>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">{{ $meeting->teacher->name }}</p>
                                <p class="text-xs text-gray-500">{{ $meeting->teacher->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="font-semibold">{{ $meeting->scheduled_at->format('M d, Y') }}</span><br>
                        <span class="text-xs text-gray-500">{{ $meeting->scheduled_at->format('H:i A') }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $meeting->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">✅</span>
                        <p class="font-medium">No pending meeting requests</p>
                        <p class="text-sm mt-1">All meeting requests have been processed</p>
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
