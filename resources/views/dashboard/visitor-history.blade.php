@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 w-full">
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar (Same as dashboard) -->
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

                <a href="{{ route('sm.visitor-history') }}" class="flex items-center px-4 py-3 text-white bg-[#07AF8B] rounded-lg group">
                    <i class="fas fa-history w-5 h-5 mr-3 text-white"></i>
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
                        <h2 class="text-xl font-semibold text-gray-900">Visitor History</h2>
                        <p class="text-sm text-gray-500">Complete visitor records and details</p>
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
                <form method="GET" action="{{ route('sm.visitor-history') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Visitor name, email, or reason..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B]">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#07AF8B]/20 focus:border-[#07AF8B]">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
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
                        <a href="{{ route('sm.visitor-history') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Visitor History Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Visitor Records</h3>
                    <p class="text-sm text-gray-500">{{ $visits->total() }} total records found</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visitor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($visits as $visit)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $visit->visitor->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $visit->visitor->email ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400">{{ $visit->visitor->organization ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $visit->reason ?? 'No reason provided' }}</div>
                                        <div class="text-xs text-gray-500">Code: {{ $visit->unique_code ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">Floor: {{ $visit->floor_of_visit ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $visit->staff->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $visit->staff->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visit->status == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                                Approved
                                            </span>
                                        @elseif($visit->status == 'denied')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <div class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></div>
                                                Denied
                                            </span>
                                        @elseif($visit->status == 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <div class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-1.5"></div>
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($visit->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $visit->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('sm.visitor-history.show', $visit->id) }}"
                                           class="text-[#e0b200] hover:text-[#b89400] transition-colors duration-200">
                                           <span class="inline-flex items-center px-2.5 py-2 rounded-[5px] text-xs font-medium bg-yellow-100 text-yellow-800 hover:text-yellow-900">
                                            <i class="fas fa-eye mr-1"></i>
                                            View Details
                                        </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-history text-gray-300 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No visitor history found</h3>
                                            <p class="text-gray-500">Try adjusting your search filters or check back later.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
        const exportUrl = '{{ route("sm.visitor-history.export") }}?' + params.toString();

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
                    a.download = 'visitor_history_' + new Date().toISOString().split('T')[0] + '.csv';
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
        const headers = ['Visitor Name', 'Email', 'Phone', 'Organization', 'Staff', 'Reason', 'Status', 'Visit Date', 'Floor', 'Unique Code'];
        const csvRows = [headers.join(',')];

        data.forEach(visit => {
            const row = [
                visit.visitor?.name || 'N/A',
                visit.visitor?.email || 'N/A',
                visit.visitor?.phone || 'N/A',
                visit.visitor?.organization || 'N/A',
                visit.staff?.name || 'N/A',
                visit.reason || 'N/A',
                visit.status || 'N/A',
                visit.visit_date || 'N/A',
                visit.floor_of_visit || 'N/A',
                visit.unique_code || 'N/A'
            ];
            csvRows.push(row.map(field => `"${field}"`).join(','));
        });

        return csvRows.join('\n');
    }
</script>
@endsection
