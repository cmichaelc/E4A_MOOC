@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-6">Create New School</h1>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('admin.schools.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">School Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                    <input type="text" name="address" id="address"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>

                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-300 mb-2">Assign Manager
                        (Optional)</label>
                    <select name="manager_id" id="manager_id"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Select Manager --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        Create School
                    </button>
                    <a href="{{ route('admin.schools') }}"
                        class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection