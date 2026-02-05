@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('manager.students.index') }}" class="text-blue-400 hover:text-blue-300">← Back to Students</a>
            <h1 class="text-3xl font-bold text-white mt-2">Edit Student</h1>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('manager.students.update', $student->id) }}">
                @csrf
                @method('PUT')

                <!-- Student Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Student Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $student->user->name) }}"
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
                    <input type="email" name="email" id="email" required value="{{ old('email', $student->user->email) }}"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
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
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('manager.students.index') }}"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection