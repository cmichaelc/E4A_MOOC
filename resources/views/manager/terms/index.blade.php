@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Academic Terms</h1>
                <p class="text-gray-400">Manage semesters, trimesters, and academic periods</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Create Button -->
            <div class="mb-6">
                <a href="{{ route('manager.terms.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create New Term
                </a>
            </div>

            <!-- Terms by Academic Year -->
            @forelse($terms as $year => $yearTerms)
                <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gray-750 border-b border-gray-700">
                        <h2 class="text-2xl font-semibold text-white">Academic Year {{ $year }}</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Term</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Period</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Grades</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($yearTerms as $term)
                                    <tr class="hover:bg-gray-750 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                    {{ $term->order }}
                                                </div>
                                                <span class="text-white font-medium">{{ $term->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-300">
                                            {{ $term->start_date->format('M d, Y') }} - {{ $term->end_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($term->is_current)
                                                <span
                                                    class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold">
                                                    Current
                                                </span>
                                            @elseif($term->end_date->isPast())
                                                <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-sm">
                                                    Ended
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">
                                                    Upcoming
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-300">
                                            {{ $term->grades->count() }} grades
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                @if(!$term->is_current)
                                                    <form action="{{ route('manager.terms.set-current', $term->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-sm transition">
                                                            Set Current
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('manager.terms.edit', $term->id) }}"
                                                    class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded text-sm transition">
                                                    Edit
                                                </a>

                                                <form action="{{ route('manager.terms.destroy', $term->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
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
                </div>
            @empty
                <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg font-semibold mb-2">No Academic Terms Yet</p>
                    <p class="text-gray-600 text-sm mb-6">Create your first term to start organizing your academic year</p>
                    <a href="{{ route('manager.terms.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First Term
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection