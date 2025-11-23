<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Student Registration - PRMSU CCIT Student Feedback System</title>

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
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(73, 72, 80, 0.15);
            margin: 1rem auto;
            max-width: 550px;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .register-container {
                margin: 0.5rem;
                border-radius: 15px;
            }
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .register-header {
                padding: 2rem 1rem;
            }
        }
        
        .register-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            color: white;
        }
        
        .register-header p {
            font-size: 1rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }
        
        .register-body {
            padding: 2.5rem;
        }
        
        @media (max-width: 768px) {
            .register-body {
                padding: 1.5rem;
            }
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
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 0.2rem rgba(143, 207, 168, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(143, 207, 168, 0.4);
        }
        
        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--light-green);
        }
        
        .text-center a {
            color: var(--light-green);
            text-decoration: none;
            font-weight: 600;
        }
        
        .text-center a:hover {
            text-decoration: underline;
        }
        
        .password-strength {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .password-strength.weak {
            color: var(--coral-pink);
        }
        
        .password-strength.medium {
            color: var(--golden-orange);
        }
        
        .password-strength.strong {
            color: var(--light-green);
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 2rem 0;">
        <div class="register-container">
            <div class="register-header">
                <h1><i class="fas fa-user-plus me-2"></i>Student Registration</h1>
                <p>PRMSU CCIT Student Feedback System</p>
            </div>
            
            <div class="register-body">
                <form id="studentRegisterForm">
                    <div class="form-group">
                        <label for="student_number">
                            <i class="fas fa-id-card me-2"></i>Student Number <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="student_number" 
                                   name="student_number" 
                                   placeholder="Enter your student number" 
                                   required 
                                   autofocus>
                        </div>
                        <small class="text-muted">This will be your login username</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user me-2"></i>Full Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-signature"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Enter your full name" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>Email Address (Optional)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-at"></i>
                            </span>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter your email address">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year">
                                    <i class="fas fa-calendar-alt me-2"></i>Year Level <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="year" name="year" required>
                                    <option value="">Select Year Level...</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course">
                                    <i class="fas fa-graduation-cap me-2"></i>Course <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="course" name="course" required>
                                    <option value="">Select Course...</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password (min. 6 characters)" 
                                   required
                                   minlength="6">
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">
                            <i class="fas fa-lock me-2"></i>Confirm Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Confirm your password" 
                                   required>
                        </div>
                        <div id="passwordMatch" class="mt-2"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-register" id="registerBtn">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        Already have an account? <a href="{{ route('student.login') }}">Login here</a>
                    </p>
                    <p class="text-muted">
                        Admin? <a href="{{ route('login') }}">Admin Login</a>
                    </p>
                </div>
            </div>
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
            // Password strength checker
            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthDiv = $('#passwordStrength');
                
                if (password.length === 0) {
                    strengthDiv.text('').removeClass('weak medium strong');
                    return;
                }
                
                let strength = 0;
                if (password.length >= 6) strength++;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;
                
                if (strength <= 2) {
                    strengthDiv.text('Weak password').removeClass('medium strong').addClass('weak');
                } else if (strength <= 3) {
                    strengthDiv.text('Medium password').removeClass('weak strong').addClass('medium');
                } else {
                    strengthDiv.text('Strong password').removeClass('weak medium').addClass('strong');
                }
            });
            
            // Password match checker
            $('#password_confirmation').on('input', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();
                const matchDiv = $('#passwordMatch');
                
                if (confirmPassword.length === 0) {
                    matchDiv.text('').removeClass('text-danger text-success');
                    return;
                }
                
                if (password === confirmPassword) {
                    matchDiv.html('<i class="fas fa-check-circle me-1"></i>Passwords match').removeClass('text-danger').addClass('text-success');
                } else {
                    matchDiv.html('<i class="fas fa-times-circle me-1"></i>Passwords do not match').removeClass('text-success').addClass('text-danger');
                }
            });
            
            // Form submission
            $('#studentRegisterForm').submit(function(e) {
                e.preventDefault();
                
                const password = $('#password').val();
                const passwordConfirmation = $('#password_confirmation').val();
                
                if (password !== passwordConfirmation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Mismatch',
                        text: 'Passwords do not match. Please try again.',
                        confirmButtonColor: '#F16E70'
                    });
                    return;
                }
                
                if (password.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        text: 'Password must be at least 6 characters long.',
                        confirmButtonColor: '#F16E70'
                    });
                    return;
                }
                
                const submitBtn = $('#registerBtn');
                const originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Registering...');
                
                $.ajax({
                    url: '{{ route("student.register") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful!',
                                text: response.message,
                                confirmButtonColor: '#8FCFA8'
                            }).then(function() {
                                window.location.href = response.redirect;
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Registration failed. Please try again.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: errorMessage,
                            confirmButtonColor: '#F16E70'
                        });
                        
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
</body>
</html>

