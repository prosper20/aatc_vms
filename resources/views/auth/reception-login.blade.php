{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{__("Receptionist Login")}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #e6f7f4;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            color: #007570;
        }
        .btn-custom {
            background-color: #007570;
            color: white;
        }
        .btn-custom:hover {
            background-color: #07AF8B;
        }
        .alert-custom {
            background-color: #f72585;
            color: white;
        }
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        @media (max-width: 768px) {
            .language-switcher {
                position: static;
                margin-bottom: 1rem;
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>

    <div class="language-switcher">
        @include('partials.language_switcher')
    </div>

<div class="login-container">
    <h2>{{__("Receptionist Login")}}</h2>

    @if(session('error'))
        <div class="alert alert-custom text-center">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('reception.login.submit') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">{{__("Username")}}</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">{{__("Password")}}</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">{{__("Login")}}</button>
    </form>
</div>

</body>
</html> --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __("Receptionist Login") }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #07AF8B;
            --primary-dark: #007570;
            --accent: #FFCA00;
            --light-bg: #f8fafc;
            --text: #334155;
            --text-light: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-bg);
            display: grid;
            place-items: center;
            min-height: 100vh;
            color: var(--text);
            line-height: 1.5;
            padding: 1rem;
            background-image:
                radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 0.1) 0, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 0.1) 0, transparent 50%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 28rem;
            position: relative;
        }

        .login-card {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                        0 8px 10px -6px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                        0 10px 10px -6px rgba(0, 0, 0, 0.04);
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .logo img {
            height: 3.5rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-light);
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .input-field {
            position: relative;
        }

        .input-field input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
            background-color: #f8fafc;
        }

        .input-field input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 175, 139, 0.15);
            background-color: white;
        }

        .input-field .icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .input-field input:focus + .icon {
            color: var(--primary);
        }

        .forgot-password {
            text-align: right;
            margin-top: -0.75rem;
            margin-bottom: 1.5rem;
        }

        .forgot-password a {
            color: var(--text-light);
            font-size: 0.8125rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-password a:hover {
            color: var(--primary-dark);
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .message {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            text-align: center;
        }

        .error {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        @media (max-width: 768px) {
            .language-switcher {
                top: 5px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="language-switcher">
        @include('partials.language_switcher')
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo">
                <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="AATC-VMS Logo">
            </div>

            <div class="login-header">
                <h1>{{ __("Receptionist Portal") }}</h1>
                <p>{{ __("Sign in to access your dashboard") }}</p>
            </div>

            @if (session('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('reception.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="username">{{ __("Username") }}</label>
                    <div class="input-field">
                        <i class="fas fa-user icon"></i>
                        <input type="text" id="username" name="username" required
                               placeholder="{{ __('Enter your username') }}" autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">{{ __("Password") }}</label>
                    <div class="input-field">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" id="password" name="password" required
                               placeholder="{{ __('••••••••') }}" autocomplete="current-password">
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">{{ __("Forgot password?") }}</a>
                </div>

                <button type="submit" class="login-btn">
                    {{ __("Sign In") }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>
