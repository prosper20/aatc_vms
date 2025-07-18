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
                        <h2 class="text-xl font-semibold text-gray-900">Visitor Details</h2>
                        <p class="text-sm text-gray-500">Complete visitor record</p>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('sm.visitor-history') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium bg-[#FFCA00] hover:bg-[#e0b200] rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to History
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4 lg:p-6 space-y-6 w-full">
            <!-- Visitor Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Visitor Information</h3>
                            <p class="text-sm text-gray-500">Visit ID: {{ $visit->id }}</p>
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

                <div class="p-6">
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
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Unique Code</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->unique_code ?? 'N/A' }}</p>
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
                                    <p class="text-xs font-medium text-gray-500">Staff Email</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $visit->staff->email ?? 'N/A' }}</p>
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
</script>
@endsection
