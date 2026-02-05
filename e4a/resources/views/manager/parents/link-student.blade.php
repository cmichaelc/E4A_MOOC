@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Link Student to Parent</h1>
                <p class="text-gray-400">Manage parent-student relationships</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Parent Information -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4">Parent Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400 text-sm">Name</p>
                        <p class="text-white font-medium">{{ $parent->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Email</p>
                        <p class="text-white font-medium">{{ $parent->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Current Children -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4">Current Children</h2>
                @if($parent->children->count() > 0)
                    <div class="space-y-3">
                        @foreach($parent->children as $child)
                            <div class="flex items-center justify-between bg-gray-700 rounded-lg p-4">
                                <div>
                                    <p class="text-white font-medium">{{ $child->user->name }}</p>
                                    <p class="text-gray-400 text-sm">Class: {{ $child->class->name ?? 'No Class' }}</p>
                                </div>
                                <form action="{{ route('manager.parents.unlink-student', [$parent->id, $child->id]) }}"
                                    method="POST" onsubmit="return confirm('Are you sure you want to unlink this student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                        Unlink
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No children linked yet.</p>
                @endif
            </div>

            <!-- Link New Student Form -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4">Link New Student</h2>

                @if($availableStudents->count() > 0)
                    <form action="{{ route('manager.parents.store-link-student', $parent->id) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="student_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Select Student <span class="text-red-400">*</span>
                            </label>
                            <select name="student_id" id="student_id" required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Select a student --</option>
                                @foreach($availableStudents as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->user->name }} ({{ $student->class->name ?? 'No Class' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('manager.parents.index') }}"
                                class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                                Back to Parents
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                                Link Student
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <p class="text-gray-500 mb-4">No unlinked students available</p>
                        <p class="text-gray-600 text-sm">All students in your school are either linked to parents or haven't
                            been created yet.</p>
                        <a href="{{ route('manager.students.create') }}"
                            class="inline-block mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Create New Student
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection