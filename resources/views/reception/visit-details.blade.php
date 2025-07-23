@extends('layouts.vmc')

@section('title', 'VMC Dashboard')

@section('body')
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
                        <a href="{{ route('reception.dashboard.guests') }}" class="bg-[#ffcd00] hover:bg-[#e6b800] text-[#00736e] px-4 py-2 rounded-lg text-sm font-medium transition-colors ">
                            <i class="fas fa-arrow-left mr-2 hidden md:inline"></i>Back
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Search and Register Section - Stacked on mobile -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6 justify-between">
            <!-- Empty for now -->
        </div>

        <!-- Visitor Management Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('reception.dashboard.guests') }}#walk-in" class="tab-link py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="walk-in">
                        Walk-in Guest
                    </a>
                    <a href="{{ route('reception.dashboard.guests') }}#staff-guest" class="tab-link py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="staff-guest">
                        Staff Guests
                    </a>
                    <a href="{{ route('reception.dashboard.guests') }}#history" class="tab-link py-4 px-6 border-b-2 font-medium text-sm transition-colors border-[#fecd01] text-[#007570]" data-tab="history" data-state="active">
                        Visitor History
                    </a>
                </nav>
            </div>

            <div class="p-6">
                <div id="history-content" class="bg-white">
                    <div class="border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Visit Details</h3>
                            </div>
                            <div>
                                @if($visit->status == 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        Approved
                                    </span>
                                @elseif($visit->status == 'denied')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                        Denied
                                    </span>
                                @elseif($visit->status == 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                                        Completed
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Visitor Information -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-4">VISITOR DETAILS</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Name</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->visitor->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->visitor->email ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Phone</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->visitor->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Organization</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->visitor->organization ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Visit Information -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-4">VISIT DETAILS</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Purpose</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->reason ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Visit Date</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y h:i A') : 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Floor</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            {{ $visit->floor_of_visit ? 'Floor '.$visit->floor_of_visit : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Staff Information -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-4">HOST INFORMATION</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Staff Name</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->staff->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Visit Status</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1 capitalize">{{ $visit->status ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">Check-in/out</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            @if($visit->is_checked_in && $visit->checked_in_at)
                                                Checked in: {{ \Carbon\Carbon::parse($visit->checked_in_at)->format('M d, Y h:i A') }}
                                            @else
                                                Not checked in
                                            @endif
                                            <br>
                                            @if($visit->is_checked_out && $visit->checked_out_at)
                                                Checked out: {{ \Carbon\Carbon::parse($visit->checked_out_at)->format('M d, Y h:i A') }}
                                            @else
                                                Not checked out
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        @if($visit->notes)
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">ADDITIONAL NOTES</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700">{{ $visit->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Timeline -->
                        <div class="mt-8">
                            <h4 class="text-sm font-medium text-gray-500 mb-4">VISIT TIMELINE</h4>
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-[#07AF8B] flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-calendar-plus text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Visit request created</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time datetime="{{ $visit->created_at }}">{{ $visit->created_at->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    @if($visit->status == 'approved' || $visit->status == 'denied')
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full {{ $visit->status == 'approved' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas {{ $visit->status == 'approved' ? 'fa-check' : 'fa-times' }} text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Visit request {{ $visit->status }}</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time datetime="{{ $visit->updated_at }}">{{ $visit->updated_at->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($visit->is_checked_in && $visit->checked_in_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-sign-in-alt text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Checked in by {{ $visit->checkinBy->name ?? 'security' }}</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time datetime="{{ $visit->checked_in_at }}">{{ \Carbon\Carbon::parse($visit->checked_in_at)->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($visit->card_issued_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-id-card text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Access card issued</p>
                                                        @if($visit->accessCard)
                                                            <p class="text-xs text-gray-400">Serial: {{ $visit->accessCard->serial_number }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time datetime="{{ $visit->card_issued_at }}">
                                                            {{ \Carbon\Carbon::parse($visit->card_issued_at)->format('M d, Y h:i A') }}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($visit->card_retrieved_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-id-card-alt text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Access card retrieved</p>
                                                        @if($visit->accessCard)
                                                            <p class="text-xs text-gray-400">Serial: {{ $visit->accessCard->serial_number }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time datetime="{{ $visit->card_retrieved_at }}">
                                                            {{ \Carbon\Carbon::parse($visit->card_retrieved_at)->format('M d, Y h:i A') }}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($visit->is_checked_out && $visit->checked_out_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-sign-out-alt text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Checked out by {{ $visit->checkoutBy->name ?? 'security' }}</p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time datetime="{{ $visit->checked_out_at }}">{{ \Carbon\Carbon::parse($visit->checked_out_at)->format('M d, Y h:i A') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

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
        // Highlight the current tab based on URL hash
        document.addEventListener('DOMContentLoaded', function() {
            const currentHash = window.location.hash.substring(1);
            const tabLinks = document.querySelectorAll('.tab-link');

            tabLinks.forEach(link => {
                const tabId = link.getAttribute('data-tab');
                if (tabId === currentHash || (!currentHash && tabId === 'history')) {
                    link.classList.add('border-[#fecd01]', 'text-[#007570]');
                    link.classList.remove('border-transparent', 'text-gray-500');
                } else {
                    link.classList.remove('border-[#fecd01]', 'text-[#007570]');
                    link.classList.add('border-transparent', 'text-gray-500');
                }
            });
        });

        function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
    </script>
@endsection
