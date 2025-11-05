@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="row g-4 mb-4">
            <!-- Crime Reports Over Time Chart -->
            <div class="col-xl-8 col-lg-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Crime Reports Over Time (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="crimesTimeChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Crime Reports by Severity Chart -->
            <div class="col-xl-4 col-lg-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">By Severity (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="crimesSeverityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed Login Attempts Section -->
        @if($failedLogins->count() > 0)
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-error-circle me-2 text-warning"></i>
                            Recent Failed Login Attempts for Your Account
                        </h5>
                        <span class="badge bg-warning">{{ $failedLogins->count() }} attempts</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($failedLogins as $attempt)
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ $attempt->created_at->format('M d, Y') }}</span><br>
                                            <small class="text-muted">{{ $attempt->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <code class="text-danger">{{ $attempt->ip_address }}</code>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="{{ $attempt->user_agent }}">
                                                {{ Str::limit($attempt->user_agent, 50) }}
                                            </small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4">
            <!-- File a Report Card -->

            <!-- View Map Card -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar avatar-md mx-auto mb-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-map bx-md"></i>
                            </span>
                        </div>
                        <h5 class="card-title mb-2">View Map</h5>
                        <p class="card-text">Explore interactive maps and location-based information.</p>
                        <a href="{{ route('reports.index') }}" class="btn btn-info">Open Map</a>
                    </div>
                </div>
            </div>

            <!-- Crime Reports Card -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar avatar-md mx-auto mb-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-shield-alt-2 bx-md"></i>
                            </span>
                        </div>
                        <h5 class="card-title mb-2">Crime Reports</h5>
                        <p class="card-text">Access and review crime incident reports and statistics.</p>
                        <a href="{{ route('reports.list') }}" class="btn btn-warning">View Reports</a>
                    </div>
                </div>
            </div>


            <!-- Announcements Card -->
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar avatar-md mx-auto mb-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-message bx-md"></i>
                            </span>
                        </div>
                        <h5 class="card-title mb-2">Announcements</h5>
                        <p class="card-text">Create and manage community announcements and notifications.</p>
                        <a href="{{ route('announcements.index') }}" class="btn btn-success">Manage</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Crime Reports Over Time Chart
        const timeLabels = [
            @foreach ($crimesByDay as $crime)
                '{{ \Carbon\Carbon::parse($crime->date)->format('M d') }}',
            @endforeach
        ];
        const timeData = [
            @foreach ($crimesByDay as $crime)
                {{ $crime->count }},
            @endforeach
        ];

        const ctxTime = document.getElementById('crimesTimeChart').getContext('2d');
        new Chart(ctxTime, {
            type: 'bar',
            data: {
                labels: timeLabels,
                datasets: [{
                    label: 'Crime Reports',
                    data: timeData,
                    backgroundColor: '#696cff',
                    borderColor: '#696cff',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
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

        // Crime Reports by Severity Chart
        const severityLabels = ['Low', 'Medium', 'High', 'Critical'];
        const severityData = [
            {{ $crimesBySeverity['low'] ?? 0 }},
            {{ $crimesBySeverity['medium'] ?? 0 }},
            {{ $crimesBySeverity['high'] ?? 0 }},
            {{ $crimesBySeverity['critical'] ?? 0 }}
        ];

        const ctxSeverity = document.getElementById('crimesSeverityChart').getContext('2d');
        new Chart(ctxSeverity, {
            type: 'doughnut',
            data: {
                labels: severityLabels,
                datasets: [{
                    data: severityData,
                    backgroundColor: [
                        '#71dd37', // Low - Green
                        '#ffab00', // Medium - Yellow
                        '#ff6384', // High - Red
                        '#8b0000' // Critical - Dark Red
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                    }
                }
            }
        });
    </script>
@endpush
