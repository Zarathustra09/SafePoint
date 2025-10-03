@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Role Management</h4>
                            <small class="text-muted">Manage user roles and permissions</small>
                        </div>
                        <div>
                            <span class="badge bg-info">{{ $users->count() }} Total Users</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="usersTable">
                                    <thead class="table-dark">
                                        <tr style="color: white;">
                                            <th style="color: white;">#</th>
                                            <th style="color: white;">User Details</th>
                                            <th style="color: white;">Email</th>
                                            <th style="color: white;">Current Roles</th>
                                            <th style="color: white;">Verification Status</th>
                                            <th style="color: white;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @forelse($user->getRoleNames() as $role)
                                                        <span class="badge bg-{{ $role === 'Admin' ? 'success' : 'info' }} me-1">{{ $role }}</span>
                                                    @empty
                                                        <span class="badge bg-secondary">No Roles</span>
                                                    @endforelse
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $user->is_verified ? 'success' : 'warning' }}">
                                                        {{ $user->is_verified ? 'Verified' : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(!$user->hasRole('Admin'))
                                                            <form method="POST" action="{{ route('roles.assignAdmin', $user->id) }}" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary btn-sm"
                                                                        onclick="return confirm('Are you sure you want to assign Admin role to {{ $user->name }}?')">
                                                                    <i class="bx bx-user-plus"></i> Assign Admin
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-success">
                                                                <i class="bx bx-check"></i> Admin Assigned
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-user-x display-4 text-muted"></i>
                                <h5 class="mt-3 text-muted">No Users Found</h5>
                                <p class="text-muted">There are no users in the system.</p>
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

                // Auto dismiss after 5 seconds
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

    <script>
        // Initialize DataTable if available
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('#usersTable').DataTable({
                    "pageLength": 10,
                    "responsive": true,
                    "language": {
                        "search": "Search users:",
                        "lengthMenu": "Show _MENU_ users per page",
                        "info": "Showing _START_ to _END_ of _TOTAL_ users"
                    }
                });
            }
        });
    </script>
@endsection
