<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ESP-CIT - Student Feedback Survey</title>

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
        
        .survey-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(73, 72, 80, 0.15);
            margin: 1rem auto;
            max-width: 900px;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .survey-container {
                margin: 0.5rem;
                border-radius: 15px;
            }
        }
        
        .survey-header {
            background: linear-gradient(135deg, var(--dark-gray) 0%, #5a5a6a 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .survey-header {
                padding: 2rem 1rem;
            }
        }
        
        .survey-header::before {
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
        
        .survey-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            color: white;
        }
        
        @media (max-width: 768px) {
            .survey-header h1 {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .survey-header h1 {
                font-size: 1.5rem;
            }
        }
        
        .survey-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            color: white;
        }
        
        @media (max-width: 768px) {
            .survey-header p {
                font-size: 1rem;
            }
        }
        
        .survey-body {
            padding: 3rem 2rem;
        }
        
        @media (max-width: 768px) {
            .survey-body {
                padding: 2rem 1rem;
            }
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
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
        
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .form-select:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.3rem rgba(152, 170, 231, 0.15);
            background-color: white;
        }
        
        .rating-section {
            margin: 2rem 0;
        }
        
        .rating-stars {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .star {
            font-size: 2rem;
            color: #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
            -webkit-tap-highlight-color: transparent;
        }
        
        .star:hover,
        .star.active {
            color: var(--golden-orange);
            transform: scale(1.1);
        }
        
        .star.filled {
            color: var(--golden-orange);
        }
        
        .btn-submit {
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
            .btn-submit {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(152, 170, 231, 0.4);
            color: white;
        }
        
        .btn-submit:active {
            transform: translateY(-1px);
        }
        
        .btn-submit:disabled {
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
            .star {
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
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
        .btn:focus,
        .form-select:focus {
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
        
        /* Rating text styling */
        .rating-text {
            text-align: center;
            color: var(--dark-gray);
            font-weight: 500;
            margin-top: 0.5rem;
        }
        
        /* Form section styling */
        .form-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="survey-container">
            <div class="survey-header">
                <div class="logo-section">
                    <img src="{{ asset('images/logo.png') }}" alt="ESP-CIT" class="logo">
                </div>
                <h1>ESP-CIT</h1>
                <p>Student Feedback Survey</p>
            </div>
            
            <div class="survey-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
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

                <form method="POST" action="{{ route('survey.store') }}" id="surveyForm">
                    @csrf
                    
                    <div class="form-section">
                        <h4 class="mb-3" style="color: var(--dark-gray);">
                            <i class="fas fa-user me-2" style="color: var(--light-blue);"></i>
                            Student Information
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name" class="form-label">Full Name (Optional)</label>
                                    <input type="text" class="form-control @error('student_name') is-invalid @enderror" 
                                           id="student_name" name="student_name" value="{{ old('student_name') }}" 
                                           placeholder="Enter your full name (optional)">
                                    @error('student_name')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_email" class="form-label">Email Address (Optional)</label>
                                    <input type="email" class="form-control @error('student_email') is-invalid @enderror" 
                                           id="student_email" name="student_email" value="{{ old('student_email') }}" 
                                           placeholder="Enter your email (optional)">
                                    @error('student_email')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="mb-3" style="color: var(--dark-gray);">
                            <i class="fas fa-chalkboard-teacher me-2" style="color: var(--light-blue);"></i>
                            Teacher & Subject Selection
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id" class="form-label">Select Teacher</label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                            id="teacher_id" name="teacher_id" required>
                                        <option value="">Choose a teacher...</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} - {{ $teacher->department }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id" class="form-label">Select Subject</label>
                                    <select class="form-select @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" name="subject_id" required disabled>
                                        <option value="">Choose a subject...</option>
                                    </select>
                                    @error('subject_id')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="mb-3" style="color: var(--dark-gray);">
                            <i class="fas fa-star me-2" style="color: var(--light-blue);"></i>
                            Rating & Feedback
                        </h4>
                        
                        <div class="rating-section">
                            <label class="form-label">Rate your experience (1-5 stars)</label>
                            <div class="rating-stars">
                                <i class="fas fa-star star" data-rating="1"></i>
                                <i class="fas fa-star star" data-rating="2"></i>
                                <i class="fas fa-star star" data-rating="3"></i>
                                <i class="fas fa-star star" data-rating="4"></i>
                                <i class="fas fa-star star" data-rating="5"></i>
                            </div>
                            <div class="rating-text" id="ratingText">Click on a star to rate</div>
                            <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="feedback_text" class="form-label">Additional Feedback</label>
                            <textarea class="form-control @error('feedback_text') is-invalid @enderror" 
                                      id="feedback_text" name="feedback_text" rows="4" 
                                      placeholder="Share your thoughts about the teacher and subject..." required>{{ old('feedback_text') }}</textarea>
                            @error('feedback_text')
                                <div class="error-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-submit" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ESP-CIT. All rights reserved.</p>
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
            let selectedRating = 0;
            
            // Star rating functionality
            $('.star').click(function() {
                const rating = $(this).data('rating');
                selectedRating = rating;
                
                // Update stars
                $('.star').removeClass('filled active');
                $('.star').each(function(index) {
                    if (index < rating) {
                        $(this).addClass('filled active');
                    }
                });
                
                // Update hidden input
                $('#rating').val(rating);
                
                // Update rating text
                const ratingTexts = {
                    1: 'Poor - Needs significant improvement',
                    2: 'Fair - Has room for improvement',
                    3: 'Good - Satisfactory experience',
                    4: 'Very Good - Above average experience',
                    5: 'Excellent - Outstanding experience'
                };
                $('#ratingText').text(ratingTexts[rating]);
            });
            
            // Hover effects for stars
            $('.star').hover(
                function() {
                    const rating = $(this).data('rating');
                    $('.star').removeClass('active');
                    $('.star').each(function(index) {
                        if (index < rating) {
                            $(this).addClass('active');
                        }
                    });
                },
                function() {
                    $('.star').removeClass('active');
                    $('.star').each(function(index) {
                        if (index < selectedRating) {
                            $(this).addClass('active');
                        }
                    });
                }
            );
            
            // Load subjects when teacher is selected
            $('#teacher_id').change(function() {
                const teacherId = $(this).val();
                const subjectSelect = $('#subject_id');
                
                if (teacherId) {
                    $.ajax({
                        url: '{{ route("survey.subjects-by-teacher") }}',
                        method: 'GET',
                        data: { teacher_id: teacherId },
                        success: function(response) {
                            subjectSelect.empty().append('<option value="">Choose a subject...</option>');
                            response.forEach(function(subject) {
                                subjectSelect.append(`<option value="${subject.id}">${subject.name} (${subject.subject_code})</option>`);
                            });
                            subjectSelect.prop('disabled', false);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to load subjects. Please try again.',
                                confirmButtonColor: '#F16E70'
                            });
                        }
                    });
                } else {
                    subjectSelect.empty().append('<option value="">Choose a subject...</option>').prop('disabled', true);
                }
            });
            
            // Form submission with AJAX
            $('#surveyForm').submit(function(e) {
                e.preventDefault();
                
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting...');
                
                $.ajax({
                    url: '{{ route("survey.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank You!',
                            text: 'Your feedback has been submitted successfully.',
                            confirmButtonColor: '#98AAE7'
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while submitting your feedback.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: errorMessage,
                            confirmButtonColor: '#F16E70'
                        });
                        
                        submitBtn.prop('disabled', false).html(originalText);
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