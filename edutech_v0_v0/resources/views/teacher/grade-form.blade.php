@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <a href="{{ route('teacher.dashboard') }}" class="text-blue-400 hover:text-blue-300 mb-4 inline-block">
                ← Back to Classes
            </a>
            <h1 class="text-3xl font-bold text-white">Add Grade</h1>
            <p class="text-gray-400 mt-2">
                {{ $classSubject->subject->name }} - {{ $classSubject->class->name }}
            </p>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('teacher.grades.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="class_subject_id" value="{{ $classSubject->id }}">
                <input type="hidden" name="academic_year" value="{{ date('Y') }}-{{ date('Y') + 1 }}">

                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-300 mb-2">Select Student</label>
                    <select name="student_id" id="student_id" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Choose Student --</option>
                        @foreach($classSubject->class->students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Grade Type</label>
                    <select name="type" id="type" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Control">Control</option>
                        <option value="Exam">Exam</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="score" class="block text-sm font-medium text-gray-300 mb-2">Score (0-20)</label>
                    <input type="number" name="score" id="score" min="0" max="20" step="0.01" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('score')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Date</label>
                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        Add Grade
                    </button>
                    <a href="{{ route('teacher.dashboard') }}"
                        class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection