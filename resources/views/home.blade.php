@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Report Status Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Login Attempts (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="loginChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Button Cards Section -->
    <div class="row g-4 mb-4">
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
                    <a href="{{route('reports.index')}}" class="btn btn-info">Open Map</a>
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
                    <a href="{{route('reports.list')}}" class="btn btn-warning">View Reports</a>
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
                    <a href="{{route('announcements.index')}}" class="btn btn-success">Manage</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Attempts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Recent Login Attempts</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginAttempts ?? [] as $attempt)
                                <tr>
                                    <td>{{ $attempt->email ?? 'N/A' }}</td>
                                    <td>{{ $attempt->ip_address }}</td>
                                    <td>
                                        <span class="badge {{ $attempt->successful ? 'bg-success' : 'bg-danger' }}">
                                            {{ $attempt->successful ? 'Success' : 'Failed' }}
                                        </span>
                                    </td>
                                    <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent login attempts</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Status Chart
        const statusLabels = ['pending', 'under_investigation', 'resolved', 'closed'];
        const statusData = [
            {{ $statusCounts['pending'] ?? 0 }},
            {{ $statusCounts['under_investigation'] ?? 0 }},
            {{ $statusCounts['resolved'] ?? 0 }},
            {{ $statusCounts['closed'] ?? 0 }}
        ];
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Reports (Last 7 Days)',
                    data: statusData,
                    backgroundColor: [
                        '#696cff', '#03c3ec', '#ffab00', '#71dd37'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Login Attempts Chart
        const loginLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const loginData = @json($loginAttemptsData ?? [0,0,0,0,0,0,0]);
        const loginCtx = document.getElementById('loginChart').getContext('2d');
        new Chart(loginCtx, {
            type: 'line',
            data: {
                labels: loginLabels,
                datasets: [{
                    label: 'Login Attempts',
                    data: loginData,
                    borderColor: '#696cff',
                    backgroundColor: 'rgba(105, 108, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
