<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            return view('student.no-profile');
        }

        $student->load('class.school');

        // Get all class-subjects for this student's class
        $classSubjects = $student->class->classSubjects()
            ->with(['subject', 'teacher.user'])
            ->get();

        // Calculate grades for each subject
        $gradesData = [];
        foreach ($classSubjects as $classSubject) {
            $breakdown = Grade::getGradeBreakdown($student->id, $classSubject->id);
            $gradesData[] = [
                'class_subject' => $classSubject,
                'breakdown' => $breakdown,
            ];
        }

        return view('student.dashboard', compact('student', 'gradesData'));
    }

    /**
     * Show student's attendance records
     */
    public function viewAttendance()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Get attendance records for current academic year
        $currentYear = now()->format('Y');
        $attendanceRecords = \App\Models\Attendance::where('student_id', $student->id)
            ->whereYear('date', $currentYear)
            ->orderBy('date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total' => $attendanceRecords->count(),
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'excused' => $attendanceRecords->where('status', 'excused')->count(),
        ];

        $stats['percentage'] = $stats['total'] > 0
            ? round(($stats['present'] + $stats['late']) / $stats['total'] * 100, 1)
            : 0;

        return view('student.attendance', compact('attendanceRecords', 'stats', 'student'));
    }
}
