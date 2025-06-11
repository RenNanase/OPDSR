<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user-management.index', compact('users'));
    }

    public function create()
    {
        return view('user-management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'staff', // Default role for new users
        ]);

        return redirect()->route('user-management.index')
            ->with('status', 'User created successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('user-management.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('user-management.index')
            ->with('status', 'User deleted successfully.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'password123'; // Default password
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return redirect()->route('user-management.index')
            ->with('status', "Password has been reset to: {$newPassword}");
    }
}