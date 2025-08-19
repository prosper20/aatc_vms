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
<body class="bg-gray-50 w-full">
    <!-- Header -->
    <header class="bg-white text-gray-900 shadow-lg sticky top-0 z-50">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 lg:py-6 md:py-4">
                <!-- Logo/Branding Section -->
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Logo" class="h-10 w-auto md:h-12">
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg md:text-2xl font-semibold leading-tight">Visitor Management Center</h1>
                        <p class="text-xs md:text-[14px] opacity-90">VMC Portal</p>
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
            <form method="GET" action="{{ route('reception.dashboard') }}" class="relative" id="searchForm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input id="searchInput" type="text" name="search"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#d3f2ee] focus:bg-white transition-colors duration-200"
                    placeholder="Search visitors"
                    value="{{ request('search') }}">
                <input type="hidden" name="tab" id="currentTab" value="{{ request('tab', 'expected-today') }}">
                <!-- Add hidden fields to preserve pagination -->
                <input type="hidden" name="approved_page" value="{{ request('approved_page', 1) }}">
                <input type="hidden" name="checked_in_page" value="{{ request('checked_in_page', 1) }}">
                <input type="hidden" name="checked_out_page" value="{{ request('checked_out_page', 1) }}">
            </form>
            </div>
            <a href="{{ route('reception.dashboard.guests') }}" class="bg-primary hover:bg-teal-700 text-white px-4 sm:px-6 py-3 rounded-lg font-medium transition-colors whitespace-nowrap flex items-center justify-center text-base">
                <i class="fas fa-user-plus mr-2 text-base"></i>
                <span>Add</span>
            </a>
        </div>

        <!-- Statistics Cards -->

        <!-- Visitor Management Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 ">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-700">{{ __('Visitor Management') }}</h3>
                            <p class="text-sm text-gray-500">Walk-in • Card Issuance • Card Retrieval • Check-out </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-0">
                <!-- Tabs -->
                <div class="mb-6">
                    <div class="inline-flex h-10 items-center justify-center rounded-md bg-gray-100 p-2 text-gray-500 w-full">
                        <div class="grid grid-cols-3 w-full">
                            <!-- Approved -->
                            <button data-tab="expected-today" class="tab-button inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm relative active" data-state="active">
                                <span class="hidden sm:inline">Approved</span>
                                <span class="sm:hidden">Today</span>
                                @if($approvedPendingCheckin->count() > 0)
                                <span class="ml-2 inline-flex items-center rounded-full border border-transparent bg-[#07ab8c] px-1.5 py-0.5 text-xs font-medium text-white">
                                    {{ $approvedPendingCheckin->count() }}
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
                    <!-- Approved Tab Content -->
                    <div id="expected-today" class="tab-content expected-today">
                        <div class="rounded-md ">
                            <div class="relative w-full overflow-auto">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b bg-yellow-50">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Visitor</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Contact</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Visit Date</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Floor</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Security Verification</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse($approvedPendingCheckin as $visit)
                                        <tr class="border-b transition-colors hover:bg-orange-25">
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                                {{ $visit->visitor->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->visitor->phone ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->staff->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d h:i A') }}
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
                                                    <span class="text-yellow-600">Awaiting Arrival</span>
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
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right space-x-2">
                                                <button onclick="issueCard({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                                    <i class="fas fa-id-card mr-2"></i>Issue Card
                                                </button>
                                                <button onclick="openPrintCardModal({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 px-3 bg-[#07ab8c] hover:bg-primary text-white">
                                                    <i class="fas fa-print mr-2"></i>Print Card
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

                                @if($approvedPendingCheckin->hasPages())
                                    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                        {{ $approvedPendingCheckin->appends(['search' => request('search')])->links() }}
                                    </div>
                                @endif

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
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Visitor</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Checked In At</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Number</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Issued At</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Issued By</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse($checkedInVisits as $visit)
                                        <tr class="border-b transition-colors hover:bg-muted/50">
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
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-green-100 text-green-800">
                                                    {{ $visit->accessCard->serial_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->card_issued_at ? \Carbon\Carbon::parse($visit->card_issued_at)->format('H:i A') : '-' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->accessCard->issued_by ?? '-' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right space-x-2">
                                                <button onclick="issueCard({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                                    <i class="fas fa-id-card mr-2"></i>Issue Card
                                                </button>
                                                <button onclick="openPrintCardModal({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 px-3 bg-[#07ab8c] hover:bg-primary text-white">
                                                    <i class="fas fa-print mr-2"></i>Print Card
                                                </button>
                                                <button onclick="openCheckoutModal({{ $visit->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 border-red-200 text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-sign-out-alt mr-2"></i>Check Out
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

                                @if($checkedInVisits->hasPages())
                                    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                        {{ $checkedInVisits->appends(['search' => request('search')])->links() }}
                                    </div>
                                @endif

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
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Visitor</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Contact</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Host</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Visit Date</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Check-out Time</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Floor/Venue</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Reason</th>
                                            <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 [&:has([role=checkbox])]:pr-0">Card Number</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse($checkedOutVisits as $visit)
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">
                                                {{ $visit->visitor->name }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->visitor->phone ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->staff->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d') }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ \Carbon\Carbon::parse($visit->checked_out_at)->format('H:i A') }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-gray-200">
                                                    {{ $visit->floor_of_visit }}
                                                </span>
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                {{ $visit->reason ?? '-' }}
                                            </td>
                                            <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-blue-100 text-blue-800">
                                                    {{ $visit->accessCard->serial_number ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="p-4 text-center text-muted-foreground">No checked out visitors today.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                @if($checkedOutVisits->hasPages())
                                    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                                        {{ $checkedOutVisits->appreesponds(['search' => request('search')])->links() }}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- <div id="pass-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Issue Visitor Pass</h3>
                    <div class="mb-4">
                        <div id="pass-selection" class="mb-4">
                            <label for="pass-number" class="block text-sm font-medium text-gray-700">Select Pass *</label>
                            <select id="pass-number" name="pass-number"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                   required>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>

                        <div id="no-passes-available" class="mb-4 bg-yellow-50 p-4 rounded-lg">
                            <p class="text-yellow-700">Generate new visiror pass if not available.</p>

                            <div class="mb-6">
                                <label for="floor" class="block text-sm font-medium text-gray-700 mb-2">Floor/Department *</label>
                                <select id="floor" required
                                        class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                    <option value="">Select floor</option>
                                    <option value="Ground Floor">Ground Floor</option>
                                    <option value="Mezzanine">Mezzanine</option>
                                    <option value="Floor 1">Floor 1</option>
                                    <option value="Floor 2 - Right Wing">Floor 2 - Right Wing</option>
                                    <option value="Floor 2 - Left Wing">Floor 2 - Left Wing</option>
                                    <option value="Floor 3 - Right Wing">Floor 3 - Right Wing</option>
                                    <option value="Floor 3 - Left Wing">Floor 3 - Left Wing</option>
                                    <option value="Floor 4 - Right Wing">Floor 4 - Right Wing</option>
                                    <option value="Floor 4 - Left Wing">Floor 4 - Left Wing</option>
                                    <option value="Floor 5 - Right Wing">Floor 5 - Right Wing</option>
                                    <option value="Floor 5 - Left Wing">Floor 5 - Left Wing</option>
                                    <option value="Floor 6 - Right Wing">Floor 6 - Right Wing</option>
                                    <option value="Floor 6 - Left Wing">Floor 6 - Left Wing</option>
                                    <option value="Floor 7 - Right Wing">Floor 7 - Right Wing</option>
                                    <option value="Floor 7 - Left Wing">Floor 7 - Left Wing</option>
                                    <option value="Floor 8 - Right Wing">Floor 8 - Right Wing</option>
                                    <option value="Floor 8 - Left Wing">Floor 8 - Left Wing</option>
                                    <option value="Floor 9 - Right Wing">Floor 9 - Right Wing</option>
                                    <option value="Floor 9 - Left Wing">Floor 9 - Left Wing</option>
                                </select>
                            </div>

                            <button onclick="generateNewPass()" class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary hover:bg-teal-700 focus:outline-none">
                                Generate Pass
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="issueVisitorPass()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Issue Pass
                    </button>
                    <button type="button" onclick="closePassModal()"
                            class="mt-3 w-full inline-flex justify-center px-4 py-2 rounded-md border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="access-card-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Access Card</h3>

                    <!-- Card Template -->
                    <div id="access-card-template" class="border-2 border-gray-200 rounded-lg p-4 bg-white shadow-md w-full max-w-xs mx-auto">
                        <div class="flex justify-between items-center mb-4">
                            <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Company Logo" class="h-10">
                            <div class="text-xs text-gray-500">ACCESS CARD</div>
                        </div>

                        <div class="text-center mb-4">
                            <div class="w-24 h-24 mx-auto bg-gray-200 rounded-full overflow-hidden mb-2">
                                <!-- Visitor photo placeholder -->
                                <div id="visitor-photo-placeholder" class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-user text-4xl"></i>
                                </div>
                            </div>
                            <h3 id="card-visitor-name" class="text-lg font-semibold"></h3>
                            <p id="card-visitor-company" class="text-sm text-gray-600"></p>
                        </div>

                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500">Host:</span>
                                <span id="card-host-name" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500">Valid Until:</span>
                                <span id="card-valid-until" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Card No:</span>
                                <span id="card-serial-number" class="font-medium"></span>
                            </div>
                        </div>

                        <div class="mt-4 text-center text-xs text-gray-400">
                            This card remains property of {{ config('app.name') }}
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="printAccessCard()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-print mr-2"></i> Print Card
                    </button>
                    <button type="button" onclick="closeAccessCardModal()"
                            class="mt-3 w-full inline-flex justify-center px-4 py-2 rounded-md border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <div id="checkout-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Checkout Visitor</h3>

                    <div id="checkout-card-info" class="hidden mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium">Card to retrieve:</p>
                        <p id="checkout-card-number" class="font-semibold"></p>
                    </div>

                    <div class="mb-4">
                        <label for="checkout-notes" class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                        <textarea id="checkout-notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="processCheckout()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Checkout
                    </button>
                    <button type="button" onclick="closeCheckoutModal()"
                            class="mt-3 w-full inline-flex justify-center px-4 py-2 rounded-md border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- New Code --}}
    <div id="issue-card-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Issue Card</h3>

                    <!-- Current Card Status -->
                    <div id="current-card-status" class="mb-4 p-3 bg-gray-50 rounded-lg hidden">
                        <p class="text-sm font-medium text-gray-700">Current Card:</p>
                        <div class="flex items-center justify-between mt-1">
                            <span id="current-card-info" class="font-semibold"></span>
                            <button onclick="retrieveAndIssueNew()" class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                Retrieve & Issue New
                            </button>
                        </div>
                    </div>

                    <!-- Card Type Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectCardType('visitor_pass')"
                                    class="card-type-btn p-3 border-2 border-gray-200 rounded-lg hover:border-primary text-center transition-colors"
                                    data-type="visitor_pass">
                                <i class="fas fa-id-badge text-2xl text-gray-400 mb-2"></i>
                                <p class="font-medium">Visitor Pass</p>
                                <p class="text-xs text-gray-500">Reusable pass</p>
                            </button>
                            <button type="button" onclick="selectCardType('access_card')"
                                    class="card-type-btn p-3 border-2 border-gray-200 rounded-lg hover:border-primary text-center transition-colors"
                                    data-type="access_card">
                                <i class="fas fa-id-card text-2xl text-gray-400 mb-2"></i>
                                <p class="font-medium">Access Card</p>
                                <p class="text-xs text-gray-500">Personal card</p>
                            </button>
                        </div>
                    </div>

                    <!-- Visitor Pass Options -->
                    <div id="visitor-pass-options" class="hidden">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Option</label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="pass-option" value="existing" class="mr-2">
                                    <span>Use Existing Pass</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="pass-option" value="generate" class="mr-2">
                                    <span>Generate New Pass</span>
                                </label>
                            </div>
                        </div>

                        <!-- Existing Pass Selection -->
                        <div id="existing-pass-section" class="mb-4 hidden">
                            <label for="pass-select" class="block text-sm font-medium text-gray-700 mb-2">Select Pass</label>
                            <select id="pass-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Choose a pass...</option>
                            </select>
                        </div>

                        <!-- Generate New Pass Section -->
                        <div id="generate-pass-section" class="mb-4 hidden">
                            <label for="floor-select" class="block text-sm font-medium text-gray-700 mb-2">Floor/Department</label>
                            <select id="floor-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Select floor...</option>
                                <option value="Ground Floor">Ground Floor</option>
                                <option value="Mezzanine">Mezzanine</option>
                                <option value="Floor 1">Floor 1</option>
                                <option value="Floor 2 - Right Wing">Floor 2 - Right Wing</option>
                                <option value="Floor 2 - Left Wing">Floor 2 - Left Wing</option>
                                <option value="Floor 3 - Right Wing">Floor 3 - Right Wing</option>
                                <option value="Floor 3 - Left Wing">Floor 3 - Left Wing</option>
                                <option value="Floor 4 - Right Wing">Floor 4 - Right Wing</option>
                                <option value="Floor 4 - Left Wing">Floor 4 - Left Wing</option>
                                <option value="Floor 5 - Right Wing">Floor 5 - Right Wing</option>
                                <option value="Floor 5 - Left Wing">Floor 5 - Left Wing</option>
                                <option value="Floor 6 - Right Wing">Floor 6 - Right Wing</option>
                                <option value="Floor 6 - Left Wing">Floor 6 - Left Wing</option>
                                <option value="Floor 7 - Right Wing">Floor 7 - Right Wing</option>
                                <option value="Floor 7 - Left Wing">Floor 7 - Left Wing</option>
                                <option value="Floor 8 - Right Wing">Floor 8 - Right Wing</option>
                                <option value="Floor 8 - Left Wing">Floor 8 - Left Wing</option>
                                <option value="Floor 9 - Right Wing">Floor 9 - Right Wing</option>
                                <option value="Floor 9 - Left Wing">Floor 9 - Left Wing</option>
                            </select>
                        </div>
                    </div>

                    <!-- Access Card Info -->
                    <div id="access-card-info" class="hidden mb-4 p-3 bg-[#fffdf4] rounded-lg">
                        <p class="text-sm text-[#d9ac00]">
                            <i class="fas fa-info-circle mr-1"></i>
                            Access cards are generated automatically with visitor details and have 1-week validity.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="issue-card-btn" type="button" onclick="issueSelectedCard()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Issue Card
                    </button>
                    <button type="button" onclick="closeIssueCardModal()"
                            class="mt-3 w-full inline-flex justify-center px-4 py-2 rounded-md border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Card Modal (Unified for both types) -->
    <div id="print-card-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Print Card</h3>

                    <!-- No Card Issued Message -->
                    <div id="no-card-message" class="hidden text-center py-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700">No Card Generated</p>
                        <p class="text-gray-500 mb-4">Please generate a card first before printing.</p>
                        <button onclick="closeAndOpenIssueModal()" class="bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                            Generate Card
                        </button>
                    </div>

                    <!-- Card Preview -->
                    <div id="card-preview" class="hidden">
                        <div class="mb-6">
                            <div class="min-w-full inline-flex h-10 items-center justify-center bg-gray-100 px-1 py-2 text-gray-500 w-full max-w-xs mx-auto">
                              <div class="grid grid-cols-2 w-full">
                                <!-- Front Side -->
                                <button onclick="showCardSide('front')" id="front-btn"
                                        class="side-btn inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all
                                        focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50
                                        data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm relative active"
                                        data-state="active">
                                  Front
                                </button>

                                <!-- Back Side -->
                                <button onclick="showCardSide('back')" id="back-btn"
                                        class="side-btn inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all
                                        focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50
                                        data-[state=active]:bg-white data-[state=active]:text-gray-900 data-[state=active]:shadow-sm">
                                  Back
                                </button>
                              </div>
                            </div>
                        </div>

                        <!-- Front Side -->
                        <div id="card-front" class="card-side">
                            <!-- will be populated using html template -->
                        </div>

                        <!-- Back Side -->
                        <div id="card-back" class="card-side hidden">
                            @include('partials.visitor-pass.back')
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <div class="flex space-x-2">
                        <button type="button" onclick="printCard()"
                            class="w-full md:w-auto inline-flex items-center justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#ffcd00] hover:bg-[#e6b800] text-base font-medium text-black
                            focus:outline-none focus:ring-0 focus:border-transparent sm:text-sm">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                    <button type="button" onclick="closePrintCardModal()"
                        class="mt-3 w-full inline-flex justify-center px-4 py-2 rounded-md border border-gray-300 shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50
                        focus:outline-none focus:ring-0 focus:border-gray-300 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <iframe id="print-frame" style="display: none;"></iframe>

    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Replace your existing search-related JavaScript with this
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tabs with current state
            const urlParams = new URLSearchParams(window.location.search);
            const currentTab = urlParams.get('tab') || 'expected-today';
            const searchInput = document.getElementById('searchInput');

            // Set current tab
            document.getElementById('currentTab').value = currentTab;

            // Activate the current tab
            activateTab(currentTab);

            // Tab click handler
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById('currentTab').value = tabId;

                    // Reset pagination for the new tab to page 1
                    resetPaginationForTab(tabId);

                    // Submit the form
                    document.getElementById('searchForm').submit();
                });
            });

            // Form submission handler
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                // If search input is empty, clear the search parameter
                if (!searchInput.value.trim()) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('search');
                    window.location.href = url.toString();
                    e.preventDefault();
                    return;
                }

                // Ensure we're on page 1 for all tabs when searching
                resetAllPagination();
            });

            // Function to activate a tab
            function activateTab(tabId) {
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'text-gray-900', 'shadow-sm');
                    btn.classList.add('text-gray-500');
                    btn.removeAttribute('data-state');
                });

                const tabButton = document.querySelector(`[data-tab="${tabId}"]`);
                if (tabButton) {
                    tabButton.classList.add('active', 'text-gray-900', 'shadow-sm');
                    tabButton.classList.remove('text-gray-500');
                    tabButton.setAttribute('data-state', 'active');
                }

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                const tabContent = document.getElementById(tabId);
                if (tabContent) {
                    tabContent.classList.remove('hidden');
                }
            }

            // Function to reset pagination for a specific tab
            function resetPaginationForTab(tabId) {
                switch(tabId) {
                    case 'expected-today':
                        document.querySelector('input[name="approved_page"]').value = 1;
                        break;
                    case 'checked-in':
                        document.querySelector('input[name="checked_in_page"]').value = 1;
                        break;
                    case 'checked-out':
                        document.querySelector('input[name="checked_out_page"]').value = 1;
                        break;
                }
            }

            // Function to reset all pagination to page 1
            function resetAllPagination() {
                document.querySelector('input[name="approved_page"]').value = 1;
                document.querySelector('input[name="checked_in_page"]').value = 1;
                document.querySelector('input[name="checked_out_page"]').value = 1;
            }
        });

        // Visitor Pass functions

        // function issueCard(visitId) {
        //     currentVisitId = visitId;
        //     openPassModal();
        //     fetchPassDetails(visitId);
        // }

//         function openPassModal() {
//             document.getElementById('pass-modal').classList.remove('hidden');
//         }

//         function closePassModal() {
//             document.getElementById('pass-modal').classList.add('hidden');
//         }

//         function fetchPassDetails(visitId) {
//             fetch(`/reception/visits/${visitId}/pass-details`, {
//                 method: 'GET',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': csrfToken
//                 }
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     // Populate visitor info
//                     // document.getElementById('visitor-name').textContent = data.visitor.name;
//                     // document.getElementById('host-name').textContent = data.staff.name;

//                     // Populate pass dropdown
//                     const passSelect = document.getElementById('pass-number');
//                     passSelect.innerHTML = '';

//                     if (data.availablePasses.length > 0) {
//                         data.availablePasses.forEach(pass => {
//                             const option = document.createElement('option');
//                             option.value = pass.id;
//                             option.textContent = pass.serial_number;
//                             passSelect.appendChild(option);
//                         });

//                         // document.getElementById('no-passes-available').classList.add('hidden');
//                         // document.getElementById('pass-selection').classList.remove('hidden');
//                     } else {
//                         // document.getElementById('no-passes-available').classList.remove('hidden');
//                         // document.getElementById('pass-selection').classList.add('hidden');
//                     }
//                 } else {
//                     alert(data.error || 'Failed to fetch pass details');
//                     closePassModal();
//                 }
//             })
//             .catch(error => {
//                 console.error('Error:', error);
//                 alert('Error fetching pass details');
//                 closePassModal();
//             });
//         }

//         function issueVisitorPass() {
//             const passId = document.getElementById('pass-number').value;

//             if (!passId) {
//                 alert('Please select a pass');
//                 return;
//             }

//             fetch(`/reception/visits/${currentVisitId}/issue-pass`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': csrfToken
//                 },
//                 body: JSON.stringify({
//                     pass_id: passId,
//                 })
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     alert('Visitor pass issued successfully');
//                     closePassModal();
//                     location.reload();
//                 } else {
//                     alert(data.error || 'Failed to issue visitor pass');
//                 }
//             })
//             .catch(error => {
//                 console.error('Error:', error);
//                 alert('Error issuing visitor pass');
//             });
//         }

//         function generateNewPass() {
//     const floor = document.getElementById('floor').value;

//     if (!floor) {
//         alert('Please select a floor before generating a pass');
//         return;
//     }

//     fetch('/reception/visitor-passes/generate', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken
//         },
//         body: JSON.stringify({ floor })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             alert('New pass generated successfully');
//             fetchPassDetails(currentVisitId);
//         } else {
//             alert(data.error || 'Failed to generate new pass');
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         alert('Error generating new pass');
//     });
// }


//         function printPass(visitId) {
//             window.open(`/reception/visits/${visitId}/print-pass`, '_blank');
//         }

//         // access card functions
//         let currentAccessCardVisitId = null;

//         function openAccessCardModal(visitId) {
//             currentAccessCardVisitId = visitId;
//             fetchAccessCardDetails(visitId);
//             document.getElementById('access-card-modal').classList.remove('hidden');
//         }

//         function closeAccessCardModal() {
//             document.getElementById('access-card-modal').classList.add('hidden');
//         }

//         function fetchAccessCardDetails(visitId) {
//             fetch(`/reception/visits/${visitId}/access-card-details`, {
//                 method: 'GET',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': csrfToken
//                 }
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     // Populate card details
//                     document.getElementById('card-visitor-name').textContent = data.visitor.name;
//                     document.getElementById('card-visitor-company').textContent = data.visitor.organization || 'Visitor';
//                     document.getElementById('card-host-name').textContent = data.staff.name;
//                     document.getElementById('card-serial-number').textContent = data.accessCard.serial_number;

//                     // Format valid until date
//                     const validUntil = new Date(data.accessCard.valid_until);
//                     document.getElementById('card-valid-until').textContent =
//                         validUntil.toLocaleDateString() + ' ' + validUntil.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

//                     // Set visitor photo if available
//                     if (data.visitor.photo_url) {
//                         document.getElementById('visitor-photo-placeholder').innerHTML =
//                             `<img src="${data.visitor.photo_url}" alt="Visitor Photo" class="w-full h-full object-cover">`;
//                     }
//                 } else {
//                     alert(data.error || 'Failed to fetch access card details');
//                     closeAccessCardModal();
//                 }
//             })
//             .catch(error => {
//                 console.error('Error:', error);
//                 alert('Error fetching access card details');
//                 closeAccessCardModal();
//             });
//         }

//         function printAccessCard() {
//             // First issue the card (if not already issued)
//             issueAccessCard(currentAccessCardVisitId)
//                 .then(() => {
//                     // Then open print view
//                     window.open(`/reception/visits/${currentAccessCardVisitId}/print-access-card`, '_blank');
//                     closeAccessCardModal();
//                 })
//                 .catch(error => {
//                     console.error('Error:', error);
//                     alert('Error issuing access card');
//                 });
//         }

//         function issueAccessCard(visitId) {
//             return fetch(`/reception/visits/${visitId}/issue-access-card`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': csrfToken
//                 }
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (!data.success) {
//                     throw new Error(data.error || 'Failed to issue access card');
//                 }
//                 return data;
//             });
//         }

//         // Check-out functions
//         let currentCheckoutVisitId = null;

        function openCheckoutModal(visitId) {
            currentCheckoutVisitId = visitId;
            fetchCheckoutDetails(visitId);
            document.getElementById('checkout-modal').classList.remove('hidden');
        }

        function closeCheckoutModal() {
            document.getElementById('checkout-modal').classList.add('hidden');
        }

        function fetchCheckoutDetails(visitId) {
            fetch(`/reception/visits/${visitId}/checkout-details`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show card info if visitor has an access card
                    if (data.hasAccessCard) {
                        document.getElementById('checkout-card-info').classList.remove('hidden');
                        document.getElementById('checkout-card-number').textContent =
                            data.accessCard.serial_number + ' (' + data.accessCard.access_level + ')';
                    } else {
                        document.getElementById('checkout-card-info').classList.add('hidden');
                    }
                } else {
                    alert(data.error || 'Failed to fetch checkout details');
                    closeCheckoutModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching checkout details');
                closeCheckoutModal();
            });
        }

        function processCheckout() {
            const notes = document.getElementById('checkout-notes').value;

            fetch(`/reception/visits/${currentCheckoutVisitId}/checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Visitor checked out successfully');
                    closeCheckoutModal();
                    location.reload();
                } else {
                    alert(data.error || 'Failed to checkout visitor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error checking out visitor');
            });
        }
    </script>

    <script>
        let currentVisitId = null;
        let currentCardType = null;
        let isRetrieveAndIssue = false;
        let globalCardSide = 'front';

        // Updated JavaScript functions for unified card system

        function issueCard(visitId) {
            currentVisitId = visitId;
            isRetrieveAndIssue = false;
            openIssueCardModal();
            fetchCardDetails(visitId);
        }

        function openPrintCardModal(visitId) {
            currentVisitId = visitId;
            document.getElementById('print-card-modal').classList.remove('hidden');
            fetchPrintDetails(visitId);
        }

        function openIssueCardModal() {
            document.getElementById('issue-card-modal').classList.remove('hidden');
            resetCardTypeSelection();
        }

        function closeIssueCardModal() {
            document.getElementById('issue-card-modal').classList.add('hidden');
            resetCardTypeSelection();
        }

        function closePrintCardModal() {
            document.getElementById('print-card-modal').classList.add('hidden');
        }

        function closeAndOpenIssueModal() {
            closePrintCardModal();
            openIssueCardModal();
            fetchCardDetails(currentVisitId);
        }

        function fetchCardDetails(visitId) {
            fetch(`/reception/visits/${visitId}/card-details`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle current card status
                    if (data.hasCard) {
                        document.getElementById('current-card-status').classList.remove('hidden');
                        document.getElementById('current-card-info').textContent =
                            `${data.currentCard.serial_number} (${data.cardType === 'visitor_pass' ? 'Visitor Pass' : 'Access Card'})`;
                    } else {
                        document.getElementById('current-card-status').classList.add('hidden');
                    }

                    // Populate available passes
                    const passSelect = document.getElementById('pass-select');
                    passSelect.innerHTML = '<option value="">Choose a pass...</option>';
                    data.availablePasses.forEach(pass => {
                        const option = document.createElement('option');
                        option.value = pass.id;
                        option.textContent = pass.serial_number;
                        passSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching card details');
            });
        }

        function fetchPrintDetails(visitId) {
            fetch(`/reception/visits/${visitId}/print-details`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show card preview
                    document.getElementById('no-card-message').classList.add('hidden');
                    document.getElementById('card-preview').classList.remove('hidden');

                    populateCardPreview(data);
                } else {
                    document.getElementById('no-card-message').classList.remove('hidden');
                    document.getElementById('card-preview').classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching print details');
            });
        }

        function formatDuration(startDate, endDate) {
            const totalSeconds = Math.floor((endDate - startDate) / 1000);
            if (totalSeconds <= 0) return 'Expired';

            const units = [
                { label: 'month', seconds: 30 * 24 * 60 * 60 },
                { label: 'week', seconds: 7 * 24 * 60 * 60 },
                { label: 'day', seconds: 24 * 60 * 60 },
                { label: 'hour', seconds: 60 * 60 },
                { label: 'minute', seconds: 60 },
            ];

            for (const { label, seconds } of units) {
                const value = Math.floor(totalSeconds / seconds);
                if (value > 0) {
                    return `${value} ${label}${value > 1 ? 's' : ''}`;
                }
            }

            return 'Less than a minute';
        }


        function loadCardTemplate(side = 'front', type = 'pass') {

            if (type === 'access'){
                return fetch(`/templates/${type}.html`)
                .then(res => res.text())
                .then(html => {
                    const templateWrapper = document.createElement('div');
                    templateWrapper.innerHTML = html;
                    return templateWrapper.firstElementChild;
                });
            }

            return fetch(`/templates/${type}.html`)
                .then(res => res.text())
                .then(html => {
                    const templateWrapper = document.createElement('div');
                    templateWrapper.innerHTML = html;
                    return templateWrapper.firstElementChild;
                });
        }

        async function populateCardPreview(data) {
            const card = data.card;
            const visitor = data.visitor;
            const staff = data.staff;
            const decoded = data.decoded;

            if ( card.card_type === 'access_card'){
                // Load template
                const accessEl = await loadCardTemplate('front', 'access');

                accessEl.querySelector('[data-field="name"]').textContent = visitor.name || '';
                const validUntil = new Date(card.valid_until);
                const now = new Date();

                const duration = formatDuration(now, validUntil);
                accessEl.querySelector('[data-field="duration"]').textContent = duration;

                currentVisitorId = visitor.id;
                loadExistingPhoto();

                document.getElementById('card-front').innerHTML = '';
                document.getElementById('card-front').appendChild(accessEl);

                // currentVisitorId = visitor.id;
                // loadExistingPhoto();

            } else {
                // Load template
                const passEl = await loadCardTemplate('front', 'pass');

                passEl.querySelector('[data-field="floor"]').textContent = decoded.floor || '';
                passEl.querySelector('[data-field="wing"]').textContent = decoded.wing || '';
                passEl.querySelector('[data-field="pass_id"]').textContent = decoded.pass_id || '';

                document.getElementById('card-front').innerHTML = '';
                document.getElementById('card-front').appendChild(passEl);
            }
        }

        function selectCardType(type) {
            currentCardType = type;

            // Update UI
            document.querySelectorAll('.card-type-btn').forEach(btn => {
                btn.classList.remove('border-primary', 'bg-blue-50');
                btn.classList.add('border-gray-200');
            });

            document.querySelector(`[data-type="${type}"]`).classList.add('border-primary', 'bg-blue-50');
            document.querySelector(`[data-type="${type}"]`).classList.remove('border-gray-200');

            // Show/hide relevant sections
            if (type === 'visitor_pass') {
                document.getElementById('visitor-pass-options').classList.remove('hidden');
                document.getElementById('access-card-info').classList.add('hidden');
                document.getElementById('issue-card-btn').textContent = 'Issue Pass';
            } else {
                document.getElementById('visitor-pass-options').classList.add('hidden');
                document.getElementById('access-card-info').classList.remove('hidden');
                document.getElementById('issue-card-btn').textContent = 'Generate Card';
            }
        }

        function resetCardTypeSelection() {
            currentCardType = null;
            document.querySelectorAll('.card-type-btn').forEach(btn => {
                btn.classList.remove('border-primary', 'bg-blue-50');
                btn.classList.add('border-gray-200');
            });
            document.getElementById('visitor-pass-options').classList.add('hidden');
            document.getElementById('access-card-info').classList.add('hidden');
            document.getElementById('existing-pass-section').classList.add('hidden');
            document.getElementById('generate-pass-section').classList.add('hidden');

            // Reset radio buttons
            document.querySelectorAll('input[name="pass-option"]').forEach(radio => {
                radio.checked = false;
            });
        }

        function retrieveAndIssueNew() {
            isRetrieveAndIssue = true;
            document.getElementById('current-card-status').classList.add('hidden');
        }

        // Handle pass option changes
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="pass-option"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'existing') {
                        document.getElementById('existing-pass-section').classList.remove('hidden');
                        document.getElementById('generate-pass-section').classList.add('hidden');
                        document.getElementById('issue-card-btn').textContent = 'Issue Pass';
                    } else {
                        document.getElementById('existing-pass-section').classList.add('hidden');
                        document.getElementById('generate-pass-section').classList.remove('hidden');
                        document.getElementById('issue-card-btn').textContent = 'Generate Pass';
                    }
                });
            });
        });

        function issueSelectedCard() {
            if (!currentCardType) {
                alert('Please select a card type');
                return;
            }

            const payload = {
                card_type: currentCardType,
                action: isRetrieveAndIssue ? 'retrieve_and_issue' : 'issue',
                pass_id: null,
                floor: null
            };

            if (currentCardType === 'visitor_pass') {
                const passOption = document.querySelector('input[name="pass-option"]:checked');
                if (!passOption) {
                    alert('Please select a pass option');
                    return;
                }

                if (passOption.value === 'existing') {
                    const passId = document.getElementById('pass-select').value;
                    if (!passId) {
                        alert('Please select a pass');
                        return;
                    }
                    payload.pass_id = passId;
                } else {
                    const floor = document.getElementById('floor-select').value;
                    if (!floor) {
                        alert('Please select a floor');
                        return;
                    }
                    payload.floor = floor;
                }
            }

            fetch(`/reception/visits/${currentVisitId}/issue-card`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Card issued successfully');
                    closeIssueCardModal();
                    location.reload();
                } else {
                    alert(data.error || 'Failed to issue card');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error issuing card');
            });
        }

        function showCardSide(side) {
            // Reset buttons
            document.querySelectorAll('.side-btn').forEach(btn => {
                btn.setAttribute('data-state', 'inactive');
            });

            // Activate clicked button
            const activeBtn = document.getElementById(`${side}-btn`);
            activeBtn.setAttribute('data-state', 'active');

            // Show/hide card sides
            document.querySelectorAll('.card-side').forEach(cardSide => {
                cardSide.classList.add('hidden');
            });

            document.getElementById(`card-${side}`).classList.remove('hidden');
            globalCardSide = side;
        }

        function printCard() {
            const side = globalCardSide;
            const url = `/reception/visits/${currentVisitId}/print-card?side=${side}`;

            const iframe = document.getElementById('print-frame');
            iframe.src = url;

            iframe.onload = function () {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        }

        // function openAccessCardModal(visitId) {
        //     openPrintCardModal(visitId);
        // }

        // Initialize card preview to show front by default
        document.addEventListener('DOMContentLoaded', function() {
            showCardSide('front');
        });
    </script>

<script>
    let currentStream = null;
    let currentVisitorId = null;

    function showPhotoOptions() {
        document.getElementById('photo-options-modal').classList.remove('hidden');
        document.getElementById('photo-options-modal').classList.add('flex');

        // Show remove button if photo exists
        const visitorImage = document.getElementById('visitor-image');
        const removeBtn = document.getElementById('remove-photo-btn');
        if (!visitorImage.classList.contains('hidden')) {
            removeBtn.classList.remove('hidden');
        }
    }

    function closePhotoOptions() {
        document.getElementById('photo-options-modal').classList.add('hidden');
        document.getElementById('photo-options-modal').classList.remove('flex');
        hideUrlInput();
        stopCamera();
    }

    function triggerFileUpload() {
        document.getElementById('photo-upload').click();
    }

    // function handleFileUpload(event) {
    //     const file = event.target.files[0];
    //     if (file) {
    //         const reader = new FileReader();
    //         reader.onload = function(e) {
    //             setVisitorImage(e.target.result);
    //             closePhotoOptions();
    //         };
    //         reader.readAsDataURL(file);
    //     }
    // }

    function showUrlInput() {
        document.getElementById('url-input-section').classList.remove('hidden');
    }

    function hideUrlInput() {
        document.getElementById('url-input-section').classList.add('hidden');
        document.getElementById('image-url-input').value = '';
    }

    // function loadFromUrl() {
    //     const url = document.getElementById('image-url-input').value.trim();
    //     if (url) {
    //         // Test if the URL loads properly
    //         const img = new Image();
    //         img.onload = function() {
    //             setVisitorImage(url);
    //             closePhotoOptions();
    //         };
    //         img.onerror = function() {
    //             alert('Could not load image from URL. Please check the URL and try again.');
    //         };
    //         img.src = url;
    //     }
    // }

    async function openCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            currentStream = stream;
            const video = document.getElementById('camera-video');
            video.srcObject = stream;
            video.play();
            document.getElementById('camera-section').classList.remove('hidden');
        } catch (error) {
            alert('Could not access camera. Please make sure you have given permission and try again.');
            console.error('Camera error:', error);
        }
    }

    // function capturePhoto() {
    //     const video = document.getElementById('camera-video');
    //     const canvas = document.getElementById('camera-canvas');
    //     const context = canvas.getContext('2d');

    //     canvas.width = video.videoWidth;
    //     canvas.height = video.videoHeight;
    //     context.drawImage(video, 0, 0);

    //     const imageDataUrl = canvas.toDataURL('image/png');
    //     setVisitorImage(imageDataUrl);
    //     stopCamera();
    //     closePhotoOptions();
    // }

    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }
        document.getElementById('camera-section').classList.add('hidden');
    }

    function setVisitorImage(src) {
        const defaultIcon = document.getElementById('default-icon');
        const visitorImage = document.getElementById('visitor-image');

        defaultIcon.classList.add('hidden');
        visitorImage.src = src;
        visitorImage.classList.remove('hidden');
    }

    // function removePhoto() {
    //     const defaultIcon = document.getElementById('default-icon');
    //     const visitorImage = document.getElementById('visitor-image');

    //     visitorImage.classList.add('hidden');
    //     visitorImage.src = '';
    //     defaultIcon.classList.remove('hidden');

    //     closePhotoOptions();
    // }

    // Close modal when clicking outside
    document.getElementById('photo-options-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePhotoOptions();
        }
    });

    // updated code
    function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && currentVisitorId) {
        const reader = new FileReader();
        reader.onload = function(e) {
            setVisitorImage(e.target.result);

            // Save to database
            const formData = new FormData();
            formData.append('photo', file);

            savePhotoToDatabase('upload', formData);
        };
        reader.readAsDataURL(file);
    }
}

function loadFromUrl() {
    const url = document.getElementById('image-url-input').value.trim();
    if (url && currentVisitorId) {
        // Test if the URL loads properly
        const img = new Image();
        img.onload = function() {
            setVisitorImage(url);

            // Save to database
            const formData = new FormData();
            formData.append('photo_url', url);

            savePhotoToDatabase('url', formData);
            closePhotoOptions();
        };
        img.onerror = function() {
            alert('Could not load image from URL. Please check the URL and try again.');
        };
        img.src = url;
    }
}

function capturePhoto() {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);

    const imageDataUrl = canvas.toDataURL('image/png');
    setVisitorImage(imageDataUrl);

    if (currentVisitorId) {
        // Save to database
        const formData = new FormData();
        formData.append('photo_data', imageDataUrl);

        savePhotoToDatabase('base64', formData);
    }

    stopCamera();
    closePhotoOptions();
}

function removePhoto() {
    const defaultIcon = document.getElementById('default-icon');
    const visitorImage = document.getElementById('visitor-image');

    visitorImage.classList.add('hidden');
    visitorImage.src = '';
    defaultIcon.classList.remove('hidden');

    if (currentVisitorId) {
        // Delete from database
        fetch(`/reception/visitors/${currentVisitorId}/photo`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Photo deleted successfully');
            } else {
                console.error('Failed to delete photo:', data.error);
            }
        })
        .catch(error => {
            console.error('Error deleting photo:', error);
        });
    }

    closePhotoOptions();
}

// New function to save photo to database
function savePhotoToDatabase(type, formData) {
    if (!currentVisitorId) {
        console.error('No visitor ID available');
        return;
    }

    let endpoint;
    switch(type) {
        case 'upload':
            endpoint = `/reception/visitors/${currentVisitorId}/photo/upload`;
            break;
        case 'base64':
            endpoint = `/reception/visitors/${currentVisitorId}/photo/base64`;
            break;
        case 'url':
            endpoint = `/reception/visitors/${currentVisitorId}/photo/url`;
            break;
        default:
            console.error('Invalid photo save type');
            return;
    }

    fetch(endpoint, {
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
            console.log('Photo saved successfully');
            closePhotoOptions();
        } else {
            console.error('Failed to save photo:', data.error);
            alert('Failed to save photo: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error saving photo:', error);
        alert('An error occurred while saving the photo');
    });
}

// Function to load existing photo when page loads
function loadExistingPhoto() {
    if (!currentVisitorId) return;

    fetch(`/reception/visitors/${currentVisitorId}/photo`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.has_photo) {
            setVisitorImage(data.photo_url);
        }
    })
    .catch(error => {
        console.error('Error loading photo:', error);
    });
}
</script>
</body>
</html>



