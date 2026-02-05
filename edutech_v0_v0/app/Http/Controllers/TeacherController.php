<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Grade;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return view('teacher.no-profile');
        }

        // Get all class-subjects assigned to this teacher
        $classSubjects = ClassSubject::where('teacher_id', $teacher->id)
            ->with(['class.school', 'subject'])
            ->get();

        return view('teacher.dashboard', compact('classSubjects'));
    }

    public function showGradeForm($classSubjectId)
    {
        $classSubject = ClassSubject::with(['class.students.user', 'subject'])
            ->findOrFail($classSubjectId);

        // Verify this teacher is assigned to this class-subject
        $teacher = auth()->user()->teacher;
        if ($classSubject->teacher_id !== $teacher->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return view('teacher.grade-form', compact('classSubject'));
    }

    public function storeGrade(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_subject_id' => 'required|exists:class_subject,id',
            'type' => 'required|in:Control,Exam',
            'score' => 'required|numeric|min:0|max:20',
            'date' => 'required|date',
            'academic_year' => 'required|string',
        ]);

        // Verify teacher is assigned to this class-subject
        $classSubject = ClassSubject::findOrFail($validated['class_subject_id']);
        $teacher = auth()->user()->teacher;

        if ($classSubject->teacher_id !== $teacher->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        Grade::create($validated);

        return redirect()->route('teacher.grade-form', $validated['class_subject_id'])
            ->with('success', 'Grade added successfully.');
    }

    /**
     * Show list of classes teacher can mark attendance for
     */
    public function attendanceClasses()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found.');
        }

        // Get unique classes the teacher is assigned to
        $classes = \App\Models\ClassModel::whereHas('classSubjects', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with('school')->distinct()->get();

        return view('teacher.attendance-classes', compact('classes'));
    }

    /**
     * Show attendance sheet for a specific class
     */
    public function showAttendanceSheet($classId)
    {
        $teacher = auth()->user()->teacher;
        $class = \App\Models\ClassModel::findOrFail($classId);

        // Verify teacher teaches this class
        if (!$class->classSubjects()->where('teacher_id', $teacher->id)->exists()) {
            return redirect()->back()->with('error', 'You are not assigned to this class.');
        }

        // Get all students in this class
        $students = $class->students()->with('user')->orderBy('id')->get();

        // Get today's attendance if already marked
        $today = now()->format('Y-m-d');
        $todayAttendance = \App\Models\Attendance::where('class_id', $classId)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance-sheet', compact('class', 'students', 'todayAttendance', 'today'));
    }

    /**
     * Mark attendance for a class
     */
    public function markAttendance(Request $request, $classId)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        $teacher = auth()->user()->teacher;
        $class = \App\Models\ClassModel::findOrFail($classId);

        // Verify teacher teaches this class
        if (!$class->classSubjects()->where('teacher_id', $teacher->id)->exists()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // Save attendance for each student
        foreach ($validated['attendance'] as $record) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'date' => $validated['date'],
                ],
                [
                    'class_id' => $classId,
                    'status' => $record['status'],
                    'marked_by' => auth()->id(),
                    'notes' => $record['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.attendance.sheet', $classId)
            ->with('success', 'Attendance marked successfully for ' . count($validated['attendance']) . ' students!');
    }

    /**
     * View attendance history for a class
     */
    public function attendanceHistory($classId)
    {
        $teacher = auth()->user()->teacher;
        $class = \App\Models\ClassModel::findOrFail($classId);

        // Verify access
        if (!$class->classSubjects()->where('teacher_id', $teacher->id)->exists()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // Get attendance records for past 30 days
        $attendanceRecords = \App\Models\Attendance::where('class_id', $classId)
            ->where('date', '>=', now()->subDays(30))
            ->with('student.user')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');

        return view('teacher.attendance-history', compact('class', 'attendanceRecords'));
    }
}
