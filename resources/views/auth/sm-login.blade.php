<!DOCTYPE html>
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
    </form> --}}

</div>

</body>
</html>
