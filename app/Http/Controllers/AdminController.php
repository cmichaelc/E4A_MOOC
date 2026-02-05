<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Mail\SchoolApproved;
use App\Mail\SchoolRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function schools()
    {
        $schools = School::with('manager')->where('status', '!=', 'Pending')->get();
        return view('admin.schools', compact('schools'));
    }

    public function createSchool()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.create-school', compact('managers'));
    }

    public function storeSchool(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Pending',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        School::create($validated);

        return redirect()->route('admin.schools')->with('success', 'School created successfully.');
    }

    /**
     * Show pending school registrations
     */
    public function pendingSchools()
    {
        $pendingSchools = School::with('manager')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        return view('admin.pending-schools', compact('pendingSchools'));
    }

    /**
     * Approve a school registration
     */
    public function approveSchool(School $school)
    {
        if ($school->status !== 'Pending') {
            return redirect()->back()->with('error', 'This school is not pending approval.');
        }

        $school->update([
            'status' => 'Active',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Send approval email to manager
        try {
            Mail::to($school->manager->email)->send(new SchoolApproved($school));
        } catch (\Exception $e) {
            logger()->error('Failed to send school approval email: ' . $e->getMessage());
        }

        return redirect()->route('admin.schools.pending')
            ->with('success', "School '{$school->name}' has been approved!");
    }

    /**
     * Reject a school registration
     */
    public function rejectSchool(Request $request, School $school)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($school->status !== 'Pending') {
            return redirect()->back()->with('error', 'This school is not pending approval.');
        }

        $school->update([
            'status' => 'Rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Send rejection email to manager
        try {
            Mail::to($school->manager->email)->send(new SchoolRejected($school));
        } catch (\Exception $e) {
            logger()->error('Failed to send school rejection email: ' . $e->getMessage());
        }

        return redirect()->route('admin.schools.pending')
            ->with('success', "School '{$school->name}' has been rejected. Manager can edit and resubmit.");
    }
}
