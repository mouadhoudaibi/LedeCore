@extends('layouts.admin')

@section('title', __('admin.dashboard') . ' - LedeCore')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-white">{{ __('admin.dashboard') }}</h1>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h2 class="text-xl font-semibold text-white mb-4">{{ __('admin.date_range') }}</h2>
        <form id="dateRangeForm" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-gray-300 text-sm font-medium mb-2">{{ __('admin.start_date') }}</label>
                <input type="date" id="startDate" name="start_date" value="{{ now()->subDays(30)->format('Y-m-d') }}" class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
            </div>
            <div class="flex-1">
                <label class="block text-gray-300 text-sm font-medium mb-2">{{ __('admin.end_date') }}</label>
                <input type="date" id="endDate" name="end_date" value="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg transition font-medium">
                    {{ __('admin.update_chart') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">{{ __('admin.pending_orders') }}</p>
                    <p id="pendingCount" class="text-3xl font-bold text-yellow-400 mt-2">0</p>
                </div>
                <div class="bg-yellow-400/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">{{ __('admin.validated_orders') }}</p>
                    <p id="validatedCount" class="text-3xl font-bold text-green-400 mt-2">0</p>
                </div>
                <div class="bg-green-400/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">{{ __('admin.refused_orders') }}</p>
                    <p id="refusedCount" class="text-3xl font-bold text-red-400 mt-2">0</p>
                </div>
                <div class="bg-red-400/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium">{{ __('admin.total_revenue') }}</p>
                    <p id="totalRevenue" class="text-3xl font-bold text-purple-400 mt-2">0 MAD</p>
                </div>
                <div class="bg-purple-400/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order Statistics Chart -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h2 class="text-xl font-semibold text-white mb-6">{{ __('admin.order_statistics') }}</h2>
            <div class="relative" style="height: 400px;">
                <canvas id="orderChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h2 class="text-xl font-semibold text-white mb-6">{{ __('admin.revenue_evolution') }}</h2>
            <div class="relative" style="height: 400px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let orderChart = null;
    let revenueChart = null;

    // Initialize order statistics chart
    function initOrderChart(data) {
        const ctx = document.getElementById('orderChart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (orderChart) {
            orderChart.destroy();
        }

        orderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    '{{ __('admin.pending') }}',
                    '{{ __('admin.validated') }}',
                    '{{ __('admin.refused') }}'
                ],
                datasets: [{
                    label: '{{ __('admin.number_of_orders') }}',
                    data: [data.pending, data.validated, data.refused],
                    backgroundColor: [
                        'rgba(250, 204, 21, 0.8)',  // Yellow for pending
                        'rgba(74, 222, 128, 0.8)',  // Green for validated
                        'rgba(248, 113, 113, 0.8)'  // Red for refused
                    ],
                    borderColor: [
                        'rgb(250, 204, 21)',
                        'rgb(74, 222, 128)',
                        'rgb(248, 113, 113)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
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
                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#6b7280',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return '{{ __('admin.orders') }}: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            },
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(107, 114, 128, 0.3)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Update statistics cards
    function updateCards(data) {
        document.getElementById('pendingCount').textContent = data.pending;
        document.getElementById('validatedCount').textContent = data.validated;
        document.getElementById('refusedCount').textContent = data.refused;
    }

    // Initialize revenue chart
    function initRevenueChart(data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (revenueChart) {
            revenueChart.destroy();
        }

        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: '{{ __('admin.daily_revenue') }}',
                    data: data.revenues,
                    borderColor: 'rgb(147, 51, 234)', // Purple
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, // Smooth curve
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgb(147, 51, 234)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: 'rgb(168, 85, 247)',
                    pointHoverBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#6b7280',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return '{{ __('admin.revenue') }}: ' + context.parsed.y.toFixed(2) + ' MAD';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return value.toFixed(0) + ' MAD';
                            }
                        },
                        grid: {
                            color: 'rgba(107, 114, 128, 0.3)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Load statistics
    function loadStatistics() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Load order statistics
        fetch(`{{ route('admin.dashboard.statistics') }}?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                initOrderChart(data);
                updateCards(data);
            })
            .catch(error => {
                console.error('Error loading statistics:', error);
            });

        // Load revenue statistics
        fetch(`{{ route('admin.dashboard.revenue') }}?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                initRevenueChart(data);
                // Update total revenue card
                document.getElementById('totalRevenue').textContent = data.total.toFixed(2) + ' MAD';
            })
            .catch(error => {
                console.error('Error loading revenue:', error);
            });
    }

    // Handle form submission
    document.getElementById('dateRangeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadStatistics();
    });

    // Load initial data
    loadStatistics();
});
</script>
@endsection

