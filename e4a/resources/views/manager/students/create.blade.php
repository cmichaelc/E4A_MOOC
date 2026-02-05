@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('manager.students.index') }}" class="text-blue-400 hover:text-blue-300">← Back to Students</a>
            <h1 class="text-3xl font-bold text-white mt-2">Add New Student</h1>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('manager.students.store') }}">
                @csrf

                <!-- Student Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Student Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g., John Doe"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        placeholder="student@example.com"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-400 mt-1">Login credentials will be auto-generated</p>
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class -->
                <div class="mb-6">
                    <label for="class_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Class <span class="text-red-400">*</span>
                    </label>
                    <select name="class_id" id="class_id" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select a class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-500/10 border border-blue-500 rounded-lg p-4 mb-6">
                    <p class="text-blue-400 text-sm">
                        <strong>Note:</strong> A random password will be generated and displayed after creation. Please save
                        it to share with the student.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('manager.students.index') }}"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Add Student
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection