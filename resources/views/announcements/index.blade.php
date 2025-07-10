@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="card mb-4">
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
                </div>

                <!-- Facebook-like scrollable announcement feed -->
                <div class="announcement-feed">
                    @if($announcements->count() > 0)
                        @foreach($announcements as $announcement)
                            <div class="card mb-4 announcement-card">
                                <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-circle me-3">
                                            <i class='bx bx-user' style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $announcement->user->name ?? 'Unknown' }}</h6>
                                            <small class="text-muted">
                                                {{ $announcement->created_at->format('M d, Y') }} ·
                                                <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-2">{{ $announcement->title }}</h5>
                                    <p class="card-text mb-3">{{ Str::limit($announcement->description, 200) }}</p>
                                </div>

                                @if($announcement->featured_image)
                                    <a href="{{ route('announcements.show', $announcement) }}" class="position-relative">
                                        <img src="{{ asset('storage/' . $announcement->featured_image['path']) }}"
                                            alt="{{ $announcement->title }}"
                                            class="card-img"
                                            style="max-height: 500px; object-fit: cover;">
                                    </a>
                                @endif

                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <div class="d-flex border-top pt-3">
                                        <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-light flex-grow-1 me-1">
                                            <i class='bx bx-show'></i> View
                                        </a>
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-light flex-grow-1 me-1">
                                            <i class='bx bx-edit'></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="flex-grow-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light w-100" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                <i class='bx bx-trash'></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-2 mb-4">
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <div class="card py-5">
                            <div class="text-center">
                                <i class='bx bx-news' style="font-size: 4rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">No announcements found.</p>
                                <a href="{{ route('announcements.create') }}" class="btn btn-primary mt-2">Create First Announcement</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .announcement-feed {
            max-width: 100%;
            margin: 0 auto;
        }

        .announcement-card {
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .announcement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: #f0f2f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Make the feed feel more like Facebook */
        body {
            background-color: #f0f2f5;
        }

        .card {
            border: none;
        }
    </style>

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
