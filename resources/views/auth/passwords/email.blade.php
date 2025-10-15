@extends('layouts.auth')

@section('content')
    <h2 class="text-center mb-4">{{ __('Reset Password') }}</h2>

    <div class="text-center mb-4">
        <i class="bi bi-key text-primary" style="font-size: 3rem;"></i>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <p class="text-center text-muted mb-4">
        Enter your email address and we'll send you a link to reset your password.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>

        <div class="text-center mt-3">
            <a class="site-footer-link" href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
@endsection
