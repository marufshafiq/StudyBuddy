@extends('layouts.admin')

@section('title', 'All Resources')
@section('subtitle', 'Books saved by students across the platform')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Resources</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <span class="text-3xl">📚</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Favorites</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['favorites'] }}</p>
            </div>
            <span class="text-3xl">⭐</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Books</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['by_type']['book'] ?? 0 }}</p>
            </div>
            <span class="text-3xl">📖</span>
        </div>
    </div>
</div>

<!-- Resources Grid -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Saved Resources</h3>
        <p class="text-sm text-gray-500 mt-1">All books saved by students</p>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($resources as $resource)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                @if($resource->cover_url)
                    <img src="{{ $resource->cover_url }}" alt="{{ $resource->title }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                        <span class="text-white text-5xl">📚</span>
                    </div>
                @endif
                
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-gray-800 text-sm line-clamp-2 flex-1">{{ $resource->title }}</h4>
                        @if($resource->is_favorite)
                            <span class="text-yellow-500 text-lg ml-2">⭐</span>
                        @endif
                    </div>
                    
                    @if($resource->authors)
                        <p class="text-xs text-gray-600 mb-2">
                            {{ is_array($resource->authors) ? implode(', ', $resource->authors) : $resource->authors }}
                        </p>
                    @endif
                    
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($resource->user->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $resource->user->name }}</span>
                        </div>
                    </div>
                    
                    @if($resource->read_url)
                        <a href="{{ $resource->read_url }}" target="_blank" 
                           class="block mt-3 bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-lg text-xs font-semibold transition">
                            📖 Read Online
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <span class="text-4xl block mb-2">📚</span>
                <p>No resources found</p>
            </div>
            @endforelse
        </div>
    </div>

    @if($resources->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $resources->links() }}
    </div>
    @endif
</div>
@endsection
