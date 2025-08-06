<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ESP-CIT - Student Feedback Survey</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Source Sans Pro', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .survey-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .survey-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
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
            color: #495057;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .form-label {
                font-size: 1rem;
            }
        }
        
        .form-control, .form-select {
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
            .form-control, .form-select {
                padding: 0.875rem;
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15);
            background-color: white;
            transform: translateY(-2px);
        }
        
        .rating-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 2px solid #e9ecef;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .rating-container {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
                padding: 1rem;
            }
        }
        
        .star-rating {
            font-size: 2.5rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        
        @media (max-width: 768px) {
            .star-rating {
                font-size: 2rem;
                margin: 0 0.15rem;
            }
        }
        
        @media (max-width: 480px) {
            .star-rating {
                font-size: 1.75rem;
                margin: 0 0.1rem;
            }
        }
        
        .star-rating:hover {
            color: #ffc107;
            transform: scale(1.1);
        }
        
        .star-rating.active {
            color: #ffc107;
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }
        
        .star-rating:active {
            transform: scale(0.95);
        }
        
        .rating-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #495057;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-width: 60px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .rating-value {
                font-size: 1.25rem;
                padding: 0.375rem 0.75rem;
            }
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 1.25rem 3rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            width: 100%;
            max-width: 300px;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        
        @media (max-width: 768px) {
            .btn-submit {
                padding: 1rem 2rem;
                font-size: 1rem;
                max-width: 100%;
            }
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
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
        
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }
        
        .form-section h3 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
            font-size: 1.25rem;
        }
        
        @media (max-width: 768px) {
            .form-section h3 {
                font-size: 1.1rem;
            }
        }
        
        .loading-spinner {
            display: none;
            margin-left: 1rem;
        }
        
        @media (max-width: 768px) {
            .loading-spinner {
                margin-left: 0.5rem;
            }
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        .footer {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .footer {
                padding: 1rem;
                font-size: 0.8rem;
            }
        }
        
        .footer a {
            color: #667eea;
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
            
            .row {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
            
            .col-md-6 {
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
        .form-select:focus,
        .btn:focus {
            outline: 2px solid #667eea;
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
        <div class="survey-container">
            <div class="survey-header">
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="ESP-CIT" style="height: 80px; width: auto; margin-right: 20px; filter: brightness(1.1) contrast(1.1);">
                    <h1 style="margin: 0; font-size: 3rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">ESP-CIT</h1>
                </div>
                <h2 style="font-size: 1.8rem; font-weight: 600; margin-bottom: 0.5rem;">Student Feedback Survey</h2>
                <p>Help us improve by providing your valuable feedback</p>
            </div>
            
            <div class="survey-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="surveyForm">
                    @csrf
                    
                    <div class="form-section">
                        <h3><i class="fas fa-user-tie mr-2"></i>Instructor & Subject Selection</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id" class="form-label">Select Instructor</label>
                                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                                        <option value="">Choose an instructor...</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->department }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id" class="form-label">Select Subject</label>
                                    <select class="form-select" id="subject_id" name="subject_id" required>
                                        <option value="">Choose a subject...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-star mr-2"></i>Rating & Feedback</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Rate your experience</label>
                            <div class="rating-container">
                                <div class="stars">
                                    <i class="fas fa-star star-rating" data-rating="1"></i>
                                    <i class="fas fa-star star-rating" data-rating="2"></i>
                                    <i class="fas fa-star star-rating" data-rating="3"></i>
                                    <i class="fas fa-star star-rating" data-rating="4"></i>
                                    <i class="fas fa-star star-rating" data-rating="5"></i>
                                </div>
                                <div class="rating-value">0.0</div>
                            </div>
                            <input type="hidden" id="rating" name="rating" value="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="feedback_text" class="form-label">Additional Feedback (Optional)</label>
                            <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" 
                                      placeholder="Share your thoughts about the instructor, teaching methods, course content, or any suggestions for improvement..."></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-user mr-2"></i>Student Information (Optional)</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="student_name" name="student_name" 
                                           placeholder="Enter your name (optional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_email" class="form-label">Your Email</label>
                                    <input type="email" class="form-control" id="student_email" name="student_email" 
                                           placeholder="Enter your email (optional)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-submit" id="submitBtn">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Feedback
                            <span class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ESP-CIT. All rights reserved.</p>
            <p><a href="{{ route('dashboard') }}">Admin Panel</a></p>
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
            let currentRating = 0;
            
            // Star rating functionality with touch support
            $('.star-rating').on('click touchstart', function(e) {
                e.preventDefault();
                currentRating = $(this).data('rating');
                $('#rating').val(currentRating);
                highlightStars(currentRating);
                updateRatingDisplay(currentRating);
            });
            
            // Hover effects for desktop
            if (window.innerWidth > 768) {
                $('.star-rating').hover(
                    function() {
                        const rating = $(this).data('rating');
                        highlightStars(rating);
                    },
                    function() {
                        highlightStars(currentRating);
                    }
                );
            }
            
            function highlightStars(rating) {
                $('.star-rating').removeClass('active');
                $('.star-rating').each(function(index) {
                    if (index < rating) {
                        $(this).addClass('active');
                    }
                });
            }
            
            function updateRatingDisplay(rating) {
                $('.rating-value').text(rating.toFixed(1));
            }
            
            // Dynamic subject loading based on teacher selection
            $('#teacher_id').change(function() {
                const teacherId = $(this).val();
                const subjectSelect = $('#subject_id');
                
                if (teacherId) {
                    $.ajax({
                        url: '{{ route("survey.subjects-by-teacher") }}',
                        method: 'GET',
                        data: { teacher_id: teacherId },
                        success: function(subjects) {
                            subjectSelect.html('<option value="">Choose a subject...</option>');
                            subjects.forEach(function(subject) {
                                subjectSelect.append(`<option value="${subject.id}">${subject.name} (${subject.subject_code})</option>`);
                            });
                        },
                        error: function() {
                            showError('Failed to load subjects for this teacher.');
                        }
                    });
                } else {
                    subjectSelect.html('<option value="">Choose a subject...</option>');
                }
            });
            
            // Form submission with mobile-friendly feedback
            $('#surveyForm').submit(function(e) {
                e.preventDefault();
                
                if (currentRating === 0) {
                    showError('Please provide a rating before submitting.');
                    // Scroll to rating section on mobile
                    if (window.innerWidth <= 768) {
                        $('html, body').animate({
                            scrollTop: $('.rating-container').offset().top - 100
                        }, 500);
                    }
                    return;
                }
                
                const submitBtn = $('#submitBtn');
                const loadingSpinner = $('.loading-spinner');
                
                submitBtn.prop('disabled', true);
                loadingSpinner.show();
                
                $.ajax({
                    url: '{{ route("survey.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thank You!',
                                text: response.message,
                                confirmButtonText: 'Submit Another',
                                showCancelButton: true,
                                cancelButtonText: 'Close',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errorMessage = 'Please check the following:\n';
                            Object.values(xhr.responseJSON.errors).forEach(function(errors) {
                                errors.forEach(function(error) {
                                    errorMessage += '• ' + error + '\n';
                                });
                            });
                            showError(errorMessage);
                        } else {
                            showError('An error occurred while submitting your feedback. Please try again.');
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        loadingSpinner.hide();
                    }
                });
            });
            
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonColor: '#667eea',
                    allowOutsideClick: true
                });
            }
            
            // Auto-hide alerts on mobile
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