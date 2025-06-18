{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __("Security Manager Login") }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #333;
}

.login-container {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 100%;
    max-width: 400px;
    transition: transform 0.3s ease;
}

.login-container:hover {
    transform: translateY(-5px);
}

h2 {
    text-align: center;
    color: #007570;
    margin-bottom: 25px;
    font-weight: 600;
    font-size: 24px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: border 0.3s;
    box-sizing: border-box;
}

input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #07AF8B;
    outline: none;
    box-shadow: 0 0 0 3px rgba(7, 175, 139, 0.2);
}

.login-btn {
    background-color: #07AF8B;
    color: white;
    border: none;
    padding: 12px 20px;
    width: 100%;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.login-btn:hover {
    background-color: #007570;
}

.message {
    text-align: center;
    margin-top: 20px;
    padding: 10px;
    border-radius: 5px;
}

.error {
    background-color: #ffebee;
    color: #c62828;
}

.success {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.register-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
}

.register-link a {
    color: #07AF8B;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}

.forgot-password {
    text-align: right;
    margin-top: -15px;
    margin-bottom: 20px;
    font-size: 13px;
}

.forgot-password a {
    color: #7f8c8d;
    text-decoration: none;
}

.forgot-password a:hover {
    text-decoration: underline;
    color: #FFCA00;
}
.language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        /* @media (max-width: 768px) {
            .language-switcher {
                position: static;
                margin-bottom: 1rem;
                display: flex;
                justify-content: flex-end;
            }
        } */
    </style>
</head>
<body>
    <div class="language-switcher">
        @include('partials.language_switcher')
    </div>

<div class="login-container">
    <h2>{{ __("Security Manager Login") }}</h2>

    @if (session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('sm.login.submit') }}">
        @csrf
        <div class="form-group">
            <label for="email">{{ __("Email") }}</label>
            <input type="email" id="email" name="email" required placeholder="{{ __('Enter your email') }}">
        </div>
        <div class="form-group">
            <label for="password">{{ __("Password") }}</label>
            <input type="password" id="password" name="password" required placeholder="{{ __('Enter your password') }}">
        </div>
        <div class="forgot-password">
            <a href="{{ route('password.request') }}">{{ __("Forgot password?") }}</a>
        </div>
        <button type="submit" class="login-btn">{{ __("Login") }}</button>
    </form>


    {{-- <form method="POST" action="{{ route('cso.login.submit') }}">
        @csrf
        <div class="form-group">
            <label for="email">{{ __("Email") }}</label>
            <input type="email" id="email" name="email" required placeholder="{{ __('Enter your email') }}">
        </div>
        <div class="form-group">
            <label for="password">{{ __("Password") }}</label>
            <input type="password" id="password" name="password" required placeholder="{{ __('Enter your password') }}">
        </div>
        <div class="forgot-password">
            <a href="{{ route('password.request') }}">{{ __("Forgot password?") }}</a>
        </div>
        <button type="submit" class="login-btn">{{ __("Login") }}</button>
    </form> --

</div>

</body>
</html> --}}


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __("Security Manager Login") }}</title>
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

        /* .language-switcher {
            position: absolute;
            top: -3.5rem;
            right: 0;
            z-index: 10;
        } */

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

        @media (max-width: 640px) {
            .login-card {
                padding: 1.75rem;
            }

            /* .language-switcher {
                top: -3rem;
            } */
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

        /* @media (max-width: 768px) {
            .language-switcher {
                position: static;
                margin-bottom: 1rem;
                display: flex;
                justify-content: flex-end;
            }
        } */
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
                <h1>{{ __("Security Manager Portal") }}</h1>
                <p>{{ __("Sign in to access your dashboard") }}</p>
            </div>

            @if (session('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('sm.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __("Email Address") }}</label>
                    <div class="input-field">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" id="email" name="email" required
                               placeholder="{{ __('your@email.com') }}" autocomplete="email">
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
