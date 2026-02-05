@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-xl p-6">
            <h1 class="text-3xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-blue-100 mt-1">Teacher Dashboard - Manage your classes and students</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Attendance -->
            <a href="{{ route('teacher.attendance') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-green-400 transition">Mark Attendance</h3>
                    <p class="text-sm text-gray-400 mt-1">Daily attendance tracking</p>
                </div>
            </a>

            <!-- Messages -->
            <a href="{{ route('teacher.messages') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                        </div>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="px-2 py-1 bg-red-600 text-white rounded-full text-xs font-semibold">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-purple-400 transition">Messages</h3>
                    <p class="text-sm text-gray-400 mt-1">Parent communications</p>
                </div>
            </a>

            <!-- Announcements -->
            <a href="{{ route('announcements.index') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-yellow-400 transition">Announcements</h3>
                    <p class="text-sm text-gray-400 mt-1">Create and view updates</p>
                </div>
            </a>

            <!-- My Classes -->
            <a href="{{ route('teacher.dashboard') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-semibold">
                            {{ $classSubjects->count() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">My Classes</h3>
                    <p class="text-sm text-gray-400 mt-1">View assigned classes</p>
                </div>
            </a>
        </div>

        <!-- Classes & Subjects -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">My Classes & Subjects</h2>
                <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm font-semibold">
                    {{ $classSubjects->count() }} {{ Str::plural('Class', $classSubjects->count()) }}
                </span>
            </div>

            @if($classSubjects->isEmpty())
                <div class="bg-yellow-500/10 border border-yellow-500 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-yellow-400 font-semibold">You haven't been assigned to any classes yet.</p>
                    <p class="text-gray-400 text-sm mt-2">Contact your school manager for class assignments</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classSubjects as $classSubject)
                        <div
                            class="bg-gray-750 hover:bg-gray-700 rounded-lg p-6 transition border border-gray-700 hover:border-blue-500">
                            <div class="mb-4">
                                <h3 class="text-xl font-semibold text-white mb-1">{{ $classSubject->subject->name }}</h3>
                                <p class="text-sm text-gray-400">{{ $classSubject->class->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $classSubject->class->school->name }}</p>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-500/20 text-purple-400">
                                    Coefficient: {{ $classSubject->coefficient }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $classSubject->class->students()->count() }} students
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('teacher.grade-form', $classSubject->id) }}"
                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium text-center transition">
                                    Add Grades
                                </a>
                                <a href="{{ route('teacher.attendance.sheet', $classSubject->class->id) }}"
                                    class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-medium text-center transition">
                                    Attendance
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection