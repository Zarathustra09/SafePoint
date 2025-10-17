@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .map-background {
            position: fixed;
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
            padding: 40px 0;
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

        .card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
            border-radius: 15px;
        }

        .card-header {
            background: rgba(13, 110, 253, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px 15px 0 0 !important;
            color: white;
        }

        .card-footer {
            background: rgba(248, 249, 250, 0.9) !important;
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0 0 15px 15px !important;
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

        .btn-outline-secondary {
            border-color: rgba(108, 117, 125, 0.5);
            color: #6c757d;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }

        .btn-outline-secondary:hover {
            background: rgba(108, 117, 125, 0.9);
            border-color: #6c757d;
            color: white;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background: rgba(13, 110, 253, 0.1);
        }

        #map {
            border-radius: 0 0 15px 15px;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
            font-size: 0.875rem;
        }

        .legend-item i {
            margin-right: 5px;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .modal-header {
            background: rgba(13, 110, 253, 0.9);
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bg-success {
            background: rgba(25, 135, 84, 0.9) !important;
        }

        .info-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>

    <div class="map-background">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15461.845991887456!2d121.14880000000001!3d14.0865!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd72cf28b8a663%3A0x5b0e9c6e3a3a3a3a!2sTanauan%2C%20Batangas%2C%20Philippines!5e0!3m2!1sen!2sus!4v1645123456789!5m2!1sen!2sus"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <section class="hero-section d-flex justify-content-center align-items-center pt-5" id="section_1">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4 justify-content-center">
                <div class="col-lg-8">
                    <div class="custom-block text-center py-4">
                        <i class="fas fa-map-marked-alt fa-3x text-primary mb-3"></i>
                        <h2 class="h3 mb-2">AI Crime Map</h2>
                        <p class="text-muted mb-0">Interactive map showing crime reports in Tanauan City, Batangas</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Map Section -->
                <div class="col-lg-8">
                    <div class="custom-block p-4">
                        <div class="mb-4 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Interactive Crime Map
                                </h5>
                                <div class="d-flex gap-2 flex-wrap mt-2 mt-md-0">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-filter"></i> Severity
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="filterBySeverity('all')">
                                                <i class="fas fa-list me-2"></i>All
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterBySeverity('critical')">
                                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>Critical
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterBySeverity('high')">
                                                <i class="fas fa-exclamation-circle text-warning me-2"></i>High
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterBySeverity('medium')">
                                                <i class="fas fa-info-circle text-info me-2"></i>Medium
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterBySeverity('low')">
                                                <i class="fas fa-check-circle text-success me-2"></i>Low
                                            </a></li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-tasks"></i> Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">
                                                <i class="fas fa-list me-2"></i>All
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterByStatus('pending')">
                                                <i class="fas fa-clock text-secondary me-2"></i>Pending
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterByStatus('under_investigation')">
                                                <i class="fas fa-search text-warning me-2"></i>Under Investigation
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterByStatus('resolved')">
                                                <i class="fas fa-check text-success me-2"></i>Resolved
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Map Container -->
                        <div class="mb-4">
                            <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>
                        </div>

                        <!-- Map Legend -->
                        <div class="d-flex flex-wrap justify-content-center gap-3 mb-3">
                            <span class="legend-item">
                                <i class="fas fa-map-marker-alt text-danger"></i> Critical
                            </span>
                            <span class="legend-item">
                                <i class="fas fa-map-marker-alt text-warning"></i> High
                            </span>
                            <span class="legend-item">
                                <i class="fas fa-map-marker-alt text-info"></i> Medium
                            </span>
                            <span class="legend-item">
                                <i class="fas fa-map-marker-alt text-success"></i> Low
                            </span>
                        </div>

                        <!-- Action Buttons -->
{{--                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">--}}
{{--                            <a href="{{ route('reports.create') }}" class="btn btn-primary">--}}
{{--                                <i class="fas fa-plus me-2"></i>--}}
{{--                                Report Crime--}}
{{--                            </a>--}}
{{--                            <button type="button" class="btn btn-outline-secondary" onclick="centerMap()">--}}
{{--                                <i class="fas fa-crosshairs me-2"></i>--}}
{{--                                Center Map--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div>

                <!-- Map Information Sidebar -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="custom-block mb-4">
                        <div class="card-header text-white mb-3 rounded">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Map Statistics
                            </h5>
                        </div>
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Total Reports</h6>
                                    <p class="mb-0 text-muted">{{ count($crimeReports) }} incidents reported</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Coverage Area</h6>
                                    <p class="mb-0 text-muted">Tanauan City, Batangas</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="info-icon">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Last Updated</h6>
                                    <p class="mb-0 text-muted">{{ now()->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="d-flex align-items-center">
                                <div class="info-icon">
                                    <i class="fas fa-shield-alt text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Safety Level</h6>
                                    <p class="mb-0 text-muted">
                                        @if(count($crimeReports) < 5)
                                            <span class="badge bg-success">Safe</span>
                                        @elseif(count($crimeReports) < 15)
                                            <span class="badge bg-warning">Moderate</span>
                                        @else
                                            <span class="badge bg-danger">High Alert</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="custom-block">
                        <div class="bg-success text-white mb-3 p-3 rounded">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Map Instructions
                            </h5>
                        </div>
                        <p class="mb-3">Click on map markers to view detailed crime report information. Use filters to narrow down results by severity or status.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('contact.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-envelope me-2"></i>
                                Contact Support
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fas fa-question-circle me-2"></i>
                                Map Help
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Crime Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-alt me-2"></i>
                        Crime Report Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&callback=initMap">
    </script>

    <script>
        let map;
        let markers = [];
        let infoWindow;

        // Crime reports data
        const crimeReports = @json($crimeReports);

        function initMap() {
            // Default location - Tanauan City, Batangas, Philippines
            const tanauanCity = { lat: 14.0865, lng: 121.1488 };

            // Initialize map
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: tanauanCity,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    },
                    {
                        featureType: "water",
                        elementType: "geometry",
                        stylers: [{ color: "#e3f2fd" }]
                    },
                    {
                        featureType: "landscape",
                        elementType: "geometry",
                        stylers: [{ color: "#f5f5f5" }]
                    }
                ]
            });

            // Initialize info window
            infoWindow = new google.maps.InfoWindow({
                maxWidth: 350
            });

            // Add markers for each crime report
            crimeReports.forEach(report => {
                addMarker(report);
            });

            // Fit map to show all markers if there are any
            if (markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(marker => bounds.extend(marker.getPosition()));
                map.fitBounds(bounds);

                // Don't zoom too close if there's only one marker
                if (markers.length === 1) {
                    map.setZoom(15);
                }
            }
        }

        function addMarker(report) {
            if (!report.latitude || !report.longitude) return;

            const position = { lat: parseFloat(report.latitude), lng: parseFloat(report.longitude) };

            // Choose marker color based on severity
            let markerColor;
            let severityBadge;
            switch (report.severity) {
                case 'critical':
                    markerColor = 'red';
                    severityBadge = 'danger';
                    break;
                case 'high':
                    markerColor = 'orange';
                    severityBadge = 'warning';
                    break;
                case 'medium':
                    markerColor = 'yellow';
                    severityBadge = 'info';
                    break;
                case 'low':
                    markerColor = 'green';
                    severityBadge = 'success';
                    break;
                default:
                    markerColor = 'blue';
                    severityBadge = 'primary';
            }

            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: report.title,
                icon: `https://maps.google.com/mapfiles/ms/icons/${markerColor}-dot.png`,
                animation: google.maps.Animation.DROP
            });

            // Store report data with marker
            marker.reportData = report;

            // Create enhanced info window content
            const infoContent = `
                <div style="max-width: 320px; font-family: 'Segoe UI', Arial, sans-serif;">
                    <div style="border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 15px;">
                        <h6 style="margin: 0; color: #0d6efd; font-weight: 600;">${report.title}</h6>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span><strong>Severity:</strong></span>
                            <span class="badge bg-${severityBadge}" style="font-size: 0.75rem;">${report.severity.toUpperCase()}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span><strong>Status:</strong></span>
                            <span class="badge bg-${report.status === 'resolved' ? 'success' : (report.status === 'under_investigation' ? 'warning' : 'secondary')}" style="font-size: 0.75rem;">${report.status.replace('_', ' ').toUpperCase()}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span><strong>Date:</strong></span>
                            <span style="color: #6c757d;">${new Date(report.incident_date).toLocaleDateString()}</span>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <strong>Address:</strong><br>
                            <span style="color: #6c757d; font-size: 0.9em;">${report.address || 'N/A'}</span>
                        </div>
                    </div>

                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                        <strong style="color: #495057;">Description:</strong><br>
                        <span style="color: #6c757d; font-size: 0.9em; line-height: 1.4;">
                            ${report.description.length > 120 ? report.description.substring(0, 120) + '...' : report.description}
                        </span>
                    </div>

                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <a href="/reports/${report.id}" class="btn btn-primary btn-sm" style="text-decoration: none; font-size: 0.8rem;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        ${report.status !== 'resolved' ? `<a href="/reports/${report.id}/edit" class="btn btn-outline-secondary btn-sm" style="text-decoration: none; font-size: 0.8rem;"><i class="fas fa-edit"></i> Edit</a>` : ''}
                    </div>
                </div>
            `;

            // Add click listener to marker
            marker.addListener('click', () => {
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);

                // Bounce animation
                marker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => marker.setAnimation(null), 1400);
            });

            markers.push(marker);
        }

        // Filter functions
        function filterBySeverity(severity) {
            markers.forEach(marker => {
                if (severity === 'all' || marker.reportData.severity === severity) {
                    marker.setVisible(true);
                } else {
                    marker.setVisible(false);
                }
            });
            infoWindow.close();
        }

        function filterByStatus(status) {
            markers.forEach(marker => {
                if (status === 'all' || marker.reportData.status === status) {
                    marker.setVisible(true);
                } else {
                    marker.setVisible(false);
                }
            });
            infoWindow.close();
        }

        function centerMap() {
            const tanauanCity = { lat: 14.0865, lng: 121.1488 };
            map.setCenter(tanauanCity);
            map.setZoom(13);
            infoWindow.close();
        }

        // Auto-dismiss alerts after 8 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.classList.remove('show');
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 150);
                    }
                }, 8000);
            });
        });

        // Add map click listener to close info window
        if (map) {
            map.addListener('click', () => {
                infoWindow.close();
            });
        }
    </script>
@endsection
