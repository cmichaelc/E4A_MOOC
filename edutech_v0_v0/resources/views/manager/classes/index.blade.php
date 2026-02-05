@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Manage Classes</h1>
                <p class="text-gray-400 mt-1">Create and manage school classes</p>
            </div>
            <a href="{{ route('manager.classes.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Create New Class
            </a>
        </div>

        @if($classes->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Classes Yet</h3>
                <p class="text-gray-400 mb-4">Create your first class to get started</p>
                <a href="{{ route('manager.classes.create') }}"
                    class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Create Class
                </a>
            </div>
        @else
            <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-750">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Class Name</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Students</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Capacity</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($classes as $class)
                            <tr class="hover:bg-gray-750 transition">
                                <td class="px-6 py-4">
                                    <span class="text-white font-medium">{{ $class->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm font-semibold">
                                        {{ $class->students_count }} students
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-300">
                                    {{ $class->capacity ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('manager.classes.edit', $class->id) }}"
                                            class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded text-sm transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('manager.classes.destroy', $class->id) }}"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Delete this class? This will fail if there are students enrolled.')"
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