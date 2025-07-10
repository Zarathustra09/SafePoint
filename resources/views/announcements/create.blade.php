@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Create Announcement</h4>
                            <small class="text-muted">Add a new announcement</small>
                        </div>
                        <div>
                            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Images</label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                        id="images" name="images[]" multiple accept="image/*">
                                    <label class="input-group-text" for="images">Upload</label>
                                </div>
                                <small class="text-muted">Upload one or more images. The first image will be set as featured.</small>
                                @error('images.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                <!-- Image preview container -->
                                <div class="mt-2 d-flex flex-wrap" id="imagePreviewContainer"></div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> Create Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
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

                    // Add featured badge to the first image
                    if (i === 0) {
                        const featuredBadge = document.createElement('span');
                        featuredBadge.className = 'badge bg-primary position-absolute';
                        featuredBadge.style.top = '5px';
                        featuredBadge.style.right = '5px';
                        featuredBadge.textContent = 'Featured';
                        previewDiv.appendChild(featuredBadge);
                    }
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
