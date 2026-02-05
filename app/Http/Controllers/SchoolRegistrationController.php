<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Mail\SchoolRegistrationConfirmation;
use App\Mail\SchoolRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SchoolRegistrationController extends Controller
{
    /**
     * Show the school registration form
     */
    public function create()
    {
        return view('register.school');
    }

    /**
     * Handle school registration submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // School information
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string|max:500',
            'school_phone' => 'required|string|max:20',
            'school_email' => 'required|email|unique:schools,name', // Temporary validation
            'school_type' => 'required|in:Primary,Secondary,High School,University',

            // Manager information
            'manager_name' => 'required|string|max:255',
            'manager_email' => 'required|email|unique:users,email',
            'manager_phone' => 'required|string|max:20',

            // Terms
            'terms' => 'required|accepted',
        ]);

        // Generate password for manager
        $password = Str::random(10);

        // Create manager user account
        $manager = User::create([
            'name' => $validated['manager_name'],
            'email' => $validated['manager_email'],
            'password' => Hash::make($password),
            'role' => 'manager',
            'status' => 'active',
        ]);

        // Create school with Pending status
        $school = School::create([
            'name' => $validated['school_name'],
            'address' => $validated['school_address'],
            'status' => 'Pending',
            'manager_id' => $manager->id,
        ]);

        // Send confirmation email to manager with credentials
        try {
            Mail::to($manager->email)->send(new SchoolRegistrationConfirmation($manager, $school, $password));
        } catch (\Exception $e) {
            // Log error but don't fail registration
            logger()->error('Failed to send registration confirmation email: ' . $e->getMessage());
        }

        // Send notification to admin
        try {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                Mail::to($admin->email)->send(new SchoolRegistrationNotification($school, $manager));
            }
        } catch (\Exception $e) {
            logger()->error('Failed to send admin notification email: ' . $e->getMessage());
        }

        return redirect()->route('register.school.success')
            ->with('success', 'School registration submitted successfully! Check your email for login credentials.');
    }

    /**
     * Show success page after registration
     */
    public function success()
    {
        return view('register.school-success');
    }
}
