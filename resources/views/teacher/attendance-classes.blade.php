@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Attendance</h1>
                <p class="text-gray-400 mt-1">Select a class to mark attendance</p>
            </div>
            <a href="{{ route('teacher.dashboard') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Back to Dashboard
            </a>
        </div>

        @if($classes->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Classes Assigned</h3>
                <p class="text-gray-400">You haven't been assigned to any classes yet.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classes as $class)
                    @php
                        $today = now()->format('Y-m-d');
                        $attendanceMarked = \App\Models\Attendance::where('class_id', $class->id)
                            ->where('date', $today)
                            ->exists();
                        $studentCount = $class->students()->count();
                    @endphp

                    <div class="bg-gray-800 rounded-lg shadow-xl p-6 hover:shadow-2xl transition">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-white">{{ $class->name }}</h3>
                                <p class="text-sm text-gray-400">{{ $class->school->name }}</p>
                            </div>
                            @if($attendanceMarked)
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                    ✓ Marked
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">
                                    Pending
                                </span>
                            @endif
                        </div>

                        <div class="mb-4 space-y-2">
                            <div class="flex items-center text-gray-300">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span class="text-sm">{{ $studentCount }} {{ Str::plural('student', $studentCount) }}</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm">Today: {{ now()->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('teacher.attendance.sheet', $class->id) }}"
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-center font-medium transition">
                                {{ $attendanceMarked ? 'Update Attendance' : 'Mark Attendance' }}
                            </a>
                            <a href="{{ route('teacher.attendance.history', $class->id) }}"
                                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition"
                                title="View History">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection