@extends('layouts.auth')

@section('content')
    <h2 class="text-center mb-4">{{ __('Verify Your Email Address') }}</h2>

    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="text-center mb-4">
        <i class="bi bi-envelope-check text-primary" style="font-size: 3rem;"></i>
    </div>

    <p class="text-center mb-4">
        {{ __('Before proceeding, please check your email for a verification link.') }}
    </p>

    <p class="text-center">
        {{ __('If you did not receive the email') }},
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-decoration-none">
                {{ __('click here to request another') }}
            </button>.
        </form>
    </p>
@endsection
