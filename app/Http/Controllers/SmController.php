<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('sm')->check()) {
            return redirect()->route('sm.dashboard');
        }
        return view('auth.sm-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::guard('sm')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('sm.dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        Auth::guard('sm')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sm.login')->with('error', 'You have been logged out.');
    }

    public function dashboard()
    {
        if (!Auth::guard('sm')->check()) {
            return redirect()->route('sm.login')->with('error', 'Please login first.');
        }

        return view('sm_dashboard');
    }
}
