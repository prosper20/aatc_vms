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
    <div class="min-h-screen p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-row justify-between items-start md:items-center mb-6 gap-4">
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
                    <a href="/sm/analytics/export" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-50 text-blue-600 mr-4">
                            <i class="fas fa-calendar-day text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Visits Today</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $visitsToday }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-purple-50 text-purple-600 mr-4">
                            <i class="fas fa-redo text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Visits This Week</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $visitsThisWeek }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-purple-50 text-purple-600 mr-4">
                            <i class="fas fa-redo text-lg"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Visits This Month</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $visitsThisMonth }}</p>
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

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Frequent Visitors Table -->
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
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <div class="flex items-center justify-center h-64 text-gray-400">
                        <p>Recent visitor check-ins will appear here</p>
                    </div>
                </div>
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
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    borderColor: '#3B82F6',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#3B82F6',
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
                    backgroundColor: '#3B82F6',
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
    </script>
</body>
</html>
