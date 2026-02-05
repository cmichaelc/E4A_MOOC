<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Grade;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $parent = $user->parentModel;

        if (!$parent) {
            return view('parent.no-profile');
        }

        $parent->load('students.user', 'students.class.school');

        // Get grades for all children
        $childrenData = [];
        foreach ($parent->students as $student) {
            $classSubjects = $student->class->classSubjects()
                ->with(['subject', 'teacher.user'])
                ->get();

            $gradesData = [];
            $totalGrade = 0;
            $subjectCount = 0;

            foreach ($classSubjects as $classSubject) {
                $breakdown = Grade::getGradeBreakdown($student->id, $classSubject->id);
                $gradesData[] = [
                    'class_subject' => $classSubject,
                    'breakdown' => $breakdown,
                ];

                if ($breakdown['final_grade']) {
                    $totalGrade += $breakdown['final_grade'];
                    $subjectCount++;
                }
            }

            $averageGrade = $subjectCount > 0 ? round($totalGrade / $subjectCount, 2) : 0;

            $childrenData[] = [
                'student' => $student,
                'grades' => $gradesData,
                'average' => $averageGrade,
            ];
        }

        return view('parent.dashboard', compact('parent', 'childrenData'));
    }

    /**
     * Show attendance overview for all children
     */
    public function viewAttendance()
    {
        $parent = auth()->user()->parentModel;

        if (!$parent) {
            return redirect()->back()->with('error', 'Parent profile not found.');
        }

        $children = $parent->students()->with('user')->get();

        $childrenStats = [];
        foreach ($children as $child) {
            $records = \App\Models\Attendance::where('student_id', $child->id)
                ->whereYear('date', now()->year)
                ->get();

            $childrenStats[$child->id] = [
                'name' => $child->user->name,
                'total' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
                'percentage' => $records->count() > 0
                    ? round(($records->where('status', 'present')->count() + $records->where('status', 'late')->count()) / $records->count() * 100, 1)
                    : 0,
            ];
        }

        return view('parent.attendance', compact('children', 'childrenStats'));
    }

    /**
     * Show detailed attendance for specific child
     */
    public function viewChildAttendance($studentId)
    {
        $parent = auth()->user()->parentModel;
        $child = \App\Models\Student::findOrFail($studentId);

        // Verify this is parent's child
        if ($child->parent_id !== $parent->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Get attendance records
        $attendanceRecords = \App\Models\Attendance::where('student_id', $studentId)
            ->whereYear('date', now()->year)
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

        return view('parent.child-attendance', compact('child', 'attendanceRecords', 'stats'));
    }
}
