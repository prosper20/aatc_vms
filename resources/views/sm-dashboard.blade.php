<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Visitor Management Dashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #07AF8B;
            --accent-color: #FFCA00;
            --dark-green: #007570;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1200px;
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .stat-card {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-green);
        }

        .stat-card .label {
            font-size: 0.9rem;
            color: #666;
        }

        .visitor-list {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.07);
            padding: 2rem;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .header-bar h1 {
            font-size: 1.6rem;
            color: var(--dark-green);
            font-weight: 700;
        }

        .nav-tabs .nav-link {
            color: var(--dark-green);
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
            background-color: transparent;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 2rem;
        }

        .concentric-chart {
            max-width: 300px;
            margin: 0 auto;
        }

        .visitor-item {
            display: flex;
            flex-direction: column;
            padding: 1rem 0;
            border-bottom: 1px solid #eaeaea;
            transition: background-color 0.2s;
        }

        .visitor-item:hover {
            background-color: #f9f9f9;
        }

        @media (min-width: 576px) {
            .visitor-item {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .visitor-info {
            flex-grow: 1;
        }

        .visitor-info h5 {
            margin-bottom: 0.5rem;
            color: var(--dark-green);
            font-weight: 600;
        }

        .visitor-info small {
            display: block;
            color: #555;
            margin-bottom: 0.2rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        @media (min-width: 576px) {
            .action-buttons {
                margin-top: 0;
            }
        }

        .btn-success {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #777;
        }

        .empty-state i {
            color: #ccc;
        }

        .badge.bg-primary {
            background-color: var(--accent-color) !important;
            color: #000;
        }

        .badge.bg-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .logout-btn {
            color: var(--dark-green);
            border: 1px solid var(--dark-green);
        }

        .logout-btn:hover {
            background-color: var(--dark-green);
            color: white;
        }

        .tab-content {
            padding-top: 1.5rem;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 1rem;
            text-align: center;
        }

        .stat-card-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $active_tab == 'approvals' ? 'active' : '' }}"
                   href="{{ route('cso.dashboard', ['tab' => 'approvals']) }}">
                    {{ __('Approvals') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $active_tab == 'analytics' ? 'active' : '' }}"
                   href="{{ route('cso.dashboard', ['tab' => 'analytics']) }}">
                    {{ __('Analytics') }}
                </a>
            </li>
        </ul>
        <form action="{{ route('cso.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn logout-btn btn-sm align-self-start">
                <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
            </button>
        </form>
    </div>

    <div class="tab-content">
        @if($active_tab == 'approvals')
            <div class="row mb-4" id="stats-container">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="value" id="total-today">{{ $stats['total_today'] }}</div>
                        <div class="label">{{ __('Visitors Today') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="value" id="pending-count">{{ $stats['pending_count'] }}</div>
                        <div class="label">{{ __('Pending Approvals') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="value" id="approved-today">{{ $stats['approved_today'] }}</div>
                        <div class="label">{{ __('Approved Today') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="value" id="denied-today">{{ $stats['denied_today'] }}</div>
                        <div class="label">{{ __('Denied Today') }}</div>
                    </div>
                </div>
            </div>

            <div class="visitor-list mt-4">
                <div class="header-bar">
                    <h1>
                        {{ __('Visitor Approvals') }}
                        <span class="badge bg-primary ms-2" id="pending-badge">
                            {{ $stats['pending_count'] }} {{ __('Pending') }}
                        </span>
                    </h1>
                    <button onclick="refreshAll()" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-sync-alt me-1"></i> {{ __('Refresh') }}
                    </button>
                </div>

                <div id="visitor-container">
                    @include('cso.partials.visitor-list', ['visitors' => $visitors ?? []])
                </div>
            </div>
        @else
            <!-- Analytics Tab Content -->
            <div class="dashboard-card">
                <h3 class="mb-4">{{ __('Security Analytics') }}</h3>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="chart-title">{{ __('Weekly Visitor Trend') }}</div>
                        <div class="chart-container">
                            <canvas id="weeklyTrendChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-title">{{ __('Top Hosts by Visitors') }}</div>
                        <div class="chart-container">
                            <canvas id="topHostsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="chart-title">{{ __('Visitor Status Today') }}</div>
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-title">{{ __('Repeat Visitors') }}</div>
                        <div class="concentric-chart">
                            <canvas id="repeatVisitorsChart"></canvas>
                        </div>
                        <p class="text-center mt-2">
                            {{ $stats['repeat_visitors'] }} {{ __('repeat visitors identified') }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-title">{{ __('Approval Rate') }}</div>
                        <div class="concentric-chart">
                            <canvas id="approvalRateChart"></canvas>
                        </div>
                        <p class="text-center mt-2">
                            {{ $stats['approval_rate'] }}% {{ __('approval rate') }}
                        </p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="chart-title">{{ __('Hourly Visitor Distribution Today') }}</div>
                        <div class="chart-container">
                            <canvas id="hourlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Real-time refresh functionality
let refreshInterval = 5000; // 5 seconds
let visitorRefreshTimeout;
let statsRefreshTimeout;

function fetchVisitorList() {
    fetch('{{ route("cso.visitors") }}')
        .then(response => response.text())
        .then(data => {
            document.getElementById('visitor-container').innerHTML = data;
            visitorRefreshTimeout = setTimeout(fetchVisitorList, refreshInterval);
        })
        .catch(error => {
            console.error('Error fetching visitors:', error);
            visitorRefreshTimeout = setTimeout(fetchVisitorList, refreshInterval);
        });
}

function fetchStats() {
    fetch('{{ route("cso.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-today').textContent = data.total_today;
            document.getElementById('pending-count').textContent = data.pending_count;
            document.getElementById('approved-today').textContent = data.approved_today;
            document.getElementById('denied-today').textContent = data.denied_today;
            document.getElementById('pending-badge').textContent = data.pending_count + ' {{ __("Pending") }}';

            if (hourlyChart) {
                hourlyChart.data.datasets[0].data = Array.from({length: 24}, (_, i) => data.hourly_data[i] || 0);
                hourlyChart.update();
            }

            statsRefreshTimeout = setTimeout(fetchStats, refreshInterval);
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
            statsRefreshTimeout = setTimeout(fetchStats, refreshInterval);
        });
}

function refreshAll() {
    clearTimeout(visitorRefreshTimeout);
    clearTimeout(statsRefreshTimeout);
    fetchVisitorList();
    fetchStats();
}

// Start auto-refresh only on Approvals tab
if (document.querySelector('.nav-link.active').textContent === '{{ __("Approvals") }}') {
    fetchVisitorList();
    fetchStats();
}

// Tab switching behavior
document.querySelectorAll('.nav-link').forEach(tab => {
    tab.addEventListener('click', function() {
        clearTimeout(visitorRefreshTimeout);
        clearTimeout(statsRefreshTimeout);

        if (this.textContent === '{{ __("Approvals") }}') {
            fetchVisitorList();
            fetchStats();
        }
    });
});

@if($active_tab == 'analytics')
    // Analytics tab charts initialization
    const weeklyCtx = document.getElementById('weeklyTrendChart').getContext('2d');
    const hostsCtx = document.getElementById('topHostsChart').getContext('2d');
    const repeatCtx = document.getElementById('repeatVisitorsChart').getContext('2d');
    const approvalCtx = document.getElementById('approvalRateChart').getContext('2d');
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const statusCtx = document.getElementById('statusChart').getContext('2d');

    // Weekly Trend Chart
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: '{{ __("This Week") }}',
                data: @json($stats['weekly_data']),
                borderColor: 'rgba(7, 175, 139, 1)',
                backgroundColor: 'rgba(7, 175, 139, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: '{{ __("Last Week") }}',
                data: @json($stats['last_weekly_data']),
                borderColor: 'rgba(0, 117, 112, 1)',
                backgroundColor: 'rgba(0, 117, 112, 0.1)',
                tension: 0.3,
                borderDash: [5, 5],
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '{{ __("Number of Visitors") }}'
                    }
                }
            }
        }
    });

    // Top Hosts Chart
    new Chart(hostsCtx, {
        type: 'bar',
        data: {
            labels: @json(array_column($stats['top_hosts'], 'host_name')),
            datasets: [{
                label: '{{ __("Visitors") }}',
                data: @json(array_column($stats['top_hosts'], 'count')),
                backgroundColor: 'rgba(255, 202, 0, 0.7)',
                borderColor: 'rgba(255, 202, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '{{ __("Number of Visitors") }}'
                    }
                }
            }
        }
    });

    // Repeat Visitors Chart
    new Chart(repeatCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("First-time Visitors") }}', '{{ __("Repeat Visitors") }}'],
            datasets: [{
                data: [
                    {{ max(0, $stats['total_visitors'] - $stats['repeat_visitors']) }},
                    {{ $stats['repeat_visitors'] }}
                ],
                backgroundColor: [
                    'rgba(7, 175, 139, 0.7)',
                    'rgba(255, 202, 0, 0.7)'
                ],
                borderColor: [
                    'rgba(7, 175, 139, 1)',
                    'rgba(255, 202, 0, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Approval Rate Chart
    new Chart(approvalCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("Approved") }}', '{{ __("Denied") }}'],
            datasets: [{
                data: [
                    {{ $stats['approved_today'] }},
                    {{ $stats['denied_today'] }}
                ],
                backgroundColor: [
                    'rgba(7, 175, 139, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(7, 175, 139, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Hourly Distribution Chart
    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: Array.from({length: 24}, (_, i) => i + ':00'),
            datasets: [{
                label: '{{ __("Visitors") }}',
                data: Array.from({length: 24}, (_, i) => {{ $stats['hourly_data'][i] ?? 0 }}),
                backgroundColor: 'rgba(7, 175, 139, 0.7)',
                borderColor: 'rgba(7, 175, 139, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '{{ __("Number of Visitors") }}'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: '{{ __("Hour of Day") }}'
                    }
                }
            }
        }
    });

    // Status Chart
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("Pending") }}', '{{ __("Approved") }}', '{{ __("Denied") }}'],
            datasets: [{
                data: [
                    {{ $stats['pending_count'] }},
                    {{ $stats['approved_today'] }},
                    {{ $stats['denied_today'] }}
                ],
                backgroundColor: [
                    'rgba(255, 202, 0, 0.7)',
                    'rgba(7, 175, 139, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 202, 0, 1)',
                    'rgba(7, 175, 139, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
@endif
</script>
</body>
</html>
