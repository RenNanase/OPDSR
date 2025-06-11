<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard based on their role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('admin-dashboard');
        } elseif ($user->isStaff()) {
            return view('staff-dashboard');
        }

        // Default fallback if a user logs in without a defined role (shouldn't happen with our seeder)
        return view('dashboard'); // Or redirect to a generic error/unauthorized page
    }
}