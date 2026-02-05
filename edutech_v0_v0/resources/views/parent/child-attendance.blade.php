@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $child->user->name }}'s Attendance</h1>
                <p class="text-gray-400 mt-1">{{ $child->class->name }} - {{ $child->class->school->name }}</p>
            </div>
            <a href="{{ route('parent.attendance') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                Back to Children
            </a>
        </div>

        <!-- Statistics Cards (Same as student view) -->
        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Attendance Percentage -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg shadow-xl p-6">
                <div class="text-blue-200 text-sm font-medium mb-2">Attendance Rate</div>
                <div class="text-4xl font-bold text-white">{{ $stats['percentage'] }}%</div>
                <div class="text-blue-200 text-xs mt-1">{{ $stats['total'] }} days marked</div>
            </div>

            <!-- Present -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Present</span>
                    <span class="text-2xl">✓</span>
                </div>
                <div class="text-3xl font-bold text-green-400">{{ $stats['present'] }}</div>
                <div class="text-gray-500 text-xs mt-1">
                    {{ $stats['total'] > 0 ? round($stats['present'] / $stats['total'] * 100, 1) : 0 }}%
                </div>
            </div>

            <!-- Absent -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Absent</span>
                    <span class="text-2xl">✗</span>
                </div>
                <div class="text-3xl font-bold text-red-400">{{ $stats['absent'] }}</div>
                <div class="text-gray-500 text-xs mt-1">
                    {{ $stats['total'] > 0 ? round($stats['absent'] / $stats['total'] * 100, 1) : 0 }}%
                </div>
            </div>

            <!-- Late -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Late</span>
                    <span class="text-2xl">⏰</span>
                </div>
                <div class="text-3xl font-bold text-yellow-400">{{ $stats['late'] }}</div>
                <div class="text-gray-500 text-xs mt-1">
                    {{ $stats['total'] > 0 ? round($stats['late'] / $stats['total'] * 100, 1) : 0 }}%
                </div>
            </div>

            <!-- Excused -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Excused</span>
                    <span class="text-2xl">📝</span>
                </div>
                <div class="text-3xl font-bold text-blue-400">{{ $stats['excused'] }}</div>
                <div class="text-gray-500 text-xs mt-1">
                    {{ $stats['total'] > 0 ? round($stats['excused'] / $stats['total'] * 100, 1) : 0 }}%
                </div>
            </div>
        </div>

        <!-- Attendance Records -->
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-750 border-b border-gray-700">
                <h2 class="text-xl font-bold text-white">Attendance History</h2>
                <p class="text-sm text-gray-400">{{ now()->year }} Academic Year</p>
            </div>

            @if($attendanceRecords->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-300 mb-2">No Attendance Records</h3>
                    <p class="text-gray-400">Attendance will appear here once marked by the teacher</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-750">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Date</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-300">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-300">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($attendanceRecords as $record)
                                <tr class="hover:bg-gray-750 transition">
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ \Carbon\Carbon::parse($record->date)->format('l, M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                    @if($record->status == 'present') bg-green-500/20 text-green-400
                                                    @elseif($record->status == 'absent') bg-red-500/20 text-red-400
                                                    @elseif($record->status == 'late') bg-yellow-500/20 text-yellow-400
                                                    @else bg-blue-500/20 text-blue-400
                                                    @endif">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 text-sm">
                                        {{ $record->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection