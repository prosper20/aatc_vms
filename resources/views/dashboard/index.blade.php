@extends('layouts.app')

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

            {{-- <div>
                <h2 class="text-xl font-bold mb-2 text-[#07AF8B]">Pending Requests</h2>
                <div class="space-y-2">
                    @forelse($pendingRequests as $visit)
                        <div class="p-4 bg-white rounded shadow text-sm">
                            <p>
                                <span class="font-semibold text-gray-800">{{ $visit->visitor->name }}</span> visiting
                                <span class="text-gray-600">{{ $visit->staff->name }}</span> on
                                <span class="text-[#FFCA00]">{{ \Carbon\Carbon::parse($visit->visit_date)->format('d M, Y h:i A') }}</span>
                            </p>
                        </div>
                    @empty
                        <div class="p-4 bg-white rounded shadow text-gray-500 text-center">
                            No pending requests.
                        </div>
                    @endforelse
                </div>
            </div> --}}
             <!-- Chart -->
             {{-- <div class="bg-white p-4 rounded-xl shadow flex-1">
                <h2 class="text-lg font-bold mb-2 text-[#07AF8B]">Visitor Trends</h2>
                <canvas id="visitorChart" height="120"></canvas>
            </div> --}}
        </div>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($visitTrends->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'))->toArray()) !!},
            datasets: [{
                label: 'Visitors',
                data: {!! json_encode($visitTrends->pluck('total')) !!},
                borderColor: '#07AF8B',
                fill: true,
                backgroundColor: 'rgba(7, 175, 139, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    }
</script>

{{-- New script --}}
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

@endsection


{{-- @extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-64 min-h-screen p-4 bg-white shadow">
        <nav class="space-y-4">
            <h2 class="text-lg font-bold">Visitor Management System</h2>
            <a href="{{ route('sm.dashboard') }}" class="block text-gray-700">Dashboard</a>
            <a href="{{ route('sm.dashboard') }}" class="block text-gray-700">Visitor History</a>
            <a href="{{ route('sm.dashboard') }}" class="block text-gray-700">Pending Visits</a>
            <a href="{{ route('sm.analytics') }}" class="block text-gray-700">Analytics</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="mt-4 text-red-600">Logout →</button>
            </form>
        </nav>
    </aside>

        <div class="flex flex-col gap-6 w-full p-6">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left: Summary Boxes -->
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-4 w-full lg:w-1/3">
                    <div class="bg-white p-4 rounded shadow text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $visitorsToday }}</p>
                        <p class="text-gray-600">Visitors Today</p>
                    </div>
                    <div class="bg-white p-4 rounded shadow text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingRequests->count() }}</p>
                        <p class="text-gray-600">Pending Approvals</p>
                    </div>
                    <div class="bg-white p-4 rounded shadow text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $approvedToday }}</p>
                        <p class="text-gray-600">Approved Today</p>
                    </div>
                    <div class="bg-white p-4 rounded shadow text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $deniedToday }}</p>
                        <p class="text-gray-600">Denied Today</p>
                    </div>
                </div>

                <!-- Right: Chart -->
                <div class="bg-white p-4 rounded shadow flex-1">
                    <h2 class="font-bold text-lg mb-2">Visitor Trends</h2>
                    <canvas id="visitorChart" height="100"></canvas>
                </div>
            </div>


            <!-- Requets History -->
            <div>
                <h2 class="text-xl font-bold mb-2">Pending Requests</h2>
                <div class="space-y-2">
                    @foreach($visitHistory as $visit)
                    <div class="p-4 bg-white rounded shadow">
                        <p>{{ $visit->visitor->name }} - {{ $visit->visit_date }} - {{ $visit->staff->name }} - <span class="capitalize">{{ $visit->status }}</span></p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($visitTrends->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'))->toArray()) !!},
            datasets: [{
                label: 'Visitors',
                data: {!! json_encode($visitTrends->pluck('total')) !!},
                fill: false,
                borderColor: '#0ea5e9',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection --}}
