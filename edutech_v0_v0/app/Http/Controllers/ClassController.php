<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\School;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Show all classes for manager's school
     */
    public function index()
    {
        $manager = auth()->user()->manager;

        if (!$manager) {
            return redirect()->back()->with('error', 'Manager profile not found.');
        }

        $classes = ClassModel::where('school_id', $manager->school_id)
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('manager.classes.index', compact('classes'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('manager.classes.create');
    }

    /**
     * Store new class
     */
    public function store(Request $request)
    {
        $manager = auth()->user()->manager;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:100',
        ]);

        ClassModel::create([
            'school_id' => $manager->school_id,
            'name' => $validated['name'],
            'capacity' => $validated['capacity'] ?? null,
            'academic_year' => date('Y') . '-' . (date('Y') + 1), // e.g., "2025-2026"
        ]);

        return redirect()->route('manager.classes.index')
            ->with('success', 'Class created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $manager = auth()->user()->manager;

        $class = ClassModel::where('school_id', $manager->school_id)
            ->findOrFail($id);

        return view('manager.classes.edit', compact('class'));
    }

    /**
     * Update class
     */
    public function update(Request $request, $id)
    {
        $manager = auth()->user()->manager;

        $class = ClassModel::where('school_id', $manager->school_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1|max:100',
        ]);

        $class->update($validated);

        return redirect()->route('manager.classes.index')
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Delete class
     */
    public function destroy($id)
    {
        $manager = auth()->user()->manager;

        $class = ClassModel::where('school_id', $manager->school_id)
            ->findOrFail($id);

        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class with students. Please transfer students first.');
        }

        $class->delete();

        return redirect()->route('manager.classes.index')
            ->with('success', 'Class deleted successfully!');
    }
}
