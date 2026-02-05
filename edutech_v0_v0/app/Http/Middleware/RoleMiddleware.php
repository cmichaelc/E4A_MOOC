<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  The required role to access the route
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect to appropriate dashboard based on user's actual role
            return $this->redirectToDashboard($user->role);
        }

        return $next($request);
    }

    /**
     * Redirect user to their role-based dashboard
     */
    protected function redirectToDashboard($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.schools'),
            'manager' => redirect()->route('manager.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => redirect('/'),
        };
    }
}
