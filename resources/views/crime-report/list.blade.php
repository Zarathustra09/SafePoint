@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Crime Reports List</h4>
                            <small class="text-muted">All crime reports in Tanauan City, Batangas</small>
                        </div>
                        <div>
                            <a href="{{ route('reports.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Report
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($crimeReports->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="reports">
                                    <thead class="table-dark">
                                        <tr style="color: white;">
                                            <th style="color: white;">#</th>
                                            <th style="color: white;">Title</th>
                                            <th style="color: white;">Severity</th>
                                            <th style="color: white;">Status</th>
                                            <th style="color: white;">Address</th>
                                            <th style="color: white;">Incident Date</th>
                                            <th style="color: white;">Reported By</th>
                                            <th style="color: white;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($crimeReports as $index => $report)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $report->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($report->description, 60) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $report->severity === 'critical' ? 'danger' : ($report->severity === 'high' ? 'warning' : ($report->severity === 'medium' ? 'info' : 'secondary')) }}">
                                                        {{ ucfirst($report->severity) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $report->status === 'resolved' ? 'success' : ($report->status === 'under_investigation' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($report->address, 40) }}</td>
                                                <td>
                                                    {{ $report->incident_date->format('M d, Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ $report->incident_date->format('H:i') }}</small>
                                                </td>
                                                <td>{{ $report->reporter->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('reports.show', $report) }}" class="btn btn-outline-primary btn-sm" title="View">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="{{ route('reports.edit', $report) }}" class="btn btn-outline-secondary btn-sm" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('reports.destroy', $report) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this report?')" title="Delete">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bx bx-file bx-lg text-muted mb-3"></i>
                                <h5 class="text-muted">No crime reports found</h5>
                                <p class="text-muted">Start by creating your first crime report.</p>
                                <a href="{{ route('reports.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus"></i> Create First Report
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            });
        </script>
    @endif

    <style>
        /* DataTable search bar spacing */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 15px;
        }

        .dataTables_wrapper .row:first-child {
            margin-bottom: 20px;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#reports').DataTable();
        });
    </script>
@endpush
