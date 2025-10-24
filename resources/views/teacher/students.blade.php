@extends('layouts.teacher')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">👥 All Students</h1>
            <p class="mt-2 text-gray-600">View and manage all students in the system</p>
        </div>

        <!-- Students Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($students as $student)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-6">
                        <!-- Student Avatar -->
                        <div class="flex items-center mb-4">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $student->email }}</p>
                            </div>
                        </div>

                        <!-- Student Stats -->
                        <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ $student->total_tasks ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Total Tasks</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $student->completed_tasks ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Completed</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $student->pending_tasks ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Pending</div>
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                <span class="font-medium">Member since:</span> {{ $student->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="font-medium">Last active:</span> {{ $student->updated_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('teacher.assignments') }}?student={{ $student->id }}" 
                               class="flex-1 bg-purple-600 text-white text-center py-2 px-4 rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                                Assign Task
                            </a>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-700">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No students found</h3>
                    <p class="text-gray-500">There are no students registered in the system yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Summary Stats -->
        <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6 border border-purple-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-purple-600">{{ $students->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Students</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600">{{ $students->sum('completed_tasks') }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Completed Tasks</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $students->sum('pending_tasks') }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Pending Tasks</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ $students->sum('total_tasks') }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Tasks Assigned</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
