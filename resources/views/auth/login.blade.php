@extends('layouts.auth')

@section('content')
    <h2 class="text-center mb-4">{{ __('Login') }}</h2>

    <form method="POST" action="{{ route('login') }}" autocomplete="on">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="username" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div class="position-relative">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                    onclick="togglePassword()" style="text-decoration: none; z-index: 10;">
                    <i id="toggleIcon" class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn custom-btn">
                {{ __('Login') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <div class="text-center mt-3">
                <a class="site-footer-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        @endif
    </form>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }

        // Remember credentials functionality
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            const rememberCheckbox = document.getElementById('remember');
            const form = document.querySelector('form');

            // Load remembered email on page load
            const rememberedEmail = localStorage.getItem('remembered_email');
            if (rememberedEmail) {
                emailField.value = rememberedEmail;
                rememberCheckbox.checked = true;
            }

            // Save or remove email on form submit
            form.addEventListener('submit', function() {
                if (rememberCheckbox.checked && emailField.value) {
                    localStorage.setItem('remembered_email', emailField.value);
                } else {
                    localStorage.removeItem('remembered_email');
                }
            });
        });
    </script>
@endsection
