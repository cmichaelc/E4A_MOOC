@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Children's Attendance</h1>
                <p class="text-gray-400 mt-1">View attendance for all your children</p>
            </div>
            <a href="{{ route('parent.dashboard') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Back to Dashboard
            </a>
        </div>

        @if($children->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Children Linked</h3>
                <p class="text-gray-400">Ask your school manager to link your children to your account</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($children as $child)
                    @php
                        $stats = $childrenStats[$child->id];
                        $attendanceColor = $stats['percentage'] >= 90 ? 'green' :
                            ($stats['percentage'] >= 75 ? 'yellow' : 'red');
                    @endphp

                    <div class="bg-gray-800 rounded-lg shadow-xl p-6 hover:shadow-2xl transition">
                        <!-- Child Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                    {{ substr($child->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $child->user->name }}</h3>
                                    <p class="text-sm text-gray-400">{{ $child->class->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Percentage Circle -->
                        <div class="mb-6 text-center">
                            <div class="inline-block relative">
                                <svg class="w-32 h-32" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#374151" stroke-width="8" />
                                    <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor"
                                        class="text-{{ $attendanceColor }}-500" stroke-width="8" stroke-dasharray="339.292"
                                        stroke-dashoffset="{{ 339.292 - (339.292 * $stats['percentage'] / 100) }}"
                                        stroke-linecap="round" transform="rotate(-90 60 60)" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div>
                                        <div class="text-3xl font-bold text-{{ $attendanceColor }}-400">
                                            {{ $stats['percentage'] }}%
                                        </div>
                                        <div class="text-xs text-gray-400">Attendance</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Grid -->
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            <div class="text-center p-2 bg-gray-750 rounded">
                                <div class="text-lg font-bold text-green-400">{{ $stats['present'] }}</div>
                                <div class="text-xs text-gray-400">Present</div>
                            </div>
                            <div class="text-center p-2 bg-gray-750 rounded">
                                <div class="text-lg font-bold text-red-400">{{ $stats['absent'] }}</div>
                                <div class="text-xs text-gray-400">Absent</div>
                            </div>
                            <div class="text-center p-2 bg-gray-750 rounded">
                                <div class="text-lg font-bold text-yellow-400">{{ $stats['late'] }}</div>
                                <div class="text-xs text-gray-400">Late</div>
                            </div>
                            <div class="text-center p-2 bg-gray-750 rounded">
                                <div class="text-lg font-bold text-gray-300">{{ $stats['total'] }}</div>
                                <div class="text-xs text-gray-400">Total</div>
                            </div>
                        </div>

                        <!-- View Details Button -->
                        <a href="{{ route('parent.attendance.child', $child->id) }}"
                            class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-center font-medium transition">
                            View Detailed Attendance
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection