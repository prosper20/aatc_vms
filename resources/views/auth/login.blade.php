@extends('layouts.app')

@push('styles')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa, #e0f7f4);
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
        color: #333;
    }

    .logo {
        margin-top: 40px;
        margin-bottom: 20px;
        text-align: center;
    }

    .logo img {
        width: 150px;
        height: auto;
        transition: transform 0.3s ease;
    }

    .logo img:hover {
        transform: scale(1.05);
    }

    .login-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 100%;
        max-width: 400px;
        transition: transform 0.3s ease;
        margin-bottom: 40px;
    }

    .login-container:hover {
        transform: translateY(-5px);
    }

    h2 {
        text-align: center;
        color: #007570;
        margin-bottom: 25px;
        font-weight: 600;
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
        color: #777;
        text-decoration: none;
    }

    .forgot-password a:hover {
        text-decoration: underline;
        color: #FFCA00;
    }
</style>
@endpush

@section('content')
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Company Logo">
        </a>
    </div>

    <div class="login-container">
        <h2>Welcome, Please Login</h2>

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <div class="forgot-password">
                <a href="{{ url('password_reset.html') }}">Forgot password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
@endsection


{{-- @extends('layouts.app')
@section('content')

<div class="logo">
    <a href="{{ url('/') }}">
        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Company Logo">
    </a>
</div>

<div class="login-container">
    <h2>Welcome, Please Login</h2>
    @if (!empty($message))
        <div class="message {{ $message_class }}">
            {{ $message }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>

        <div class="forgot-password">
            <a href="{{ url('password_reset.html') }}">Forgot password?</a>
        </div>

        <button type="submit" class="login-btn">Login</button>
    </form>
</div>

@endsection --}}


{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
