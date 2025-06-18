<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Visitor Registration') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #07AF8B;
            --accent: #FFCA00;
            --deep: #007570;
            --bg: #f4f6f8;
            --text-dark: #1f2d3d;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
        }

        .header-bar {
            background-color: var(--deep);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-bar img {
            height: 40px;
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 10px rgba(0,0,0,0.05);
        }

        .guest-form {
            background-color: white;
            border-left: 5px solid var(--accent);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .btn-custom {
            background-color: var(--deep);
            color: white;
        }

        .btn-custom:hover {
            background-color: var(--primary);
        }

        .success-message {
            border-left: 5px solid #28a745;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background-color: #d4edda;
        }

        .error-message {
            border-left: 5px solid #dc3545;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background-color: #f8d7da;
        }

        .profile-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #14532d;
            font-weight: bold;
            font-size: 16px;
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .floating-back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: var(--deep);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .floating-back-btn:hover {
            background: var(--primary);
            transform: translateX(-3px);
        }

        .import-sample {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .modal-body ul {
            padding-left: 20px;
        }

        .modal-body li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <div class="d-flex align-items-center">
            <a href="{{ route('home') }}" class="me-3">
                <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="{{ __('Logo') }}" style="height: 40px;">
            </a>

            @include('partials.language_switcher')
        </div>

        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center me-3">
                @if(auth('staff')->user()->profile_completed)
                    <div class="profile-placeholder me-2" style="background-color: #14532d; color: white; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr(auth('staff')->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="employee-name">{{ __('Welcome, :name', ['name' => auth('staff')->user()->name ?? __('Staff')]) }}</div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
{{-- <div class="header-bar">
    <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="{{ __('Logo') }}">
    <a href="{{ route('home') }}" class="btn btn-outline-light me-2" style="margin-right: 10px;">
        <i class="bi bi-arrow-left"></i> {{ __('Back to Dashboard') }}
    </a>

    <div class="employee-name">{{ __('Welcome, :name', ['name' => $employee_name ?? __('Employee')]) }}</div>
    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
    </form>
</div> --}}

<div class="container">
    <h2 class="mb-4">{{ __('Register Visitors') }}</h2>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    @if(session('import_message'))
        <div class="{{ str_contains(session('import_message'), __('Error')) ? 'error-message' : 'success-message' }}">
            {{ session('import_message') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-message">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('visitors.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="guest-forms">
            <div class="guest-form">
                <h5>{{ __('Visitor 1') }}</h5>

                <!-- Section 1: Email Lookup -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">{{ __('Visitor Email') }}</label>
                        <div class="input-group">
                            <input type="email" name="guests[0][email]" class="form-control visitor-email"
                                   value="{{ old('guests.0.email') }}" required>
                            <button class="btn btn-outline-secondary lookup-btn" type="button">
                                <i class="bi bi-search"></i> {{ __('Check Visitor') }}
                            </button>
                        </div>
                        <div class="visitor-status mt-2" style="display:none;">
                            <span class="badge bg-success existing-visitor-badge" style="display:none;">
                                <i class="bi bi-check-circle"></i> {{ __('Existing visitor found') }}
                            </span>
                            <span class="badge bg-info new-visitor-badge" style="display:none;">
                                <i class="bi bi-person-plus"></i> {{ __('New visitor will be registered') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Visitor Info -->
                <div class="visitor-details">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Full Name') }}</label>
                            <input type="text" name="guests[0][name]" class="form-control"
                                   value="{{ old('guests.0.name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Host Name') }}</label>
                            <input type="text" name="guests[0][host_name]" class="form-control bg-light"
                                   value="{{ $employee_name ?? __('Employee') }}" readonly>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">{{ __('Phone') }}</label>
                            <input type="tel" name="guests[0][phone]" class="form-control"
                                   placeholder="+2341234567890" value="{{ old('guests.0.phone') }}" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">{{ __('Organization') }}</label>
                            <input type="text" name="guests[0][organization]" class="form-control"
                                   value="{{ old('guests.0.organization') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Visit Info -->
                <div class="visit-details">
                    <div class="row mb-3">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">{{ __('Visit Date') }}</label>
                            <input type="date" name="guests[0][visit_date]" class="form-control"
                                   value="{{ old('guests.0.visit_date') }}" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">{{ __('Time of Visit') }}</label>
                            <input type="time" name="guests[0][time_of_visit]" class="form-control"
                                   value="{{ old('guests.0.time_of_visit') }}" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">{{ __('Floor of Visit') }}</label>
                            <select name="guests[0][floor_of_visit]" class="form-control" required>
                                <option value="">{{ __('Select Floor') }}</option>
                                <option value="Ground Floor" {{ old('guests.0.floor_of_visit') == 'Ground Floor' ? 'selected' : '' }}>{{ __('Ground Floor') }}</option>
                                <option value="Mezzanine" {{ old('guests.0.floor_of_visit') == 'Mezzanine' ? 'selected' : '' }}>{{ __('Mezzanine') }}</option>
                                @foreach(range(1, 9) as $floor)
                                    <option value="Floor {{ $floor }}" {{ old('guests.0.floor_of_visit') == "Floor $floor" ? 'selected' : '' }}>{{ __('Floor') }} {{ $floor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">{{ __('Reason for Visit') }}</label>
                            <textarea name="guests[0][reason]" class="form-control" rows="2" required>{{ old('guests.0.reason') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-primary me-2" onclick="addGuestForm()">
                <i class="bi bi-plus-circle"></i> {{ __('Add Another Visitor') }}
            </button>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet"></i> {{ __('Import Visitors') }}
            </button>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-custom btn-lg px-5">
                <i class="bi bi-send-check"></i> {{ __('Submit Visitor Requests') }}
            </button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle lookup for existing guests
        document.querySelectorAll('.lookup-btn').forEach(button => {
            button.addEventListener('click', function() {
                const formGroup = this.closest('.guest-form');
                const emailInput = formGroup.querySelector('.visitor-email');
                const email = emailInput.value;

                if (!email) {
                    alert('{{ __("Please enter an email address") }}');
                    return;
                }

                fetch(`/api/visitors/lookup?email=${encodeURIComponent(email)}`)
                    .then(res => res.json())
                    .then(data => {
                        const statusDiv = formGroup.querySelector('.visitor-status');
                        const existingBadge = formGroup.querySelector('.existing-visitor-badge');
                        const newBadge = formGroup.querySelector('.new-visitor-badge');

                        statusDiv.style.display = 'block';

                        if (data.exists) {
                            // Existing visitor found
                            existingBadge.style.display = 'inline-block';
                            newBadge.style.display = 'none';

                            // Autofill visitor details
                            const nameInput = formGroup.querySelector('input[name$="[name]"]');
                            const phoneInput = formGroup.querySelector('input[name$="[phone]"]');
                            const orgInput = formGroup.querySelector('input[name$="[organization]"]');

                            if (nameInput) nameInput.value = data.visitor.name || '';
                            if (phoneInput) phoneInput.value = data.visitor.phone || '';
                            if (orgInput) orgInput.value = data.visitor.organization || '';

                            // Disable fields (optional)
                            // nameInput.readOnly = true;
                            // phoneInput.readOnly = true;
                            // orgInput.readOnly = true;
                        } else {
                            // New visitor
                            existingBadge.style.display = 'none';
                            newBadge.style.display = 'inline-block';

                            // Enable fields (if they were disabled)
                            const inputs = formGroup.querySelectorAll('.visitor-details input');
                            inputs.forEach(input => input.readOnly = false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('{{ __("An error occurred while checking visitor status") }}');
                    });
            });
        });

        // Also trigger lookup when email field loses focus
        document.querySelectorAll('.visitor-email').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value) {
                    this.closest('.guest-form').querySelector('.lookup-btn').click();
                }
            });
        });
    });

    function addGuestForm() {
        const container = document.getElementById('guest-forms');
        const guestCount = container.querySelectorAll('.guest-form').length;
        const newGuestForm = container.querySelector('.guest-form').cloneNode(true);

        // Update indexes in names and reset values
        newGuestForm.innerHTML = newGuestForm.innerHTML.replace(/guests\[0\]/g, `guests[${guestCount}]`);

        // Update the visitor number
        const header = newGuestForm.querySelector('h5');
        if (header) header.textContent = `{{ __('Visitor') }} ${guestCount + 1}`;

        // Reset values
        const inputs = newGuestForm.querySelectorAll('input:not([name$="[host_name]"])');
        inputs.forEach(input => {
            if (input.type !== 'button' && input.type !== 'submit') {
                input.value = '';
            }
        });

        // Reset selects and textareas
        const selects = newGuestForm.querySelectorAll('select');
        selects.forEach(select => select.selectedIndex = 0);

        const textareas = newGuestForm.querySelectorAll('textarea');
        textareas.forEach(textarea => textarea.value = '');

        // Reset status badges
        const statusDiv = newGuestForm.querySelector('.visitor-status');
        if (statusDiv) statusDiv.style.display = 'none';

        const badges = newGuestForm.querySelectorAll('.existing-visitor-badge, .new-visitor-badge');
        badges.forEach(badge => badge.style.display = 'none');

        container.appendChild(newGuestForm);

        // Add event listeners to the new form
        const newLookupBtn = newGuestForm.querySelector('.lookup-btn');
        const newEmailInput = newGuestForm.querySelector('.visitor-email');

        newLookupBtn.addEventListener('click', function() {
            const formGroup = this.closest('.guest-form');
            const emailInput = formGroup.querySelector('.visitor-email');
            const email = emailInput.value;

            if (!email) {
                alert('{{ __("Please enter an email address") }}');
                return;
            }

            fetch(`/api/visitors/lookup?email=${encodeURIComponent(email)}`)
                .then(res => res.json())
                .then(data => {
                    const statusDiv = formGroup.querySelector('.visitor-status');
                    const existingBadge = formGroup.querySelector('.existing-visitor-badge');
                    const newBadge = formGroup.querySelector('.new-visitor-badge');

                    statusDiv.style.display = 'block';

                    if (data.exists) {
                        existingBadge.style.display = 'inline-block';
                        newBadge.style.display = 'none';

                        const nameInput = formGroup.querySelector('input[name$="[name]"]');
                        const phoneInput = formGroup.querySelector('input[name$="[phone]"]');
                        const orgInput = formGroup.querySelector('input[name$="[organization]"]');

                        if (nameInput) nameInput.value = data.visitor.name || '';
                        if (phoneInput) phoneInput.value = data.visitor.phone || '';
                        if (orgInput) orgInput.value = data.visitor.organization || '';
                    } else {
                        existingBadge.style.display = 'none';
                        newBadge.style.display = 'inline-block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __("An error occurred while checking visitor status") }}');
                });
        });

        newEmailInput.addEventListener('blur', function() {
            if (this.value) {
                this.closest('.guest-form').querySelector('.lookup-btn').click();
            }
        });
    }
    </script>

    <style>
    .visitor-status {
        height: 24px;
    }
    .existing-visitor-badge, .new-visitor-badge {
        font-size: 0.9rem;
    }
    </style>

    {{-- <form method="POST" action="{{ route('visitors.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="guest-forms">
            <div class="guest-form">
                <h5>{{ __('Visitor 1') }}</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Full Name') }}</label>
                        <input type="text" name="guests[0][name]" class="form-control" value="{{ old('guests.0.name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Host Name') }}</label>
                        <input type="text" name="guests[0][host_name]" class="form-control bg-light" value="{{ $employee_name ?? __('Employee') }}" readonly>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Phone') }}</label>
                        <input type="tel" name="guests[0][phone]" class="form-control" placeholder="+2341234567890" value="{{ old('guests.0.phone') }}" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" name="guests[0][email]" class="form-control" value="{{ old('guests.0.email') }}" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Organization') }}</label>
                        <input type="text" name="guests[0][organization]" class="form-control" value="{{ old('guests.0.organization') }}" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Visit Date') }}</label>
                        <input type="date" name="guests[0][visit_date]" class="form-control" value="{{ old('guests.0.visit_date') }}" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Time of Visit') }}</label>
                        <input type="time" name="guests[0][time_of_visit]" class="form-control" value="{{ old('guests.0.time_of_visit') }}" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Floor of Visit') }}</label>
                        <select name="guests[0][floor_of_visit]" class="form-control" required>
                            <option value="">{{ __('Select Floor') }}</option>
                            <option value="Ground Floor" {{ old('guests.0.floor_of_visit') == 'Ground Floor' ? 'selected' : '' }}>{{ __('Ground Floor') }}</option>
                            <option value="Mezzanine" {{ old('guests.0.floor_of_visit') == 'Mezzanine' ? 'selected' : '' }}>{{ __('Mezzanine') }}</option>
                            <option value="Floor 1" {{ old('guests.0.floor_of_visit') == 'Floor 1' ? 'selected' : '' }}>{{ __('Floor 1') }}</option>
                            <option value="Floor 2" {{ old('guests.0.floor_of_visit') == 'Floor 2' ? 'selected' : '' }}>{{ __('Floor 2') }}</option>
                            <option value="Floor 3" {{ old('guests.0.floor_of_visit') == 'Floor 3' ? 'selected' : '' }}>{{ __('Floor 3') }}</option>
                            <option value="Floor 4" {{ old('guests.0.floor_of_visit') == 'Floor 4' ? 'selected' : '' }}>{{ __('Floor 4') }}</option>
                            <option value="Floor 5" {{ old('guests.0.floor_of_visit') == 'Floor 5' ? 'selected' : '' }}>{{ __('Floor 5') }}</option>
                            <option value="Floor 6" {{ old('guests.0.floor_of_visit') == 'Floor 6' ? 'selected' : '' }}>{{ __('Floor 6') }}</option>
                            <option value="Floor 7" {{ old('guests.0.floor_of_visit') == 'Floor 7' ? 'selected' : '' }}>{{ __('Floor 7') }}</option>
                            <option value="Floor 8" {{ old('guests.0.floor_of_visit') == 'Floor 8' ? 'selected' : '' }}>{{ __('Floor 8') }}</option>
                            <option value="Floor 9" {{ old('guests.0.floor_of_visit') == 'Floor 9' ? 'selected' : '' }}>{{ __('Floor 9') }}</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label class="form-label">{{ __('Reason for Visit') }}</label>
                        <textarea name="guests[0][reason]" class="form-control" rows="2" required>{{ old('guests.0.reason') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-primary me-2" onclick="addGuestForm()">
                <i class="bi bi-plus-circle"></i> {{ __('Add Another Visitor') }}
            </button>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-spreadsheet"></i> {{ __('Import Visitors') }}
            </button>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-custom btn-lg px-5">
                <i class="bi bi-send-check"></i> {{ __('Submit Visitor Requests') }}
            </button>
        </div>
    </form> --}}
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-earmark-spreadsheet"></i> {{ __('Import Visitors from CSV') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('visitors.import') }}" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-4">
                        <h6><i class="bi bi-info-circle"></i> {{ __('Instructions:') }}</h6>
                        <ul>
                            <li>{{ __('Prepare a CSV file with the following headers: name, phone, email, organization, visit_date, time_of_visit, floor, reason') }}</li>
                            <li>{{ __('Date format should be YYYY-MM-DD (e.g., 2025-05-20)') }}</li>
                            <li>{{ __('Time format should be HH:MM (e.g., 14:30)') }}</li>
                            <li>{{ __('Floor values should match our options (e.g., "Ground Floor", "Floor 1", etc.)') }}</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="visitor_csv" class="form-label">{{ __('Select CSV File') }}</label>
                        <input type="file" class="form-control" id="visitor_csv" name="visitor_csv" accept=".csv" required>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="bi bi-file-earmark-text"></i> {{ __('Sample CSV Format:') }}</h6>
                        <pre class="import-sample">name,phone,email,organization,visit_date,time_of_visit,floor,reason
John Doe,+2341234567890,john@example.com,ABC Corp,2025-05-20,14:30,Floor 3,Business Meeting
Jane Smith,+2349876543210,jane@example.com,XYZ Ltd,2025-05-21,10:00,Floor 5,Interview</pre>
                        <a href="{{ route('visitors.sample-csv') }}" class="btn btn-sm btn-outline-dark mt-2" download><i class="bi bi-download"></i> {{ __('Download Sample') }}</a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-success" onclick="document.getElementById('importForm').submit()">
                    <i class="bi bi-check2"></i> {{ __('Import Visitors') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let guestCount = 1;
    const employeeName = @json($employee_name ?? __('Employee'));

    function addGuestForm() {
        const container = document.getElementById('guest-forms');
        const index = guestCount;
        guestCount++;

        const form = document.createElement('div');
        form.classList.add('guest-form');
        form.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>{{ __('Visitor') }} ${index + 1}</h5>
                <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">
                    <i class="bi bi-trash"></i> {{ __('Remove') }}
                </button>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Full Name') }}</label>
                    <input type="text" name="guests[${index}][name]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Host Name') }}</label>
                    <input type="text" name="guests[${index}][host_name]" class="form-control bg-light" value="${employeeName}" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">{{ __('Phone') }}</label>
                    <input type="tel" name="guests[${index}][phone]" class="form-control" placeholder="+2341234567890" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" name="guests[${index}][email]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">{{ __('Organization') }}</label>
                    <input type="text" name="guests[${index}][organization]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">{{ __('Visit Date') }}</label>
                    <input type="date" name="guests[${index}][visit_date]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">{{ __('Time of Visit') }}</label>
                    <input type="time" name="guests[${index}][time_of_visit]" class="form-control" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">{{ __('Floor of Visit') }}</label>
                    <select name="guests[${index}][floor_of_visit]" class="form-control" required>
                        <option value="">{{ __('Select Floor') }}</option>
                        <option value="Ground Floor">{{ __('Ground Floor') }}</option>
                        <option value="Mezzanine">{{ __('Mezzanine') }}</option>
                        <option value="Floor 1">{{ __('Floor 1') }}</option>
                        <option value="Floor 2">{{ __('Floor 2') }}</option>
                        <option value="Floor 3">{{ __('Floor 3') }}</option>
                        <option value="Floor 4">{{ __('Floor 4') }}</option>
                        <option value="Floor 5">{{ __('Floor 5') }}</option>
                        <option value="Floor 6">{{ __('Floor 6') }}</option>
                        <option value="Floor 7">{{ __('Floor 7') }}</option>
                        <option value="Floor 8">{{ __('Floor 8') }}</option>
                        <option value="Floor 9">{{ __('Floor 9') }}</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <label class="form-label">{{ __('Reason for Visit') }}</label>
                    <textarea name="guests[${index}][reason]" class="form-control" rows="2" required></textarea>
                </div>
            </div>
        `;
        container.appendChild(form);

        // Set minimum date for new date inputs
        const newDateInput = form.querySelector('input[type="date"]');
        if (newDateInput) {
            newDateInput.setAttribute('min', getTodayDate());
        }
    }

    document.querySelector('form').addEventListener('submit', function (e) {
        let isValid = true;
        const phoneRegex = /^\+?[1-9]\d{7,14}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        document.querySelectorAll('[name^="guests"]').forEach(input => {
            if (input.name.includes('[phone]')) {
                if (!phoneRegex.test(input.value)) {
                    alert(`{{ __('Invalid phone number:') }} ${input.value}`);
                    isValid = false;
                }
            }

            if (input.name.includes('[email]')) {
                if (!emailRegex.test(input.value)) {
                    alert(`{{ __('Invalid email address:') }} ${input.value}`);
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });

    function getTodayDate() {
        return new Date().toISOString().split('T')[0];
    }

    function getTomorrowDate() {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        return tomorrow.toISOString().split('T')[0];
    }

    function getDayAfterTomorrowDate() {
        const dayAfter = new Date();
        dayAfter.setDate(dayAfter.getDate() + 2);
        return dayAfter.toISOString().split('T')[0];
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const today = getTodayDate();
        const dateInputs = document.querySelectorAll('input[type="date"]');

        // Set the min attribute to today's date for all date input fields
        dateInputs.forEach(input => {
            input.setAttribute('min', today);
        });
    });
</script>
</body>
</html>
