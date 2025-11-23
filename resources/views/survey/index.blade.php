<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>PRMSU CCIT - Student Feedback Survey</title>

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
        
        /* Mobile scrolling improvements */
        @media (max-width: 768px) {
            html, body {
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch;
            }
            
            .survey-container {
                -webkit-overflow-scrolling: touch;
            }
            
            /* Ensure proper scrolling on mobile */
            .form-section, .part-section {
                scroll-margin-top: 20px;
            }
            
            /* Touch feedback for mobile interactions */
            .form-section.touching, .part-section.touching {
                transform: scale(0.98);
                transition: transform 0.1s ease;
            }
            
            .btn-group .btn.btn-touching {
                transform: scale(0.95);
                transition: transform 0.1s ease;
            }
            
            /* Improve touch targets */
            .btn-group .btn {
                min-height: 50px;
                min-width: 50px;
                touch-action: manipulation;
            }
            
            /* Better scrolling for mobile */
            .survey-container {
                position: relative;
                z-index: 1;
            }
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

        /* Survey Questions Styling */
        .btn-group .btn {
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0.75rem 0.5rem;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.2;
        }
        
        .btn-group .btn small {
            font-size: 0.7rem;
            font-weight: 400;
            margin-top: 2px;
            opacity: 0.8;
        }
        
        .btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(152, 170, 231, 0.3);
        }
        
        .btn-check:checked + .btn {
            background-color: var(--light-blue);
            border-color: var(--light-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(152, 170, 231, 0.3);
        }
        
        .btn-check:checked + .btn:hover {
            background-color: #7a8cd6;
            border-color: #7a8cd6;
        }
        
        .btn-check:checked + .btn small {
            opacity: 1;
        }
        
        /* Part 2 specific button styling for better visibility */
        .part2 .btn-group .btn {
            border-width: 2px;
            font-weight: 600;
        }
        
        .part2 .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        
        .part2 .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        .part2 .btn-outline-warning {
            color: #fd7e14;
            border-color: #fd7e14;
        }
        
        .part2 .btn-outline-warning:hover {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        
        .part2 .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }
        
        .part2 .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        .part2 .btn-outline-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }
        
        .part2 .btn-outline-info:hover {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            color: white;
        }
        
        .part2 .btn-outline-success {
            color: #198754;
            border-color: #198754;
        }
        
        .part2 .btn-outline-success:hover {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }
        
        /* Selected state for Part 2 buttons */
        .part2 .btn-check:checked + .btn-outline-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        .part2 .btn-check:checked + .btn-outline-warning {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        
        .part2 .btn-check:checked + .btn-outline-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        .part2 .btn-check:checked + .btn-outline-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            color: white;
        }
        
        .part2 .btn-check:checked + .btn-outline-success {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }
        
        /* Part 1 specific button styling for better visibility */
        .part-section:not(.part2) .btn-group .btn {
            border-width: 2px;
            font-weight: 600;
        }
        
        .part-section:not(.part2) .btn-outline-success {
            color: #198754;
            border-color: #198754;
        }
        
        .part-section:not(.part2) .btn-outline-success:hover {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }
        
        .part-section:not(.part2) .btn-outline-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }
        
        .part-section:not(.part2) .btn-outline-info:hover {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            color: white;
        }
        
        .part-section:not(.part2) .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }
        
        .part-section:not(.part2) .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        .part-section:not(.part2) .btn-outline-warning {
            color: #fd7e14;
            border-color: #fd7e14;
        }
        
        .part-section:not(.part2) .btn-outline-warning:hover {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        
        .part-section:not(.part2) .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        
        .part-section:not(.part2) .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        /* Selected state for Part 1 buttons */
        .part-section:not(.part2) .btn-check:checked + .btn-outline-success {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }
        
        .part-section:not(.part2) .btn-check:checked + .btn-outline-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            color: white;
        }
        
        .part-section:not(.part2) .btn-check:checked + .btn-outline-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        .part-section:not(.part2) .btn-check:checked + .btn-outline-warning {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        
        .part-section:not(.part2) .btn-check:checked + .btn-outline-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        /* Part-specific styling */
        .part-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--light-blue);
        }
        
        .part-section.part2 {
            border-left-color: var(--golden-orange);
        }
        
        .part-section.part3 {
            border-left-color: var(--light-blue);
        }
        
        .section-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: rgba(152, 170, 231, 0.1);
            border-radius: 6px;
        }
        
        .question-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .question-number {
            color: var(--light-blue);
            font-weight: 700;
        }
        
        /* Mobile responsive for survey questions */
        @media (max-width: 768px) {
            .btn-group {
                flex-wrap: wrap;
            }
            
            .btn-group .btn {
                flex: 1;
                min-width: 60px;
                margin: 2px;
                padding: 0.5rem 0.25rem;
                min-height: 50px;
                font-size: 0.8rem;
            }
            
            .btn-group .btn small {
                font-size: 0.6rem;
            }
            
            .col-md-6 {
                margin-bottom: 2rem;
            }
            
            .part-section {
                padding: 1rem;
            }
        }

        /* Tab Navigation Styling */
        .survey-tab {
            display: none;
        }
        
        .survey-tab.active {
            display: block;
        }
        
        .nav-buttons {
            display: none; /* Hidden by default */
        }
        
        .tab-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        
        .tab-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tab-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .tab-dot:hover {
            transform: scale(1.1);
            background: #adb5bd;
        }
        
        .tab-dot.active {
            background: var(--light-blue);
            transform: scale(1.2);
        }
        
        .tab-dot.completed {
            background: var(--light-green);
        }
        
        .tab-dot.completed:hover {
            background: #7bb894;
        }
        
        .nav-buttons {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            padding: 1.5rem 0;
            border-top: 1px solid #e9ecef;
            margin-top: 2rem;
        }
        
        .btn-nav {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .btn-prev {
            background: #f8f9fa;
            color: var(--dark-gray);
            border-color: #dee2e6;
        }
        
        .btn-prev:hover {
            background: #e9ecef;
            color: var(--dark-gray);
            transform: translateY(-2px);
        }
        
        .btn-next {
            background: var(--light-blue);
            color: white;
            border-color: var(--light-blue);
        }
        
        .btn-next:hover {
            background: #7a8cd6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(152, 170, 231, 0.3);
        }
        
        .btn-submit-final {
            background: var(--light-green);
            color: white;
            border-color: var(--light-green);
        }
        
        .btn-submit-final:hover {
            background: #7bb894;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(143, 207, 168, 0.3);
        }
        
        .btn-nav:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
        
        /* Mobile responsive for tabs */
        @media (max-width: 768px) {
            .tab-navigation {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .nav-buttons {
                width: 100%;
                justify-content: space-between;
            }
            
            .btn-nav {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="survey-container">
            <div class="survey-header">
                <div class="logo-section">
                                    <img src="{{ asset('images/logo.png') }}" alt="PRMSU CCIT" class="logo">
            </div>
            <h1>PRMSU CCIT</h1>
                <p>Student Feedback Survey</p>
            </div>
            
            <div class="survey-body">
                @if(isset($student))
                <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <i class="fas fa-user-graduate me-2"></i>
                        <strong>Logged in as:</strong> {{ $student->name }} ({{ $student->student_number }})
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="logoutBtn">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </div>
                @endif
                
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
                        
                        @if(isset($student))
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Your information has been auto-filled from your account. You can update it if needed.
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('student_name') is-invalid @enderror" 
                                           id="student_name" name="student_name" 
                                           value="{{ old('student_name', isset($student) ? $student->name : '') }}" 
                                           placeholder="Enter your full name">
                                    @error('student_name')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('student_email') is-invalid @enderror" 
                                           id="student_email" name="student_email" 
                                           value="{{ old('student_email', isset($student) ? $student->email : '') }}" 
                                           placeholder="Enter your email">
                                    @error('student_email')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year" class="form-label">Year Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('year') is-invalid @enderror" 
                                            id="year" name="year" required>
                                        <option value="">Select Year Level...</option>
                                        <option value="1st Year" {{ old('year', isset($student) ? $student->year : '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year', isset($student) ? $student->year : '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year', isset($student) ? $student->year : '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year', isset($student) ? $student->year : '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                    @error('year')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course" class="form-label">Program <span class="text-danger">*</span></label>
                                    <select class="form-select @error('course') is-invalid @enderror" 
                                            id="course" name="course" required>
                                        <option value="">Select Program...</option>
                                        <option value="BSIT" {{ old('course', isset($student) ? $student->course : '') == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                                        <option value="BSCS" {{ old('course', isset($student) ? $student->course : '') == 'BSCS' ? 'selected' : '' }}>BSCS</option>
                                    </select>
                                    @error('course')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="mb-3" style="color: var(--dark-gray);">
                            <i class="fas fa-chalkboard-teacher me-2" style="color: var(--light-blue);"></i>
                            Faculty & Course Selection
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id" class="form-label">Select Faculty</label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                            id="teacher_id" name="teacher_id" required>
                                        <option value="">Choose a faculty...</option>
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
                                    <label for="subject_id" class="form-label">Select Course</label>
                                    <select class="form-select @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" name="subject_id" required disabled>
                                        <option value="">Choose a course...</option>
                                    </select>
                                    @error('subject_id')
                                        <div class="error-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Survey Questions Section -->
                    <div class="form-section">
                        <h4 class="mb-3" style="color: var(--dark-gray);">
                            <i class="fas fa-question-circle me-2" style="color: var(--light-blue);"></i>
                            Faculty Evaluation Survey
                        </h4>
                        
                        <!-- Tab Navigation -->
                        <div class="tab-navigation">
                            <div class="tab-indicator">
                                <span class="me-2">Progress:</span>
                                <div class="tab-dot active" data-tab="1"></div>
                                <div class="tab-dot" data-tab="2"></div>
                                <div class="tab-dot" data-tab="3"></div>
                            </div>
                        </div>
                        
                        <!-- Tab 1: Part 1 - Faculty Evaluation -->
                        <div class="survey-tab active" id="tab1">
                            @if(isset($questionsByPart['part1']))
                            <div class="part-section">
                                <h5 class="mb-3" style="color: var(--light-blue); border-bottom: 2px solid var(--light-blue); padding-bottom: 0.5rem;">
                                    <i class="fas fa-star me-2"></i>Part 1: Faculty Evaluation
                                </h5>
                                <div class="alert alert-info mb-3">
                                    <strong>Rating Scale:</strong> 5 (Outstanding) | 4 (Very Satisfactory) | 3 (Satisfactory) | 2 (Fair) | 1 (Poor)
                                </div>
                                
                                @php
                                    $part1Questions = $questionsByPart['part1'];
                                    $sections = $part1Questions->groupBy('section')->sortKeys();
                                    // Move "No Section" to the end
                                    if ($sections->has('')) {
                                        $noSection = $sections->pull('');
                                        $sections->put('', $noSection);
                                    }
                                @endphp
                                
                                @foreach($sections as $sectionName => $sectionQuestions)
                                <div class="mb-4">
                                    <div class="section-subtitle">{{ $sectionQuestions->first()->section_label ?? $sectionName }}</div>
                                    @foreach($sectionQuestions as $question)
                                    <div class="form-group mb-3">
                                        <label class="question-label">
                                            <span class="question-number">{{ $question->order_number }}.</span> {{ $question->question_text }}
                                        </label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                                   id="q{{ $question->id }}_5" value="5" required>
                                            <label class="btn btn-outline-success" for="q{{ $question->id }}_5">
                                                5<br><small>Outstanding</small>
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                                   id="q{{ $question->id }}_4" value="4" required>
                                            <label class="btn btn-outline-info" for="q{{ $question->id }}_4">
                                                4<br><small>Very Satisfactory</small>
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                                   id="q{{ $question->id }}_3" value="3" required>
                                            <label class="btn btn-outline-secondary" for="q{{ $question->id }}_3">
                                                3<br><small>Satisfactory</small>
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                                   id="q{{ $question->id }}_2" value="2" required>
                                            <label class="btn btn-outline-warning" for="q{{ $question->id }}_2">
                                                2<br><small>Fair</small>
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                                   id="q{{ $question->id }}_1" value="1" required>
                                            <label class="btn btn-outline-danger" for="q{{ $question->id }}_1">
                                                1<br><small>Poor</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Navigation buttons for Tab 1 -->
                            <div class="nav-buttons mt-4">
                                <button type="button" class="btn btn-nav btn-prev" id="btnPrev" disabled>
                                    <i class="fas fa-arrow-left me-2"></i>Previous
                                </button>
                                <button type="button" class="btn btn-nav btn-next" id="btnNext">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Tab 2: Part 2 - Difficulty Level -->
                        <div class="survey-tab" id="tab2">
                            @if(isset($questionsByPart['part2']))
                            <div class="part-section part2">
                                <h5 class="mb-3" style="color: var(--golden-orange); border-bottom: 2px solid var(--golden-orange); padding-bottom: 0.5rem;">
                                    <i class="fas fa-chart-line me-2"></i>Part 2: Difficulty Level
                                </h5>
                                <div class="alert alert-warning mb-3">
                                    <strong>Rating Scale:</strong> 5 (Very Difficult) | 4 (Difficult) | 3 (Slightly Difficult) | 2 (Not Difficult) | 1 (Very Not Difficult)
                                </div>
                                
                                @php
                                    $part2Questions = $questionsByPart['part2'];
                                    $part2Sections = $part2Questions->groupBy('section')->sortKeys();
                                    // Move "No Section" to the end
                                    if ($part2Sections->has('')) {
                                        $noSection = $part2Sections->pull('');
                                        $part2Sections->put('', $noSection);
                                    }
                                @endphp
                                
                                @foreach($part2Sections as $sectionName => $sectionQuestions)
                                <div class="mb-4">
                                    @if($sectionName)
                                        <div class="section-subtitle">{{ $sectionQuestions->first()->section_label ?? $sectionName }}</div>
                                    @endif
                                    @foreach($sectionQuestions as $question)
                                <div class="form-group mb-3">
                                    <label class="question-label">
                                        <span class="question-number">{{ $question->order_number }}.</span> {{ $question->question_text }}
                                    </label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_5" value="5" required>
                                        <label class="btn btn-outline-danger" for="q{{ $question->id }}_5">
                                            5<br><small>Very Difficult</small>
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_4" value="4" required>
                                        <label class="btn btn-outline-warning" for="q{{ $question->id }}_4">
                                            4<br><small>Difficult</small>
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_3" value="3" required>
                                        <label class="btn btn-outline-secondary" for="q{{ $question->id }}_3">
                                            3<br><small>Slightly Difficult</small>
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_2" value="2" required>
                                        <label class="btn btn-outline-info" for="q{{ $question->id }}_2">
                                            2<br><small>Not Difficult</small>
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="question_responses[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_1" value="1" required>
                                        <label class="btn btn-outline-success" for="q{{ $question->id }}_1">
                                            1<br><small>Very Not Difficult</small>
                                        </label>
                                    </div>
                                </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Navigation buttons for Tab 2 -->
                            <div class="nav-buttons mt-4">
                                <button type="button" class="btn btn-nav btn-prev" id="btnPrev2">
                                    <i class="fas fa-arrow-left me-2"></i>Previous
                                </button>
                                <button type="button" class="btn btn-nav btn-next" id="btnNext2">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Tab 3: Part 3 - Open Comments -->
                        <div class="survey-tab" id="tab3">
                            @if(isset($questionsByPart['part3']))
                            <div class="part-section part3">
                                <h5 class="mb-3" style="color: var(--light-blue); border-bottom: 2px solid var(--light-blue); padding-bottom: 0.5rem;">
                                    <i class="fas fa-comments me-2"></i>Part 3: Open Comments
                                </h5>
                                <div class="alert alert-info mb-3">
                                    <strong>Instructions:</strong> Please provide detailed responses to the following questions.
                                </div>
                                
                                @php
                                    $part3Questions = $questionsByPart['part3'];
                                    $part3Sections = $part3Questions->groupBy('section')->sortKeys();
                                    // Move "No Section" to the end
                                    if ($part3Sections->has('')) {
                                        $noSection = $part3Sections->pull('');
                                        $part3Sections->put('', $noSection);
                                    }
                                @endphp
                                
                                @foreach($part3Sections as $sectionName => $sectionQuestions)
                                <div class="mb-4">
                                    @if($sectionName)
                                        <div class="section-subtitle">{{ $sectionQuestions->first()->section_label ?? $sectionName }}</div>
                                    @endif
                                    @foreach($sectionQuestions as $question)
                                <div class="form-group mb-3">
                                    <label for="comment_{{ $question->id }}" class="question-label">
                                        <span class="question-number">{{ $question->order_number }}.</span> {{ $question->question_text }}
                                    </label>
                                    <textarea class="form-control" 
                                              id="comment_{{ $question->id }}" 
                                              name="question_responses[{{ $question->id }}]" 
                                              rows="3" 
                                              placeholder="Please provide your response..."></textarea>
                                </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Navigation buttons for Tab 3 -->
                            <div class="nav-buttons mt-4">
                                <button type="button" class="btn btn-nav btn-prev" id="btnPrev3">
                                    <i class="fas fa-arrow-left me-2"></i>Previous
                                </button>
                                <button type="button" class="btn btn-nav btn-submit-final" id="btnSubmit">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Survey
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="form-section" style="display: none;">
                        <h4 class="mb-3" style="color: var(--dark-gray);">
                            <i class="fas fa-comments me-2" style="color: var(--light-blue);"></i>
                            Additional Comments
                        </h4>
                        
                        <div class="form-group">
                            <label for="feedback_text" class="form-label">Any additional feedback or suggestions</label>
                            <textarea class="form-control @error('feedback_text') is-invalid @enderror" 
                                      id="feedback_text" name="feedback_text" rows="4" 
                                      placeholder="Share any additional thoughts or suggestions...">{{ old('feedback_text') }}</textarea>
                            @error('feedback_text')
                                <div class="error-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </form>
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
            let currentTab = 1;
            const totalTabs = 3;
            
            // Mobile-friendly scroll function
            function scrollToElement(element, offset = 100) {
                if (element && element.length) {
                    const elementTop = element.offset().top - offset;
                    
                    // Use different scrolling methods for mobile vs desktop
                    if (window.innerWidth <= 768) {
                        // Mobile: Use window.scrollTo for better compatibility
                        window.scrollTo({
                            top: elementTop,
                            behavior: 'smooth'
                        });
                    } else {
                        // Desktop: Use jQuery animate
                        $('html, body').animate({
                            scrollTop: elementTop
                        }, 500);
                    }
                }
            }
            
            // Tab Navigation Functions
            function showTab(tabNumber) {
                // Hide all tabs
                $('.survey-tab').removeClass('active');
                // Show current tab
                $(`#tab${tabNumber}`).addClass('active');
                
                // Update tab dots
                $('.tab-dot').removeClass('active');
                $(`.tab-dot[data-tab="${tabNumber}"]`).addClass('active');
                
                // Update navigation buttons
                updateNavigationButtons();
            }
            
            function updateNavigationButtons() {
                // Hide all navigation button sets
                $('.nav-buttons').hide();
                
                // Show navigation buttons for current tab
                $(`#tab${currentTab} .nav-buttons`).show();
                
                // Update previous buttons
                $('.btn-prev').prop('disabled', currentTab === 1);
            }
            
            function validateCurrentTab() {
                const currentTabElement = $(`#tab${currentTab}`);
                let isValid = true;
                
                // Check required radio buttons
                currentTabElement.find('input[type="radio"]:required').each(function() {
                    const name = $(this).attr('name');
                    if (!$(`input[name="${name}"]:checked`).length) {
                        isValid = false;
                        return false; // break loop
                    }
                });
                
                // Check required textareas
                currentTabElement.find('textarea[required]').each(function() {
                    if (!$(this).val().trim()) {
                        isValid = false;
                        return false; // break loop
                    }
                });
                
                return isValid;
            }
            
            // Navigation button handlers
            $('.btn-next').click(function() {
                if (validateCurrentTab()) {
                    // Mark current tab as completed
                    $(`.tab-dot[data-tab="${currentTab}"]`).addClass('completed');
                    
                    // Move to next tab
                    currentTab++;
                    showTab(currentTab);
                    
                    // Scroll to top of new tab
                    scrollToElement($('.survey-tab.active'), 100);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please Complete All Questions',
                        text: 'Please answer all required questions in this section before proceeding.',
                        confirmButtonColor: '#F5B445'
                    });
                }
            });
            
            $('.btn-prev').click(function() {
                currentTab--;
                showTab(currentTab);
                
                // Scroll to top of new tab
                scrollToElement($('.survey-tab.active'), 100);
            });
            
            // Tab dot click handlers
            $('.tab-dot').click(function() {
                const tabNumber = parseInt($(this).data('tab'));
                if (tabNumber < currentTab || validateCurrentTab()) {
                    currentTab = tabNumber;
                    showTab(currentTab);
                    
                    // Scroll to top of new tab
                    scrollToElement($('.survey-tab.active'), 100);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please Complete Current Section',
                        text: 'Please complete all questions in the current section before jumping to another section.',
                        confirmButtonColor: '#F5B445'
                    });
                }
            });
            
            // Submit button handler
            $('#btnSubmit').click(function() {
                if (validateCurrentTab()) {
                    // Mark current tab as completed
                    $(`.tab-dot[data-tab="${currentTab}"]`).addClass('completed');
                    
                    // Submit the form
                    $('#surveyForm').submit();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please Complete All Questions',
                        text: 'Please answer all required questions before submitting the survey.',
                        confirmButtonColor: '#F5B445'
                    });
                }
            });
            
            // Initialize
            updateNavigationButtons();
            
            // Existing surveys data
            const existingSurveys = @json($existingSurveys ?? []);
            
            // Check if faculty-course combination has been evaluated
            function checkExistingSurvey() {
                const teacherId = $('#teacher_id').val();
                const subjectId = $('#subject_id').val();
                const submitBtn = $('#btnSubmit');
                const subjectSelect = $('#subject_id');
                
                if (teacherId && subjectId) {
                    const key = teacherId + '_' + subjectId;
                    if (existingSurveys[key]) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Already Evaluated',
                            html: 'You have already submitted a survey for this faculty and course combination.<br><br>You can only submit one survey per faculty-course pair.',
                            confirmButtonColor: '#F5B445'
                        }).then(function() {
                            // Deselect the subject after showing the message
                            subjectSelect.val('').trigger('change');
                        });
                        submitBtn.prop('disabled', true);
                        return true;
                    } else {
                        submitBtn.prop('disabled', false);
                        return false;
                    }
                }
                return false;
            }
            
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
                            checkExistingSurvey();
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
            
            // Check when subject is selected
            $('#subject_id').change(function() {
                checkExistingSurvey();
            });
            
            // Logout button
            $('#logoutBtn').click(function() {
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#F16E70',
                    cancelButtonColor: '#98AAE7',
                    confirmButtonText: 'Yes, logout'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("student.logout") }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                window.location.href = '{{ route("student.login") }}';
                            }
                        });
                    }
                });
            });
            
            // Form submission with AJAX
            $('#surveyForm').submit(function(e) {
                e.preventDefault();
                
                const submitBtn = $('#btnSubmit');
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
                            window.location.href = '{{ route("survey.index") }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while submitting your feedback.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
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
            
            // Mobile-specific touch handlers for better scrolling
            if (window.innerWidth <= 768) {
                // Add touch event listeners for form sections
                $('.form-section, .part-section').on('touchstart', function() {
                    $(this).addClass('touching');
                }).on('touchend', function() {
                    $(this).removeClass('touching');
                });
                
                // Improve button group interactions on mobile
                $('.btn-group .btn').on('touchstart', function() {
                    $(this).addClass('btn-touching');
                }).on('touchend', function() {
                    $(this).removeClass('btn-touching');
                });
                
                // Add smooth scrolling for form controls
                $('.form-control, .form-select').on('focus', function() {
                    setTimeout(() => {
                        scrollToElement($(this), 150);
                    }, 300);
                });
            }
            
            // Handle window resize to update mobile functionality
            $(window).on('resize', function() {
                // Re-initialize mobile touch handlers if needed
                if (window.innerWidth <= 768) {
                    // Ensure mobile touch handlers are active
                    $('.form-section, .part-section').off('touchstart touchend');
                    $('.form-section, .part-section').on('touchstart', function() {
                        $(this).addClass('touching');
                    }).on('touchend', function() {
                        $(this).removeClass('touching');
                    });
                }
            });
        });
    </script>
</body>
</html> 