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

        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(13, 110, 253, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
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

        .contact-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .contact-item:last-child {
            border-bottom: none;
        }

        .card-header {
            background: rgba(13, 110, 253, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bg-success {
            background: rgba(25, 135, 84, 0.9) !important;
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
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h2 class="h3 mb-2">Contact SafePoint</h2>
                        <p class="text-muted mb-0">We're here to help! Send us a message and we'll respond as soon as possible.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="custom-block p-4">
                        <div class="mb-4 pb-3 border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-paper-plane text-primary me-2"></i>
                                Send us a Message
                            </h5>
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

                        <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user text-muted me-1"></i>
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter your full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="Enter your email address"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-tag text-muted me-1"></i>
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('subject') is-invalid @enderror"
                                        id="subject"
                                        name="subject"
                                        required>
                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Choose a subject</option>
                                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                    <option value="Crime Report Issue" {{ old('subject') == 'Crime Report Issue' ? 'selected' : '' }}>Crime Report Issue</option>
                                    <option value="Feature Request" {{ old('subject') == 'Feature Request' ? 'selected' : '' }}>Feature Request</option>
                                    <option value="Bug Report" {{ old('subject') == 'Bug Report' ? 'selected' : '' }}>Bug Report</option>
                                    <option value="Account Issues" {{ old('subject') == 'Account Issues' ? 'selected' : '' }}>Account Issues</option>
                                    <option value="Privacy Concerns" {{ old('subject') == 'Privacy Concerns' ? 'selected' : '' }}>Privacy Concerns</option>
                                    <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment text-muted me-1"></i>
                                    Message <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          name="message"
                                          rows="6"
                                          placeholder="Please describe your inquiry in detail..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="charCount">0</span>/5000 characters
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Message
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="loadingSpinner"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Information Sidebar -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="custom-block mb-4">
                        <div class="card-header text-white mb-3 rounded">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Contact Information
                            </h5>
                        </div>
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Address</h6>
                                    <p class="mb-0 text-muted">Tanauan City, Batangas<br>Philippines</p>
                                </div>
                            </div>
                        </div>
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon">
                                    <i class="fas fa-phone text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Phone</h6>
                                    <p class="mb-0 text-muted">+63 123 456 7890</p>
                                </div>
                            </div>
                        </div>
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Email</h6>
                                    <p class="mb-0 text-muted">info@safepoint.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Business Hours</h6>
                                    <p class="mb-0 text-muted">
                                        Mon - Fri: 8:00 AM - 5:00 PM<br>
                                        Sat: 9:00 AM - 1:00 PM<br>
                                        Sun: Closed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="custom-block">
                        <div class="bg-success text-white mb-3 p-3 rounded">
                            <h5 class="mb-0">
                                <i class="fas fa-question-circle me-2"></i>
                                Need Help?
                            </h5>
                        </div>
                        <p class="mb-3">Before contacting us, you might find answers in our frequently asked questions.</p>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-outline-success">
                                <i class="fas fa-book me-2"></i>
                                View FAQ
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fas fa-life-ring me-2"></i>
                                User Guide
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Character counter for message textarea
            const messageTextarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');

            function updateCharCount() {
                const count = messageTextarea.value.length;
                charCount.textContent = count;

                if (count > 4500) {
                    charCount.style.color = '#dc3545';
                } else if (count > 4000) {
                    charCount.style.color = '#fd7e14';
                } else {
                    charCount.style.color = '#6c757d';
                }
            }

            messageTextarea.addEventListener('input', updateCharCount);
            updateCharCount(); // Initial count

            // Form submission with loading state
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');

            contactForm.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                loadingSpinner.classList.remove('d-none');
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Sending... <span class="spinner-border spinner-border-sm ms-2"></span>';
            });

            // Auto-dismiss alerts after 8 seconds
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
    </script>
@endsection
