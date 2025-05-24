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

        if (Auth::guard('employee')->attempt($credentials)) {
            $employee = Auth::guard('employee')->user();
            session(['employee_id' => $employee->id, 'name' => $employee->name]);

            return $employee->profile_completed == 0
                ? redirect('/update-profile')
                : redirect('/home');
        }

        // $employee = Employee::where('email', $credentials['email'])->first();

        // if ($employee && Hash::check($credentials['password'], $employee->password)) {
        //     // session(['employee_id' => $employee->id, 'name' => $employee->name]);

        //     if (Auth::guard('web')->attempt($credentials)) {
        //         $employee = Auth::user();
        //         session(['employee_id' => $employee->id, 'name' => $employee->name]);

        //         return $employee->profile_completed == 0
        //             ? redirect('/update-profile')
        //             : redirect('/home');
        //     }

        //     return $employee->profile_completed == 0
        //         ? redirect('/update-profile')
        //         : redirect('/home');
        // }

        return redirect()->route('login', ['error' => 1]);
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
