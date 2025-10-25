<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - StudyBuddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-purple-700 to-purple-900 text-white fixed h-full shadow-2xl flex flex-col">
            <div class="p-6 border-b border-purple-600">
                <h1 class="text-2xl font-bold">👑 Admin Panel</h1>
                <p class="text-purple-200 text-sm mt-1">StudyBuddy Control Center</p>
            </div>
            
            <nav class="mt-6 flex-1 overflow-y-auto pb-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">📊</span>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <div class="mt-6 px-6 text-xs font-semibold text-purple-300 uppercase tracking-wider">
                    User Management
                </div>
                
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users*') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">👥</span>
                    <span class="font-medium">All Users</span>
                </a>
                
                <a href="{{ route('admin.teachers') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.teachers') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">👨‍🏫</span>
                    <span class="font-medium">Teachers</span>
                </a>
                
                <a href="{{ route('admin.students') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.students') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">👨‍🎓</span>
                    <span class="font-medium">Students</span>
                </a>
                
                <div class="mt-6 px-6 text-xs font-semibold text-purple-300 uppercase tracking-wider">
                    System Data
                </div>
                
                <a href="{{ route('admin.tasks') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.tasks') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">📝</span>
                    <span class="font-medium">Tasks</span>
                </a>
                
                <a href="{{ route('admin.meetings') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.meetings') && !request()->routeIs('admin.meetings.pending') && !request()->routeIs('admin.meetings.rejected') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">📅</span>
                    <span class="font-medium">Meetings</span>
                </a>
                
                <a href="{{ route('admin.meetings.pending') }}" 
                   class="flex items-center px-6 py-3 pl-12 {{ request()->routeIs('admin.meetings.pending') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">⏳</span>
                    <span class="font-medium">Pending Requests</span>
                </a>
                
                <a href="{{ route('admin.meetings.rejected') }}" 
                   class="flex items-center px-6 py-3 pl-12 {{ request()->routeIs('admin.meetings.rejected') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">❌</span>
                    <span class="font-medium">Rejected</span>
                </a>
                
                <a href="{{ route('admin.notifications') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.notifications') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">🔔</span>
                    <span class="font-medium">Notifications</span>
                </a>
                
                <a href="{{ route('admin.resources') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.resources') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">📚</span>
                    <span class="font-medium">Resources</span>
                </a>
                
                <div class="mt-6 px-6 text-xs font-semibold text-purple-300 uppercase tracking-wider">
                    Analytics
                </div>
                
                <a href="{{ route('admin.stats') }}" 
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.stats') ? 'bg-purple-800 border-l-4 border-white' : 'hover:bg-purple-800' }} transition">
                    <span class="mr-3 text-xl">📈</span>
                    <span class="font-medium">Statistics</span>
                </a>
            </nav>
            
            <div class="p-6 border-t border-purple-600 bg-purple-900">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-purple-300">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 mt-1">@yield('subtitle', 'Welcome to the admin control panel')</p>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-green-500 text-xl mr-3">✅</span>
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-red-500 text-xl mr-3">❌</span>
                            <p class="text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
