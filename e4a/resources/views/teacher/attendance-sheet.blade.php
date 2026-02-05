@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Mark Attendance - {{ $class->name }}</h1>
            <p class="text-gray-400 mt-1">{{ $class->school->name }}</p>
        </div>
        <a href="{{ route('teacher.attendance') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
            Back to Classes
        </a>
    </div>

    <form method="POST" action="{{ route('teacher.attendance.mark', $class->id) }}" id="attendanceForm">
        @csrf

        <!-- Date Selector -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Attendance Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $today) }}" max="{{ now()->format('Y-m-d') }}"
                           class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                           onchange="checkDateChange()">
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="markAll('present')" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                        Mark All Present
                    </button>
                    <button type="button" onclick="markAll('absent')" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                        Mark All Absent
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-750">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Student Name</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Present</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Absent</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Late</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Excused</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($students as $index => $student)
                            @php
                                $existingAttendance = $todayAttendance->get($student->id);
                                $currentStatus = $existingAttendance ? $existingAttendance->status : 'present';
                            @endphp
                            <tr class="hover:bg-gray-750 transition">
                                <td class="px-6 py-4 text-gray-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                            {{ substr($student->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-white font-medium">{{ $student->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $index }}][status]" value="present" 
                                           {{ $currentStatus == 'present' ? 'checked' : '' }}
                                           class="w-5 h-5 text-green-600 focus:ring-green-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $index }}][status]" value="absent" 
                                           {{ $currentStatus == 'absent' ? 'checked' : '' }}
                                           class="w-5 h-5 text-red-600 focus:ring-red-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $index }}][status]" value="late" 
                                           {{ $currentStatus == 'late' ? 'checked' : '' }}
                                           class="w-5 h-5 text-yellow-600 focus:ring-yellow-500">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="attendance[{{ $index }}][status]" value="excused" 
                                           {{ $currentStatus == 'excused' ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" name="attendance[{{ $index }}][notes]" 
                                           value="{{ $existingAttendance ? $existingAttendance->notes : '' }}"
                                           placeholder="Optional notes"
                                           class="w-full px-3 py-1 bg-gray-700 border border-gray-600 rounded text-white text-sm focus:ring-2 focus:ring-blue-500">
                                    <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No students in this class
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit Button -->
        @if($students->isNotEmpty())
            <div class="flex justify-end gap-4">
                <a href="{{ route('teacher.attendance') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                    Save Attendance
                </button>
            </div>
        @endif
    </form>
</div>

<script>
function markAll(status) {
    const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
    radios.forEach(radio => {
        radio.checked = true;
    });
}

function checkDateChange() {
    const selectedDate = document.getElementById('date').value;
    const today = '{{ $today }}';
    
    if (selectedDate !== today) {
        if (confirm('You are marking attendance for a past date. Continue?')) {
            // Reload page with new date to show existing attendance
            window.location.href = `{{ route('teacher.attendance.sheet', $class->id) }}?date=${selectedDate}`;
        } else {
            document.getElementById('date').value = today;
        }
    }
}
</script>
@endsection
