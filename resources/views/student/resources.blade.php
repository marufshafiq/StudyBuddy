@extends('layouts.student')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">📚 Learning Resources</h1>
        <p class="text-gray-600">Search and save educational books for your studies</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">🔍 Search Books</h2>
        
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex-1">
                <input type="text" id="searchQuery" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Search by title, author, or ISBN...">
            </div>
            <button onclick="searchBooks()" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                Search
            </button>
        </div>

        <!-- Popular Subjects -->
        <div class="mt-4">
            <p class="text-sm text-gray-600 mb-2">Or browse by subject:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($subjects as $key => $label)
                    <button onclick="searchBySubject('{{ $key }}')" 
                            class="px-3 py-1 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 rounded-full text-sm transition">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div id="searchResults" class="hidden mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Search Results</h2>
            <button onclick="closeSearchResults()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="booksGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Books will be loaded here dynamically -->
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600">Searching for books...</p>
    </div>

    <!-- Saved Books -->
    @if($savedResources->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">📖 My Library ({{ $savedResources->count() }} books)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($savedResources as $resource)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-64 bg-gray-200 flex items-center justify-center">
                        @if($resource->cover_url)
                            <img src="{{ $resource->cover_url }}" alt="{{ $resource->title }}" 
                                 class="h-full w-full object-cover">
                        @else
                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $resource->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $resource->authors_string }}</p>
                        @if($resource->first_publish_year)
                            <p class="text-xs text-gray-500 mb-2">Published: {{ $resource->first_publish_year }}</p>
                        @endif
                        
                        <!-- Read Online Button -->
                        @if($resource->read_url)
                            <a href="{{ $resource->read_url }}" target="_blank" 
                               class="w-full block text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold mb-2">
                                📖 Read Online
                            </a>
                        @endif
                        
                        <div class="flex items-center gap-2 mt-3">
                            <button onclick="toggleFavorite({{ $resource->id }}, this)" 
                                    class="flex-1 px-3 py-2 rounded-lg text-sm transition {{ $resource->is_favorite ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700' }}">
                                <span class="favorite-icon">{{ $resource->is_favorite ? '⭐' : '☆' }}</span>
                                <span class="favorite-text">{{ $resource->is_favorite ? 'Favorite' : 'Add to Favorites' }}</span>
                            </button>
                            <form action="{{ route('student.resources.remove', $resource) }}" method="POST" 
                                  onsubmit="return confirm('Remove this book from your library?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-sm transition">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-lg p-12 text-center mb-8">
        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="text-gray-500">Your library is empty</p>
        <p class="text-sm text-gray-400 mt-1">Search for books above to start building your collection</p>
    </div>
    @endif

    <!-- Favorites -->
    @if($favoriteResources->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">⭐ Favorites</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($favoriteResources as $resource)
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition border border-yellow-200">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($resource->cover_url)
                            <img src="{{ $resource->cover_url }}" alt="{{ $resource->title }}" 
                                 class="h-full w-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $resource->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $resource->authors_string }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
let currentBooks = [];

function searchBooks() {
    const query = document.getElementById('searchQuery').value.trim();
    if (!query) {
        alert('Please enter a search term');
        return;
    }
    
    performSearch(query, 'search');
}

function searchBySubject(subject) {
    performSearch(subject, 'subject');
    document.getElementById('searchQuery').value = subject.replace('_', ' ');
}

function performSearch(query, type = 'search') {
    document.getElementById('loadingIndicator').classList.remove('hidden');
    document.getElementById('searchResults').classList.add('hidden');
    
    fetch(`{{ route('student.resources.search') }}?query=${encodeURIComponent(query)}&type=${type}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingIndicator').classList.add('hidden');
        if (data.success && data.books.length > 0) {
            currentBooks = data.books;
            displayBooks(data.books);
            document.getElementById('searchResults').classList.remove('hidden');
        } else {
            alert('No books found. Try a different search term.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loadingIndicator').classList.add('hidden');
        alert('An error occurred while searching. Please try again.');
    });
}

function displayBooks(books) {
    const grid = document.getElementById('booksGrid');
    grid.innerHTML = books.map(book => `
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="h-64 bg-gray-200 flex items-center justify-center">
                ${book.cover_url 
                    ? `<img src="${book.cover_url}" alt="${book.title}" class="h-full w-full object-cover">` 
                    : `<svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                      </svg>`
                }
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">${book.title}</h3>
                <p class="text-sm text-gray-600 mb-2">${book.authors.join(', ') || 'Unknown Author'}</p>
                ${book.first_publish_year ? `<p class="text-xs text-gray-500 mb-2">Published: ${book.first_publish_year}</p>` : ''}
                ${book.subjects.length > 0 ? `<p class="text-xs text-gray-500 mb-3">${book.subjects.slice(0, 3).join(', ')}</p>` : ''}
                ${book.read_url ? `
                    <a href="${book.read_url}" target="_blank" 
                       class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold mb-2">
                        📖 Read Online
                    </a>
                ` : ''}
                <button onclick="saveBook(${JSON.stringify(book).replace(/"/g, '&quot;')})" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                    📚 Save to Library
                </button>
            </div>
        </div>
    `).join('');
}

function saveBook(book) {
    fetch('{{ route('student.resources.save') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
            title: book.title,
            authors: book.authors,
            openlibrary_key: book.key,
            cover_url: book.cover_url,
            isbn: book.isbn,
            first_publish_year: book.first_publish_year,
            subjects: book.subjects,
            read_url: book.read_url,
            has_fulltext: book.has_fulltext,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to save book');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function closeSearchResults() {
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('searchQuery').value = '';
}

function toggleFavorite(resourceId, button) {
    fetch(`/student/resources/${resourceId}/favorite`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('.favorite-icon');
            const text = button.querySelector('.favorite-text');
            
            if (data.is_favorite) {
                button.classList.remove('bg-gray-100', 'text-gray-700');
                button.classList.add('bg-yellow-100', 'text-yellow-700');
                icon.textContent = '⭐';
                text.textContent = 'Favorite';
            } else {
                button.classList.remove('bg-yellow-100', 'text-yellow-700');
                button.classList.add('bg-gray-100', 'text-gray-700');
                icon.textContent = '☆';
                text.textContent = 'Add to Favorites';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
