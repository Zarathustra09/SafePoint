@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Crime Reports Map</h4>
                            <small class="text-muted">Crime reports in Tanauan City, Batangas</small>
                        </div>
                        <div>
                            <a href="{{ route('reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Report
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 600px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Crime Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crime Report Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
            });

            // Initialize info window
            infoWindow = new google.maps.InfoWindow();

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
            switch (report.severity) {
                case 'critical':
                    markerColor = 'red';
                    break;
                case 'high':
                    markerColor = 'orange';
                    break;
                case 'medium':
                    markerColor = 'yellow';
                    break;
                case 'low':
                    markerColor = 'green';
                    break;
                default:
                    markerColor = 'blue';
            }

            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: report.title,
                icon: `https://maps.google.com/mapfiles/ms/icons/${markerColor}-dot.png`
            });

            // Create info window content
            const infoContent = `
                <div style="max-width: 300px;">
                    <h6 class="mb-2">${report.title}</h6>
                    <p class="mb-1"><strong>Severity:</strong>
                        <span class="badge bg-${report.severity === 'critical' ? 'danger' : (report.severity === 'high' ? 'warning' : (report.severity === 'medium' ? 'info' : 'secondary'))}">${report.severity}</span>
                    </p>
                    <p class="mb-1"><strong>Status:</strong>
                        <span class="badge bg-${report.status === 'resolved' ? 'success' : (report.status === 'under_investigation' ? 'warning' : 'secondary')}">${report.status.replace('_', ' ')}</span>
                    </p>
                    <p class="mb-1"><strong>Date:</strong> ${new Date(report.incident_date).toLocaleDateString()}</p>
                    <p class="mb-2"><strong>Address:</strong> ${report.address || 'N/A'}</p>
                    <p class="mb-2">${report.description.length > 100 ? report.description.substring(0, 100) + '...' : report.description}</p>
                    <div class="btn-group btn-group-sm">
                        <a href="/reports/${report.id}" class="btn btn-primary btn-sm">View Details</a>
                        <a href="/reports/${report.id}/edit" class="btn btn-secondary btn-sm">Edit</a>
                    </div>
                </div>
            `;

            // Add click listener to marker
            marker.addListener('click', () => {
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
            });

            markers.push(marker);
        }

        // Filter functions
        function filterBySeverity(severity) {
            markers.forEach(marker => {
                const report = crimeReports.find(r => r.title === marker.getTitle());
                if (severity === 'all' || report.severity === severity) {
                    marker.setVisible(true);
                } else {
                    marker.setVisible(false);
                }
            });
        }

        function filterByStatus(status) {
            markers.forEach(marker => {
                const report = crimeReports.find(r => r.title === marker.getTitle());
                if (status === 'all' || report.status === status) {
                    marker.setVisible(true);
                } else {
                    marker.setVisible(false);
                }
            });
        }
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            });
        </script>
    @endif
@endsection
