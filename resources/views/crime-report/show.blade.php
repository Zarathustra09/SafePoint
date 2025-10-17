@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Crime Report Details</h4>
                        <div>
                            <a href="{{ route('reports.edit', $crimeReport->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('reports.list') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>{{ $crimeReport->title }}</h5>
                                <div class="mb-3">
                                    <span class="badge bg-{{ $crimeReport->severity === 'critical' ? 'danger' : ($crimeReport->severity === 'high' ? 'warning' : ($crimeReport->severity === 'medium' ? 'info' : 'secondary')) }} me-2">
                                        {{ ucfirst($crimeReport->severity) }} Severity
                                    </span>
                                    <span class="badge bg-{{ $crimeReport->status === 'resolved' ? 'success' : ($crimeReport->status === 'under_investigation' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $crimeReport->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    <strong>Report ID:</strong> #{{ $crimeReport->id }}<br>
                                    <strong>Reported:</strong> {{ $crimeReport->created_at->format('M d, Y H:i') }}<br>
                                    <strong>Updated:</strong> {{ $crimeReport->updated_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <h6>Description</h6>
                                <p class="mb-4">{{ $crimeReport->description }}</p>
                            </div>
                        </div>

                        @if($crimeReport->report_image)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6>Report Image</h6>
                                    <img src="{{ asset('storage/' . $crimeReport->report_image) }}" alt="Report Image" class="img-fluid rounded border" style="max-height: 350px;">
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Incident Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Incident Date:</strong></td>
                                        <td>{{ $crimeReport->incident_date->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td>{{ $crimeReport->address ?: 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordinates:</strong></td>
                                        <td>{{ $crimeReport->latitude }}, {{ $crimeReport->longitude }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Report Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Reported By:</strong></td>
                                        <td>{{ $crimeReport->reporter->name ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Severity Level:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $crimeReport->severity === 'critical' ? 'danger' : ($crimeReport->severity === 'high' ? 'warning' : ($crimeReport->severity === 'medium' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($crimeReport->severity) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Status:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $crimeReport->status === 'resolved' ? 'success' : ($crimeReport->status === 'under_investigation' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $crimeReport->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Location Map</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('reports.edit', $crimeReport->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Report
                            </a>
                            <form method="POST" action="{{ route('reports.destroy', $crimeReport->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this report?')">
                                    <i class="fas fa-trash"></i> Delete Report
                                </button>
                            </form>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list"></i> View All Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&callback=initMap">
    </script>

    <script>
        function initMap() {
            const reportLocation = {
                lat: {{ $crimeReport->latitude }},
                lng: {{ $crimeReport->longitude }}
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: reportLocation,
                mapTypeControl: false,
                streetViewControl: true,
            });

            // Choose marker color based on severity
            let markerColor;
            switch('{{ $crimeReport->severity }}') {
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
                position: reportLocation,
                map: map,
                title: "{{ $crimeReport->title }}",
                icon: `https://maps.google.com/mapfiles/ms/icons/${markerColor}-dot.png`
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <h6>{{ $crimeReport->title }}</h6>
                        <p><strong>Severity:</strong> {{ ucfirst($crimeReport->severity) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $crimeReport->status)) }}</p>
                        <p><strong>Date:</strong> {{ $crimeReport->incident_date->format('M d, Y H:i') }}</p>
                        @if($crimeReport->address)
                        <p><strong>Address:</strong> {{ $crimeReport->address }}</p>
                        @endif
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            // Open info window by default
            infoWindow.open(map, marker);
        }
    </script>
@endsection
