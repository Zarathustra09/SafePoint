@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Crime Report</h4>
                    </div>
                    <div class="card-body">
                        <form id="crimeReportForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title *</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="severity" class="form-label">Severity *</label>
                                        <select class="form-select" id="severity" name="severity" required>
                                            <option value="">Select Severity</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude *</label>
                                        <input type="number" class="form-control" id="latitude" name="latitude" step="any" readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude *</label>
                                        <input type="number" class="form-control" id="longitude" name="longitude" step="any" readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="incident_date" class="form-label">Incident Date *</label>
                                <input type="datetime-local" class="form-control" id="incident_date" name="incident_date" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    Create Report
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="getCurrentLocation()">
                                    Get Current Location
                                </button>
                                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Select Location on Map</h5>
                        <small class="text-muted">Click on the map to pin the crime location</small>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 500px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&libraries=places&callback=initMap">
    </script>

    <script>
        let map;
        let marker;
        let geocoder;

        function initMap() {
            // Default location - Tanauan City, Batangas, Philippines
            const defaultLocation = { lat: 14.0865, lng: 121.1488 };

            // Initialize map
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: defaultLocation,
                mapTypeControl: false,
                streetViewControl: false,
            });

            // Initialize geocoder
            geocoder = new google.maps.Geocoder();

            // Add click listener to map
            map.addListener("click", (event) => {
                placeMarker(event.latLng);
                updateLocationFields(event.latLng);
            });

            // Initialize autocomplete for address field
            const addressInput = document.getElementById('address');
            const autocomplete = new google.maps.places.Autocomplete(addressInput);
            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                    placeMarker(place.geometry.location);
                    updateLocationFields(place.geometry.location);
                }
            });
        }

        function placeMarker(location) {
            // Remove existing marker
            if (marker) {
                marker.setMap(null);
            }

            // Create new marker
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true,
                title: "Crime Location"
            });

            // Add drag listener to marker
            marker.addListener('dragend', (event) => {
                updateLocationFields(event.latLng);
            });
        }

        function updateLocationFields(location) {
            // Handle different location object structures
            let lat, lng;

            if (typeof location.lat === 'function') {
                // Google Maps LatLng object (from map clicks)
                lat = location.lat();
                lng = location.lng();
            } else {
                // Plain object (from getCurrentLocation)
                lat = location.lat;
                lng = location.lng;
            }

            // Update latitude and longitude fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Create LatLng object for geocoding
            const latLng = new google.maps.LatLng(lat, lng);

            // Reverse geocode to get address
            geocoder.geocode({ location: latLng }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    document.getElementById('address').value = results[0].formatted_address;
                }
            });
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const location = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        map.setCenter(location);
                        map.setZoom(17);
                        placeMarker(location);
                        updateLocationFields(location);
                    },
                    (error) => {
                        alert('Error getting location: ' + error.message);
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        // Form submission
        document.getElementById('crimeReportForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const spinner = submitBtn.querySelector('.spinner-border');

            // Validate that location is selected
            if (!document.getElementById('latitude').value || !document.getElementById('longitude').value) {
                alert('Please select a location on the map');
                return;
            }

            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            try {
                const formData = new FormData(this);

                const response = await fetch('{{ route("reports.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                if (response.ok) {
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <strong>Success!</strong> Crime report created successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    this.insertBefore(alertDiv, this.firstChild);

                    // Reset form and map
                    this.reset();
                    if (marker) {
                        marker.setMap(null);
                        marker = null;
                    }

                    // Redirect to reports index
                    setTimeout(() => {
                        window.location.href = '{{ route("reports.index") }}';
                    }, 2000);

                } else {
                    const errorData = await response.json();

                    // Display validation errors
                    if (errorData.errors) {
                        Object.keys(errorData.errors).forEach(field => {
                            const input = document.getElementById(field);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = errorData.errors[field][0];
                                }
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                // Hide loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
@endsection
