<!DOCTYPE html>
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
</html>
