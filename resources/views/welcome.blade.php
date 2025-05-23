<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Abuja-AATC Visitor Management Portal</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    :root {
      --primary-green: #004225;
      --accent-green: #007f5f;
      --yellow: #ffc107;
      --bg-light: #f4f6f9;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-light);
    }

    /* Loader Spinner */
    #loader {
      position: fixed;
      inset: 0;
      background: #ffffff;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 6px solid #e3e3e3;
      border-top: 6px solid var(--accent-green);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .container {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    .left-side {
      position: relative;
      flex: 1;
      background: url('assets/afx.jpg') no-repeat center center;
      background-size: cover;
    }

    .left-side::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom right, rgba(0, 66, 37, 0.4), rgba(0, 127, 95, 0.4));
      z-index: 1;
    }

    .overlay-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  color: #fff;
  text-align: center;
  animation: fadeInText 2s ease-out forwards;
  opacity: 0;
}

.overlay-text h1 {
  font-size: 42px;
  font-weight: 700;
  margin: 0;
  line-height: 1.2;
  text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
}

.overlay-text h2 {
  font-size: 20px;
  font-weight: 400;
  margin-top: 12px;
  color: #e8e8e8;
  letter-spacing: 1px;
  text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4);
}

@keyframes fadeInText {
  from {
    transform: translate(-50%, -60%);
    opacity: 0;
  }
  to {
    transform: translate(-50%, -50%);
    opacity: 1;
  }
}

@media (max-width: 768px) {
  .overlay-text h1 {
    font-size: 30px;
  }

  .overlay-text h2 {
    font-size: 16px;
  }
}



    .right-side {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f4f6f9, #e9f7f5);
      animation: fadeIn 1.2s ease-in-out both;
      position: relative;
      z-index: 2;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-wrapper {
      backdrop-filter: blur(16px);
      background-color: rgba(255, 255, 255, 0.65);
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 400px;
      padding: 40px 30px;
      transition: all 0.4s ease;
    }

    .logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo img {
      width: 140px;
      height: auto;
    }

    h2 {
      font-size: 24px;
      font-weight: 600;
      color: var(--primary-green);
      text-align: center;
      margin-bottom: 25px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    label {
      display: block;
      font-size: 14px;
      color: #444;
      margin-bottom: 6px;
    }

    .input-icon {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #888;
      font-size: 14px;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 15px 12px 38px;
      font-size: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      transition: all 0.3s ease;
      background-color: #fff;
    }

    input:focus {
      outline: none;
      border-color: var(--accent-green);
      box-shadow: 0 0 0 3px rgba(0, 127, 95, 0.15);
      transform: scale(1.02);
    }

    .forgot-password {
      text-align: right;
      font-size: 13px;
      margin-top: -10px;
      margin-bottom: 20px;
    }

    .forgot-password a {
      text-decoration: none;
      color: #666;
      transition: color 0.3s ease;
    }

    .forgot-password a:hover {
      color: var(--yellow);
    }

    button[type="submit"] {
      background-color: var(--accent-green);
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: var(--primary-green);
    }

    @media (max-width: 991px) {
      .container {
        flex-direction: column;
      }

      .left-side {
        height: 35vh;
      }

      .right-side {
        height: 65vh;
        padding: 20px;
      }

      .login-wrapper {
        max-width: 90%;
      }
    }

      #toast {
    visibility: hidden;
    min-width: 250px;
    background-color: #c62828;
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    opacity: 0;
    transition: opacity 0.4s ease, transform 0.4s ease;

  }

  #toast.show {
    visibility: visible;
    opacity: 1;
    transform: translateY(0);
  }
  .language-switcher-container {
  position: absolute;
  top: 20px;
  right: 20px;
  z-index: 10;
}

  </style>
</head>
<body>

  <!-- Page Loader -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <div class="container" style="display: none;" id="main-content">
    <div class="language-switcher-container">
        @include('partials/language_switcher')
    </div>
    <div class="left-side">
        <div class="overlay-text">
          <h1>{{__(('Abuja-AATC'))}}</h1>
          <h2>{{__('Visitor Management Portal') }}</h2>
        </div>
      </div>


    <div class="right-side">
    <div id="toast">Invalid credentials.</div>

      <div class="login-wrapper">
        <div class="logo">
          <a href="index.html">
            <img src="assets/logo-green-yellow.png" alt="Company Logo" />
          </a>
        </div>



        <h2>{{ __('Login') }}</h2>

        <form method="POST" action="employee_login.php">
          <div class="form-group">
            <label for="email"></label>
            <i class="fas fa-user input-icon"></i>
            <input type="email" name="email" id="email" required placeholder={{__("Staff ID")}}>
          </div>

          <div class="form-group">
            <label for="password"></label>
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password" required placeholder={{__("Password")}}>
          </div>

          <div class="forgot-password">
            <a href="password_reset.html">{{__('Contact support')}}</a>
          </div>

          <button type="submit">{{__('Login')}}</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Fade-in after load -->
  <script>
    window.addEventListener("load", function () {
      document.getElementById("loader").style.display = "none";
      document.getElementById("main-content").style.display = "flex";
    });


  // Check if credetials error
  const params = new URLSearchParams(window.location.search);
  if (params.get("error") === "1") {
    const toast = document.getElementById("toast");
    toast.classList.add("show");
    setTimeout(() => {
      toast.classList.remove("show");
    }, 3000); // Hide after 3 seconds
    window.history.replaceState({}, document.title, window.location.pathname);
  }
  </script>

</body>
</html>



{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AATC VMS</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">VMS</a>
            <div class="d-flex">
                @include('partials/language_switcher')
                @if (Route::has('login'))
                    <div class="ms-auto">
                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-primary">Home</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <div class="container text-center mt-5">
        <h1>{!! __('Welcome to AATC VMS') !!}</h1>
        <p class="lead">{!! __('This is an initial setup of the Laravel environment') !!}</p>
    </div>
</body>

</html> --}}
