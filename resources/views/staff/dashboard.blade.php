<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AATC VMS') }} - Staff Dashboard</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="VMS">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        secondary: '#f3f4f6',
                    }
                }
            }
        }
    </script>
    <style>
        .tab-content {
    transition: opacity 0.3s ease;
}

#toast {
    transition: all 0.3s ease;
}

.badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.badge-pending {
    @apply bg-yellow-100 text-yellow-800;
}

.badge-approved {
    @apply bg-green-100 text-green-800;
}

.badge-denied {
    @apply bg-red-100 text-red-800;
}

.badge-completed {
    @apply bg-blue-100 text-blue-800;
}
    </style>
</head>

<body class="min-h-screen bg-gray-50">
    <header class="bg-[#22807e] text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 lg:py-6 md:py-4">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('assets/logo-no-bg.png') }}" alt="Logo" class="h-10 w-auto md:h-12">
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg md:text-2xl font-semibold leading-tight">Abuja AATC-VMS</h1>
                        <p class="text-xs md:text-[14px] opacity-90">Visitor Management System</p>
                    </div>
                </div>


                <div class="flex items-center space-x-4">
                    <!-- Language Dropdown (Desktop only) -->
                    <div class="hidden md:flex items-center space-x-2">
                        @include('partials.language_switcher')
                    </div>

                    <!-- Notification Icon -->
                    <div class="relative">
                        <button onclick="toggleNotifications()" class="p-2 rounded-full hover:bg-white/10 focus:outline-none focus:ring-white">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            {{-- <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span> --}}
                        </button>

                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown"
                            class="hidden absolute right-0 mt-2 w-80 md:w-96 bg-white rounded-xl shadow-lg z-50 transition-all duration-300 ease-in-out">
                            <div class="p-4 border-b">
                                <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                            </div>
                            <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                                <!-- Dynamic notifications would go here -->

                                <div class="flex items-center space-x-3 p-3 hover:bg-gray-50">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <div class="font-medium text-sm text-gray-700">Pending</div>
                                        <div class="text-sm text-gray-600">Prosper Bobson</div>
                                    </div>
                                    <div class="text-xs text-gray-500">8:58PM 06/07/2025</div>
                                </div>
                                <!-- More notifications... -->
                            {{-- </div> --}}

                                {{-- <div class="flex items-center space-x-3 p-3 rounded-lg bg-yellow-50">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="font-medium text-sm">Pending</div>
                                        <div class="text-sm text-gray-600">Prosper Bobson</div>
                                    </div>
                                    <div class="text-xs text-gray-500">8:58PM 06/07/2025</div>
                                </div> --}}
                                <!-- More notification items -->
                            </div>
                            <div class="p-3 border-t text-center">
                                <a href="#" class="text-sm text-teal-600 hover:underline">View all notifications</a>
                            </div>
                        </div>
                    </div>

                        <div class="flex items-center space-x-3 hidden md:flex">
                            <div class="text-right">
                                <p class="text-xs md:text-[14px]">{{ $fullName }}</p>
                                <p class="text-xs md:text-[14px]">Location: Abuja</p>
                            </div>
                            <button onclick="toggleSidebar()" onclick="toggleSidebar()" class="h-8 w-8 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center">
                                <i class="fas fa-user text-[#00aa8c]"></i>
                            </button>
                        </div>

                    <!-- Hamburger Menu (Mobile) -->
                    <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 right-0 h-full w-full md:w-96 bg-white shadow-2xl z-60 transform translate-x-full transition-transform duration-300">
        <div class="h-full overflow-y-auto">
            <!-- Sidebar Header -->
            <div class="!bg-gradient-to-br !from-[#22807e] !to-[#00aa8c] text-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Profile Settings</h2>
                    <button onclick="toggleSidebar()" class="p-2 hover:bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- User Info -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-lg font-medium">{{ strtoupper(substr($fullName, 0, 2)) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold">{{ $fullName }}</div>
                        <div class="text-sm opacity-90">Location: Abuja</div>
                        <div class="text-sm opacity-75">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Basic Information</h3>

                <form class="space-y-4">
                    <!-- Form fields would go here -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" value="{{ $fullName }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $staffEmail) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Update Profile Button -->
                    <button type="submit" class="w-full !bg-gradient-to-br !from-[#22807e] !to-[#00aa8c] text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity mt-6">
                        Update Profile
                    </button>
                </form>

                <!-- Action Buttons -->
                <div class="mt-8 space-y-3">
                    <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        Change Password
                    </button>
                    <form method="POST" action="{{ route('staff.logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-medium hover:bg-red-200 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (!sidebar.classList.contains('translate-x-full')) {
                    toggleSidebar();
                }
            }
        });

        // Close notifications when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const button = event.target.closest('button[onclick="toggleNotifications()"]');

            if (dropdown && !dropdown.contains(event.target) && !button) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <!-- Main Content -->
    <main class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">

            <div class="relative bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                <dt>
                    <div class="absolute bg-[#FFCA00]/10 rounded-xl p-3 flex items-center justify-center">
                        <i class="fas fa-users text-orange-600 text-lg" aria-hidden="true"></i>
                    </div>
                    <p class="ml-16 text-sm font-medium text-gray-600 truncate">Total Invitations</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline">
                    <p class="text-3xl font-bold text-gray-900">{{ $totalInvitations }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold {{ strpos($percentageTotalInvitations, '+') !== false ? 'text-green-600' : (strpos($percentageTotalInvitations, '-') !== false ? 'text-red-600' : 'text-gray-500') }}">
                        {{ $percentageTotalInvitations }}
                    </p>
                </dd>
            </div>

            <div class="relative bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                <dt>
                    <div class="absolute bg-[#FFCA00]/10 rounded-xl p-3 flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-lg" aria-hidden="true"></i>
                    </div>
                    <p class="ml-16 text-sm font-medium text-gray-600 truncate">Pending Approval</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline">
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingApproval }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                        {{ $percentagePendingApproval }}
                    </p>
                </dd>
            </div>

            <div class="relative bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                <dt>
                    <div class="absolute bg-[#FFCA00]/10 rounded-xl p-3 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg" aria-hidden="true"></i>
                    </div>
                    <p class="ml-16 text-sm font-medium text-gray-600 truncate">Approved Today</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline">
                    <p class="text-3xl font-bold text-gray-900">{{ $approvedToday }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold {{ strpos($percentageApproved, '+') !== false ? 'text-green-600' : (strpos($percentageApproved, '-') !== false ? 'text-red-600' : 'text-gray-500') }}">
                        {{ $percentageApproved }}
                    </p>
                </dd>
            </div>

            <div class="relative bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                <dt>
                    <div class="absolute bg-[#FFCA00]/10 rounded-xl p-3 flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-lg" aria-hidden="true"></i>
                    </div>
                    <p class="ml-16 text-sm font-medium text-gray-600 truncate">Cancelled/Denied</p>
                </dt>
                <dd class="ml-16 pb-6 flex items-baseline">
                    <p class="text-3xl font-bold text-gray-900">{{ $denied }}</p>
                    <p class="ml-2 flex items-baseline text-sm font-semibold {{ strpos($percentageDenied, '-') !== false ? 'text-green-600' : (strpos($percentageDenied, '+') !== false ? 'text-red-600' : 'text-gray-500') }}">
                        {{ $percentageDenied }}
                    </p>
                </dd>
            </div>

        </div>

        <!-- Visitor Management Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('invite')" class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600" data-tab="invite">
                        Invite Guest
                    </button>
                    <button onclick="showTab('active')" class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="active">
                        Active Visits
                    </button>
                    <button onclick="showTab('history')" class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="history">
                        Visit History
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Invite Guest Tab -->
                {{-- <div id="invite-tab" class="tab-content">
                    <div class="max-w-2xl">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Invite a Guest</h2>
                            <p class="text-gray-600">Fill out the form below to send an invitation to your guest.</p>
                        </div>

                        <form id="invite-form" class="space-y-6">
                            @csrf
                            <!-- Guest Information -->
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user text-blue-600 mr-2"></i>
                                    Guest Information
                                </h3>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="guest_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                        <input type="text" name="guest_name" id="guest_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter guest's full name">
                                    </div>
                                    <div>
                                        <label for="guest_email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                        <input type="email" name="guest_email" id="guest_email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="guest@example.com">
                                    </div>
                                    <div>
                                        <label for="guest_phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                        <input type="tel" name="guest_phone" id="guest_phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="+1 (555) 123-4567">
                                    </div>
                                    <div>
                                        <label for="organization" class="block text-sm font-medium text-gray-700">Organization (Optional)</label>
                                        <input type="text" name="organization" id="organization" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Company name">
                                    </div>
                                </div>
                            </div>

                            <!-- Visit Details -->
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-building text-blue-600 mr-2"></i>
                                    Visit Details
                                </h3>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label for="visit_reason" class="block text-sm font-medium text-gray-700">Reason for Visit *</label>
                                        <textarea name="visit_reason" id="visit_reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Describe the purpose of the visit"></textarea>
                                    </div>
                                    <div>
                                        <label for="visit_date" class="block text-sm font-medium text-gray-700">Visit Date *</label>
                                        <input type="date" name="visit_date" id="visit_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label for="visit_time" class="block text-sm font-medium text-gray-700">Visit Time *</label>
                                        <input type="time" name="visit_time" id="visit_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="floor" class="block text-sm font-medium text-gray-700">Floor/Department *</label>
                                        <select name="floor" id="floor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select floor or department</option>
                                            @foreach($floorOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-md font-medium">
                                    Send Invitation
                                </button>
                            </div>
                        </form>
                    </div>
                </div> --}}
                <div id="invite-tab" class="tab-content">
                    <div class="max-w-7xl">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Invite Guests</h2>
                            <p class="text-gray-600">Fill out the form below to send invitations to your guests.</p>
                        </div>

                        <form id="invite-form" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Left Side - Guest Form -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Guest Information -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-user text-blue-600 mr-2"></i>
                                            Guest Information
                                        </h3>
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label for="guest_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                                <input type="text" name="guest_name" id="guest_name" required
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="Enter guest's full name">
                                            </div>
                                            <div>
                                                <label for="guest_email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                                <input type="email" name="guest_email" id="guest_email" required
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="guest@example.com">
                                            </div>
                                            <div>
                                                <label for="guest_phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                                <input type="tel" name="guest_phone" id="guest_phone" required
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="+1 (555) 123-4567">
                                            </div>
                                            <div>
                                                <label for="organization" class="block text-sm font-medium text-gray-700">Organization (Optional)</label>
                                                <input type="text" name="organization" id="organization"
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="Company name">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Visit Details -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-building text-blue-600 mr-2"></i>
                                            Visit Details
                                        </h3>
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div class="sm:col-span-2">
                                                <label for="visit_reason" class="block text-sm font-medium text-gray-700">Reason for Visit *</label>
                                                <textarea name="visit_reason" id="visit_reason" rows="3" required
                                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                          placeholder="Describe the purpose of the visit"></textarea>
                                            </div>
                                            <div>
                                                <label for="visit_date" class="block text-sm font-medium text-gray-700">Visit Date *</label>
                                                <input type="date" name="visit_date" id="visit_date" required
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       min="{{ date('Y-m-d') }}">
                                            </div>
                                            <div>
                                                <label for="visit_time" class="block text-sm font-medium text-gray-700">Visit Time *</label>
                                                <input type="time" name="visit_time" id="visit_time" required
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="floor" class="block text-sm font-medium text-gray-700">Floor/Department *</label>
                                                <select name="floor" id="floor" required
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">Select floor or department</option>
                                                    <option value="ground">Ground Floor - Reception</option>
                                                    <option value="1st">1st Floor - HR & Admin</option>
                                                    <option value="2nd">2nd Floor - Finance</option>
                                                    <option value="3rd">3rd Floor - IT Department</option>
                                                    <option value="4th">4th Floor - Management</option>
                                                    <option value="5th">5th Floor - Conference Rooms</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side - Guest Management -->
                                <div class="lg:col-span-1 space-y-6">
                                    <!-- Guest Navigation -->
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-medium text-gray-900">Guests</h3>
                                            <span class="text-sm text-gray-500" id="guest-count">1 Guest</span>
                                        </div>

                                        <div id="guest-navigation" class="flex items-center justify-between mb-4 hidden">
                                            <button type="button" id="prev-guest"
                                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>

                                            <span class="text-sm font-medium" id="guest-indicator">Guest 1 of 1</span>

                                            <button type="button" id="next-guest"
                                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>

                                        <div class="space-y-2">
                                            <button type="button" id="add-guest"
                                                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <i class="fas fa-plus mr-2"></i>
                                                Add Another Guest
                                            </button>

                                            <button type="button" id="remove-guest"
                                                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md text-red-600 hover:text-red-700 hover:bg-red-50 hidden">
                                                Remove Current Guest
                                            </button>
                                        </div>
                                    </div>

                                    <!-- CSV Import/Export -->
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">CSV Import/Export</h3>
                                        <div class="space-y-2">
                                            <button type="button" id="download-template"
                                                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                                <i class="fas fa-download mr-2"></i>
                                                Download CSV Template
                                            </button>

                                            <div class="relative">
                                                <input type="file" accept=".csv" id="csv-import" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                                <button type="button" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                                    <i class="fas fa-upload mr-2"></i>
                                                    Import from CSV
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Current Guest Summary -->
                                    <div class="bg-blue-50 p-4 rounded-lg border border-green-200">
                                        <h4 class="text-sm font-medium text-green-900 mb-2">Current Guest Summary</h4>
                                        <div class="text-sm text-green-700 space-y-1" id="guest-summary">
                                            <p><strong>Name:</strong> <span id="summary-name">Not specified</span></p>
                                            <p><strong>Email:</strong> <span id="summary-email">Not specified</span></p>
                                            <p><strong>Date:</strong> <span id="summary-date">Not specified</span></p>
                                            <p><strong>Floor:</strong> <span id="summary-floor">Not specified</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end mt-8">
                                <button type="submit" id="submit-btn" class="bg-[#22807e] hover:bg-[#00aa8c] text-white px-8 py-2 rounded-md font-medium">
                                    Send All Invitations (<span id="submit-count">1</span>)
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Hidden template for additional guests -->
                    <template id="guest-form-template">
                        <div class="guest-form-data" style="display: none;">
                            <input type="text" class="guest-name" name="guests[][name]">
                            <input type="email" class="guest-email" name="guests[][email]">
                            <input type="tel" class="guest-phone" name="guests[][phone]">
                            <input type="text" class="guest-organization" name="guests[][organization]">
                            <textarea class="guest-reason" name="guests[][reason]"></textarea>
                            <input type="date" class="guest-date" name="guests[][date]">
                            <input type="time" class="guest-time" name="guests[][time]">
                            <input type="text" class="guest-floor" name="guests[][floor]">
                        </div>
                    </template>

                    <!-- Success/Error Messages -->
                    <div id="message-container" class="fixed top-4 right-4 z-50"></div>
                </div>

                <!-- Active Visits Tab -->
                <div id="active-tab" class="tab-content hidden">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Active Invitations</h2>
                        <p class="text-gray-600">Manage your current guest invitations and their approval status.</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($activeVisits as $visit)
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                                            @if($visit->status == 'pending')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> Pending Approval
                                                </span>
                                            @elseif($visit->status == 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Denied
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                                            <div class="space-y-1">
                                                <p class="flex items-center">
                                                    <i class="fas fa-envelope mr-2"></i>
                                                    {{ $visit->visitor->email }}
                                                </p>
                                                @if($visit->visitor->organization)
                                                    <p class="flex items-center">
                                                        <i class="fas fa-building mr-2"></i>
                                                        {{ $visit->visitor->organization }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="space-y-1">
                                                <p class="flex items-center">
                                                    <i class="fas fa-calendar-day mr-2"></i>
                                                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }} at {{ $visit->visit_time }}
                                                </p>
                                                <p class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                                    {{ $floorOptions[$visit->floor_of_visit] ?? $visit->floor_of_visit }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> {{ $visit->reason }}
                                            </p>
                                        </div>

                                        @if($visit->status == 'approved')
                                            <div class="bg-green-50 border border-green-200 rounded-xl p-3 mb-4">
                                                <p class="text-sm text-green-800">
                                                    <span class="font-medium">Invitation Code:</span> {{ $visit->unique_code }}
                                                </p>
                                                <p class="text-xs text-green-600 mt-1">
                                                    Share this code with your guest for entry verification.
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-6 flex flex-col space-y-2">
                                        @if($visit->status == 'pending')
                                            <button onclick="cancelVisit({{ $visit->id }})" class="inline-flex items-center px-3 py-1 border border-red-200 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                                Cancel
                                            </button>
                                            <button onclick="editVisit({{ $visit->id }})" class="inline-flex items-center px-3 py-1 border border-green-200 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                                Edit
                                            </button>
                                        @endif
                                        @if($visit->status == 'approved')
                                            <button onclick="resendCode({{ $visit->id }})" class="inline-flex items-center px-3 py-1 border border-gray-200 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50">
                                                Resend Code
                                            </button>
                                        @endif
                                        @if($visit->status == 'rejected')
                                            <button onclick="resubmitVisit({{ $visit->id }})" class="inline-flex items-center px-3 py-1 border border-blue-200 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                                Resubmit
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-exclamation-circle mx-auto text-4xl text-gray-400"></i>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No active invitations</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Get started by creating a new guest invitation.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($activeVisits->hasPages())
                    <div class="mt-8 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $activeVisits->firstItem() }} to {{ $activeVisits->lastItem() }} of {{ $activeVisits->total() }} results
                        </div>
                        <div class="flex space-x-2">
                            @if($activeVisits->onFirstPage())
                                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Previous
                                </button>
                            @else
                                <a href="{{ $activeVisits->previousPageUrl() }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @foreach(range(1, $activeVisits->lastPage()) as $page)
                                @if($page == $activeVisits->currentPage())
                                    <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $activeVisits->url($page) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($activeVisits->hasMorePages())
                                <a href="{{ $activeVisits->nextPageUrl() }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Next
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Visit History Tab -->
                <div id="history-tab" class="tab-content hidden">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Visit History</h2>
                        <p class="text-gray-600">Review your past guest invitations and visit records.</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($visitHistory as $visit)
                            <div class="bg-white border border-gray-200 rounded-xl p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                                        @if($visit->status == 'completed' || $visit->is_checked_out)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Completed
                                            </span>
                                        @elseif($visit->status == 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Cancelled
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i> No Show
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                                    <div class="space-y-1">
                                        <p class="flex items-center">
                                            <i class="fas fa-envelope mr-2"></i>
                                            {{ $visit->visitor->email }}
                                        </p>
                                        @if($visit->visitor->organization)
                                            <p class="flex items-center">
                                                <i class="fas fa-building mr-2"></i>
                                                {{ $visit->visitor->organization }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <p class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            Scheduled: {{ $visit->visit_time }}
                                        </p>
                                        <p class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            {{ $floorOptions[$visit->floor_of_visit] ?? $visit->floor_of_visit }}
                                        </p>
                                    </div>
                                    @if($visit->checked_in_at)
                                        <div class="space-y-1">
                                            <p class="flex items-center">
                                                <i class="fas fa-sign-in-alt mr-2 text-green-600"></i>
                                                Arrived: {{ \Carbon\Carbon::parse($visit->checked_in_at)->format('h:i A') }}
                                            </p>
                                            <p class="flex items-center">
                                                <i class="fas fa-sign-out-alt mr-2 text-blue-600"></i>
                                                Departed: {{ $visit->checked_out_at ? \Carbon\Carbon::parse($visit->checked_out_at)->format('h:i A') : 'N/A' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Reason:</span> {{ $visit->reason }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-exclamation-circle mx-auto text-4xl text-gray-400"></i>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No visit history found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Your guest visits will appear here once completed.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($visitHistory->hasPages())
                    <div class="mt-8 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $visitHistory->firstItem() }} to {{ $visitHistory->lastItem() }} of {{ $visitHistory->total() }} results
                        </div>
                        <div class="flex space-x-2">
                            @if($visitHistory->onFirstPage())
                                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Previous
                                </button>
                            @else
                                <a href="{{ $visitHistory->previousPageUrl() }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @foreach(range(1, $visitHistory->lastPage()) as $page)
                                @if($page == $visitHistory->currentPage())
                                    <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $visitHistory->url($page) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if($visitHistory->hasMorePages())
                                <a href="{{ $visitHistory->nextPageUrl() }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Next
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Visit Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Visit Details</h3>
                    <form id="edit-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="visit_id" id="edit_visit_id">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="edit_guest_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                <input type="text" name="guest_name" id="edit_guest_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_guest_email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                <input type="email" name="guest_email" id="edit_guest_email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_guest_phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                <input type="tel" name="guest_phone" id="edit_guest_phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_organization" class="block text-sm font-medium text-gray-700">Organization</label>
                                <input type="text" name="organization" id="edit_organization" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="edit_visit_reason" class="block text-sm font-medium text-gray-700">Reason for Visit *</label>
                                <textarea name="visit_reason" id="edit_visit_reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>
                            <div>
                                <label for="edit_visit_date" class="block text-sm font-medium text-gray-700">Visit Date *</label>
                                <input type="date" name="visit_date" id="edit_visit_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_floor" class="block text-sm font-medium text-gray-700">Floor/Department *</label>
                                <select name="floor" id="edit_floor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select floor or department</option>
                                    @foreach($floorOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitEditForm()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#22807e] hover:bg-[#00aa8c] text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 hidden">
        <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center">
            <span id="toast-message"></span>
            <button onclick="document.getElementById('toast').classList.add('hidden')" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.getAttribute('data-tab') === tabName) {
                    button.classList.add('border-blue-500', 'text-blue-600');
                    button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                } else {
                    button.classList.remove('border-blue-500', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                }
            });

            // Update URL
            history.pushState(null, null, '#' + tabName);
        }

        // Check for hash on page load
        window.addEventListener('load', function() {
            if (window.location.hash) {
                const tabName = window.location.hash.substring(1);
                if (['invite', 'active', 'history'].includes(tabName)) {
                    showTab(tabName);
                }
            }
        });

        // Modal functions
        function openModal() {
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        // Show toast notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }

        // Send invitation form
        document.getElementById('invite-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('visit_time', document.getElementById('visit_time').value);

            fetch("{{ route('staff.dashboard.invite') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    this.reset();
                    // Optionally refresh the active visits list
                    if (document.getElementById('active-tab').classList.contains('hidden') === false) {
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to send invitation'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the invitation');
            });
        });

        // Edit visit functions
        function editVisit(visitId) {
            fetch(`/staff/visits/${visitId}/details`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const visit = data.visit;
                    document.getElementById('edit_visit_id').value = visit.id;
                    document.getElementById('edit_guest_name').value = visit.visitor.name;
                    document.getElementById('edit_guest_email').value = visit.visitor.email;
                    document.getElementById('edit_guest_phone').value = visit.visitor.phone;
                    document.getElementById('edit_organization').value = visit.visitor.organization || '';
                    document.getElementById('edit_visit_reason').value = visit.reason;
                    document.getElementById('edit_visit_date').value = visit.visit_date;
                    document.getElementById('edit_floor').value = visit.floor_of_visit;
                    openModal();
                } else {
                    alert('Error: ' + (data.message || 'Failed to load visit details'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading visit details');
            });
        }

        function submitEditForm() {
            const formData = new FormData(document.getElementById('edit-form'));

            fetch("{{ route('staff.dashboard.edit') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    closeModal();
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update visit'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the visit');
            });
        }

        // Cancel visit
        function cancelVisit(visitId) {
            if (confirm('Are you sure you want to cancel this visit invitation?')) {
                fetch(`/staff/visits/${visitId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to cancel visit'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while cancelling the visit');
                });
            }
        }

        // Resubmit visit
        function resubmitVisit(visitId) {
            if (confirm('Are you sure you want to resubmit this visit for approval?')) {
                fetch(`/staff/visits/${visitId}/resubmit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to resubmit visit'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while resubmitting the visit');
                });
            }
        }

        // Resend code
        function resendCode(visitId) {
            if (confirm('Are you sure you want to resend the invitation code to the guest?')) {
                fetch(`/staff/visits/${visitId}/resend-code`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                    } else {
                        alert('Error: ' + (data.message || 'Failed to resend code'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while resending the code');
                });
            }
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let guests = [{}]; // Array to store guest data
        let currentGuestIndex = 0;

        // Form elements
        const form = document.getElementById('invite-form');
        const guestNameInput = document.getElementById('guest_name');
        const guestEmailInput = document.getElementById('guest_email');
        const guestPhoneInput = document.getElementById('guest_phone');
        const organizationInput = document.getElementById('organization');
        const visitReasonInput = document.getElementById('visit_reason');
        const visitDateInput = document.getElementById('visit_date');
        const visitTimeInput = document.getElementById('visit_time');
        const floorSelect = document.getElementById('floor');

        // Control elements
        const addGuestBtn = document.getElementById('add-guest');
        const removeGuestBtn = document.getElementById('remove-guest');
        const prevGuestBtn = document.getElementById('prev-guest');
        const nextGuestBtn = document.getElementById('next-guest');
        const guestNavigation = document.getElementById('guest-navigation');
        const guestCount = document.getElementById('guest-count');
        const guestIndicator = document.getElementById('guest-indicator');
        const submitCount = document.getElementById('submit-count');
        const downloadTemplateBtn = document.getElementById('download-template');
        const csvImportInput = document.getElementById('csv-import');

        // Summary elements
        const summaryName = document.getElementById('summary-name');
        const summaryEmail = document.getElementById('summary-email');
        const summaryDate = document.getElementById('summary-date');
        const summaryFloor = document.getElementById('summary-floor');

        // Update UI based on current state
        function updateUI() {
            // Update counters
            guestCount.textContent = `${guests.length} Guest${guests.length !== 1 ? 's' : ''}`;
            guestIndicator.textContent = `Guest ${currentGuestIndex + 1} of ${guests.length}`;
            submitCount.textContent = guests.length;

            // Show/hide navigation
            if (guests.length > 1) {
                guestNavigation.classList.remove('hidden');
                removeGuestBtn.classList.remove('hidden');
            } else {
                guestNavigation.classList.add('hidden');
                removeGuestBtn.classList.add('hidden');
            }

            // Update navigation buttons
            prevGuestBtn.disabled = currentGuestIndex === 0;
            nextGuestBtn.disabled = currentGuestIndex === guests.length - 1;

            // Update add button state
            addGuestBtn.disabled = !isCurrentGuestComplete();
        }

        // Load guest data into form
        function loadGuestData() {
            const guest = guests[currentGuestIndex] || {};

            guestNameInput.value = guest.name || '';
            guestEmailInput.value = guest.email || '';
            guestPhoneInput.value = guest.phone || '';
            organizationInput.value = guest.organization || '';
            visitReasonInput.value = guest.reason || '';
            visitDateInput.value = guest.date || '';
            visitTimeInput.value = guest.time || '';
            floorSelect.value = guest.floor || '';

            updateSummary();
        }

        // Save current form data to guests array
        function saveCurrentGuestData() {
            guests[currentGuestIndex] = {
                name: guestNameInput.value,
                email: guestEmailInput.value,
                phone: guestPhoneInput.value,
                organization: organizationInput.value,
                reason: visitReasonInput.value,
                date: visitDateInput.value,
                time: visitTimeInput.value,
                floor: floorSelect.value
            };
        }

        // Check if current guest form is complete
        function isCurrentGuestComplete() {
            const guest = guests[currentGuestIndex] || {};
            return guest.name && guest.email && guest.phone && guest.reason && guest.date && guest.time && guest.floor;
        }

        // Update summary panel
        function updateSummary() {
            const guest = guests[currentGuestIndex] || {};
            summaryName.textContent = guest.name || 'Not specified';
            summaryEmail.textContent = guest.email || 'Not specified';
            summaryDate.textContent = guest.date || 'Not specified';
            summaryFloor.textContent = guest.floor || 'Not specified';
        }

        // Show toast message
        function showToast(title, message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `p-4 mb-4 rounded-lg ${type === 'success' ? 'bg-green-100 border border-green-200 text-green-800' : 'bg-red-100 border border-red-200 text-red-800'}`;
            toast.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">${title}</h3>
                        <p class="text-sm mt-1">${message}</p>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.getElementById('message-container').appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        // Event listeners for form inputs
        [guestNameInput, guestEmailInput, guestPhoneInput, organizationInput, visitReasonInput, visitDateInput, visitTimeInput, floorSelect].forEach(input => {
            input.addEventListener('input', () => {
                saveCurrentGuestData();
                updateSummary();
                updateUI();
            });
        });

        // Add guest button
        addGuestBtn.addEventListener('click', () => {
            if (isCurrentGuestComplete()) {
                guests.push({});
                currentGuestIndex = guests.length - 1;
                loadGuestData();
                updateUI();
            }
        });

        // Remove guest button
        removeGuestBtn.addEventListener('click', () => {
            if (guests.length > 1) {
                guests.splice(currentGuestIndex, 1);
                currentGuestIndex = Math.max(0, currentGuestIndex - 1);
                loadGuestData();
                updateUI();
            }
        });

        // Navigation buttons
        prevGuestBtn.addEventListener('click', () => {
            if (currentGuestIndex > 0) {
                currentGuestIndex--;
                loadGuestData();
                updateUI();
            }
        });

        nextGuestBtn.addEventListener('click', () => {
            if (currentGuestIndex < guests.length - 1) {
                currentGuestIndex++;
                loadGuestData();
                updateUI();
            }
        });

        // Download CSV template
        downloadTemplateBtn.addEventListener('click', () => {
            const csvContent = "Guest Name,Email,Phone,Organization,Visit Reason,Visit Date,Visit Time,Floor\n" +
                              "John Doe,john@example.com,+1234567890,ABC Corp,Business Meeting,2024-01-15,14:00,3rd Floor - IT Department\n" +
                              "Jane Smith,jane@example.com,+1987654321,XYZ Ltd,Project Review,2024-01-16,10:30,2nd Floor - Finance";

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'guest_invitation_template.csv';
            a.click();
            window.URL.revokeObjectURL(url);

            showToast('Template Downloaded', 'CSV template has been downloaded to your device.');
        });

        // CSV import
        csvImportInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const text = e.target.result;
                const lines = text.split('\n');
                const headers = lines[0].split(',');

                const importedGuests = [];

                for (let i = 1; i < lines.length; i++) {
                    const values = lines[i].split(',');
                    if (values.length >= 8 && values[0].trim()) {
                        importedGuests.push({
                            name: values[0].trim(),
                            email: values[1].trim(),
                            phone: values[2].trim(),
                            organization: values[3].trim(),
                            reason: values[4].trim(),
                            date: values[5].trim(),
                            time: values[6].trim(),
                            floor: values[7].trim(),
                        });
                    }
                }

                if (importedGuests.length > 0) {
                    guests = importedGuests;
                    currentGuestIndex = 0;
                    loadGuestData();
                    updateUI();
                    showToast('CSV Imported Successfully', `${importedGuests.length} guest(s) imported from CSV file.`);
                }
            };

            reader.readAsText(file);
            event.target.value = '';
        });

        // Form submission
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            saveCurrentGuestData();

            console.log('Form submitted with guests:', guests);

            showToast('Invitations Sent Successfully!', `${guests.length} guest(s) will receive email invitations with unique codes.`);

            // Reset form
            guests = [{}];
            currentGuestIndex = 0;
            loadGuestData();
            updateUI();
        });

        // Initialize
        updateUI();
        updateSummary();
    });
    </script>
</body>
</html>
