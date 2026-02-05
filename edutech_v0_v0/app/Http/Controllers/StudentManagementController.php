<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StudentManagementController extends Controller
{
    /**
     * Show all students for manager's school
     */
    public function index()
    {
        $manager = auth()->user()->manager;

        if (!$manager) {
            return redirect()->back()->with('error', 'Manager profile not found.');
        }

        $students = Student::whereHas('class', function ($query) use ($manager) {
            $query->where('school_id', $manager->school_id);
        })->with(['user', 'class', 'parent.user'])->get();

        return view('manager.students.index', compact('students'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $manager = auth()->user()->manager;

        $classes = ClassModel::where('school_id', $manager->school_id)
            ->orderBy('name')
            ->get();

        return view('manager.students.create', compact('classes'));
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        $manager = auth()->user()->manager;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'class_id' => 'required|exists:classes,id',
        ]);

        // Verify class belongs to manager's school
        $class = ClassModel::where('id', $validated['class_id'])
            ->where('school_id', $manager->school_id)
            ->first();

        if (!$class) {
            return redirect()->back()->with('error', 'Invalid class selected.');
        }

        // Generate password
        $password = Str::random(10);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'student',
        ]);

        // Create student
        Student::create([
            'user_id' => $user->id,
            'class_id' => $validated['class_id'],
        ]);

        return redirect()->route('manager.students.index')
            ->with('success', "Student created! Email: {$validated['email']}, Password: {$password}");
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $manager = auth()->user()->manager;

        $student = Student::whereHas('class', function ($query) use ($manager) {
            $query->where('school_id', $manager->school_id);
        })->with('user')->findOrFail($id);

        $classes = ClassModel::where('school_id', $manager->school_id)
            ->orderBy('name')
            ->get();

        return view('manager.students.edit', compact('student', 'classes'));
    }

    /**
     * Update student
     */
    public function update(Request $request, $id)
    {
        $manager = auth()->user()->manager;

        $student = Student::whereHas('class', function ($query) use ($manager) {
            $query->where('school_id', $manager->school_id);
        })->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'class_id' => 'required|exists:classes,id',
        ]);

        // Verify class belongs to manager's school
        $class = ClassModel::where('id', $validated['class_id'])
            ->where('school_id', $manager->school_id)
            ->first();

        if (!$class) {
            return redirect()->back()->with('error', 'Invalid class selected.');
        }

        // Update user
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student
        $student->update([
            'class_id' => $validated['class_id'],
        ]);

        return redirect()->route('manager.students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Delete student
     */
    public function destroy($id)
    {
        $manager = auth()->user()->manager;

        $student = Student::whereHas('class', function ($query) use ($manager) {
            $query->where('school_id', $manager->school_id);
        })->findOrFail($id);

        // Delete user (cascade will delete student)
        $student->user->delete();

        return redirect()->route('manager.students.index')
            ->with('success', 'Student deleted successfully!');
    }
}
