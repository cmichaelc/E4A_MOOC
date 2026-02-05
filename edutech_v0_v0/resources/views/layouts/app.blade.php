<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduTech Benin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-xl font-bold text-blue-400">
                                EduTech Benin
                            </a>
                        </div>
                        @auth
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.schools') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.*') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Schools
                                    </a>
                                @elseif(auth()->user()->isManager())
                                    <a href="{{ route('manager.dashboard') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('manager.dashboard') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('manager.link-parent') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('manager.link-parent') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Link Parent
                                    </a>
                                @elseif(auth()->user()->isTeacher())
                                    <a href="{{ route('teacher.dashboard') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('teacher.dashboard') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        My Classes
                                    </a>
                                    <a href="{{ route('teacher.attendance') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('teacher.attendance*') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Attendance
                                    </a>
                                    <a href="{{ route('teacher.messages') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('teacher.messages*') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Messages
                                    </a>
                                @elseif(auth()->user()->isStudent())
                                    <a href="{{ route('student.dashboard') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('student.dashboard') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        My Grades
                                    </a>
                                    <a href="{{ route('student.attendance') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('student.attendance') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        My Attendance
                                    </a>
                                @elseif(auth()->user()->isParent())
                                    <a href="{{ route('parent.dashboard') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('parent.dashboard') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Children's Grades
                                    </a>
                                    <a href="{{ route('parent.attendance') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('parent.attendance*') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Attendance
                                    </a>
                                    <a href="{{ route('parent.messages') }}"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('parent.messages*') ? 'border-blue-400 text-blue-400' : 'border-transparent text-gray-400 hover:text-gray-200' }} text-sm font-medium">
                                        Messages
                                    </a>
                                @endif
                            </div>
                        @endauth
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-500 text-white">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-400 hover:text-gray-200">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-200">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-500/10 border border-green-500 text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500 text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>