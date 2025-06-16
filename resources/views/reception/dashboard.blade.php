<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AATC VMS') }}</title>

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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    transition: opacity 1s ease;
}
    </style>
</head>

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Dashboard - Diplomatic VMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
</head> --}}

<body class="bg-gray-50">
    <!-- Header -->
    <header class="!bg-gradient-to-br !from-[#22807e] !to-[#00aa8c] text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 lg:py-6 md:py-4">
                <!-- Logo/Branding Section -->
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('assets/logo-no-bg.png') }}" alt="Logo" class="h-10 w-auto md:h-12">
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg md:text-2xl font-semibold leading-tight">Abuja AATC-VMS</h1>
                        <p class="text-xs md:text-[14px] opacity-90">Reception Dashboard</p>
                    </div>
                </div>

                <!-- User Controls -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm md:text-base font-medium truncate max-w-[160px] md:max-w-none">Welcome, {{$firstName}}</p>
                    </div>

                    <form method="POST" action="{{ route('reception.logout') }}">
                        @csrf
                        <button type="submit" class="bg-[#ffcd00] hover:bg-[#e6b800] text-[#00736e] px-4 py-2 rounded-lg text-sm font-medium transition-colors ">
                            <i class="fas fa-sign-out-alt mr-2 hidden md:inline"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Search and Register Section - Stacked on mobile -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6 justify-between">
            <div class="flex-1 w-full max-w-2xl">
                <form method="GET" action="{{ route('reception.search') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input id="searchInput" type="text" name="search"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B] transition-colors duration-200"
                           placeholder="Search visitors"
                           value="{{ request('search') }}">
                </form>
            </div>
            <button class="bg-primary hover:bg-teal-700 text-white px-4 sm:px-6 py-3 rounded-lg font-medium transition-colors whitespace-nowrap flex items-center justify-center text-base">
                <i class="fas fa-user-plus mr-2 text-base"></i>
                <span>Register Walk-In</span>
            </button>
        </div>

        <!-- Statistics Cards - 2 columns on mobile -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Expected Today -->

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">{{ __('Expected Today') }}</p>
                        <p class="text-3xl font-bold text-[#FFCA00] mt-2" id="expected-today">{{ $expectedTodayCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-[#FFCA00]/10 rounded-xl flex items-center justify-center">
                        {{-- <i class="fas fa-clock text-[#FFCA00] text-xl"></i> --}}
                        <i class="fas fa-calendar-day text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Checked In -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">{{ __('Checked In') }}</p>
                        <p class="text-3xl font-bold text-green-600 mt-2" id="checked-in">{{ $checkedInCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-sign-in-alt text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Checked Out -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">{{ __('Checked Out') }}</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2" id="checked-out">{{ $checkedOutCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Cards -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">{{ __('Active Cards') }}</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2" id="active-cards">{{ $cardsIssuedCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-id-badge text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- Visitor Management Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 ">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-700">{{ __('Visitor Management') }}</h3>
                            <p class="text-sm text-gray-500">Check-in • Card Issuance • Check-out • Card Retrieval</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-0">
                <!-- Tabs -->
                <div class="mb-6">
                    <div class="inline-flex h-10 items-center justify-center rounded-md bg-gray-100 p-2 text-gray-500 w-full">
                        <div class="grid grid-cols-3 w-full">
                            <!-- Expected Today -->
                            <button data-tab="expected-today" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm relative active" data-state="active">
                                <span class="hidden sm:inline">Expected Today</span>
                                <span class="sm:hidden">Today</span>
                                @if($expectedVisits->count() > 0)
                                <span class="ml-2 inline-flex items-center rounded-full border border-transparent bg-red-500 px-1.5 py-0.5 text-xs font-medium text-white">
                                    {{ $expectedVisits->count() }}
                                </span>
                                @endif
                            </button>

                            <!-- Checked In -->
                            <button data-tab="checked-in" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm">
                                <span class="hidden sm:inline">Checked In</span>
                                <span class="sm:hidden">In</span>
                                <span class="ml-1">({{ $checkedInVisits->count() }})</span>
                            </button>

                            <!-- Checked Out -->
                            <button data-tab="checked-out" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm">
                                <span class="hidden sm:inline">Checked Out</span>
                                <span class="sm:hidden">Out</span>
                                <span class="ml-1">({{ $checkedOutVisits->count() }})</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div>
                    <!-- Expected Today Tab Content -->
                    <div id="expected-today" class="tab-content expected-today">
                        <div class="rounded-md ">
                            <div class="relative w-full overflow-auto">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b bg-yellow-50">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">#</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Name</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Scheduled Time</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Floor Access</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Security Verification</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Status</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse($expectedVisits as $visit)
                                        <tr class="border-b transition-colors hover:bg-orange-25">
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{ $loop->iteration }}</td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                                {{ $visit->visitor->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->staff->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ \Carbon\Carbon::parse($visit->visit_date)->format('H:i A') }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-gray-200">
                                                    {{ $visit->floor_of_visit }}
                                                </span>
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                <div class="flex items-center gap-1">
                                                    @if($visit->verification_passed)
                                                    <span class="text-green-600">Successful</span>
                                                    @elseif (is_null($visit->arrived_at_gate))
                                                    <span class="text-yellow-600">Not Verified</span>
                                                    @else
                                                    <span class="text-red-600">Failed</span>
                                                    @endif

                                                    @if($visit->verification_message)
                                                    <div class="group relative">
                                                        <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                        <div class="absolute z-10 left-0 mt-2 w-64 p-2 text-sm bg-white border border-gray-200 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                                            {{ $visit->verification_message }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                @if($visit->arrived_at_gate)
                                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                    Arrived at {{ \Carbon\Carbon::parse($visit->arrived_at_gate)->format('H:i A') }}
                                                </span>
                                                @else
                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                    Awaiting Arrival
                                                </span>
                                                @endif
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right space-x-2">
                                                <button onclick="checkInVisitor({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 px-3 bg-[#07ab8c]  hover:bg-primary text-white">
                                                    Check In
                                                </button>
                                                <button onclick="issueCard({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                                    <i class="fas fa-id-card mr-2"></i>Issue Card
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="p-4 text-center text-muted-foreground">No visits expected today.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <!-- Checked In Tab Content -->
                    <div id="checked-in" class="tab-content checked-in hidden">
                        <div class="rounded-md border">
                            <div class="relative w-full overflow-auto">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b bg-green-50">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">#</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Name</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Checked In At</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Check-In By</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Number</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Issued At</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse($checkedInVisits as $visit)
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{ $loop->iteration }}</td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                                {{ $visit->visitor->name }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->staff->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ \Carbon\Carbon::parse($visit->checked_in_at)->format('H:i A') }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->checkin_by ?? '-' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-green-100 text-green-800">
                                                    {{ $visit->accessCard->serial_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->card_issued_at ? \Carbon\Carbon::parse($visit->card_issued_at)->format('H:i A') : '-' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                                <button onclick="checkOutVisitor({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 border-red-200 text-red-600 hover:bg-red-50">
                                                    Check Out
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="p-4 text-center text-muted-foreground">No checked in visitors today.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Checked Out Tab Content -->
                    <div id="checked-out" class="tab-content checked-out hidden">
                        <div class="rounded-md border">
                            <div class="relative w-full overflow-auto">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b bg-blue-50">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">#</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Name</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Checked Out At</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Check-Out By</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Number</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Retrieved At</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse($checkedOutVisits as $visit)
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{ $loop->iteration }}</td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                                {{ $visit->visitor->name }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->staff->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ \Carbon\Carbon::parse($visit->checked_out_at)->format('H:i A') }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->checkout_by ?? '-' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-blue-100 text-blue-800">
                                                    {{ $visit->accessCard->serial_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->card_retrieved_at ? \Carbon\Carbon::parse($visit->card_retrieved_at)->format('H:i A') : '-' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="p-4 text-center text-muted-foreground">No checked out visitors today.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





{{-- <div class="rounded-lg border bg-white shadow-sm">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight text-gray-700">Visitor Management</h3>
        <p class="text-sm text-gray-500">
            Check-in • Card Issuance • Check-out • Card Retrieval
        </p>
    </div>

    <div class="p-6 pt-0">
        <!-- Tabs -->
        <div class="mb-6">
            <div class="inline-flex h-10 items-center justify-center rounded-md bg-gray-100 p-1 text-gray-500 w-full">
                <div class="grid grid-cols-3 w-full">
                    <!-- Expected Today -->
                    <button data-tab="expected-today" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm relative active" data-state="active">
                        <span class="hidden sm:inline">Expected Today</span>
                        <span class="sm:hidden">Today</span>
                        @if($expectedVisits->count() > 0)
                        <span class="ml-2 inline-flex items-center rounded-full border border-transparent bg-red-500 px-1.5 py-0.5 text-xs font-medium text-white">
                            {{ $expectedVisits->count() }}
                        </span>
                        @endif
                    </button>

                    <!-- Checked In -->
                    <button data-tab="checked-in" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm">
                        <span class="hidden sm:inline">Checked In</span>
                        <span class="sm:hidden">In</span>
                        <span class="ml-1">({{ $checkedInVisits->count() }})</span>
                    </button>

                    <!-- Checked Out -->
                    <button data-tab="checked-out" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm">
                        <span class="hidden sm:inline">Checked Out</span>
                        <span class="sm:hidden">Out</span>
                        <span class="ml-1">({{ $checkedOutVisits->count() }})</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Contents -->
        <div>
            <!-- Expected Today Tab Content -->
            <div id="expected-today" class="tab-content expected-today">
                <div class="rounded-md border">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b bg-yellow-50">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">#</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Name</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Scheduled Time</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Floor Access</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Security Verification</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Status</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse($expectedVisits as $visit)
                                <tr class="border-b transition-colors hover:bg-orange-25">
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{ $loop->iteration }}</td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                        {{ $visit->visitor->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->staff->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ \Carbon\Carbon::parse($visit->visit_date)->format('H:i A') }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-gray-200">
                                            {{ $visit->floor_of_visit }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        <div class="flex items-center gap-1">
                                            @if($visit->verification_passed)
                                            <span class="text-green-600">Successful</span>
                                            @else
                                            <span class="text-red-600">Failed</span>
                                            @endif

                                            @if($visit->verification_message)
                                            <div class="group relative">
                                                <button class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                                <div class="absolute z-10 left-0 mt-2 w-64 p-2 text-sm bg-white border border-gray-200 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                                    {{ $visit->verification_message }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        @if($visit->arrived_at_gate)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            Arrived at {{ \Carbon\Carbon::parse($visit->arrived_at_gate)->format('H:i A') }}
                                        </span>
                                        @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                            Awaiting Arrival
                                        </span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right space-x-2">
                                        <button onclick="checkInVisitor({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 px-3 bg-[#07ab8c]  hover:bg-primary text-white">
                                            Check In
                                        </button>
                                        <button onclick="issueCard({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                            <i class="fas fa-id-card mr-2"></i>Issue Card
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="p-4 text-center text-muted-foreground">No visits expected today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <!-- Checked In Tab Content -->
            <div id="checked-in" class="tab-content checked-in hidden">
                <div class="rounded-md border">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b bg-green-50">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">#</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Name</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Checked In At</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Check-In By</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Number</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Issued At</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse($checkedInVisits as $visit)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{ $loop->iteration }}</td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                        {{ $visit->visitor->name }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->staff->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ \Carbon\Carbon::parse($visit->checked_in_at)->format('H:i A') }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->checkin_by ?? '-' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-green-100 text-green-800">
                                            {{ $visit->accessCard->serial_number ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->card_issued_at ? \Carbon\Carbon::parse($visit->card_issued_at)->format('H:i A') : '-' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right">
                                        <button onclick="checkOutVisitor({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 border-red-200 text-red-600 hover:bg-red-50">
                                            Check Out
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="p-4 text-center text-muted-foreground">No checked in visitors today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Checked Out Tab Content -->
            <div id="checked-out" class="tab-content checked-out hidden">
                <div class="rounded-md border">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b bg-blue-50">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">#</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Name</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Checked Out At</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Check-Out By</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Number</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Retrieved At</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse($checkedOutVisits as $visit)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">{{ $loop->iteration }}</td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                        {{ $visit->visitor->name }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->staff->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ \Carbon\Carbon::parse($visit->checked_out_at)->format('H:i A') }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->checkout_by ?? '-' }}
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-blue-100 text-blue-800">
                                            {{ $visit->accessCard->serial_number ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                        {{ $visit->card_retrieved_at ? \Carbon\Carbon::parse($visit->card_retrieved_at)->format('H:i A') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-muted-foreground">No checked out visitors today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div> --}}


    </main>

    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        // Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize first tab as active
    const defaultTab = document.querySelector('[data-tab="expected-today"]');
    if (defaultTab) {
        defaultTab.classList.add('active');
        defaultTab.setAttribute('data-state', 'active');
        document.getElementById('expected-today').classList.remove('hidden');
    }

    // Tab click handler
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Update all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'text-gray-900', 'shadow-sm');
                btn.classList.add('text-gray-500');
                btn.removeAttribute('data-state');
            });

            // Update clicked button
            this.classList.add('active', 'text-gray-900', 'shadow-sm');
            this.classList.remove('text-gray-500');
            this.setAttribute('data-state', 'active');

            // Update all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected content
            document.querySelector(`.${tabId}`).classList.remove('hidden');
            document.getElementById(tabId).classList.remove('hidden');
        });
    });
});


        // Search functionality
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;

            searchTimeout = setTimeout(() => {
                if (query.length >= 2) {
                    fetch(`{{ route('reception.search') }}?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Search results:', data);
                        // Implement search results display here
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
                }
            }, 300);
        });

        // Check in visitor
        function checkInVisitor(visitId) {
            if (confirm('Are you sure you want to check in this visitor?')) {
                fetch(`{{ route('reception.dashboard') }}/check-in/${visitId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert(data.error || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Check-in error:', error);
                    alert('An error occurred while checking in the visitor');
                });
            }
        }

        // Check out visitor
        function checkOutVisitor(visitId) {
            if (confirm('Are you sure you want to check out this visitor?')) {
                fetch(`{{ route('reception.dashboard') }}/check-out/${visitId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert(data.error || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Check-out error:', error);
                    alert('An error occurred while checking out the visitor');
                });
            }
        }



    </script>
</body>
</html>



