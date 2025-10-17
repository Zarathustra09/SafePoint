<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('css/templatemo-topic-listing.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .map-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            min-height: 100vh;
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

        /* Ensure footer has solid background */
        .site-footer {
            background-color: var(--dark-color) !important;
            z-index: 100 !important;
            position: relative;
        }

        /* Ensure main content doesn't overlap footer */
        main {
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0d6efd, #0056b3);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #004085);
            transform: translateY(-1px);
        }
    </style>
</head>

<body id="top">
    <main>
        @include('layouts.header')

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
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>

