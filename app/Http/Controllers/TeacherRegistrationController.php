<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherRegistrationController extends Controller
{
    /**
     * Show the teacher registration form
     */
    public function create()
    {
        return view('register.teacher');
    }

    /**
     * Handle teacher registration submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'terms' => 'required|accepted',
        ]);

        // Generate password for teacher
        $password = Str::random(10);

        // Create teacher user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'teacher',
            'status' => 'active',
        ]);

        // Create teacher profile
        Teacher::create([
            'user_id' => $user->id,
            'specialization' => $validated['specialization'],
            'phone' => $validated['phone'],
        ]);

        // Store password in session to display on success page
        session(['teacher_password' => $password, 'teacher_email' => $user->email]);

        return redirect()->route('register.teacher.success')
            ->with('success', 'Teacher registration successful!');
    }

    /**
     * Show success page after registration
     */
    public function success()
    {
        if (!session('teacher_password')) {
            return redirect()->route('register.teacher')
                ->with('error', 'No registration data found.');
        }

        $email = session('teacher_email');
        $password = session('teacher_password');

        // Clear the session data after displaying
        session()->forget(['teacher_password', 'teacher_email']);

        return view('register.teacher-success', compact('email', 'password'));
    }
}
