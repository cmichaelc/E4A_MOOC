@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-6">Link Parent to Student</h1>

        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <p class="text-gray-300 mb-6">Select a student and parent to create a link. You can only link students from your
                school.</p>

            <form method="POST" action="{{ route('manager.link-parent.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-300 mb-2">Select Student</label>
                    <select name="student_id" id="student_id" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Choose Student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->name }} - {{ $student->class->name ?? 'No Class' }}
                                @if($student->parent)
                                    (Already linked to {{ $student->parent->user->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-300 mb-2">Select Parent</label>
                    <select name="parent_id" id="parent_id" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Choose Parent --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">
                                {{ $parent->user->name }} ({{ $parent->user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        Link Parent
                    </button>
                    <a href="{{ route('manager.dashboard') }}"
                        class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection