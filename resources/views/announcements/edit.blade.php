@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Edit Announcement</h4>
                            <small class="text-muted">Editing: {{ $announcement->title }}</small>
                        </div>
                        <div>
                            <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to Details
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="5" required>{{ old('description', $announcement->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Images -->
                            @if(!empty($announcement->images) && count($announcement->images) > 0)
                                <div class="mb-3">
                                    <label class="form-label">Current Images</label>
                                    <div class="d-flex flex-wrap" id="currentImages">
                                        @foreach($announcement->images as $index => $image)
                                            <div class="position-relative me-2 mb-2" id="image-{{ $image['id'] }}" style="width: 100px; height: 100px;">
                                                <img src="{{ asset('storage/' . $image['path']) }}"
                                                    class="img-thumbnail w-100 h-100"
                                                    style="object-fit: cover;"
                                                    alt="Image {{ $index + 1 }}">

                                                @if(!(isset($image['is_featured']) && $image['is_featured']))
                                                    <button type="button" class="btn btn-sm btn-primary mx-1" onclick="setFeatured('{{ $image['id'] }}')">
                                                        <i class='bx bx-star'></i>
                                                    </button>
                                                @endif

                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center image-overlay" style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.2s;">
                                                    <button type="button" class="btn btn-sm btn-danger mx-1" onclick="deleteImage('{{ $image['id'] }}')">
                                                        <i class='bx bx-trash'></i>
                                                    </button>

                                                    @if(!(isset($image['is_featured']) && $image['is_featured']))
                                                        <button type="button" class="btn btn-sm btn-primary mx-1"
                                                            onclick="setFeatured('{{ $image['id'] }}')">
                                                <i class='bx bx-star'></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="images" class="form-label">Add New Images</label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                        id="images" name="images[]" multiple accept="image/*">
                                    <label class="input-group-text" for="images">Upload</label>
                                </div>
                                <small class="text-muted">Upload one or more new images</small>
                                @error('images.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                <!-- Image preview container -->
                                <div class="mt-2 d-flex flex-wrap" id="imagePreviewContainer"></div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ $announcement->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> Update Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hover effect for current images
        document.querySelectorAll('.image-overlay').forEach(overlay => {
            const parent = overlay.parentElement;

            parent.addEventListener('mouseenter', () => {
                overlay.style.opacity = 1;
            });

            parent.addEventListener('mouseleave', () => {
                overlay.style.opacity = 0;
            });
        });

        // New image preview functionality
        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = '';

            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.match('image.*')) continue;

                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'position-relative me-2 mb-2';
                    previewDiv.style.width = '100px';
                    previewDiv.style.height = '100px';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail w-100 h-100';
                    img.style.objectFit = 'cover';

                    previewDiv.appendChild(img);
                    previewContainer.appendChild(previewDiv);
                };

                reader.readAsDataURL(file);
            }
        });

        // Set an image as featured
        function setFeatured(imageId) {
            fetch(`/announcements/{{ $announcement->id }}/image/${imageId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_featured: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove all featured badges
                    document.querySelectorAll('.badge.bg-primary').forEach(badge => {
                        badge.remove();
                    });

                    // Add badge to the selected image
                    const imageDiv = document.getElementById(`image-${imageId}`);
                    const featuredBadge = document.createElement('span');
                    featuredBadge.className = 'badge bg-primary position-absolute';
                    featuredBadge.style.top = '5px';
                    featuredBadge.style.right = '5px';
                    featuredBadge.textContent = 'Featured';
                    imageDiv.appendChild(featuredBadge);

                    // Show success message
                    showAlert('Featured image has been updated', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Failed to update featured image', 'danger');
            });
        }

        // Delete an image
        function deleteImage(imageId) {
            if (!confirm('Are you sure you want to delete this image?')) return;

            fetch(`/announcements/{{ $announcement->id }}/image/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image from DOM
                    document.getElementById(`image-${imageId}`).remove();

                    // Show success message
                    showAlert('Image has been deleted', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Failed to delete image', 'danger');
            });
        }

        // Helper function to show alerts
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
@endsection
