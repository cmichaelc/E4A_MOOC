@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Create Announcement</h1>
                <p class="text-gray-400 mt-1">Share news with your school community</p>
            </div>
            <a href="{{ route('announcements.index') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Cancel
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('announcements.store') }}">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        Title <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-300 mb-2">
                        Content <span class="text-red-400">*</span>
                    </label>
                    <textarea name="content" id="content" rows="10" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                <!-- Target -->
                <div class="mb-6">
                    <label for="target" class="block text-sm font-medium text-gray-300 mb-2">
                        Target Audience <span class="text-red-400">*</span>
                    </label>
                    <select name="target" id="target" required onchange="toggleClassSelector()"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="all">All School</option>
                        <option value="specific_class">Specific Class</option>
                    </select>
                </div>

                <!-- Target Class (conditional) -->
                <div class="mb-6" id="classSelector" style="display: none;">
                    <label for="target_class_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Select Class <span class="text-red-400">*</span>
                    </label>
                    <select name="target_class_id" id="target_class_id"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Choose a class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority -->
                <div class="mb-6">
                    <label for="priority" class="block text-sm font-medium text-gray-300 mb-2">
                        Priority Level <span class="text-red-400">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="low">Low</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    <p class="text-sm text-gray-400 mt-1">Higher priority announcements are displayed more prominently</p>
                </div>

                <!-- Publish Now -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="publish_now" value="1" checked
                            class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="ml-2 text-sm text-gray-300">Publish immediately</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('announcements.index') }}"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Create Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleClassSelector() {
            const targetSelect = document.getElementById('target');
            const classSelector = document.getElementById('classSelector');

            if (targetSelect.value === 'specific_class') {
                classSelector.style.display = 'block';
                document.getElementById('target_class_id').required = true;
            } else {
                classSelector.style.display = 'none';
                document.getElementById('target_class_id').required = false;
            }
        }
    </script>
@endsection