<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use Illuminate\Http\Request;

class AcademicTermController extends Controller
{
    /**
     * Display list of all academic terms for the school
     */
    public function index()
    {
        $manager = auth()->user()->manager;
        $terms = AcademicTerm::where('school_id', $manager->school_id)
            ->orderBy('academic_year', 'desc')
            ->orderBy('order')
            ->get()
            ->groupBy('academic_year');

        return view('manager.terms.index', compact('terms'));
    }

    /**
     * Show form to create new term
     */
    public function create()
    {
        $manager = auth()->user()->manager;

        // Get existing academic years for dropdown
        $existingYears = AcademicTerm::where('school_id', $manager->school_id)
            ->distinct()
            ->pluck('academic_year');

        return view('manager.terms.create', compact('existingYears'));
    }

    /**
     * Store new academic term
     */
    public function store(Request $request)
    {
        $manager = auth()->user()->manager;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'order' => 'required|integer|min:1',
            'is_current' => 'boolean',
        ]);

        // Check for unique term name within academic year
        $exists = AcademicTerm::where('school_id', $manager->school_id)
            ->where('academic_year', $validated['academic_year'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'A term with this name already exists for this academic year.'])->withInput();
        }

        // If marking as current, unmark other current terms
        if ($request->has('is_current') && $request->is_current) {
            AcademicTerm::where('school_id', $manager->school_id)
                ->update(['is_current' => false]);
        }

        AcademicTerm::create([
            'school_id' => $manager->school_id,
            'name' => $validated['name'],
            'academic_year' => $validated['academic_year'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'order' => $validated['order'],
            'is_current' => $request->has('is_current') ? true : false,
        ]);

        return redirect()->route('manager.terms.index')
            ->with('success', 'Academic term created successfully!');
    }

    /**
     * Show edit form for term
     */
    public function edit($id)
    {
        $manager = auth()->user()->manager;
        $term = AcademicTerm::where('school_id', $manager->school_id)->findOrFail($id);

        return view('manager.terms.edit', compact('term'));
    }

    /**
     * Update academic term
     */
    public function update(Request $request, $id)
    {
        $manager = auth()->user()->manager;
        $term = AcademicTerm::where('school_id', $manager->school_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'order' => 'required|integer|min:1',
            'is_current' => 'boolean',
        ]);

        // Check for unique term name (excluding current term)
        $exists = AcademicTerm::where('school_id', $manager->school_id)
            ->where('academic_year', $term->academic_year)
            ->where('name', $validated['name'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'A term with this name already exists for this academic year.'])->withInput();
        }

        // If marking as current, unmark other current terms
        if ($request->has('is_current') && $request->is_current) {
            AcademicTerm::where('school_id', $manager->school_id)
                ->where('id', '!=', $id)
                ->update(['is_current' => false]);
        }

        $term->update([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'order' => $validated['order'],
            'is_current' => $request->has('is_current') ? true : false,
        ]);

        return redirect()->route('manager.terms.index')
            ->with('success', 'Academic term updated successfully!');
    }

    /**
     * Delete academic term
     */
    public function destroy($id)
    {
        $manager = auth()->user()->manager;
        $term = AcademicTerm::where('school_id', $manager->school_id)->findOrFail($id);

        // Prevent deletion if term has grades
        if ($term->grades()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete term with associated grades. Please remove or reassign grades first.']);
        }

        $term->delete();

        return redirect()->route('manager.terms.index')
            ->with('success', 'Academic term deleted successfully!');
    }

    /**
     * Set a term as the current active term
     */
    public function setCurrent($id)
    {
        $manager = auth()->user()->manager;
        $term = AcademicTerm::where('school_id', $manager->school_id)->findOrFail($id);

        // Unmark all other terms as current
        AcademicTerm::where('school_id', $manager->school_id)
            ->update(['is_current' => false]);

        // Mark this term as current
        $term->update(['is_current' => true]);

        return redirect()->route('manager.terms.index')
            ->with('success', "'{$term->name}' is now the current term!");
    }
}
