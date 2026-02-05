@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Create Parent Account</h1>
                <p class="text-gray-400">Add a new parent to the system</p>
            </div>

            <!-- Form -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-8">
                <form action="{{ route('manager.parents.store') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Parent Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter parent's full name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email Address <span class="text-red-400">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="parent@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">A random password will be generated for this account</p>
                    </div>

                    <!-- Phone (Optional) -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                            Phone Number (Optional)
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="+229 12345678">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address (Optional) -->
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-300 mb-2">
                            Address (Optional)
                        </label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter parent's address">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('manager.parents.index') }}"
                            class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            Create Parent Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Note -->
            <div class="mt-6 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                <p class="text-blue-400 text-sm">
                    <strong>Note:</strong> After creating the parent account, a random password will be generated and
                    displayed.
                    Make sure to copy it and provide it to the parent, as it will not be shown again.
                </p>
            </div>
        </div>
    </div>
@endsection