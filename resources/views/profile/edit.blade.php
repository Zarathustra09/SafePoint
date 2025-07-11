@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Edit Profile') }}</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                             alt="Profile Picture"
                                             class="img-fluid rounded-circle mb-3"
                                             style="width: 150px; height: 150px; object-fit: cover;"
                                             id="profilePreview">
                                    @else
                                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                             style="width: 150px; height: 150px;"
                                             id="profilePlaceholder">
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">{{ __('Profile Picture') }}</label>
                                    <input type="file"
                                           class="form-control @error('profile_picture') is-invalid @enderror"
                                           id="profile_picture"
                                           name="profile_picture"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    @error('profile_picture')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Member since:</strong>
                                            {{ $user->created_at->format('F Y') }}
                                        </div>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Email Verified</span>
                                        @else
                                            <span class="badge bg-warning">Email Not Verified</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-danger">Danger Zone</h6>
                            <p class="text-muted">Once you delete your account, all of its resources and data will be permanently deleted.</p>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p><strong>All your data including announcements will be permanently deleted.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('profile.destroy') }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const placeholder = document.getElementById('profilePlaceholder');
            const preview = document.getElementById('profilePreview');

            if (placeholder) {
                placeholder.style.display = 'none';
            }

            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            } else {
                // Create new image element if it doesn't exist
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.alt = 'Profile Picture';
                newImg.className = 'img-fluid rounded-circle mb-3';
                newImg.style = 'width: 150px; height: 150px; object-fit: cover;';
                newImg.id = 'profilePreview';

                if (placeholder) {
                    placeholder.parentNode.insertBefore(newImg, placeholder);
                }
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
