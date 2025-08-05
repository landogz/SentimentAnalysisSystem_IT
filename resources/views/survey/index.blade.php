<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Student Feedback Survey</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .survey-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 800px;
        }
        
        .survey-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        
        .survey-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .rating-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .star-rating {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .star-rating:hover,
        .star-rating.active {
            color: #ffc107;
        }
        
        .rating-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-left: 1rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .progress-bar {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .error-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .success-message {
            text-align: center;
            padding: 3rem;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="survey-container">
            <!-- Survey Header -->
            <div class="survey-header">
                <h1><i class="fas fa-graduation-cap me-2"></i>Student Feedback Survey</h1>
                <p class="mb-0">Help us improve our teaching quality by providing your valuable feedback</p>
            </div>

            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <!-- Survey Form -->
            <div class="survey-body" id="surveyForm">
                <form id="feedbackForm">
                    <!-- Step 1: Teacher Selection -->
                    <div class="form-step" id="step1">
                        <div class="form-group">
                            <label for="teacher_id" class="form-label">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Select Teacher/Instructor
                            </label>
                            <select class="form-select" id="teacher_id" name="teacher_id" required>
                                <option value="">Choose a teacher...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->department }})</option>
                                @endforeach
                            </select>
                            <div class="error-feedback" id="teacher_error"></div>
                        </div>
                    </div>

                    <!-- Step 2: Subject Selection -->
                    <div class="form-step" id="step2" style="display: none;">
                        <div class="form-group">
                            <label for="subject_id" class="form-label">
                                <i class="fas fa-book me-2"></i>Select Subject
                            </label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="">Choose a subject...</option>
                            </select>
                            <div class="error-feedback" id="subject_error"></div>
                        </div>
                    </div>

                    <!-- Step 3: Rating -->
                    <div class="form-step" id="step3" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-star me-2"></i>Overall Rating
                            </label>
                            <div class="rating-container">
                                <div class="stars">
                                    <i class="fas fa-star star-rating" data-rating="1"></i>
                                    <i class="fas fa-star star-rating" data-rating="2"></i>
                                    <i class="fas fa-star star-rating" data-rating="3"></i>
                                    <i class="fas fa-star star-rating" data-rating="4"></i>
                                    <i class="fas fa-star star-rating" data-rating="5"></i>
                                </div>
                                <span class="rating-value" id="ratingValue">0.0</span>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="0.0" required>
                            <div class="error-feedback" id="rating_error"></div>
                        </div>
                    </div>

                    <!-- Step 4: Feedback -->
                    <div class="form-step" id="step4" style="display: none;">
                        <div class="form-group">
                            <label for="feedback_text" class="form-label">
                                <i class="fas fa-comment me-2"></i>Additional Feedback (Optional)
                            </label>
                            <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" 
                                      placeholder="Please share your thoughts about the teacher and subject..."></textarea>
                            <div class="error-feedback" id="feedback_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="student_name" class="form-label">
                                <i class="fas fa-user me-2"></i>Your Name (Optional)
                            </label>
                            <input type="text" class="form-control" id="student_name" name="student_name" 
                                   placeholder="Enter your name">
                        </div>

                        <div class="form-group">
                            <label for="student_email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Your Email (Optional)
                            </label>
                            <input type="email" class="form-control" id="student_email" name="student_email" 
                                   placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                            <i class="fas fa-arrow-left me-2"></i>Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="nextBtn">
                            Next<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-submit" id="submitBtn" style="display: none;">
                            <i class="fas fa-paper-plane me-2"></i>Submit Survey
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success Message -->
            <div class="survey-body" id="successMessage" style="display: none;">
                <div class="success-message">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Thank You!</h3>
                    <p>Your feedback has been submitted successfully. We appreciate your time and valuable input.</p>
                    <button type="button" class="btn btn-primary" onclick="location.reload()">
                        <i class="fas fa-redo me-2"></i>Submit Another Survey
                    </button>
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
        let currentStep = 1;
        const totalSteps = 4;
        let selectedRating = 0;

        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize
        $(document).ready(function() {
            updateProgress();
            setupStarRating();
            setupTeacherChange();
        });

        // Progress bar
        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            $('#progressFill').css('width', progress + '%');
        }

        // Star rating functionality
        function setupStarRating() {
            $('.star-rating').click(function() {
                const rating = $(this).data('rating');
                selectedRating = rating;
                
                $('.star-rating').removeClass('active');
                $(this).prevAll().addClass('active');
                $(this).addClass('active');
                
                $('#ratingValue').text(rating + '.0');
                $('#ratingInput').val(rating + '.0');
            });

            $('.star-rating').hover(
                function() {
                    const rating = $(this).data('rating');
                    $('.star-rating').removeClass('active');
                    $(this).prevAll().addClass('active');
                    $(this).addClass('active');
                    $('#ratingValue').text(rating + '.0');
                },
                function() {
                    if (selectedRating === 0) {
                        $('.star-rating').removeClass('active');
                        $('#ratingValue').text('0.0');
                    } else {
                        $('.star-rating').removeClass('active');
                        $('.star-rating[data-rating="' + selectedRating + '"]').prevAll().addClass('active');
                        $('.star-rating[data-rating="' + selectedRating + '"]').addClass('active');
                        $('#ratingValue').text(selectedRating + '.0');
                    }
                }
            );
        }

        // Teacher change handler
        function setupTeacherChange() {
            $('#teacher_id').change(function() {
                const teacherId = $(this).val();
                if (teacherId) {
                    $.get('{{ route("survey.subjects-by-teacher") }}', { teacher_id: teacherId })
                        .done(function(subjects) {
                            $('#subject_id').empty().append('<option value="">Choose a subject...</option>');
                            subjects.forEach(function(subject) {
                                $('#subject_id').append('<option value="' + subject.id + '">' + subject.name + ' (' + subject.subject_code + ')</option>');
                            });
                        });
                }
            });
        }

        // Navigation
        $('#nextBtn').click(function() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showCurrentStep();
                }
            }
        });

        $('#prevBtn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                showCurrentStep();
            }
        });

        function showCurrentStep() {
            $('.form-step').hide();
            $('#step' + currentStep).show();
            
            $('#prevBtn').toggle(currentStep > 1);
            $('#nextBtn').toggle(currentStep < totalSteps);
            $('#submitBtn').toggle(currentStep === totalSteps);
            
            updateProgress();
        }

        function validateCurrentStep() {
            let isValid = true;
            $('.error-feedback').empty();

            switch(currentStep) {
                case 1:
                    if (!$('#teacher_id').val()) {
                        $('#teacher_error').text('Please select a teacher.');
                        isValid = false;
                    }
                    break;
                case 2:
                    if (!$('#subject_id').val()) {
                        $('#subject_error').text('Please select a subject.');
                        isValid = false;
                    }
                    break;
                case 3:
                    if (selectedRating === 0) {
                        $('#rating_error').text('Please provide a rating.');
                        isValid = false;
                    }
                    break;
            }

            return isValid;
        }

        // Form submission
        $('#feedbackForm').submit(function(e) {
            e.preventDefault();
            
            if (!validateCurrentStep()) {
                return;
            }

            const formData = $(this).serialize();
            
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting...');

            $.post('{{ route("survey.store") }}', formData)
                .done(function(response) {
                    if (response.success) {
                        $('#surveyForm').hide();
                        $('#successMessage').show();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                })
                .fail(function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.errors) {
                        Object.keys(response.errors).forEach(function(field) {
                            $('#' + field + '_error').text(response.errors[field][0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while submitting the survey. Please try again.'
                        });
                    }
                })
                .always(function() {
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Submit Survey');
                });
        });
    </script>
</body>
</html> 