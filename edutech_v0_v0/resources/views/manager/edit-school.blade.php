@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Rejection Notice -->
        <div class="bg-red-500/10 border border-red-500 rounded-lg p-6">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-400 mb-2">Registration Requires Changes</h3>
                    <p class="text-gray-300 mb-4">Your school registration needs to be updated before approval can proceed.
                    </p>

                    <div class="bg-black/20 rounded p-4">
                        <p class="text-sm text-gray-400 mb-1"><strong>Reason for Rejection:</strong></p>
                        <p class="text-white">{{ $school->rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <h2 class="text-2xl font-bold text-white mb-6">Edit School Information</h2>

            <form method="POST" action="{{ route('manager.school.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        School Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $school->name) }}"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-300 mb-2">
                        Address <span class="text-red-400">*</span>
                    </label>
                    <textarea name="address" id="address" rows="3" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $school->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-500/10 border border-blue-500/50 rounded-lg p-4">
                    <p class="text-sm text-blue-300">
                        <strong>Note:</strong> After saving your changes, you'll need to resubmit your registration for
                        admin approval.
                    </p>
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Resubmit Section -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Ready to Resubmit?</h3>
            <p class="text-gray-300 mb-6">Once you've made the necessary changes above, click the button below to resubmit
                your registration for approval.</p>

            <form method="POST" action="{{ route('manager.school.resubmit') }}">
                @csrf
                <button type="submit"
                    onclick="return confirm('Are you sure you want to resubmit this registration? The admin will review it again.')"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Resubmit for Approval
                </button>
            </form>
        </div>
    </div>
@endsection