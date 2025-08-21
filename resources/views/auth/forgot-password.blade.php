<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Forgot Password - PRMSU CCIT Student Feedback System</title>

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
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .forgot-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(73, 72, 80, 0.15);
            margin: 1rem auto;
            max-width: 450px;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .forgot-container {
                margin: 0.5rem;
                border-radius: 15px;
            }
        }
        
        .forgot-header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #5a5a6a 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .forgot-header {
                padding: 2rem 1rem;
            }
        }
        
        .forgot-header::before {
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
            margin-bottom: 1.5rem;
        }
        
        .logo-section img {
            height: 80px;
            width: auto;
            margin-bottom: 1rem;
            filter: brightness(1.1) contrast(1.1);
        }
        
        .forgot-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            color: white;
        }
        
        @media (max-width: 768px) {
            .forgot-header h1 {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .forgot-header h1 {
                font-size: 1.5rem;
            }
        }
        
        .forgot-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            color: white;
        }
        
        @media (max-width: 768px) {
            .forgot-header p {
                font-size: 1rem;
            }
        }
        
        .forgot-body {
            padding: 3rem 2rem;
        }
        
        @media (max-width: 768px) {
            .forgot-body {
                padding: 2rem 1rem;
            }
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        @media (max-width: 768px) {
            .form-control {
                padding: 0.875rem;
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
        
        .form-control:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.3rem rgba(152, 170, 231, 0.15);
            background-color: white;
            transform: translateY(-2px);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: var(--dark-gray);
        }
        
        .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%);
            border: none;
            color: white;
            padding: 1.25rem 3rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(152, 170, 231, 0.3);
            width: 100%;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        
        @media (max-width: 768px) {
            .btn-reset {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }
        
        .btn-reset:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(152, 170, 231, 0.4);
            color: white;
        }
        
        .btn-reset:active {
            transform: translateY(-1px);
        }
        
        .btn-reset:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(73, 72, 80, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, var(--coral-pink) 0%, #e55a5c 100%);
            color: #721c24;
        }
        
        .error-feedback {
            color: var(--coral-pink);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .info-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .info-section i {
            font-size: 4rem;
            color: var(--light-blue);
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .info-section i {
                font-size: 3rem;
            }
        }
        
        .info-section p {
            color: var(--dark-gray);
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .links-section {
            text-align: center;
            margin-top: 2rem;
        }
        
        .links-section a {
            color: var(--light-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .links-section a:hover {
            color: #7a8cd6;
            text-decoration: underline;
        }
        
        .divider {
            margin: 2rem 0;
            border-top: 1px solid #e9ecef;
            position: relative;
        }
        
        .divider::before {
            content: 'or';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 1rem;
            color: var(--dark-gray);
            font-size: 0.875rem;
        }
        
        .footer {
            text-align: center;
            padding: 2rem;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .footer {
                padding: 1rem;
                font-size: 0.8rem;
            }
        }
        
        .footer a {
            color: var(--light-blue);
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Mobile-specific improvements */
        @media (max-width: 768px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Improve touch targets */
            .form-check-input {
                min-width: 20px;
                min-height: 20px;
            }
            
            /* Better spacing for mobile */
            .mb-3 {
                margin-bottom: 1rem !important;
            }
            
            /* Improve button touch area */
            .btn {
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        
        /* Prevent zoom on double tap (iOS) */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            select,
            textarea,
            input {
                font-size: 16px;
            }
        }
        
        /* Smooth scrolling for mobile */
        html {
            scroll-behavior: smooth;
        }
        
        /* Better focus indicators for accessibility */
        .form-control:focus,
        .btn:focus {
            outline: 2px solid var(--light-blue);
            outline-offset: 2px;
        }
        
        /* Loading animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .fa-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="forgot-container">
            <div class="forgot-header">
                <div class="logo-section">
                    <img src="{{ asset('images/logo.png') }}" alt="PRMSU CCIT" class="logo">
                </div>
                <h1>PRMSU CCIT</h1>
                <p>Student Feedback System</p>
            </div>
            
            <div class="forgot-body">
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="info-section">
                    <i class="fas fa-key"></i>
                    <p>
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                    @csrf
                    
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Enter your email" required autofocus>
                        </div>
                        @error('email')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-reset" id="resetBtn">
                            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                        </button>
                    </div>
                </form>

                <div class="divider"></div>

                <div class="links-section">
                    <p class="text-muted mb-0">
                        Remember your password? 
                        <a href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login here
                        </a>
                    </p>
                </div>

                <div class="links-section">
                    <a href="{{ route('survey.index') }}">
                        <i class="fas fa-external-link-alt me-1"></i>Access Public Survey
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer">
                            <p>&copy; {{ date('Y') }} PRMSU CCIT. All rights reserved.</p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Form submission with AJAX
            $('#forgotForm').submit(function(e) {
                e.preventDefault();
                
                const resetBtn = $('#resetBtn');
                const originalText = resetBtn.html();
                
                resetBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
                
                $.ajax({
                    url: '{{ route("password.email") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Sent!',
                            text: 'If an account exists with that email, we have sent a password reset link.',
                            confirmButtonColor: '#98AAE7'
                        });
                        
                        resetBtn.prop('disabled', false).html(originalText);
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while sending the reset link.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#F16E70'
                        });
                        
                        resetBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
            
            // Prevent zoom on double tap (iOS)
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function (event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
        });
    </script>
</body>
</html> 