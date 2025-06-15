@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 w-full">
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white shadow-xl fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0">
        <div class="flex flex-col h-full">
            <!-- Logo Section -->
            <div class="flex items-center justify-center p-6 border-b border-gray-100">
                <div class="flex items-center space-y-3 flex-col">
                    <div class=" bg-gradient-to-br from-[#07AF8B] to-[#007570] rounded-xl flex items-center justify-center">
                        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt={{__("Logo")}} class="h-10 md:h-12">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-[#007570]">Abuja AATC-VMS</h1>
                        <p class="text-xs text-gray-500">Security Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('sm.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-[#07AF8B]/10 hover:text-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-home w-5 h-5 mr-3 text-gray-400 group-hover:text-[#07AF8B]"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('sm.visitor-history') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-[#07AF8B]/10 hover:text-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-history w-5 h-5 mr-3 text-gray-400 group-hover:text-[#07AF8B]"></i>
                    <span class="font-medium">Visitor History</span>
                </a>

                <a href="{{ route('sm.pending-visits') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-[#07AF8B]/10 hover:text-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-clock w-5 h-5 mr-3 text-gray-400 group-hover:text-[#07AF8B]"></i>
                    <span class="font-medium">Pending Visits</span>
                    @if(isset($pendingVisits) && $pendingVisits->count() > 0)
                        <span class="ml-auto bg-[#FFCA00] text-black text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $pendingVisits->count() }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('sm.analytics') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-[#07AF8B]/10 hover:text-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-chart-bar w-5 h-5 mr-3 text-gray-400 group-hover:text-[#07AF8B]"></i>
                    <span class="font-medium">Analytics</span>
                </a>
            </nav>

            <!-- User Profile & Logout -->
            <div class="border-t border-gray-100 p-4">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#07AF8B] to-[#007570] rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('sm')->user()->name ?? 'Security Manager' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::guard('sm')->user()->email ?? 'manager@example.com' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('sm.logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200 group">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 w-full md:ml-0">
        <!-- Enhanced Top Navigation -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                <!-- Left Section: Hamburger + Page Title -->
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-[#07AF8B] hover:text-[#007570] transition-colors duration-200" onclick="openSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="hidden md:block">
                        <h2 class="text-xl font-semibold text-gray-900">Dashboard</h2>
                        <p class="text-sm text-gray-500">Welcome back</p>
                    </div>
                </div>

                <!-- Center Section: Search -->
                <div class="flex-1 max-w-2xl mx-4">
                    <form method="GET" action="{{ route('sm.dashboard') }}" class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B] transition-colors duration-200"
                               placeholder="Search visitors"
                               value="{{ request('search') }}">
                    </form>
                </div>

                <!-- Right Section: Notifications + Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 text-gray-400 hover:text-[#07AF8B] transition-colors duration-200 relative">
                            <i class="fas fa-bell text-lg"></i>
                            @if(isset($pendingVisits) && $pendingVisits->count() > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    {{ $pendingVisits->count() > 9 ? '9+' : $pendingVisits->count() }}
                                </span>
                            @endif
                        </button>
                    </div>

                    <!-- Quick Actions -->
                    {{-- <div class="hidden lg:flex items-center space-x-2">
                        <button onclick="refreshAll()" class="px-3 py-2 text-sm text-gray-600 hover:text-[#07AF8B] hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh
                        </button>
                    </div> --}}
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-4 lg:p-6 space-y-6 w-full">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6" id="stats-container">
                <!-- Visitors Today Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-medium text-gray-600">{{ __('Visitors Today') }}</p>
                            <p class="text-3xl font-bold text-[#007570] mt-2" id="total-today">{{ $visitorsToday }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#07AF8B]/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-[#07AF8B] text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-medium text-gray-600">{{ __('Pending Approvals') }}</p>
                            <p class="text-3xl font-bold text-[#FFCA00] mt-2" id="pending-count">{{ $pendingVisits->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFCA00]/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-[#FFCA00] text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Approved Today Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-medium text-gray-600">{{ __('Approved Today') }}</p>
                            <p class="text-3xl font-bold text-green-600 mt-2" id="approved-today">{{ $approvedToday }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Denied Today Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-medium text-gray-600">{{ __('Denied Today') }}</p>
                            <p class="text-3xl font-bold text-red-600 mt-2" id="denied-today">{{ $deniedToday }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#07AF8B]/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-[#07AF8B]"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('Visitor Approvals') }}</h3>
                                <p class="text-sm text-gray-500">Manage pending visitor requests</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#FFCA00]/10 text-[#FFCA00]" id="pending-badge">
                                <div class="w-2 h-2 bg-[#FFCA00] rounded-full mr-2"></div>
                                {{ $stats['pending_count'] ?? $pendingVisits->count() }} {{ __('Pending') }}
                            </span>
                            {{-- <button onclick="refreshAll()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                <i class="fas fa-sync-alt mr-2"></i>
                                {{ __('Refresh') }}
                            </button> --}}
                            <div class="hidden lg:flex items-center space-x-2">
                                <button onclick="refreshAll()" class="px-3 py-2 text-sm text-gray-600 hover:text-[#07AF8B] hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="visitor-container" class="p-6">
                    @include('partials.visitor-list', ['visitors' => $pendingVisits ?? []])
                </div>
            </div>
        </main>
    </div>
</div>

<style>
/* Custom styles for smooth animations */
.sidebar-open {
    transform: translateX(0) !important;
}

@media (max-width: 768px) {
    .sidebar-open ~ #sidebar-overlay {
        display: block !important;
    }
}

/* Loading animation for refresh button */
.refresh-loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
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


    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const hamburger = event.target.closest('[onclick="openSidebar()"]');

        if (window.innerWidth < 768 && !sidebar.contains(event.target) && !hamburger) {
            closeSidebar();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeSidebar();
        }
    });
</script>

{{-- Enhanced AJAX functionality --}}
<script>
    function approveVisitor(id) {
        const button = event.target;
        const originalContent = button.innerHTML;

        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Approving...';
        button.disabled = true;

        fetch('/sm/visits/' + id + '/approve', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                refreshAll();
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        }).catch(error => {
            button.innerHTML = originalContent;
            button.disabled = false;
            console.error('Error:', error);
        });
    }

    function denyVisitor(id) {
        const button = event.target;
        const originalContent = button.innerHTML;

        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Denying...';
        button.disabled = true;

        fetch('/sm/visits/' + id + '/deny', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                refreshAll();
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        }).catch(error => {
            button.innerHTML = originalContent;
            button.disabled = false;
            console.error('Error:', error);
        });
    }

    function refreshAll() {
        const refreshButton = document.querySelector('[onclick="refreshAll()"]');
        const refreshIcon = refreshButton.querySelector('i');

        refreshIcon.classList.add('refresh-loading');

        fetch('/sm/visits/pending')
            .then(res => res.json())
            .then(data => {
                document.getElementById('visitor-container').innerHTML = data.html;
                document.getElementById('pending-badge').innerHTML = `
                    <div class="w-2 h-2 bg-[#FFCA00] rounded-full mr-2"></div>
                    ${data.pendingCount} Pending
                `;
                document.getElementById('pending-count').textContent = data.pendingCount;
            })
            .catch(error => {
                console.error('Error refreshing data:', error);
            })
            .finally(() => {
                refreshIcon.classList.remove('refresh-loading');
            });
    }

    // Auto-refresh every 30 seconds
    setInterval(refreshAll, 30000);
</script>

@endsection

{{-- @extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 w-full">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white shadow p-4 hidden md:block fixed md:relative z-30">
        <nav class="space-y-4">
            <h2 class="text-lg font-bold text-[#07AF8B]">Visitor Management</h2>
            <a href="{{ route('sm.dashboard') }}" class="block text-gray-700 hover:text-[#07AF8B]">Dashboard</a>
            <a href="{{ route('sm.dashboard') }}" class="block text-gray-700 hover:text-[#07AF8B]">Visitor History</a>
            <a href="{{ route('sm.dashboard') }}" class="block text-gray-700 hover:text-[#07AF8B]">Pending Visits</a>
            <a href="{{ route('sm.analytics') }}" class="block text-gray-700 hover:text-[#07AF8B]">Analytics</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="mt-4 text-red-600 hover:underline">Logout →</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 w-full">
        <!-- Top Nav -->
        <div class="bg-white md:bg-gray-100 px-4 py-8 flex justify-between items-center shadow md:sticky top-0 z-20">
            <!-- Hamburger -->
            <button class="md:hidden text-[#07AF8B]" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Search -->
            <form method="GET" action="{{ route('sm.dashboard') }}" class="flex-grow mx-4 max-w-2xl">
                <input type="text" name="search"
                       class="w-full px-4 py-2 border border-gray-300 rounded-full text-sm focus:outline-none"
                       placeholder="Search visitors..." value="{{ request('search') }}">
            </form>

            <!-- Notifications -->
            <div class="relative">
                <span class="material-icons text-[#6c757d]">notifications</span>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="p-6 space-y-6 w-full">
            <!-- Top Section -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Summary Boxes -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 w-full" id="stats-container">
                    <!-- Visitors Today Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[#07AF8B] text-2xl mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="text-3xl font-bold text-[#007570]" id="total-today">{{ $visitorsToday }}</div>
                        <div class="text-sm text-gray-600">{{ __('Visitors Today') }}</div>
                    </div>

                    <!-- Pending Approvals Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[#07AF8B] text-2xl mb-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="text-3xl font-bold text-[#007570]" id="pending-count">{{$pendingVisits->count() }}</div>
                        <div class="text-sm text-gray-600">{{ __('Pending Approvals') }}</div>
                    </div>

                    <!-- Approved Today Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[#07AF8B] text-2xl mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-3xl font-bold text-[#007570]" id="approved-today">{{ $approvedToday }}</div>
                        <div class="text-sm text-gray-600">{{ __('Approved Today') }}</div>
                    </div>

                    <!-- Denied Today Card -->
                    <div class="bg-white rounded-lg shadow-sm p-4 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                        <div class="text-[#07AF8B] text-2xl mb-2">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="text-3xl font-bold text-[#007570]" id="denied-today">{{ $deniedToday }}</div>
                        <div class="text-sm text-gray-600">{{ __('Denied Today') }}</div>
                    </div>
                </div>


            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mt-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                    <h1 class="text-xl font-bold text-[#007570] flex items-center">
                        {{ __('Visitor Approvals') }}
                        <span class="ml-3 inline-block text-sm font-medium text-black bg-[#FFCA00] px-3 py-1 rounded-full" id="pending-badge">
                            {{ $stats['pending_count'] }} {{ __('Pending') }}
                        </span>
                    </h1>
                    <button onclick="refreshAll()" class="text-sm border border-gray-400 text-gray-700 px-4 py-2 rounded hover:bg-gray-100 transition">
                        <i class="fas fa-sync-alt mr-2"></i> {{ __('Refresh') }}
                    </button>
                </div>

                <div id="visitor-container">
                    @include('partials.visitor-list', ['visitors' => $pendingVisits ?? []])
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    }
</script>

<!-- New script -->
<script>
    function approveVisitor(id) {
        fetch('/sm/visits/' + id + '/approve', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                refreshAll();
            }
        });
    }

    function denyVisitor(id) {
        fetch('/sm/visits/' + id + '/deny', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                refreshAll();
            }
        });
    }

    function refreshAll() {
        fetch('/sm/visits/pending')
            .then(res => res.json())
            .then(data => {
                document.getElementById('visitor-container').innerHTML = data.html;
                document.getElementById('pending-badge').textContent = data.pendingCount + ' Pending';
            });
    }
</script>

@endsection --}}
