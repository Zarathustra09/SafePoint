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
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bxs-plus-square bx-md"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">File a Report</h5>
                    <p class="card-text">Submit incident reports and documentation for community safety.</p>
                    <a href="#" class="btn btn-primary">Create Report</a>
                </div>
            </div>
        </div>

        <!-- View Map Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-map bx-md"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">View Map</h5>
                    <p class="card-text">Explore interactive maps and location-based information.</p>
                    <a href="#" class="btn btn-info">Open Map</a>
                </div>
            </div>
        </div>

        <!-- Crime Reports Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-shield-alt-2 bx-md"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">Crime Reports</h5>
                    <p class="card-text">Access and review crime incident reports and statistics.</p>
                    <a href="#" class="btn btn-warning">View Reports</a>
                </div>
            </div>
        </div>

        <!-- Community Portal Moderation Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="bx bx-cog bx-md"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">Portal Moderation</h5>
                    <p class="card-text">Manage and moderate community portal content and users.</p>
                    <a href="#" class="btn btn-secondary">Moderate</a>
                </div>
            </div>
        </div>

        <!-- Announcements Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-message bx-md"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">Announcements</h5>
                    <p class="card-text">Create and manage community announcements and notifications.</p>
                    <a href="#" class="btn btn-success">Manage</a>
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="col-xl-7 col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Recent Activity</h5>
                    <small class="text-muted">Last 7 days</small>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Reports Filed</span>
                        <span class="badge bg-primary">12</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Maps Accessed</span>
                        <span class="badge bg-info">45</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Crime Reports Reviewed</span>
                        <span class="badge bg-warning">8</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Announcements Posted</span>
                        <span class="badge bg-success">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
