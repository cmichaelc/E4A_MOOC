@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Message Teacher</h1>
                <p class="text-gray-400 mt-1">About {{ $student->user->name }}</p>
            </div>
            <a href="{{ route('parent.messages') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Cancel
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <form method="POST" action="{{ route('parent.messages.send') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <!-- Student Info -->
                <div class="mb-6 p-4 bg-gray-750 rounded-lg">
                    <p class="text-sm text-gray-400">Student</p>
                    <p class="text-white font-semibold">{{ $student->user->name }}</p>
                    <p class="text-gray-400 text-sm">{{ $student->class->name }}</p>
                </div>

                <!-- Teacher Selection -->
                <div class="mb-6">
                    <label for="recipient_teacher_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Select Teacher
                    </label>
                    <select name="recipient_teacher_id" id="recipient_teacher_id" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Choose a teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->user->name }}
                                @if($teacher->specialization)
                                    ({{ $teacher->specialization }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                        Message
                    </label>
                    <textarea name="message" id="message" rows="8" required
                        placeholder="Type your message to the teacher..."
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('parent.messages') }}"
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
@endsection