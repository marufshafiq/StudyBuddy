@extends('layouts.admin')

@section('title', 'All Teachers')
@section('subtitle', 'View all teachers and their statistics')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">👨‍🏫 Teacher List</h3>
                <p class="text-sm text-gray-500 mt-1">Total: {{ $teachers->total() }} teachers</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tasks Created</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Meetings</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Upcoming</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($teachers as $teacher)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <p class="font-semibold text-gray-800">{{ $teacher->name }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $teacher->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            📝 {{ $teacher->created_tasks_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                            📅 {{ $teacher->teacher_meetings_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            ⏰ {{ $teacher->upcoming_meetings_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $teacher->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.details', $teacher) }}" 
                           class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                            View Details →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">👨‍🏫</span>
                        <p>No teachers found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($teachers->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $teachers->links() }}
    </div>
    @endif
</div>
@endsection
