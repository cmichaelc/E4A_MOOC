@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Manage Teachers</h1>
            <a href="{{ route('manager.dashboard') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Back to Dashboard
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid lg:grid-cols-2 gap-6">

            <!-- Left Column: Available Teachers -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Available Teachers</h2>
                <p class="text-gray-400 mb-4">Teachers registered but not yet assigned to your school</p>

                @if($availableTeachers->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <p>No available teachers at the moment</p>
                        <a href="{{ route('register.teacher') }}"
                            class="text-blue-400 hover:text-blue-300 text-sm mt-2 inline-block">
                            Register a new teacher →
                        </a>
                    </div>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($availableTeachers as $teacher)
                            <div class="bg-gray-750 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-white">{{ $teacher->user->name }}</h3>
                                    <p class="text-sm text-gray-400">{{ $teacher->specialization }}</p>
                                    <p class="text-xs text-gray-500">{{ $teacher->user->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('manager.teachers.assign') }}">
                                    @csrf
                                    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                        Add to School
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Assigned Teachers -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Your School's Teachers</h2>
                <p class="text-gray-400 mb-4">Teachers currently assigned to {{ $school->name }}</p>

                @if($assignedTeachers->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <p>No teachers assigned yet</p>
                        <p class="text-sm mt-1">Add teachers from the left panel</p>
                    </div>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($assignedTeachers as $teacher)
                            <div class="bg-gray-750 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white">{{ $teacher->user->name }}</h3>
                                        <p class="text-sm text-gray-400">{{ $teacher->specialization }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->phone }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('manager.teachers.unassign', $teacher->id) }}"
                                        onsubmit="return confirm('Remove this teacher from your school? This will also remove all class assignments.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm">
                                            Remove
                                        </button>
                                    </form>
                                </div>

                                <!-- Assign to Class/Subject -->
                                <div class="border-t border-gray-700 pt-3 mt-3">
                                    <p class="text-xs text-gray-500 mb-2">Assign to Class & Subject:</p>
                                    <form method="POST" action="{{ route('manager.teachers.assign-class') }}"
                                        class="grid grid-cols-12 gap-2">
                                        @csrf
                                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                                        <select name="class_id" required
                                            class="col-span-4 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs">
                                            <option value="">Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>

                                        <select name="subject_id" required
                                            class="col-span-4 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs">
                                            <option value="">Subject</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>

                                        <input type="number" name="coefficient" step="0.5" min="0.5" max="10" placeholder="Coef"
                                            required
                                            class="col-span-2 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs">

                                        <button type="submit"
                                            class="col-span-2 px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-medium">
                                            Assign
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Current Assignments Table -->
        @if($assignedTeachers->isNotEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Class & Subject Assignments</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-700">
                                <th class="pb-3 text-gray-400 font-semibold">Class</th>
                                <th class="pb-3 text-gray-400 font-semibold">Subject</th>
                                <th class="pb-3 text-gray-400 font-semibold">Teacher</th>
                                <th class="pb-3 text-gray-400 font-semibold">Coefficient</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-300">
                            @php
                                $classSubjects = \App\Models\ClassSubject::with(['class', 'subject', 'teacher.user'])
                                    ->whereHas('class', function ($query) use ($school) {
                                        $query->where('school_id', $school->id);
                                    })
                                    ->whereNotNull('teacher_id')
                                    ->get();
                            @endphp

                            @forelse($classSubjects as $cs)
                                <tr class="border-b border-gray-750">
                                    <td class="py-3">{{ $cs->class->name }}</td>
                                    <td class="py-3">{{ $cs->subject->name }}</td>
                                    <td class="py-3">{{ $cs->teacher->user->name }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-blue-600/20 text-blue-400 rounded text-sm">
                                            {{ $cs->coefficient }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400">
                                        No class-subject assignments yet. Use the forms above to assign teachers.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection