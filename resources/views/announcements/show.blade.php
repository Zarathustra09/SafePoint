@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>{{ $announcement->title }}</h4>
                            <small class="text-muted">Created {{ $announcement->created_at->format('M d, Y') }} by {{ $announcement->user->name ?? 'Unknown' }}</small>
                        </div>
                        <div>
                            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                            <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-primary">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Images Carousel -->
                            @if(!empty($announcement->images) && count($announcement->images) > 0)
                                <div class="col-lg-6 mb-4">
                                    <div id="announcementCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($announcement->images as $index => $image)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $image['path']) }}"
                                                        class="d-block w-100"
                                                        alt="Announcement Image"
                                                        style="height: 400px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(count($announcement->images) > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Image Thumbnails -->
                                    @if(count($announcement->images) > 1)
                                        <div class="d-flex flex-wrap mt-2">
                                            @foreach($announcement->images as $index => $image)
                                                <div class="me-2 mb-2" style="width: 60px; height: 60px;">
                                                    <img src="{{ asset('storage/' . $image['path']) }}"
                                                        class="img-thumbnail w-100 h-100"
                                                        style="object-fit: cover; cursor: pointer;"
                                                        onclick="document.querySelector('#announcementCarousel').querySelector('.carousel-item:nth-child({{ $index + 1 }})').classList.add('active');"
                                                        alt="Thumbnail">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Announcement Details -->
                            <div class="{{ !empty($announcement->images) && count($announcement->images) > 0 ? 'col-lg-6' : 'col-12' }}">
                                <div class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }} mb-3">
                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                </div>

                                <h5 class="mb-3">Description</h5>
                                <div class="mb-4">
                                    {{ $announcement->description }}
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Created:</strong> {{ $announcement->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Last Updated:</strong> {{ $announcement->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
