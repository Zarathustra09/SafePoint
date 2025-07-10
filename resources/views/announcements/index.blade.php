@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Announcements</h4>
                            <small class="text-muted">Manage all announcements</small>
                        </div>
                        <div>
                            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                                <i class='bx bx-plus'></i> Create Announcement
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($announcements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($announcements as $announcement)
                                            <tr>
                                                <td>
                                                    @if($announcement->featured_image)
                                                        <img src="{{ asset('storage/' . $announcement->featured_image['path']) }}"
                                                            alt="{{ $announcement->title }}"
                                                            class="img-thumbnail"
                                                            style="width: 80px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                                            style="width: 80px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $announcement->title }}</td>
                                                <td>{{ Str::limit($announcement->description, 100) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>{{ $announcement->user->name ?? 'Unknown' }}</td>
                                                <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class='bx bx-show'></i> View
                                                        </a>
                                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class='bx bx-edit'></i> Edit
                                                        </a>
                                                        <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class='bx bx-trash'></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $announcements->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No announcements found.</p>
                                <a href="{{ route('announcements.create') }}" class="btn btn-primary">Create First Announcement</a>
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
@endsection
