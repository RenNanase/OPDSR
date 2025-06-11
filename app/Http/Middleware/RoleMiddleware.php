<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            // User is not logged in, redirect to login page
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if the user has the required role
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        if ($role === 'staff' && !$user->isStaff()) {
            abort(403, 'Unauthorized. Staff access required.');
        }

        // If the role is neither admin nor staff, or if it's an unrecognized role,
        // or if a different role is passed, deny access by default unless specific
        // logic is added here. For now, if the role isn't explicitly matched, it will deny.
        // You might want to adjust this based on how specific your roles are.
        if ($role !== 'admin' && $role !== 'staff') {
             // For now, if a non-defined role is requested, deny access
             // Or you can decide to allow it if no specific check is needed.
             // For safety, let's keep it denying.
             abort(403, 'Unauthorized. Invalid role specified.');
        }


        return $next($request);
    }
}