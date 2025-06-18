<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $staff->profile_completed ? __('Update Profile') : __('Complete Your Profile') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-green: #004225;
      --accent-green: #007f5f;
      --yellow: #ffc107;
      --bg-light: #f4f6f9;
      --error-red: #dc3545;
      --success-green: #28a745;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-light);
      padding: 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .profile-container {
      width: 100%;
      max-width: 500px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      padding: 2rem;
    }

    .logo {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .logo img {
      height: 60px;
    }

    h1 {
      color: var(--primary-green);
      text-align: center;
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.25rem;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      color: #444;
      font-weight: 500;
      font-size: 0.9rem;
    }

    input, select {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
    }

    input:focus, select:focus {
      outline: none;
      border-color: var(--accent-green);
      box-shadow: 0 0 0 3px rgba(0, 127, 95, 0.15);
    }

    .btn {
      background-color: var(--accent-green);
      color: white;
      border: none;
      padding: 0.75rem;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      width: 100%;
      transition: background 0.3s ease;
      margin-top: 0.5rem;
    }

    .btn:hover {
      background-color: var(--primary-green);
    }

    .error-message {
      color: var(--error-red);
      font-size: 0.875rem;
      margin-top: 0.25rem;
      display: block;
    }

    .success-message {
      color: var(--success-green);
      font-size: 0.875rem;
      margin-top: 0.25rem;
      display: block;
      text-align: center;
      margin-bottom: 1rem;
    }

    .required:after {
      content: " *";
      color: var(--error-red);
    }

    .password-toggle {
      position: absolute;
      right: 10px;
      top: 38px;
      cursor: pointer;
      color: #777;
    }

    .section-title {
      font-size: 1.1rem;
      color: var(--primary-green);
      margin: 1.5rem 0 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #eee;
    }

    .phone-input-group {
      display: flex;
      gap: 0.5rem;
    }

    .country-code-select {
      width: 120px;
    }

    .phone-number-input {
      flex: 1;
    }
  </style>
</head>
<body>
  <div class="profile-container">
    <div class="logo">
      <a href="{{ route('home') }}">
        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Company Logo">
      </a>
    </div>

    <h1>{{ $staff->profile_completed ? __('Update Your Profile') : __('Complete Your Profile') }}</h1>
    @unless($staff->profile_completed)
      <p style="text-align: center; margin-bottom: 1.5rem; color: #666;">'Please complete your profile information to continue'</p>
    @endunless

    {{-- <h1>{{ __('Update Your Profile')}}</h1>
    <p style="text-align: center; margin-bottom: 1.5rem; color: #666;">{{ __('Please complete your profile information to continue')}}</p> --}}

    @if ($errors->any())
      <div class="error-message" style="text-align: center; margin-bottom: 1rem;">
        @foreach ($errors->all() as $error)
          {{ $error }}<br>
        @endforeach
      </div>
    @endif

    @if (session('success'))
      <div class="success-message">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      <div class="section-title">{{ __('Basic Information')}}</div>

      <div class="form-group">
        <label for="name" class="required">{{ __('Full Name')}}</label>
        <input type="text" id="name" name="name" value="{{ old('name', $staff->name) }}" required>
      </div>

      <div class="form-group">
        <label for="organization" class="required">{{ __('Organization')}}</label>
        <input type="text" id="organization" name="organization" value="{{ old('organization', $staff->organization) }}" required>
      </div>

      <div class="form-group">
        <label for="designation" class="required">{{ __('Designation')}}</label>
        <input type="text" id="designation" name="designation" value="{{ old('designation', $staff->designation) }}" required>
      </div>

      <div class="form-group">
        <label for="email" class="required">{{ __('Email')}}</label>
        <input type="email" id="email" name="email" value="{{ $staff->email }}" required readonly style="background-color: #f4f6f9;">
      </div>

      <div class="form-group">
        <label for="phone" class="required">{{ __('Phone Number')}}</label>
        <div class="phone-input-group">
          <select id="country_code" name="country_code" class="country-code-select" required>
            @foreach(config('country_codes') as $code => $country)
              <option value="{{ $code }}" {{ old('country_code', $staff->country_code) == $code ? 'selected' : '' }}>
                {{ $country }}
              </option>
            @endforeach
          </select>
          <input type="tel" id="phone" name="phone" class="phone-number-input"
                 pattern="^[0-9]{6,15}$" title="Enter a valid phone number (6-15 digits)"
                 value="{{ old('phone', $staff->phone) }}" required>
        </div>
      </div>

      @unless($staff->profile_completed)
        <div class="section-title">{{ __('Set Password') }}</div>

        <div class="form-group">
          <label for="new_password" class="required">{{ __('Password') }}</label>
          <input type="password" id="new_password" name="new_password" required>
          <span class="password-toggle" onclick="togglePassword('new_password')">
            <i class="fas fa-eye"></i>
          </span>
        </div>

        <div class="form-group">
          <label for="confirm_password" class="required">{{ __('Confirm Password') }}</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
          <span class="password-toggle" onclick="togglePassword('confirm_password')">
            <i class="fas fa-eye"></i>
          </span>
        </div>
      @endunless

      <button type="submit" class="btn">
        {{ $staff->profile_completed ? __('Update Profile') : __('Complete Profile') }}
      </button>

      {{-- <div class="section-title">{{ __('Change Password (Optional)')}}</div>

      <div class="form-group">
        <label for="new_password">{{ __('New Password')}}</label>
        <input type="password" id="new_password" name="new_password">
        <span class="password-toggle" onclick="togglePassword('new_password')">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <div class="form-group">
        <label for="confirm_password">{{ __('Confirm Password')}}</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <span class="password-toggle" onclick="togglePassword('confirm_password')">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <button type="submit" class="btn">{{ __('Update Profile')}}</button> --}}
    </form>
  </div>

  <script>
    function togglePassword(id) {
      const input = document.getElementById(id);
      const icon = input.nextElementSibling.querySelector('i');

      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }

    // Prevent non-numeric input in phone field
    document.getElementById('phone').addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  </script>
</body>
</html>
