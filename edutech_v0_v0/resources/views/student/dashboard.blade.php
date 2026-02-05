@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-lg shadow-xl p-6">
            <h1 class="text-3xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-purple-100 mt-1">{{ $student->class->name }} - {{ $student->class->school->name }}</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- My Grades -->
            <a href="{{ route('student.dashboard') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        @php
                            $totalGrade = 0;
                            $count = 0;
                            foreach ($gradesData as $grade) {
                                if ($grade['breakdown']['final_grade']) {
                                    $totalGrade += $grade['breakdown']['final_grade'];
                                    $count++;
                                }
                            }
                            $average = $count > 0 ? round($totalGrade / $count, 2) : 0;
                        @endphp
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-semibold">
                            Avg: {{ $average }}/20
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">My Grades</h3>
                    <p class="text-sm text-gray-400 mt-1">{{ $count }} subjects</p>
                </div>
            </a>

            <!-- My Attendance -->
            <a href="{{ route('student.attendance') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-green-400 transition">My Attendance</h3>
                    <p class="text-sm text-gray-400 mt-1">View attendance records</p>
                </div>
            </a>

            <!-- Report Card -->
            <a href="{{ route('student.report-selector') }}" class="block group">
                <div class="bg-red-600 hover:bg-red-700 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-red-100 transition">Report Card</h3>
                    <p class="text-sm text-red-100 mt-1">Select Term & Download</p>
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

        <!-- Academic Performance -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Academic Performance</h2>
                @if($average > 0)
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Overall Average</p>
                        <p class="text-3xl font-bold {{ $average >= 10 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $average }}/20
                        </p>
                    </div>
                @endif
            </div>

            @php
                $classSubjects = $student->class->classSubjects()->with(['subject', 'teacher.user'])->get();
            @endphp

            @if(empty($gradesData))
                <div class="bg-blue-500/10 border border-blue-500 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <p class="text-blue-400 font-semibold">No grades yet</p>
                    <p class="text-gray-400 text-sm mt-2">Your grades will appear here once teachers add them</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-750">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Subject</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Teacher</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Controls Avg</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Exams Sum</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Final Grade</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Coefficient</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($gradesData as $data)
                                <tr class="hover:bg-gray-750 transition">
                                    <td class="px-6 py-4 text-white font-medium">{{ $data['class_subject']->subject->name }}</td>
                                    <td class="px-6 py-4 text-center text-gray-400 text-sm">
                                        {{ $data['class_subject']->teacher ? $data['class_subject']->teacher->user->name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-300">
                                        {{ $data['breakdown']['average_controls'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-300">
                                        {{ $data['breakdown']['sum_exams'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($data['breakdown']['final_grade'])
                                            <span
                                                class="px-3 py-1 rounded-full font-semibold
                                                                                                            {{ $data['breakdown']['final_grade'] >= 10 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                {{ number_format($data['breakdown']['final_grade'], 2) }}/20
                                            </span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-400">{{ $data['class_subject']->coefficient }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection