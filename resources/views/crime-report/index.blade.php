@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>AI Crime Map</h4>
                            <small class="text-muted">Crime reports in Tanauan City, Batangas</small>
                        </div>
                        <!-- Filter Controls -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button"
                                data-bs-toggle="dropdown" id="filterButton">
                                <i class="fas fa-filter"></i> Filter by Severity: <span id="currentFilter">All</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="#" onclick="filterBySeverity('all')">
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

    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
    </script>

    <script>
        let map;
        let markers = [];
        let infoWindow;

        // Crime reports data
        const crimeReports = @json($crimeReports);

        function initMap() {
            // Default location - Tanauan City, Batangas, Philippines
            const tanauanCity = {
                lat: 14.0865,
                lng: 121.1488
            };

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
            if (!report) return;

            // Validate coordinates
            const lat = parseFloat(report.latitude);
            const lng = parseFloat(report.longitude);
            if (Number.isNaN(lat) || Number.isNaN(lng)) return;
            const position = {
                lat,
                lng
            };

            // Normalize severity/status/title/description/address/date
            const severity = (report.severity || 'unknown').toString().toLowerCase();
            const status = (report.status || 'unknown').toString().toLowerCase();
            const title = report.title || 'Untitled';
            const description = report.description || '';
            const address = report.address || 'N/A';
            const incidentDate = report.incident_date ? new Date(report.incident_date).toLocaleDateString() : 'N/A';

            // Choose marker color based on severity
            let markerColor;
            switch (severity) {
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
                position,
                map,
                title,
                icon: `https://maps.google.com/mapfiles/ms/icons/${markerColor}-dot.png`
            });

            // Create info window content safely
            const severityBadgeClass = severity === 'critical' ? 'danger' : (severity === 'high' ? 'warning' : (severity ===
                'medium' ? 'info' : 'secondary'));
            const statusBadgeClass = status === 'resolved' ? 'success' : (status === 'under_investigation' ? 'warning' :
                'secondary');
            const shortDesc = description.length > 100 ? description.substring(0, 100) + '...' : description;

            const infoContent = `
                <div style="max-width: 300px;">
                    <h6 class="mb-2">${title}</h6>
                    <p class="mb-1"><strong>Severity:</strong>
                        <span class="badge bg-${severityBadgeClass}">${severity}</span>
                    </p>
                    <p class="mb-1"><strong>Status:</strong>
                        <span class="badge bg-${statusBadgeClass}">${status.replace('_', ' ')}</span>
                    </p>
                    <p class="mb-1"><strong>Date:</strong> ${incidentDate}</p>
                    <p class="mb-2"><strong>Address:</strong> ${address}</p>
                    <p class="mb-2">${shortDesc}</p>
                    <div class="btn-group btn-group-sm">
                        <a href="/reports/${report.id}" class="btn btn-primary btn-sm">View Details</a>
                        <a href="/reports/${report.id}/edit" class="btn btn-secondary btn-sm">Edit</a>
                    </div>
                </div>
            `;

            marker.addListener('click', () => {
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
            });

            // Attach original report to marker for reliable filtering
            marker.__report = report;
            markers.push(marker);
        }

        // Filter functions
        function filterBySeverity(severity) {
            // Update button text
            const filterNames = {
                'all': 'All',
                'critical': 'Critical',
                'high': 'High',
                'medium': 'Medium',
                'low': 'Low'
            };
            document.getElementById('currentFilter').textContent = filterNames[severity] || 'All';

            // Update active state in dropdown
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => item.classList.remove('active'));

            // Find and activate the selected item
            const selectedItem = Array.from(dropdownItems).find(item =>
                item.getAttribute('onclick')?.includes(`'${severity}'`)
            );
            if (selectedItem) {
                selectedItem.classList.add('active');
            }

            // Apply the filter
            markers.forEach(marker => {
                const r = marker.__report || {};
                const reportSeverity = (r.severity || 'unknown').toString().toLowerCase();
                if (severity === 'all' || reportSeverity === severity) {
                    marker.setVisible(true);
                } else {
                    marker.setVisible(false);
                }
            });
            infoWindow.close();
        }

        function filterByStatus(status) {
            markers.forEach(marker => {
                const r = marker.__report || {};
                const reportStatus = (r.status || 'unknown').toString().toLowerCase();
                if (status === 'all' || reportStatus === status) {
                    marker.setVisible(true);
                } else {
                    marker.setVisible(false);
                }
            });
        }
    </script>

    @if (session('success'))
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
