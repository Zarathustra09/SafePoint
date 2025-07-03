@extends('layouts.app')

@section('content')
    <style>
       .map-background {
           position: absolute;
           top: 0;
           left: 0;
           width: 100%;
           height: 120vh; /* Extended height to cover the gap */
           z-index: -1;
           overflow: hidden;
       }

       .map-background iframe {
           width: 100%;
           height: 100%;
           border: none;
           filter: grayscale(30%) brightness(0.8);
           transform: scale(1.1);
       }

       .hero-section {
           background: transparent;
           min-height: 120vh; /* Match the map height */
           position: relative;
       }

       .hero-section::before {
           content: '';
           position: absolute;
           top: 0;
           left: 0;
           right: 0;
           bottom: 0;
           background: rgba(0, 0, 0, 0.3);
           z-index: 1;
       }

       .custom-block {
           background: rgba(255, 255, 255, 0.95) !important;
           backdrop-filter: blur(15px);
           border: 1px solid rgba(255, 255, 255, 0.3);
           box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
           border-radius: 15px;
           position: relative;
           z-index: 2;
       }

       .container {
           position: relative;
           z-index: 2;
       }
    </style>

    <div class="map-background">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3592.4951!2d-80.1917902!3d25.7616798!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9b0a20ec8c111%3A0xa5e4c8e0e5e5f5e5!2sMiami%2C%20FL%2C%20USA!5e0!3m2!1sen!2sus!4v1645123456789!5m2!1sen!2sus"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="custom-block bg-white shadow-lg p-4">
                        <h2 class="text-center mb-4">{{ __('Register') }}</h2>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn custom-btn">
                                    {{ __('Register') }}
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <p>Already have an account? <a href="{{ route('login') }}" class="site-footer-link">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
