@extends('layouts.auth')

@section('content')
    <h2 class="text-center mb-4">{{ __('Password Updated') }}</h2>

    @if(session('status'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <p class="mb-4">{{ __('Your password has been updated. You can now use your password') }}</p>

    <div class="d-grid">
        <a href="{{ url('/') }}" class="btn btn-primary">
            {{ __('Continue to Application') }}
        </a>
    </div>
@endsection
