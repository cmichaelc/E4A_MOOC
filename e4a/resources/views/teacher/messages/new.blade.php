@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">New Message</h1>
                <p class="text-gray-400 mt-1">Start a conversation with a parent</p>
            </div>
            <a href="{{ route('teacher.messages') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Cancel
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('teacher.messages.send') }}">
                @csrf

                <!-- Student Selection -->
                <div class="mb-6">
                    <label for="student_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Select Student
                    </label>
                    <select name="student_id" id="student_id" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                        onchange="updateParentInfo()">
                        <option value="">-- Choose a student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                data-parent="{{ $student->parent ? $student->parent->user->name : 'No parent linked' }}">
                                {{ $student->user->name }} ({{ $student->class->name }})
                            </option>
                        @endforeach
                    </select>
                    <p id="parentInfo" class="text-sm text-gray-400 mt-2"></p>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                        Message
                    </label>
                    <textarea name="message" id="message" rows="8" required placeholder="Type your message to the parent..."
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('teacher.messages') }}"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateParentInfo() {
            const select = document.getElementById('student_id');
            const option = select.options[select.selectedIndex];
            const parentName = option.getAttribute('data-parent');
            const parentInfo = document.getElementById('parentInfo');

            if (parentName && parentName !== 'No parent linked') {
                parentInfo.textContent = 'This message will be sent to: ' + parentName;
                parentInfo.classList.remove('text-red-400');
                parentInfo.classList.add('text-green-400');
            } else if (parentName === 'No parent linked') {
                parentInfo.textContent = '⚠️ This student has no parent linked. Please link a parent first.';
                parentInfo.classList.remove('text-green-400');
                parentInfo.classList.add('text-red-400');
            } else {
                parentInfo.textContent = '';
            }
        }
    </script>
@endsection