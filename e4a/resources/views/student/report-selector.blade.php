@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Download Report Card</h1>
                <p class="text-gray-400">Select an academic term to download your report card</p>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-xl p-8">
                @php
                    $student = auth()->user()->student;
                    $school = $student->class->school;
                    $terms = \App\Models\AcademicTerm::where('school_id', $school->id)
                        ->where('academic_year', $student->class->academic_year)
                        ->orderBy('order')
                        ->get();
                @endphp

                @if($terms->count() > 0)
                    <div class="space-y-4">
                        @foreach($terms as $term)
                            <a href="{{ route('student.report-card', ['term' => $term->id]) }}"
                                class="block p-6 bg-gray-700 hover:bg-gray-600 rounded-lg transition group">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold text-white group-hover:text-blue-400 transition">
                                            {{ $term->name }}
                                        </h3>
                                        <p class="text-gray-400 text-sm mt-1">
                                            {{ $term->start_date->format('M d, Y') }} - {{ $term->end_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        @if($term->is_current)
                                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm font-semibold">
                                                Current
                                            </span>
                                        @endif
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white transition" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        <!-- All Terms Option -->
                        <a href="{{ route('student.report-card') }}"
                            class="block p-6 bg-blue-600 hover:bg-blue-700 rounded-lg transition group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-white">
                                        Full Year Report
                                    </h3>
                                    <p class="text-blue-100 text-sm mt-1">
                                        All terms combined ({{ $terms->count() }} terms)
                                    </p>
                                </div>
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-4">No academic terms defined yet</p>
                        <p class="text-gray-600 text-sm">Contact your school manager to set up academic terms</p>
                    </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-700">
                    <a href="{{ route('student.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection