@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Create Academic Term</h1>
                <p class="text-gray-400">Define a new semester, trimester, or academic period</p>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-xl p-8">
                <form action="{{ route('manager.terms.store') }}" method="POST">
                    @csrf

                    <!-- Quick Setup Presets -->
                    <div class="mb-8 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <p class="text-blue-400 text-sm mb-3">Quick Setup (Coming Soon):</p>
                        <div class="flex gap-2">
                            <button type="button"
                                class="px-4 py-2 bg-gray-700 text-gray-400 rounded cursor-not-allowed opacity-50">
                                2 Semesters
                            </button>
                            <button type="button"
                                class="px-4 py-2 bg-gray-700 text-gray-400 rounded cursor-not-allowed opacity-50">
                                3 Trimesters
                            </button>
                            <button type="button"
                                class="px-4 py-2 bg-gray-700 text-gray-400 rounded cursor-not-allowed opacity-50">
                                4 Quarters
                            </button>
                        </div>
                    </div>

                    <!-- Academic Year -->
                    <div class="mb-6">
                        <label for="academic_year" class="block text-sm font-medium text-gray-300 mb-2">
                            Academic Year <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="academic_year" id="academic_year"
                            value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}" required
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 2024-2025">
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Term Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Term Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 1st Semester, Fall Trimester">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates Row -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">
                                Start Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">
                                End Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-300 mb-2">
                            Display Order <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', 1) }}" min="1" required
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="1">
                        <p class="mt-1 text-xs text-gray-400">Lower numbers appear first in listings</p>
                        @error('order')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mark as Current -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                            <span class="ml-2 text-gray-300">Mark as current active term</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-400">This will unmark any previously current term</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                        <a href="{{ route('manager.terms.index') }}"
                            class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            Create Term
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection