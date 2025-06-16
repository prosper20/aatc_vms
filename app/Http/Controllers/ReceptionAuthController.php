<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Receptionist;

class ReceptionAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.reception-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt authentication using the receptionist guard
        if (Auth::guard('receptionist')->attempt([
            'username' => $request->username,
            'password' => $request->password
        ], $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('reception.dashboard'));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('receptionist')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('reception.login');
    }
}
