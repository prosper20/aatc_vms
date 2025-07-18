@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 w-full">

    <!-- Toast -->
    <div
        x-data="{ open: false, message: '', type: 'success' }"
        x-show="open"
        x-transition
        x-init="$watch('open', value => { if (value) setTimeout(() => open = false, 3000) })"
        x-bind:class="type === 'success' ? 'bg-green-600' : 'bg-red-600'"
        class="fixed top-4 right-4 text-white px-4 py-3 rounded shadow z-50"
        x-text="message"
    ></div>

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white shadow-xl fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0">
        <div class="flex flex-col h-full">
            <!-- Logo Section -->
            <div class="flex flex-col justify-center space-x-3 px-4 py-3">
                <div class="p-2">
                    <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="{{ __('Logo') }}" class="h-10 md:h-12">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-[#007570]">Abuja AATC-VMS</h1>
                    <p class="text-xs text-gray-500">Security Portal</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('sm.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg bg-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-home w-5 h-5 mr-3 text-white "></i>
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
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('sm')->user()->name ?? 'Security Manager' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::guard('sm')->user()->email ?? 'manager@example.com' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('sm.logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center ml-3 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200 group">
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

                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-4 lg:p-6 space-y-6 w-full">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6" id="stats-container">
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


            <div id="visitor-container" >
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-2 sm:p-4 md:p-6 ">
                        <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="w-full flex items-center space-x-3">
                                <div class="flex w-full justify-between md:justify-start">
                                    <h2 class="text-2xl font-semibold text-[#0b7570]">{{ __('Visitor Approvals') }}</h2>
                                    @if($pendingVisits->count() > 0)
                                        <span class="ml-2 inline-flex items-center rounded-[5px] border border-transparent bg-[#feca01] px-1.5 py-0.5 text-sm font-semibold">
                                            {{ $pendingVisits->count() }} Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div >
                        <div class="p-0 sm:p-4 md:p-6 inline-flex h-10 items-center justify-center bg-gray-100 text-gray-500 w-full">
                            <div class="grid grid-cols-3 w-full">
                                <button class="inline-flex items-center justify-start whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900">
                                    <span >Visitor</span>
                                </button>

                                <button class="inline-flex items-center justify-start md:justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900">
                                    <span >Host</span>
                                </button>

                                <button class="inline-flex items-center justify-end whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-950 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-white data-[state=active]:text-gray-900">
                                    <span >Time of Arival</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-0 pb-4 sm:p-4 md:p-6 pt-0 sm:pt-0 md:pt-0">
                        @include('partials.visitor-list', ['visitors' => $pendingVisits->take(5)])
                    </div>

                    <div class="px-2 sm:px-4 md:px-6 pb-4 text-right">
                        <a href="/sm/pending-visits" class="inline-flex items-center px-4 py-2 text-sm font-medium bg-[#FFCA00] hover:bg-[#e0b200] rounded-lg transition-colors duration-200">
                            View All
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
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

<script>

    function approveVisit(visitId) {
    // Find the specific visit element
    const visitElement = document.getElementById(`visit-${visitId}`);
    const alpineData = visitElement.__x ? visitElement.__x.$data : null;

    if (!alpineData) {
        console.error('Alpine.js data not found');
        return;
    }

    // Set loading state
    alpineData.loading = true;

    // Make the API call
    fetch(`{{ url('sm/visits') }}/${visitId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Show success toast
        showToast('Visit approved successfully!', 'success');

        // Smooth removal animation
        visitElement.style.transition = 'all 0.5s ease-out';
        visitElement.style.transform = 'translateX(100%)';
        visitElement.style.opacity = '0';

        // Remove element after animation
        setTimeout(() => {
            visitElement.remove();
            // Update counters
            updateCounters();
        }, 500);
    })
    .catch(error => {
        console.error('Error approving visit:', error);
        showToast('Failed to approve visit. Please try again.', 'error');
        // Reset loading state on error
        alpineData.loading = false;
    });
}

// Function to update counters after approval
function updateCounters() {
    const pendingCountElement = document.getElementById('pending-count');
    const pendingBadgeElement = document.getElementById('pending-badge');

    if (pendingCountElement) {
        const currentCount = parseInt(pendingCountElement.textContent) || 0;
        const newCount = Math.max(0, currentCount - 1);
        pendingCountElement.textContent = newCount;

        // Update badge
        if (pendingBadgeElement) {
            pendingBadgeElement.innerHTML = `
                <div class="w-2 h-2 bg-[#FFCA00] rounded-full mr-2"></div>
                ${newCount} Pending
            `;
        }
    }
}

// Enhanced toast function that works with your existing toast system
function showToast(message, type = 'success') {
    // Try to use your existing Alpine.js toast system
    const toastElement = document.querySelector('[x-data*="open"]');

    if (toastElement && toastElement.__x) {
        const toastData = toastElement.__x.$data;
        toastData.message = message;
        toastData.type = type;
        toastData.open = true;
    } else {
        // Fallback to a simple notification
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded shadow z-50 text-white transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
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

    function showToast(message, type = 'success') {
            const toast = document.querySelector('[x-data*="open"]');
            if (!toast) return;

            const x = toast.__x.$data;
            x.message = message;
            x.type = type;
            x.open = true;
        }

</script>

@endsection
