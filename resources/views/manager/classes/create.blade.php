@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('manager.classes.index') }}" class="text-blue-400 hover:text-blue-300">← Back to Classes</a>
            <h1 class="text-3xl font-bold text-white mt-2">Create New Class</h1>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('manager.classes.store') }}">
                @csrf

                <!-- Class Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Class Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" required placeholder="e.g., 6ème A, CM2 B"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity (Optional) -->
                <div class="mb-6">
                    <label for="capacity" class="block text-sm font-medium text-gray-300 mb-2">
                        Class Capacity (Optional)
                    </label>
                    <input type="number" name="capacity" id="capacity" min="1" max="100" placeholder="e.g., 40"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-400 mt-1">Maximum number of students allowed in this class</p>
                    @error('capacity')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('manager.classes.index') }}"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection