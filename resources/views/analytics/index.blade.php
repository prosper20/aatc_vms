<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visits Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .chart-container {
            height: 250px;
        }
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Header -->
    <div class="p-4 md:p-6 flex flex-row justify-between items-start md:items-center mb-6 gap-4 sticky top-0 z-30 bg-white">
        <!-- Back Arrow + Title -->
        <div class="flex items-center space-x-3">
            <a href="/sm/dashboard" class="text-gray-600 hover:text-gray-800 text-lg md:text-xl">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <span class="block md:hidden">Analytics</span>
                    <span class="hidden md:block">Visitor Analytics</span>
                  </h1>

                <p class="text-gray-500 text-sm">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Export Button -->
        <div class="mt-2 md:mt-0">
            {{-- <button class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all">
                <i class="fas fa-download mr-2"></i>Export Report
            </button> --}}
            <a href="/sm/analytics/export" class="border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium bg-[#FFCA00] hover:bg-[#e0b200] transition-all">
                <i class="fas fa-download mr-2"></i>Export Report
            </a>
        </div>
    </div>
    <div class="min-h-screen p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-50 text-green-600 mr-4">
                            <i class="fas fa-calendar-day text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Visits Today</p>
                            <p class="text-2xl font-bold text-[#0b7570]">{{ $visitsToday }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-yellow-50 text-yellow-400 mr-4">
                            <i class="fas fa-redo text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Visits This Week</p>
                            <p class="text-2xl font-bold text-[#0b7570]">{{ $visitsThisWeek }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-yellow-50 text-yellow-400 mr-4">
                            <i class="fas fa-redo text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Visits This Month</p>
                            <p class="text-2xl font-bold text-[#0b7570]">{{ $visitsThisMonth }}</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-50 text-green-600 mr-4">
                            <i class="fas fa-clipboard-check text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Visitor Status</p>
                            <div class="flex items-center mt-1">
                                <span class="status-dot bg-green-500"></span>
                                <span class="text-sm font-medium">{{ $approvedToday }} Approved</span>
                            </div>
                            <div class="flex items-center mt-1">
                                <span class="status-dot bg-yellow-500"></span>
                                <span class="text-sm font-medium">{{ $pendingToday }} Pending</span>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Daily Trend Chart -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-gray-800">Daily Visit Volume</h2>
                        <select class="text-sm border border-gray-200 rounded px-2 py-1 bg-gray-50">
                            <option>Last 7 Days</option>
                            <option>Last 14 Days</option>
                            <option>Last 30 Days</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="dailyTrend"></canvas>
                    </div>
                </div>

                <!-- Weekly Trend Chart -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-gray-800">Weekly Visitor Trend</h2>
                        <select class="text-sm border border-gray-200 rounded px-2 py-1 bg-gray-50">
                            <option>This Week</option>
                            <option>Last Week</option>
                            <option>Last 4 Weeks</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="weeklyTrend"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{--
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-800">Access Cards</h2>
                        <i class="fas fa-id-card text-blue-500"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $accessCardStats['issued_today'] }}</div>
                            <div class="text-xs text-gray-500">Issued Today</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $accessCardStats['retrieved_today'] }}</div>
                            <div class="text-xs text-gray-500">Retrieved Today</div>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Outstanding Cards</span>
                            <span class="text-sm font-semibold text-orange-600">{{ $accessCardStats['outstanding'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Return Rate</span>
                            <span class="text-sm font-semibold">{{ $accessCardStats['utilization_rate'] }}%</span>
                        </div>
                    </div>
                </div> --}}

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Frequent Visitors</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-500 text-sm border-b">
                                    <th class="pb-2 font-medium">Visitor</th>
                                    <th class="pb-2 font-medium">Email</th>
                                    <th class="pb-2 font-medium text-right">Visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($frequentVisitors as $visitor)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 text-sm font-medium text-gray-800">{{ $visitor->name }}</td>
                                    <td class="py-3 text-sm text-gray-500">{{ $visitor->email }}</td>
                                    <td class="py-3 text-sm text-right font-medium">{{ $visitor->visits_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Activity (Placeholder for future content) -->
                {{-- <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-800">Recent Activity</h2>
                        <button id="refreshActivities" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-sync-alt text-sm"></i>
                        </button>
                    </div>

                    <div id="activityContainer" class="space-y-3">
                        @if($recentActivities->isEmpty())
                            <div class="flex items-center justify-center h-32 text-gray-400">
                                <div class="text-center">
                                    <i class="fas fa-clock text-2xl mb-2"></i>
                                    <p class="text-sm">No recent activity</p>
                                </div>
                            </div>
                        @else
                            @foreach($recentActivities as $activity)
                                <div class="activity-item flex items-start space-x-3 p-3 rounded-lg border border-gray-50 hover:border-gray-100">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full {{ $activity['bg_color'] }} flex items-center justify-center">
                                            <i class="{{ $activity['icon'] }} {{ $activity['color'] }} text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                                            <div class="flex items-center space-x-1">
                                                @if(isset($activity['extra_info']))
                                                    <span class="text-xs px-2 py-1 rounded-full
                                                        {{ $activity['extra_info'] === 'Verified' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                                        {{ $activity['extra_info'] }}
                                                    </span>
                                                @endif
                                                <span class="pulse-dot w-2 h-2 bg-blue-400 rounded-full"></span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity['description'] }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-400 text-center">
                            Last updated: <span id="lastUpdated">{{ now()->format('H:i:s') }}</span>
                        </p>
                    </div>
                </div> --}}
                {{-- <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <div class="flex items-center justify-center h-64 text-gray-400">
                        <p>Recent visitor activities will appear here</p>
                    </div>
                </div> --}}


                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Arrival Methods</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-car text-[#0b7570] text-xl"></i>
                            </div>
                            <div class="text-lg font-bold text-gray-800">{{ $arrivalModeStats['vehicle'] }}</div>
                            <div class="text-xs text-gray-500">Vehicle ({{ $arrivalModeStats['vehicle_percentage'] }}%)</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-walking text-yellow-500 text-xl"></i>
                            </div>
                            <div class="text-lg font-bold text-gray-800">{{ $arrivalModeStats['foot'] }}</div>
                            <div class="text-xs text-gray-500">Walk-in ({{ $arrivalModeStats['foot_percentage'] }}%)</div>
                        </div>
                    </div>

                    <h2 class="font-semibold text-gray-800 mb-4 mt-5">Vehicle Types</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-car-side text-purple-500 mr-3"></i>
                                <span class="text-sm text-gray-600">Drop-off</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm font-semibold mr-2">{{ $vehicleStats['drop_off_count'] }}</span>
                                <span class="text-xs text-gray-400">({{ $vehicleStats['drop_off_percentage'] }}%)</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-parking text-orange-500 mr-3"></i>
                                <span class="text-sm text-gray-600">Waiting</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm font-semibold mr-2">{{ $vehicleStats['waiting_count'] }}</span>
                                <span class="text-xs text-gray-400">({{ $vehicleStats['waiting_percentage'] }}%)</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Vehicle Visits</span>
                            <span class="font-semibold">{{ $vehicleStats['total_vehicle_visits'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-800">Visit Duration</h2>
                        <i class="fas fa-clock text-[#16af8b]"></i>
                    </div>
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-[#16af8b]">{{ $visitDurationStats['average_duration_formatted'] }}</div>
                        <div class="text-sm text-gray-500">Average Duration</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="p-2 bg-gray-50 rounded">
                            <div class="text-sm font-semibold">{{ floor($visitDurationStats['shortest_visit'] / 60) }}h {{ $visitDurationStats['shortest_visit'] % 60 }}m</div>
                            <div class="text-xs text-gray-500">Shortest</div>
                        </div>
                        <div class="p-2 bg-gray-50 rounded">
                            <div class="text-sm font-semibold">{{ floor($visitDurationStats['longest_visit'] / 60) }}h {{ $visitDurationStats['longest_visit'] % 60 }}m</div>
                            <div class="text-xs text-gray-500">Longest</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Visit Status Overview</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">Approved</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold">{{ $visitStatusStats['approved'] }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $visitStatusStats['approved_percentage'] }}%)</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">Pending</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold">{{ $visitStatusStats['pending'] }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $visitStatusStats['pending_percentage'] }}%)</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-600">Rejected</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold">{{ $visitStatusStats['rejected'] }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $visitStatusStats['rejected_percentage'] }}%)</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Visits</span>
                            <span class="font-semibold">{{ $visitStatusStats['total'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Popular Floors</h2>
                    <div class="space-y-3">
                        @foreach($floorDistribution as $floor)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xs font-bold text-purple-600">{{ $floor->floor_of_visit }}</span>
                                </div>
                                <span class="text-sm text-gray-600">Floor {{ $floor->floor_of_visit }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-16 h-2 bg-gray-200 rounded-full mr-3">
                                    <div class="h-2 bg-purple-500 rounded-full" style="width: {{ ($floor->count / $floorDistribution->max('count')) * 100 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $floor->count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> --}}



                {{-- <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-gray-800">Security Today</h2>
                        <i class="fas fa-shield-alt text-green-500"></i>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Gate Arrivals</span>
                            <span class="text-sm font-semibold">{{ $verificationStats['total_arrivals_today'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Verified</span>
                            <span class="text-sm font-semibold text-green-600">{{ $verificationStats['verified_today'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pending</span>
                            <span class="text-sm font-semibold text-yellow-600">{{ $verificationStats['pending_verification'] }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Verification Rate</span>
                            <span class="text-sm font-bold text-green-600">{{ $verificationStats['verification_rate'] }}%</span>
                        </div>
                    </div>
                </div> --}}

                <!-- 7. Top Organizations Card -->
                {{-- <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Top Organizations</h2>
                    <div class="space-y-3">
                        @foreach($organizationStats->take(5) as $org)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs font-bold text-indigo-600">{{ substr($org->organization, 0, 2) }}</span>
                                </div>
                                <span class="text-sm text-gray-600 truncate">{{ Str::limit($org->organization, 20) }}</span>
                            </div>
                            <span class="text-sm font-semibold">{{ $org->visitor_count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div> --}}

                {{-- <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Peak Hours (This Week)</h2>
                    <div class="peak-hours-container" style="height: 200px;">
                        <canvas id="peakHoursChart"></canvas>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <script>
        // Daily Trend Chart
        const dailyTrend = new Chart(document.getElementById('dailyTrend'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyVisitTrend->pluck('day')) !!},
                datasets: [{
                    label: 'Visits',
                    data: {!! json_encode($dailyVisitTrend->pluck('total')) !!},
                    backgroundColor: 'rgba(255, 202, 0, 0.1)',
                    borderColor: '#FFCA00',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#e0b200',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: { size: 12, weight: 'normal' },
                        bodyFont: { size: 14, weight: 'bold' },
                        padding: 12,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y + ' visits';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Weekly Trend Chart
        const weeklyTrend = new Chart(document.getElementById('weeklyTrend'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($weeklyVisitTrend->pluck('day')) !!},
                datasets: [{
                    label: 'Visits',
                    data: {!! json_encode($weeklyVisitTrend->pluck('total')) !!},
                    backgroundColor: '#16AF8B',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: { size: 12, weight: 'normal' },
                        bodyFont: { size: 14, weight: 'bold' },
                        padding: 12,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y + ' visits';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Recent Activity Refresh Functionality
        document.getElementById('refreshActivities').addEventListener('click', function() {
            const button = this;
            const icon = button.querySelector('i');

            // Add spinning animation
            icon.classList.add('fa-spin');

            fetch('/sm/analytics/recent-activities')
                .then(response => response.json())
                .then(data => {
                    updateRecentActivities(data.activities);
                    document.getElementById('lastUpdated').textContent = data.last_updated;
                })
                .catch(error => {
                    console.error('Error fetching recent activities:', error);
                })
                .finally(() => {
                    // Remove spinning animation
                    setTimeout(() => {
                        icon.classList.remove('fa-spin');
                    }, 500);
                });
        });

        function updateRecentActivities(activities) {
            const container = document.getElementById('activityContainer');

            if (activities.length === 0) {
                container.innerHTML = `
                    <div class="flex items-center justify-center h-32 text-gray-400">
                        <div class="text-center">
                            <i class="fas fa-clock text-2xl mb-2"></i>
                            <p class="text-sm">No recent activity</p>
                        </div>
                    </div>
                `;
                return;
            }

            let html = '';
            activities.forEach(activity => {
                const extraInfoHtml = activity.extra_info ?
                    `<span class="text-xs px-2 py-1 rounded-full ${activity.extra_info === 'Verified' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600'}">${activity.extra_info}</span>` : '';

                html += `
                    <div class="activity-item flex items-start space-x-3 p-3 rounded-lg border border-gray-50 hover:border-gray-100">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full ${activity.bg_color} flex items-center justify-center">
                                <i class="${activity.icon} ${activity.color} text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-800">${activity.title}</p>
                                <div class="flex items-center space-x-1">
                                    ${extraInfoHtml}
                                    <span class="pulse-dot w-2 h-2 bg-blue-400 rounded-full"></span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">${activity.description}</p>
                            <p class="text-xs text-gray-400 mt-1">${activity.time_ago}</p>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Auto-refresh recent activities every 30 seconds
        setInterval(() => {
            fetch('/sm/analytics/recent-activities')
                .then(response => response.json())
                .then(data => {
                    updateRecentActivities(data.activities);
                    document.getElementById('lastUpdated').textContent = data.last_updated;
                })
                .catch(error => {
                    console.error('Error auto-refreshing activities:', error);
                });
        }, 30000);

        // Peak Hours Chart
const peakHoursChart = new Chart(document.getElementById('peakHoursChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($peakHoursData->pluck('hour')->map(function($hour) { return $hour . ':00'; })) !!},
        datasets: [{
            label: 'Check-ins',
            data: {!! json_encode($peakHoursData->pluck('count')) !!},
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderRadius: 4,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1F2937',
                titleFont: { size: 12, weight: 'normal' },
                bodyFont: { size: 14, weight: 'bold' },
                padding: 12,
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' check-ins';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false,
                    color: 'rgba(0,0,0,0.05)'
                },
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// // Get the chart container and canvas elements
// const peakHoursChartContainer = document.querySelector('.peak-hours-chart-container');
// const chartCanvas = document.getElementById('peakHoursChart');

// // Get the peak hours data from PHP
// const peakHoursLabels = {!! json_encode($peakHoursData->pluck('hour')->map(function($hour) { return $hour . ':00'; })) !!};
// const peakHoursValues = {!! json_encode($peakHoursData->pluck('count')) !!};

// // Check if there's any data to display
// if (peakHoursValues.some(value => value > 0)) {
//     // Create the chart if there's data
//     const peakHoursChart = new Chart(chartCanvas, {
//         type: 'bar',
//         data: {
//             labels: peakHoursLabels,
//             datasets: [{
//                 label: 'Check-ins',
//                 data: peakHoursValues,
//                 backgroundColor: 'rgba(99, 102, 241, 0.8)',
//                 borderRadius: 4,
//                 borderSkipped: false
//             }]
//         },
//         options: {
//             responsive: true,
//             maintainAspectRatio: false,
//             plugins: {
//                 legend: {
//                     display: false
//                 },
//                 tooltip: {
//                     backgroundColor: '#1F2937',
//                     titleFont: { size: 12, weight: 'normal' },
//                     bodyFont: { size: 14, weight: 'bold' },
//                     padding: 12,
//                     callbacks: {
//                         label: function(context) {
//                             return context.parsed.y + ' check-ins';
//                         }
//                     }
//                 }
//             },
//             scales: {
//                 y: {
//                     beginAtZero: true,
//                     grid: {
//                         drawBorder: false,
//                         color: 'rgba(0,0,0,0.05)'
//                     },
//                     ticks: {
//                         stepSize: 1
//                     }
//                 },
//                 x: {
//                     grid: {
//                         display: false
//                     }
//                 }
//             }
//         }
//     });
// } else {
//     // Show a placeholder message if no data
//     peakHoursChartContainer.innerHTML = `
//         <div class="flex flex-col items-center justify-center h-full text-gray-400">
//             <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
//                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
//             </svg>
//             <p class="text-center">No check-in data available for this week</p>
//         </div>
//     `;
// }
    </script>
</body>
</html>
