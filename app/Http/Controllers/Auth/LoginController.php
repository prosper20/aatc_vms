<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OktaService;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    private $oktaService;

    public function __construct(OktaService $oktaService)
    {
        $this->oktaService = $oktaService;
    }

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

            // return $staff->profile_completed == 0
            //     ? redirect()->route('profile.edit')
            //     : redirect('/staff/dashboard');

            return redirect('/staff/dashboard');
        }

        return redirect()->route('login', ['error' => 1]);
    }

    public function logout(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // If this was an Okta user, also logout from Okta
        if ($staff && $staff->isOktaUser()) {
            $oktaLogoutUrl = "https://" . config('okta.domain') . "/oauth2/default/v1/logout?" . http_build_query([
                'post_logout_redirect_uri' => route('login')
            ]);
            return redirect($oktaLogoutUrl);
        }

        return redirect('/');
    }

    public function redirectToOkta()
    {
        $authUrl = $this->oktaService->getAuthorizationUrl();
        return redirect($authUrl);
    }

    public function handleOktaCallback(Request $request)
    {
        try {
            $code = $request->get('code');
            $state = $request->get('state');

            if (!$code) {
                return redirect()->route('login', ['error' => 1])
                    ->with('message', 'Authorization failed. Please try again.')
                    ->with('message_class', 'error');
            }

            // Exchange code for tokens
            $tokens = $this->oktaService->exchangeCodeForTokens($code, $state);

            // Get user info from Okta
            $userInfo = $this->oktaService->getUserInfo($tokens['access_token']);

            // Find or create staff user
            $staff = $this->findOrCreateStaff($userInfo);

            // Log the staff in
            Auth::guard('staff')->login($staff);
            session(['staff_id' => $staff->id, 'name' => $staff->name]);

            // Clear the state from session
            session()->forget('okta_state');

            // Check if profile needs completion
            // return $staff->profile_completed == 0
            //     ? redirect()->route('profile.edit')
            //     : redirect('/staff/dashboard');

            return redirect('/staff/dashboard');

        } catch (\Exception $e) {
            Log::error('Okta callback error: ' . $e->getMessage());
            return redirect()->route('login', ['error' => 1])
                ->with('message', 'Authentication failed. Please try again.')
                ->with('message_class', 'error');
        }
    }

    private function findOrCreateStaff($userInfo)
    {
        // Look for existing staff by email
        $staff = Staff::where('email', $userInfo['email'])->first();

        if (!$staff) {
            // Create new staff user
            $staff = Staff::create([
                'name' => $userInfo['name'] ?? $userInfo['preferred_username'] ?? $userInfo['email'],
                'email' => $userInfo['email'],
                'okta_id' => $userInfo['sub'],
                'profile_completed' => false, // They'll need to complete profile
                'password' => null, // No password for Okta users
            ]);
        } else {
            // Update existing staff with Okta ID if not set
            if (!$staff->okta_id) {
                $staff->update(['okta_id' => $userInfo['sub']]);
            }
        }

        return $staff;
    }
}

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Auth;

// use App\Models\Employee;

// class LoginController extends Controller
// {
//     public function showLoginForm(Request $request)
//     {
//         $error = $request->query('error') === '1' ? 'Invalid credentials.' : null;
//         return view('welcome', [
//             'message' => $error,
//             'message_class' => 'error'
//         ]);
//     }

//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         if (Auth::guard('staff')->attempt($credentials)) {
//             $staff = Auth::guard('staff')->user();
//             session(['staff_id' => $staff->id, 'name' => $staff->name]);

//             return $staff->profile_completed == 0
//                 ? redirect()->route('profile.edit')
//                 : redirect('/staff/dashboard');
//                 // : redirect('/home');
//         }

//         return redirect()->route('login', ['error' => 1]);
//     }

//     public function logout(Request $request)
//     {
//         Auth::guard('staff')->logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return redirect('/');
//     }
// }


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
