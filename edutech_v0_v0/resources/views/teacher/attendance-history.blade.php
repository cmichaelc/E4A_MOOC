@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Attendance History - {{ $class->name }}</h1>
                <p class="text-gray-400 mt-1">Last 30 days</p>
            </div>
            <a href="{{ route('teacher.attendance.sheet', $class->id) }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Mark Attendance
            </a>
        </div>

        @if($attendanceRecords->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Attendance Records</h3>
                <p class="text-gray-400">Start marking attendance to see history here</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($attendanceRecords as $date => $records)
                    @php
                        $dateObj = \Carbon\Carbon::parse($date);
                        $totalStudents = $records->count();
                        $presentCount = $records->where('status', 'present')->count();
                        $absentCount = $records->where('status', 'absent')->count();
                        $lateCount = $records->where('status', 'late')->count();
                        $excusedCount = $records->where('status', 'excused')->count();
                        $attendanceRate = round(($presentCount + $lateCount) / $totalStudents * 100, 1);
                    @endphp

                    <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                        <!-- Date Header -->
                        <div class="bg-gray-750 px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-gray-700 transition"
                            onclick="toggleDate('{{ $date }}')">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white">{{ $dateObj->format('d') }}</div>
                                    <div class="text-xs text-gray-400">{{ $dateObj->format('M') }}</div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $dateObj->format('l, F d, Y') }}</h3>
                                    <p class="text-sm text-gray-400">{{ $totalStudents }}
                                        {{ Str::plural('student', $totalStudents) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Statistics -->
                                <div class="flex space-x-3 text-sm">
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full font-semibold">
                                        ✓ {{ $presentCount }}
                                    </span>
                                    <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full font-semibold">
                                        ✗ {{ $absentCount }}
                                    </span>
                                    @if($lateCount > 0)
                                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full font-semibold">
                                            ⏰ {{ $lateCount }}
                                        </span>
                                    @endif
                                    @if($excusedCount > 0)
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full font-semibold">
                                            📝 {{ $excusedCount }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Attendance Rate -->
                                <div class="text-right">
                                    <div
                                        class="text-xl font-bold {{ $attendanceRate >= 80 ? 'text-green-400' : ($attendanceRate >= 60 ? 'text-yellow-400' : 'text-red-400') }}">
                                        {{ $attendanceRate }}%
                                    </div>
                                    <div class="text-xs text-gray-400">Attendance</div>
                                </div>

                                <!-- Toggle Icon -->
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform" id="icon-{{ $date }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Student Details (Collapsible) -->
                        <div id="details-{{ $date }}" class="hidden px-6 py-4 bg-gray-800">
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($records as $record)
                                    <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                {{ substr($record->student->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-white">{{ $record->student->user->name }}</span>
                                        </div>
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                                        @if($record->status == 'present') bg-green-500/20 text-green-400
                                                        @elseif($record->status == 'absent') bg-red-500/20 text-red-400
                                                        @elseif($record->status == 'late') bg-yellow-500/20 text-yellow-400
                                                        @else bg-blue-500/20 text-blue-400
                                                        @endif">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Notes if any -->
                            @php
                                $recordsWithNotes = $records->filter(fn($r) => !empty($r->notes));
                            @endphp
                            @if($recordsWithNotes->isNotEmpty())
                                <div class="mt-4 pt-4 border-t border-gray-700">
                                    <h4 class="text-sm font-semibold text-gray-400 mb-2">Notes:</h4>
                                    <div class="space-y-2">
                                        @foreach($recordsWithNotes as $record)
                                            <div class="text-sm text-gray-300">
                                                <span class="font-medium">{{ $record->student->user->name }}:</span>
                                                <span class="text-gray-400">{{ $record->notes }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleDate(date) {
            const details = document.getElementById('details-' + date);
            const icon = document.getElementById('icon-' + date);

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                details.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
@endsection