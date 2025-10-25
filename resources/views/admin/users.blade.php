@extends('layouts.admin')

@section('title', 'All Users')
@section('subtitle', 'Manage and view all system users')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">User List</h3>
                <p class="text-sm text-gray-500 mt-1">Total: {{ $users->total() }} users</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tasks</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notifications</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($user->role === 'admin') bg-red-100 text-red-700
                            @elseif($user->role === 'teacher') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="font-semibold">{{ $user->assigned_tasks_count }}</span> tasks
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="font-semibold">{{ $user->notifications_count }}</span> notifications
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.details', $user) }}" 
                           class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                            View Details →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">👥</span>
                        <p>No users found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
