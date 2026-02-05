@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-pink-600 to-pink-800 rounded-lg shadow-xl p-6">
            <h1 class="text-3xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-pink-100 mt-1">Parent Dashboard - Monitor your children's progress</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Children's Grades -->
            <a href="{{ route('parent.dashboard') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-semibold">
                            {{ count($childrenData) }} {{ Str::plural('Child', count($childrenData)) }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">Children's Grades</h3>
                    <p class="text-sm text-gray-400 mt-1">Academic performance</p>
                </div>
            </a>

            <!-- Attendance -->
            <a href="{{ route('parent.attendance') }}" class="block group">
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
                    <h3 class="text-lg font-semibold text-white group-hover:text-green-400 transition">Attendance</h3>
                    <p class="text-sm text-gray-400 mt-1">Track attendance</p>
                </div>
            </a>

            <!-- Messages -->
            <a href="{{ route('parent.messages') }}" class="block group">
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
                    <p class="text-sm text-gray-400 mt-1">Teacher communications</p>
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
                    <p class="text-sm text-gray-400 mt-1">School updates</p>
                </div>
            </a>
        </div>

        <!-- Children Overview -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <h2 class="text-2xl font-bold text-white mb-6">My Children</h2>

            @if(empty($childrenData))
                <div class="bg-yellow-500/10 border border-yellow-500 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <p class="text-yellow-400 font-semibold">No children linked to your account</p>
                    <p class="text-gray-400 text-sm mt-2">Contact your school manager to link your children</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($childrenData as $childData)
                        <div
                            class="bg-gray-750 hover:bg-gray-700 rounded-lg p-6 transition border border-gray-700 hover:border-pink-500">
                            <!-- Child Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-14 h-14 bg-pink-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                        {{ substr($childData['student']->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ $childData['student']->user->name }}</h3>
                                        <p class="text-sm text-gray-400">{{ $childData['student']->class->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $childData['student']->class->school->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Average</p>
                                    <p
                                        class="text-2xl font-bold {{ $childData['average'] >= 10 ? 'text-green-400' : 'text-red-400' }}">
                                        {{ number_format($childData['average'], 2) }}/20
                                    </p>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="bg-gray-800 rounded p-2 text-center">
                                    <p class="text-xs text-gray-400">Subjects</p>
                                    <p class="text-lg font-bold text-white">{{ count($childData['grades']) }}</p>
                                </div>
                                <div class="bg-gray-800 rounded p-2 text-center">
                                    <p class="text-xs text-gray-400">Grades</p>
                                    <p class="text-lg font-bold text-blue-400">
                                        {{ collect($childData['grades'])->filter(fn($g) => $g['breakdown']['final_grade'] > 0)->count() }}
                                    </p>
                                </div>
                                <div class="bg-gray-800 rounded p-2 text-center">
                                    <p class="text-xs text-gray-400">Status</p>
                                    <p
                                        class="text-sm font-bold {{ $childData['average'] >= 10 ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $childData['average'] >= 10 ? 'On Track' : 'Needs Help' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('parent.attendance.child', $childData['student']->id) }}"
                                    class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-medium text-center transition">
                                    View Attendance
                                </a>
                                <button onclick="toggleGrades({{ $childData['student']->id }})"
                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition">
                                    View Grades
                                </button>
                            </div>

                            <!-- Grades Detail (Collapsible) -->
                            <div id="grades-{{ $childData['student']->id }}" class="hidden mt-4 space-y-2">
                                @foreach($childData['grades'] as $gradeData)
                                    <div class="flex justify-between items-center bg-gray-800 rounded p-3">
                                        <span class="text-sm text-gray-300">{{ $gradeData['class_subject']->subject->name }}</span>
                                        <span
                                            class="px-2 py-1 rounded text-sm font-semibold
                                                        {{ $gradeData['breakdown']['final_grade'] >= 10 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                            {{ number_format($gradeData['breakdown']['final_grade'], 2) }}/20
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleGrades(studentId) {
            const element = document.getElementById('grades-' + studentId);
            element.classList.toggle('hidden');
        }
    </script>
@endsection