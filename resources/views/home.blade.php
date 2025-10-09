@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
        </div>
    </div>

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
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
    </script>
@endpush
