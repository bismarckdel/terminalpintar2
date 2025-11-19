<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Handle login request (Inertia).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $credentials['email'];
        $password = $credentials['password'];

        // Try to find user by email or nama (username)
        $user = User::where('email', $login)
            ->orWhere('nama', $login)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return back()->withErrors([
                'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
        }

        // Login user using session (web guard)
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Redirect based on role
        if ($user->hasRole('admin')) {
            return redirect()->intended('/dashboardadmin');
        }

        // Default redirect
        return redirect()->intended('/');
    }

    /**
     * Logout user (Inertia)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
