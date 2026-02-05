<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $manager = auth()->user()->manager;

        if (!$manager) {
            return view('manager.dashboard', [
                'school' => null,
                'studentsCount' => 0,
                'totalStudents' => 0,
            ]);
        }

        $school = $manager->school;

        if (!$school) {
            return view('manager.no-school');
        }

        // Calculate total students across all classes
        $totalStudents = $school->classes->sum(function ($class) {
            return $class->students()->count();
        });

        // The original studentsCount calculation is replaced by totalStudents
        // $studentsCount = Student::whereHas('class', function ($query) use ($school) {
        //     $query->where('school_id', $school->id);
        // })->count();

        return view('manager.dashboard', compact('school', 'totalStudents'));
    }

    public function linkParentToStudent()
    {
        $manager = auth()->user();
        $school = $manager->managedSchool;

        if (!$school) {
            return redirect()->back()->with('error', 'You are not assigned to any school.');
        }

        // Get students from manager's school only
        $students = Student::whereHas('class', function ($query) use ($school) {
            $query->where('school_id', $school->id);
        })->with('user')->get();

        $parents = ParentModel::with('user')->get();

        return view('manager.link-parent', compact('students', 'parents'));
    }

    public function storeParentLink(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'parent_id' => 'required|exists:parents,id',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // Verify the student belongs to manager's school
        $manager = auth()->user();
        $school = $manager->managedSchool;

        if ($student->class->school_id !== $school->id) {
            return redirect()->back()->with('error', 'You can only link parents to students in your school.');
        }

        $student->parent_id = $validated['parent_id'];
        $student->save();

        return redirect()->route('manager.dashboard')->with('success', 'Parent linked to student successfully.');
    }

    /**
     * Show form to edit rejected school
     */
    public function editSchool()
    {
        $school = auth()->user()->managedSchool;

        if (!$school) {
            abort(404, 'School not found');
        }

        // Only allow editing if rejected
        if ($school->status !== 'Rejected') {
            return redirect()->route('manager.dashboard')
                ->with('info', 'Your school is ' . strtolower($school->status) . '. No editing needed.');
        }

        return view('manager.edit-school', compact('school'));
    }

    /**
     * Update school details
     */
    public function updateSchool(Request $request)
    {
        $school = auth()->user()->managedSchool;

        if (!$school || $school->status !== 'Rejected') {
            return redirect()->route('manager.dashboard')
                ->with('error', 'Cannot edit this school.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);

        $school->update($validated);

        return redirect()->route('manager.school.edit')
            ->with('success', 'School information updated. You can now resubmit for approval.');
    }

    /**
     * Resubmit school for approval
     */
    public function resubmitSchool()
    {
        $school = auth()->user()->managedSchool;

        if (!$school || $school->status !== 'Rejected') {
            return redirect()->route('manager.dashboard')
                ->with('error', 'Cannot resubmit this school.');
        }

        $school->update([
            'status' => 'Pending',
            'rejection_reason' => null,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ]);

        // Notify admin of resubmission
        try {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                \Mail::to($admin->email)->send(new \App\Mail\SchoolRegistrationNotification($school, $school->manager));
            }
        } catch (\Exception $e) {
            logger()->error('Failed to send resubmission notification: ' . $e->getMessage());
        }

        return redirect()->route('manager.dashboard')
            ->with('success', 'School registration resubmitted successfully! You will be notified once reviewed.');
    }

    /**
     * Show teacher assignment interface
     */
    public function manageTeachers()
    {
        $school = auth()->user()->managedSchool;

        if (!$school) {
            return redirect()->back()->with('error', 'You are not assigned to any school.');
        }

        // Get all available teachers (not yet assigned to this school)
        $availableTeachers = \App\Models\Teacher::with('user')
            ->whereDoesntHave('schools', function ($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->get();

        // Get teachers already assigned to this school
        $assignedTeachers = $school->teachers()->with('user')->get();

        // Get all classes for this school
        $classes = $school->classes()->get();

        // Get all subjects
        $subjects = \App\Models\Subject::all();

        return view('manager.manage-teachers', compact('availableTeachers', 'assignedTeachers', 'classes', 'subjects', 'school'));
    }

    /**
     * Assign teacher to school
     */
    public function assignTeacherToSchool(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $school = auth()->user()->managedSchool;

        if (!$school) {
            return redirect()->back()->with('error', 'You are not assigned to any school.');
        }

        // Check if teacher is already assigned
        if ($school->teachers()->where('teacher_id', $validated['teacher_id'])->exists()) {
            return redirect()->back()->with('error', 'This teacher is already assigned to your school.');
        }

        // Assign teacher to school
        $school->teachers()->attach($validated['teacher_id'], [
            'assigned_date' => now(),
        ]);

        return redirect()->route('manager.teachers')
            ->with('success', 'Teacher assigned to your school successfully!');
    }

    /**
     * Assign teacher to class and subject
     */
    public function assignTeacherToClassSubject(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'coefficient' => 'required|numeric|min:0.5|max:10',
        ]);

        $school = auth()->user()->managedSchool;

        // Verify class belongs to manager's school
        $class = \App\Models\ClassModel::findOrFail($validated['class_id']);
        if ($class->school_id !== $school->id) {
            return redirect()->back()->with('error', 'Invalid class selection.');
        }

        // Verify teacher is assigned to the school
        if (!$school->teachers()->where('teacher_id', $validated['teacher_id'])->exists()) {
            return redirect()->back()->with('error', 'Teacher must be assigned to your school first.');
        }

        // Check if class-subject combination exists
        $classSubject = \App\Models\ClassSubject::firstOrCreate([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
        ], [
            'teacher_id' => $validated['teacher_id'],
            'coefficient' => $validated['coefficient'],
        ]);

        // If it exists, update it
        if (!$classSubject->wasRecentlyCreated) {
            $classSubject->update([
                'teacher_id' => $validated['teacher_id'],
                'coefficient' => $validated['coefficient'],
            ]);
        }

        return redirect()->route('manager.teachers')
            ->with('success', 'Teacher assigned to class and subject successfully!');
    }

    /**
     * Unassign teacher from school
     */
    public function unassignTeacher($teacherId)
    {
        $school = auth()->user()->managedSchool;

        if (!$school) {
            return redirect()->back()->with('error', 'You are not assigned to any school.');
        }

        // Remove teacher from school
        $school->teachers()->detach($teacherId);

        // Also remove from all class-subject assignments
        \App\Models\ClassSubject::where('teacher_id', $teacherId)
            ->whereHas('class', function ($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->update(['teacher_id' => null]);

        return redirect()->route('manager.teachers')
            ->with('success', 'Teacher unassigned from your school.');
    }
}
