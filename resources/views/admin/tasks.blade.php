@extends('layouts.admin')

@section('title', 'All Tasks')
@section('subtitle', 'View task assignments and their status')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Tasks</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $tasks->total() }}</p>
            </div>
            <span class="text-3xl">📝</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pending</p>
                <p class="text-2xl font-bold text-gray-600 mt-1">{{ $statusStats['pending'] ?? 0 }}</p>
            </div>
            <span class="text-3xl">⏳</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">In Progress</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $statusStats['in_progress'] ?? 0 }}</p>
            </div>
            <span class="text-3xl">🔄</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Completed</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $statusStats['completed'] ?? 0 }}</p>
            </div>
            <span class="text-3xl">✅</span>
        </div>
    </div>
</div>

<!-- Tasks Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Task List</h3>
        <p class="text-sm text-gray-500 mt-1">Who assigned task to whom</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assigned By</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $task->title }}</p>
                            @if($task->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($task->description, 50) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <span>👨‍🏫</span>
                            <span class="text-sm text-gray-700 font-medium">{{ $task->teacher->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <span>👨‍🎓</span>
                            <span class="text-sm text-gray-700 font-medium">{{ $task->student->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $task->deadline ? $task->deadline->format('M d, Y H:i') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($task->status === 'completed') bg-green-100 text-green-700
                            @elseif($task->status === 'submitted') bg-blue-100 text-blue-700
                            @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $task->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">📝</span>
                        <p>No tasks found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tasks->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection
