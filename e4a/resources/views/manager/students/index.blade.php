@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Manage Students</h1>
                <p class="text-gray-400 mt-1">Add and manage students in your school</p>
            </div>
            <a href="{{ route('manager.students.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Add New Student
            </a>
        </div>

        @if($students->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Students Yet</h3>
                <p class="text-gray-400 mb-4">Add your first student to get started</p>
                <a href="{{ route('manager.students.create') }}"
                    class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Add Student
                </a>
            </div>
        @else
            <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-750">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Class</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Parent</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-750 transition">
                                <td class="px-6 py-4">
                                    <span class="text-white font-medium">{{ $student->user->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-300">
                                    {{ $student->user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-sm">
                                        {{ $student->class->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-300">
                                    {{ $student->parent ? $student->parent->user->name : 'Not linked' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('manager.students.edit', $student->id) }}"
                                            class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded text-sm transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('manager.students.destroy', $student->id) }}"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Delete this student? This will remove all their data.')"
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection