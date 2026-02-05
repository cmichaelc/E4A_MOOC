<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentManagementController extends Controller
{
    /**
     * Display list of all parents
     */
    public function index()
    {
        $manager = auth()->user()->manager;

        // Get all parents whose children are in this school
        $parents = ParentModel::whereHas('children', function ($query) use ($manager) {
            $query->whereHas('class', function ($q) use ($manager) {
                $q->where('school_id', $manager->school_id);
            });
        })->with(['user', 'children.user', 'children.class'])->get();

        return view('manager.parents.index', compact('parents'));
    }

    /**
     * Show form to create new parent
     */
    public function create()
    {
        return view('manager.parents.create');
    }

    /**
     * Store new parent account
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Generate random password
        $password = Str::random(8);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'parent',
        ]);

        // Create parent profile
        ParentModel::create([
            'user_id' => $user->id,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()->route('manager.parents.index')
            ->with('success', 'Parent account created successfully!')
            ->with('generated_password', $password)
            ->with('parent_email', $validated['email']);
    }

    /**
     * Show form to link student to parent
     */
    public function linkStudent($parentId)
    {
        $parent = ParentModel::with(['user', 'children.user', 'children.class'])->findOrFail($parentId);
        $manager = auth()->user()->manager;

        // Get unlinked students from this school
        $availableStudents = Student::whereNull('parent_id')
            ->whereHas('class', function ($query) use ($manager) {
                $query->where('school_id', $manager->school_id);
            })
            ->with(['user', 'class'])
            ->get();

        return view('manager.parents.link-student', compact('parent', 'availableStudents'));
    }

    /**
     * Store student-parent link
     */
    public function storeLinkStudent(Request $request, $parentId)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $manager = auth()->user()->manager;
        $student = Student::with('class')->findOrFail($validated['student_id']);

        // Verify student belongs to manager's school
        if ($student->class->school_id !== $manager->school_id) {
            return back()->withErrors(['student_id' => 'This student does not belong to your school.']);
        }

        // Check if student is already linked
        if ($student->parent_id) {
            return back()->withErrors(['student_id' => 'This student is already linked to another parent.']);
        }

        // Link student to parent
        $student->parent_id = $parentId;
        $student->save();

        return redirect()->route('manager.parents.link-student', $parentId)
            ->with('success', 'Student linked successfully!');
    }

    /**
     * Unlink student from parent
     */
    public function unlinkStudent($parentId, $studentId)
    {
        $manager = auth()->user()->manager;
        $student = Student::with('class')->findOrFail($studentId);

        // Verify student belongs to manager's school
        if ($student->class->school_id !== $manager->school_id) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }

        // Verify student is linked to this parent
        if ($student->parent_id != $parentId) {
            return back()->withErrors(['error' => 'Student is not linked to this parent.']);
        }

        // Unlink
        $student->parent_id = null;
        $student->save();

        return back()->with('success', 'Student unlinked successfully!');
    }
}
