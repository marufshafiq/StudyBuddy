<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudyBuddy - Connect Teachers & Students</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN for Development -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-red-50 via-pink-50 to-orange-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-red-600">StudyBuddy</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 px-4 py-2 rounded-md transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Register</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-6">
                    Welcome to <span class="text-red-600">StudyBuddy</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    A powerful platform connecting teachers and students for seamless learning management and collaboration.
                </p>
                
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="bg-red-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-red-700 transition shadow-lg">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition border-2 border-red-600">
                        Sign In
                    </a>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Admin Feature -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Admin Dashboard</h3>
                    <p class="text-gray-600">Comprehensive system management with user statistics, role management, and system monitoring.</p>
                </div>

                <!-- Teacher Feature -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Teacher Portal</h3>
                    <p class="text-gray-600">Manage students, create assignments, track progress, and schedule meetings effortlessly.</p>
                </div>

                <!-- Student Feature -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Student Hub</h3>
                    <p class="text-gray-600">Access assignments, track your progress, view deadlines, and connect with teachers.</p>
                </div>
            </div>

            <!-- Role Information -->
            <div class="mt-20 bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Three User Roles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-red-600">A</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Admin</h3>
                        <p class="text-gray-600 text-sm">Full system access and user management</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-orange-600">T</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Teacher</h3>
                        <p class="text-gray-600 text-sm">Manage students and create tasks</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-pink-600">S</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Student</h3>
                        <p class="text-gray-600 text-sm">Access learning materials and tasks</p>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-20 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
                <p class="text-gray-600 mb-8">Join StudyBuddy today and experience seamless education management</p>
                <a href="{{ route('register') }}" class="bg-red-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-red-700 transition shadow-lg inline-block">
                    Create Free Account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-gray-600">
                    <p>&copy; {{ date('Y') }} StudyBuddy. Built with Laravel & TailwindCSS.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>