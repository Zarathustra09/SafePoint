@extends('layouts.admin.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Users Pending Approval</h4>
                            <small class="text-muted">Review and approve or reject new users</small>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="users-approval">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="color: white;">#</th>
                                            <th style="color: white;">Name</th>
                                            <th style="color: white;">Email</th>
                                            <th style="color: white;">Valid ID</th>
                                            <th style="color: white;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->valid_id_image)
                                                        <img src="{{ asset('storage/' . $user->valid_id_image) }}" alt="Valid ID" width="60" class="rounded">
                                                    @else
                                                        <span class="text-muted">Not uploaded</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('approval.show', $user) }}" class="btn btn-outline-primary btn-sm" title="Review">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('approval.approve', $user) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Approve">
                                                                <i class="bx bx-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('approval.reject', $user) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Reject" onclick="return confirm('Are you sure you want to reject this user?');">
                                                                <i class="bx bx-x"></i>
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
                                <i class="bx bx-user-check bx-lg text-muted mb-3"></i>
                                <h5 class="text-muted">No users pending approval</h5>
                                <p class="text-muted">All users are currently verified or no new registrations.</p>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#users-approval').DataTable();
        });
    </script>
@endpush
