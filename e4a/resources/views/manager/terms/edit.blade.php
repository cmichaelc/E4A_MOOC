@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Edit Academic Term</h1>
                <p class="text-gray-400">Update term details</p>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-xl p-8">
                <form action="{{ route('manager.terms.update', $term->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Academic Year (Read-only) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Academic Year
                        </label>
                        <div class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-400">
                            {{ $term->academic_year }}
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Academic year cannot be changed after creation</p>
                    </div>

                    <!-- Term Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Term Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $term->name) }}" required
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
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $term->start_date->format('Y-m-d')) }}" required
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
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $term->end_date->format('Y-m-d')) }}" required
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
                        <input type="number" name="order" id="order" value="{{ old('order', $term->order) }}" min="1"
                            required
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
                            <input type="checkbox" name="is_current" value="1" {{ old('is_current', $term->is_current) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                            <span class="ml-2 text-gray-300">Mark as current active term</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-400">This will unmark any previously current term</p>
                    </div>

                    <!-- Grades Warning -->
                    @if($term->grades->count() > 0)
                        <div class="mb-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                            <p class="text-yellow-400 text-sm">
                                ⚠️ This term has {{ $term->grades->count() }} associated grades. Exercise caution when modifying
                                dates.
                            </p>
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                        <a href="{{ route('manager.terms.index') }}"
                            class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            Update Term
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection