<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Models\Employee;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $error = $request->query('error') === '1' ? 'Invalid credentials.' : null;
        return view('welcome', [
            'message' => $error,
            'message_class' => 'error'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('staff')->attempt($credentials)) {
            $staff = Auth::guard('staff')->user();
            session(['staff_id' => $staff->id, 'name' => $staff->name]);

            return $staff->profile_completed == 0
                ? redirect()->route('profile.edit')
                : redirect('/staff/dashboard');
                // : redirect('/home');
        }

        return redirect()->route('login', ['error' => 1]);
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}


// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Hash;
// use App\Models\Employee;

// class LoginController extends Controller
// {
//     protected $redirectTo = '/home';

//     public function __construct()
//     {
//         $this->middleware('guest')->except('logout');
//     }

//     public function showLoginForm(Request $request)
//     {
//         $error = $request->query('error') === '1' ? 'Invalid credentials.' : null;
//         return view('auth.login', ['message' => $error, 'message_class' => 'error']);
//     }

//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         $employee = Employee::where('email', $credentials['email'])->first();

//         if ($employee && Hash::check($credentials['password'], $employee->password)) {
//             session(['employee_id' => $employee->id]);
//             session(['name' => $employee->name]);

//             return $employee->profile_completed == 0
//                 ? redirect()->to('/update-profile')
//                 : redirect()->to('/staff-dashboard');
//         }

//         return redirect()->route('login', ['error' => 1]);
//     }
// }
