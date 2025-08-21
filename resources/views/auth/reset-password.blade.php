<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Reset Password - PRMSU CCIT Student Feedback System</title>

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
        
        .reset-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(73, 72, 80, 0.15);
            margin: 1rem auto;
            max-width: 500px;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .reset-container {
                margin: 0.5rem;
                border-radius: 15px;
            }
        }
        
        .reset-header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #5a5a6a 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .reset-header {
                padding: 2rem 1rem;
            }
        }
        
        .reset-header::before {
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
        
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 1rem;
        }
        
        .reset-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .reset-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .reset-body {
            padding: 3rem 2rem;
        }
        
        @media (max-width: 768px) {
            .reset-body {
                padding: 2rem 1rem;
            }
        }
        
        .info-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border-left: 4px solid var(--light-blue);
        }
        
        .info-section i {
            font-size: 2.5rem;
            color: var(--light-blue);
            margin-bottom: 1rem;
        }
        
        .info-section p {
            color: var(--dark-gray);
            font-size: 1rem;
            margin-bottom: 0;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .input-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(73, 72, 80, 0.1);
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within {
            box-shadow: 0 6px 20px rgba(152, 170, 231, 0.3);
            transform: translateY(-2px);
        }
        
        .input-group-text {
            background: var(--light-blue);
            border: none;
            color: white;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }
        
        .form-control {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            background: white;
            color: var(--dark-gray);
        }
        
        .form-control:focus {
            box-shadow: none;
            background: white;
            color: var(--dark-gray);
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .error-feedback {
            color: var(--coral-pink);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, var(--light-blue) 0%, #7a8fd8 100%);
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(152, 170, 231, 0.3);
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(152, 170, 231, 0.4);
            background: linear-gradient(135deg, #7a8fd8 0%, var(--light-blue) 100%);
        }
        
        .btn-reset:disabled {
            opacity: 0.7;
            transform: none;
            box-shadow: 0 4px 15px rgba(152, 170, 231, 0.2);
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #dee2e6 50%, transparent 100%);
            margin: 2rem 0;
        }
        
        .links-section {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .links-section a {
            color: var(--light-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .links-section a:hover {
            color: #7a8fd8;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, var(--light-green) 0%, #7bbf8a 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, var(--coral-pink) 0%, #e55a5c 100%);
            color: white;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .password-strength.weak {
            background: #ffe6e6;
            color: #d63384;
            border-left: 4px solid #d63384;
        }
        
        .password-strength.medium {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .password-strength.strong {
            background: #d1e7dd;
            color: #0f5132;
            border-left: 4px solid #198754;
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
        <div class="reset-container">
            <div class="reset-header">
                <div class="logo-section">
                    <img src="{{ asset('images/logo.png') }}" alt="PRMSU CCIT" class="logo">
                </div>
                <h1>PRMSU CCIT</h1>
                <p>Student Feedback System</p>
            </div>
            
            <div class="reset-body">
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
                    <i class="fas fa-lock"></i>
                    <p>
                        Enter your new password below to reset your account password.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" id="resetForm">
                    @csrf
                    
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <!-- Email Address -->
                    <input type="hidden" name="email" value="{{ $email ?? '' }}">
                    @if(empty($email))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Email address not found. Please check your reset link or try requesting a new password reset.
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-key me-1"></i>New Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Enter your new password" required autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                        <div id="passwordStrength" class="password-strength" style="display: none;"></div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            <i class="fas fa-key me-1"></i>Confirm New Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm your new password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-reset" id="resetBtn" {{ empty($email) ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i>Reset Password
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const resetBtn = $('#resetBtn');
            const originalText = resetBtn.html();
            const email = '{{ $email ?? "" }}';
            
            // Prevent form submission if email is missing
            $('#resetForm').on('submit', function(e) {
                if (!email || email.trim() === '') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Email address is missing. Please check your reset link or request a new password reset.',
                        confirmButtonColor: '#98AAE7'
                    });
                    return false;
                }
            });
            
            // Password visibility toggle
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            $('#toggleConfirmPassword').click(function() {
                const passwordField = $('#password_confirmation');
                const icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Password strength checker
            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthDiv = $('#passwordStrength');
                
                if (password.length === 0) {
                    strengthDiv.hide();
                    return;
                }
                
                let strength = 0;
                let feedback = '';
                
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                if (strength < 3) {
                    feedback = 'Weak password. Include uppercase, lowercase, numbers, and special characters.';
                    strengthDiv.removeClass('medium strong').addClass('weak');
                } else if (strength < 5) {
                    feedback = 'Medium strength password.';
                    strengthDiv.removeClass('weak strong').addClass('medium');
                } else {
                    feedback = 'Strong password!';
                    strengthDiv.removeClass('weak medium').addClass('strong');
                }
                
                strengthDiv.text(feedback).show();
            });
            
            // Form submission with AJAX
            $('#resetForm').on('submit', function(e) {
                e.preventDefault();
                
                resetBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Resetting Password...');
                
                $.ajax({
                    url: '{{ route("password.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successfully!',
                            text: 'Your password has been reset. You can now login with your new password.',
                            confirmButtonColor: '#98AAE7'
                        }).then((result) => {
                            window.location.href = '{{ route("login") }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while resetting your password.';
                        
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
        });
    </script>
</body>
</html>
