@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 w-full">
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
                <a href="{{ route('sm.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-[#07AF8B]/10 hover:text-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-home w-5 h-5 mr-3 text-gray-400 group-hover:text-[#07AF8B]"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('sm.visitor-history') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-[#07AF8B]/10 hover:text-[#07AF8B] transition-colors duration-200 group">
                    <i class="fas fa-history w-5 h-5 mr-3 text-gray-400 group-hover:text-[#07AF8B]"></i>
                    <span class="font-medium">Visitor History</span>
                </a>

                <a href="{{ route('sm.pending-visits') }}" class="flex items-center px-4 py-3 text-white bg-[#07AF8B] rounded-lg group">
                    <i class="fas fa-clock w-5 h-5 mr-3 text-white"></i>
                    <span class="font-medium">Pending Visits</span>
                    @if($visits->total() > 0)
                        <span class="ml-auto bg-[#FFCA00] text-black text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $visits->total() }}
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
        <!-- Top Navigation -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                <!-- Left Section -->
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-[#07AF8B] hover:text-[#007570] transition-colors duration-200" onclick="openSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="hidden md:block">
                        <h2 class="text-xl font-semibold text-gray-900">Pending Visits</h2>
                        <p class="text-sm text-gray-500">Manage pending visitor approvals</p>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <button onclick="exportData()" class="inline-flex items-center px-4 py-2 text-sm font-medium bg-[#FFCA00] hover:bg-[#e0b200] rounded-lg transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Export Data
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4 lg:p-6 space-y-6 w-full">
            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('sm.pending-visits') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Visitor name, email, or reason..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B]">
                        </div>

                        <!-- Floor Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Floor</label>
                            <select name="floor" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B]">
                                <option value="">All Floors</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                                        Floor {{ $floor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                            <input type="date" name="date_from"
                                   value="{{ request('date_from') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B]">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                            <input type="date" name="date_to"
                                   value="{{ request('date_to') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B]">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium bg-[#FFCA00] hover:bg-[#e0b200] rounded-lg transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('sm.pending-visits') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Pending Visits Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                            <p class="text-sm text-gray-500">{{ $visits->total() }} pending requests found</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button onclick="refreshAll()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
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
                    <table class="w-full">
                        <tbody class="bg-white divide-y divide-gray-200" id="visitor-container">
                            @include('partials.visitor-list', ['visitors' => $visits])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($visits->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $visits->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

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

    function exportData() {
        const params = new URLSearchParams(window.location.search);
        const exportUrl = '{{ route("sm.pending-visits.export") }}?' + params.toString();

        fetch(exportUrl)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create and download CSV file
                    const csvContent = convertToCSV(data.data);
                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'pending_visits_' + new Date().toISOString().split('T')[0] + '.csv';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }
            })
            .catch(error => {
                console.error('Export failed:', error);
                alert('Export failed. Please try again.');
            });
    }

    function convertToCSV(data) {
        const headers = ['Visitor Name', 'Email', 'Phone', 'Organization', 'Staff', 'Reason', 'Visit Date', 'Floor', 'Unique Code'];
        const csvRows = [headers.join(',')];

        data.forEach(visit => {
            const row = [
                visit.visitor?.name || 'N/A',
                visit.visitor?.email || 'N/A',
                visit.visitor?.phone || 'N/A',
                visit.visitor?.organization || 'N/A',
                visit.staff?.name || 'N/A',
                visit.reason || 'N/A',
                visit.visit_date || 'N/A',
                visit.floor_of_visit || 'N/A',
                visit.unique_code || 'N/A'
            ];
            csvRows.push(row.map(field => `"${field}"`).join(','));
        });

        return csvRows.join('\n');
    }

    function refreshAll() {
        const refreshButton = document.querySelector('[onclick="refreshAll()"]');
        const refreshIcon = refreshButton.querySelector('i');

        refreshIcon.classList.add('fa-spin');

        const params = new URLSearchParams(window.location.search);
        fetch(`/sm/pending-visits?${params.toString()}&ajax=1`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('visitor-container').innerHTML = html;
            })
            .catch(error => {
                console.error('Error refreshing data:', error);
            })
            .finally(() => {
                refreshIcon.classList.remove('fa-spin');
            });
    }

    // Auto-refresh every 30 seconds
    setInterval(refreshAll, 30000);
</script>
@endsection
