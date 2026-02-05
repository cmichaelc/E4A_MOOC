@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-lg shadow-xl p-6">
            <h1 class="text-3xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-indigo-100 mt-1">School Manager - {{ $school->name }}</p>
        </div>

        <!-- School Status Alert -->
        @if($school->status === 'rejected')
            <div class="bg-red-500/10 border-l-4 border-red-500 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="text-red-400 font-semibold mb-1">School Registration Rejected</h3>
                        <p class="text-gray-300 text-sm mb-3">{{ $school->rejection_reason }}</p>
                        <a href="{{ route('manager.school.edit') }}"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition inline-block">
                            Edit & Resubmit
                        </a>
                    </div>
                </div>
            </div>
        @elseif($school->status === 'pending')
            <div class="bg-yellow-500/10 border-l-4 border-yellow-500 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-yellow-400 font-semibold mb-1">Registration Pending Approval</h3>
                        <p class="text-gray-300 text-sm">Your school registration is awaiting admin approval. You'll receive an
                            email once it's reviewed.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Manage Classes -->
            <a href="{{ route('manager.classes.index') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs font-semibold">
                            {{ $school->classes()->count() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-green-400 transition">Manage Classes</h3>
                    <p class="text-sm text-gray-400 mt-1">Create and organize</p>
                </div>
            </a>

            <!-- Manage Students -->
            <a href="{{ route('manager.students.index') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 rounded text-xs font-semibold">
                            {{ $totalStudents }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-cyan-400 transition">Manage Students</h3>
                    <p class="text-sm text-gray-400 mt-1">Add and edit students</p>
                </div>
            </a>

            <!-- Manage Teachers -->
            <a href="{{ route('manager.teachers') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-semibold">
                            {{ $school->teachers()->count() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition">Manage Teachers</h3>
                    <p class="text-sm text-gray-400 mt-1">Assign and manage</p>
                </div>
            </a>

            <!-- Link Parents -->
            <a href="{{ route('manager.parents.index') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-purple-400 transition">Link Parents</h3>
                    <p class="text-sm text-gray-400 mt-1">Connect families</p>
                </div>
            </a>

            <!-- Academic Terms -->
            <a href="{{ route('manager.terms.index') }}" class="block group">
                <div class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                @php
                    $termsCount = App\Models\AcademicTerm::where('school_id', $school->id)->count();
                @endphp
                        <span class="px-2 py-1 bg-indigo-500/20 text-indigo-400 rounded text-xs font-semibold">
                            {{ $termsCount }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-indigo-400 transition">Academic Terms</h3>
                    <p class="text-sm text-gray-400 mt-1">Manage semesters</p>
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

            <!-- School Info -->
            @if($school->status === 'rejected')
                <a href="{{ route('manager.school.edit') }}" class="block group">
                    <div
                        class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition transform hover:scale-105 border-2 border-red-600">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </div>
                            <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs font-semibold">
                                Action Required
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-white group-hover:text-red-400 transition">School Info</h3>
                        <p class="text-sm text-gray-400 mt-1">Update & resubmit</p>
                    </div>
                </a>
            @else
                <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs font-semibold capitalize">
                            {{ $school->status }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-white">School Status</h3>
                    <p class="text-sm text-gray-400 mt-1">{{ $school->name }}</p>
                </div>
            @endif
        </div>

        <!-- School Overview -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <h2 class="text-2xl font-bold text-white mb-6">School Overview</h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Classes -->
                <div class="bg-gray-750 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $school->classes()->count() }}</p>
                    <p class="text-sm text-gray-400 mt-1">Total Classes</p>
                </div>

                <!-- Students -->
                <div class="bg-gray-750 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    @php
                        $totalStudents = $school->classes->sum(function ($class) {
                            return $class->students()->count();
                        });
                    @endphp
                    <p class="text-3xl font-bold text-white">{{ $totalStudents }}</p>
                    <p class="text-sm text-gray-400 mt-1">Total Students</p>
                </div>

                <!-- Teachers -->
                <div class="bg-gray-750 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-white">{{ $school->teachers()->count() }}</p>
                    <p class="text-sm text-gray-400 mt-1">Assigned Teachers</p>
                </div>

                <!-- Subjects -->
                <div class="bg-gray-750 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    @php
                        $totalSubjects = \App\Models\Subject::count();
                    @endphp
                    <p class="text-3xl font-bold text-white">{{ $totalSubjects }}</p>
                    <p class="text-sm text-gray-400 mt-1">Available Subjects</p>
                </div>
            </div>

            <!-- School Details -->
            <div class="bg-gray-750 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">School Information</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">School Name</p>
                        <p class="text-white font-medium">{{ $school->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Address</p>
                        <p class="text-white font-medium">{{ $school->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Phone</p>
                        <p class="text-white font-medium">{{ $school->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Email</p>
                        <p class="text-white font-medium">{{ $school->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection