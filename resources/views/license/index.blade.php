<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>License Activation - PRMSU CCIT Sentiment Analysis System</title>

    <!-- Google Font: Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --dark-gray: #494850;
            --light-green: #8FCFA8;
            --coral-pink: #F16E70;
            --golden-orange: #F5B445;
            --light-blue: #98AAE7;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .license-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .license-header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #5a5a6a 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .license-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .logo-section {
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .logo-section img {
            height: 150px;
            width: auto;
            margin-bottom: 0.5rem;
            filter: brightness(1.1) contrast(1.1);
        }
        
        .license-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .license-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }
        
        .license-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.2rem rgba(152, 170, 231, 0.25);
        }
        
        .btn-activate {
            background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-activate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(152, 170, 231, 0.3);
            color: white;
        }
        
        .btn-activate:active {
            transform: translateY(0);
        }
        
        .btn-activate:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .license-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--light-blue);
        }
        
        .license-info h5 {
            color: var(--dark-gray);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .license-info p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .license-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active {
            background: var(--light-green);
            color: white;
        }
        
        .status-expired {
            background: var(--coral-pink);
            color: white;
        }
        
        .status-invalid {
            background: var(--golden-orange);
            color: white;
        }
        
        .error-feedback {
            color: var(--coral-pink);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .success-message {
            background: var(--light-green);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .error-message {
            background: var(--coral-pink);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .license-footer {
            text-align: center;
            padding: 1rem 2rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        
        .license-footer p {
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        
        .contact-developer {
            border-top: 1px solid #e9ecef;
            padding-top: 1rem;
        }
        
        .contact-developer h6 {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .contact-links {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .contact-links .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .contact-links .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .contact-links .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .contact-links .btn-outline-success:hover {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }
        
        .contact-info-error .alert {
            border-radius: 10px;
            border: none;
            background: rgba(13, 202, 240, 0.1);
        }
        
        .contact-info-error .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .contact-info-error .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
            .license-container {
                margin: 1rem;
                width: 95%;
            }
            
            .license-header {
                padding: 1.5rem;
            }
            
            .license-body {
                padding: 1.5rem;
            }
            
            .license-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="license-container">
        <div class="license-header">
            <div class="logo-section">
                <img src="{{ asset('images/logo_company.png') }}" alt="PRMSU CCIT" class="logo">
            </div>
            <h1>License Activation</h1>
            <p>Enter your license key to activate the system</p>
        </div>
        
        <div class="license-body">
            @if(session('success'))
                <div class="success-message">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
                
                <!-- Contact Info for Errors -->
                <div class="contact-info-error mt-3">
                    <div class="alert alert-info">
                        <h6 class="mb-2">
                            <i class="fas fa-info-circle me-2"></i>Need Technical Support?
                        </h6>
                        <p class="mb-2 small">Contact the system developer for assistance:</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="https://www.facebook.com/landogz" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fab fa-facebook me-1"></i>Facebook
                            </a>
                            <a href="tel:09387077940" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-phone me-1"></i>Call Now
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentLicense)
                <div class="license-info">
                    <h5><i class="fas fa-key me-2"></i>Current License</h5>
                    <p><strong>License ID:</strong> {{ $currentLicense['license_id'] }}</p>
                    <p><strong>System:</strong> {{ $currentLicense['system_name'] }}</p>
                    <p><strong>Type:</strong> {{ $currentLicense['license_type'] }}</p>
                    <p><strong>Issued To:</strong> {{ $currentLicense['issued_to'] }}</p>
                    <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($currentLicense['issue_date'])->format('M d, Y') }}</p>
                    @if($currentLicense['expiry_date'])
                        @php
                            $expiryDate = \Carbon\Carbon::parse($currentLicense['expiry_date']);
                            $isExpired = now()->isAfter($expiryDate);
                        @endphp
                        <p><strong>Expiry Date:</strong> 
                            <span class="{{ $isExpired ? 'text-danger' : 'text-success' }}">
                                {{ $expiryDate->format('M d, Y') }}
                                @if($isExpired)
                                    <i class="fas fa-exclamation-triangle ms-1"></i>
                                @endif
                            </span>
                        </p>
                    @endif
                    <p><strong>Status:</strong> 
                        <span class="license-status {{ $isExpired ? 'status-expired' : ($isValid ? 'status-active' : 'status-invalid') }}">
                            {{ $isExpired ? 'Expired' : ($isValid ? 'Active' : 'Invalid') }}
                        </span>
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('license.store') }}" id="licenseForm">
                @csrf
                
                <div class="form-group">
                    <label for="license_key" class="form-label">
                        <i class="fas fa-key me-2"></i>License Key
                    </label>
                    <input type="text" 
                           class="form-control @error('license_key') is-invalid @enderror" 
                           id="license_key" 
                           name="license_key" 
                           value="{{ old('license_key') }}" 
                           placeholder="Enter your license key (e.g., LWS-001)"
                           required>
                    @error('license_key')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-activate" id="activateBtn">
                    <i class="fas fa-unlock me-2"></i>Activate License
                </button>
            </form>
        </div>
        
        @if($isExpired)
        <div class="license-footer">
            <p><i class="fas fa-shield-alt me-2"></i>Powered by Landogz Web Solutions</p>
            
            <!-- Contact System Developer -->
            <div class="contact-developer mt-3">
                <h6 class="text-muted mb-2">
                    <i class="fas fa-headset me-2"></i>Need Help? Contact System Developer
                </h6>
                <div class="contact-links">
                    <a href="https://www.facebook.com/landogz" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fab fa-facebook me-1"></i>Facebook
                    </a>
                    <a href="tel:09387077940" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-1"></i>09387077940
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="license-footer">
            <p><i class="fas fa-shield-alt me-2"></i>Powered by Landogz Web Solutions</p>
        </div>
        @endif
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Set CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // License key validation on input
            $('#license_key').on('input', function() {
                const licenseKey = $(this).val().trim();
                const activateBtn = $('#activateBtn');
                
                if (licenseKey.length > 0) {
                    activateBtn.prop('disabled', false);
                } else {
                    activateBtn.prop('disabled', true);
                }
            });

            // Form submission with AJAX and SweetAlert
            $('#licenseForm').on('submit', function(e) {
                e.preventDefault();
                
                const licenseKey = $('#license_key').val().trim();
                const activateBtn = $('#activateBtn');
                const originalText = activateBtn.html();
                
                if (!licenseKey) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please enter a license key.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                activateBtn.prop('disabled', true);
                activateBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Validating...');
                
                $.ajax({
                    url: '{{ route("license.store") }}',
                    method: 'POST',
                    data: {
                        license_key: licenseKey,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'License Activated!',
                                text: response.message,
                                confirmButtonColor: '#198754',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            let icon = 'error';
                            let title = 'License Error';
                            
                            if (response.type === 'warning') {
                                icon = 'warning';
                                title = 'License Warning';
                            }
                            
                            Swal.fire({
                                icon: icon,
                                title: title,
                                text: response.message,
                                confirmButtonColor: icon === 'warning' ? '#fd7e14' : '#dc3545',
                                showCancelButton: response.expired,
                                cancelButtonText: 'Check License',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isDismissed && response.expired) {
                                    checkLicenseStatus();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'An error occurred while validating the license.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                            confirmButtonColor: '#dc3545'
                        });
                    },
                    complete: function() {
                        activateBtn.prop('disabled', false);
                        activateBtn.html(originalText);
                    }
                });
            });



            // Test license key validation on blur
            $('#license_key').on('blur', function() {
                const licenseKey = $(this).val().trim();
                
                if (licenseKey.length > 0) {
                    $.post('{{ route("license.test") }}', {
                        license_key: licenseKey
                    })
                    .done(function(response) {
                        if (response.valid) {
                            $('#license_key').removeClass('is-invalid').addClass('is-valid');
                        } else {
                            $('#license_key').removeClass('is-valid').addClass('is-invalid');
                        }
                    })
                    .fail(function() {
                        // Silently fail - don't show error for network issues
                    });
                }
            });
        });
    </script>
</body>
</html> 