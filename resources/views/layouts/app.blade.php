<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Student Feedback System')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --dark-gray: #494850;
            --light-green: #8FCFA8;
            --coral-pink: #F16E70;
            --golden-orange: #F5B445;
            --light-blue: #98AAE7;
        }

        .rating-stars {
            color: var(--golden-orange);
            font-size: 1.2em;
        }
        .sentiment-badge {
            font-size: 0.8em;
            padding: 0.25em 0.6em;
        }
        .card-stats {
            border-left: 4px solid var(--light-blue);
            transition: all 0.3s ease;
        }
        .card-stats:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(73, 72, 80, 0.1);
        }
        .card-stats.positive {
            border-left-color: var(--light-green);
        }
        .card-stats.negative {
            border-left-color: var(--coral-pink);
        }
        .card-stats.neutral {
            border-left-color: var(--golden-orange);
        }
        
        /* Enhanced Pagination Styling */
        .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }
        .page-link {
            color: var(--light-blue);
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .page-link:hover {
            color: var(--dark-gray);
            background-color: #e9ecef;
            border-color: #dee2e6;
            transform: translateY(-1px);
            text-decoration: none;
        }
        .page-item.active .page-link {
            background-color: var(--light-blue);
            border-color: var(--light-blue);
            color: white;
        }
        .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            cursor: not-allowed;
        }
        .pagination-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .page-item {
            margin: 0 0.125rem;
        }

        /* Enhanced Table Styling */
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(73, 72, 80, 0.1);
        }
        .table thead th {
            background-color: var(--light-green);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--dark-gray);
        }
        .table tbody tr:hover {
            background-color: rgba(143, 207, 168, 0.1);
            transition: background-color 0.2s ease;
        }

        /* Enhanced Modal Styling */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(73, 72, 80, 0.2);
        }
        .modal-header {
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--coral-pink) 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        .modal-header .close {
            color: white;
            opacity: 0.8;
        }
        .modal-header .close:hover {
            opacity: 1;
        }

        /* Enhanced Button Styling */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(73, 72, 80, 0.1);
        }
        .btn-primary {
            background-color: var(--light-blue);
            border-color: var(--light-blue);
        }
        .btn-primary:hover {
            background-color: #7a8cd6;
            border-color: #7a8cd6;
        }
        .btn-success {
            background-color: var(--light-green);
            border-color: var(--light-green);
        }
        .btn-success:hover {
            background-color: #7bb894;
            border-color: #7bb894;
        }
        .btn-danger {
            background-color: var(--coral-pink);
            border-color: var(--coral-pink);
        }
        .btn-danger:hover {
            background-color: #e55a5c;
            border-color: #e55a5c;
        }
        .btn-warning {
            background-color: var(--golden-orange);
            border-color: var(--golden-orange);
        }
        .btn-warning:hover {
            background-color: #e4a23d;
            border-color: #e4a23d;
        }

        /* Enhanced Card Styling */
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(73, 72, 80, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(73, 72, 80, 0.15);
        }

        /* Enhanced Form Styling */
        .form-control {
            border-radius: 6px;
            border: 2px solid #e9ecef;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.2rem rgba(152, 170, 231, 0.25);
        }

        /* Enhanced Badge Styling */
        .badge {
            border-radius: 12px;
            font-weight: 500;
            padding: 0.5em 0.8em;
        }
        .badge-primary {
            background-color: var(--light-blue);
        }
        .badge-success {
            background-color: var(--light-green);
        }
        .badge-danger {
            background-color: var(--coral-pink);
        }
        .badge-warning {
            background-color: var(--golden-orange);
        }

        /* Enhanced Sidebar */
        .main-sidebar {
            box-shadow: 2px 0 8px rgba(73, 72, 80, 0.1);
            background-color: var(--dark-gray) !important;
        }
        .nav-sidebar .nav-link {
            border-radius: 8px;
            margin: 2px 8px;
            transition: all 0.2s ease;
        }
        .nav-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(4px);
        }
        .nav-sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--coral-pink) 100%);
            box-shadow: 0 2px 8px rgba(152, 170, 231, 0.3);
            color: white !important;
        }
        .nav-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(4px);
            color: white !important;
        }

        /* Collapsed Sidebar Styling */
        .sidebar-collapse .main-sidebar {
            width: 70px !important;
        }
        .sidebar-collapse .brand-link {
            padding: 10px 5px !important;
            justify-content: center !important;
        }
        .sidebar-collapse .brand-image {
            height: 25px !important;
            width: auto !important;
            margin-right: 0 !important;
        }
        .sidebar-collapse .brand-text {
            display: none !important;
        }
        .sidebar-collapse .nav-sidebar .nav-link {
            padding: 12px 8px !important;
            text-align: center !important;
            margin: 4px 6px !important;
        }
        .sidebar-collapse .nav-sidebar .nav-link i {
            font-size: 1.2rem !important;
            margin-right: 0 !important;
        }
        .sidebar-collapse .nav-sidebar .nav-link p {
            display: none !important;
        }
        .sidebar-collapse .nav-sidebar .nav-item {
            margin-bottom: 8px !important;
        }
        .sidebar-collapse .sidebar {
            padding: 10px 0 !important;
        }

        /* Enhanced Navbar */
        .main-header {
            box-shadow: 0 2px 8px rgba(73, 72, 80, 0.1);
        }
        .navbar-nav .nav-link {
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(143, 207, 168, 0.1);
        }

        /* Enhanced Content Area */
        .content-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, rgba(152, 170, 231, 0.1) 100%);
            min-height: 100vh;
            padding: 20px;
        }

        /* Enhanced Stats Cards */
        .small-box {
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .small-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(73, 72, 80, 0.15);
        }
        .small-box.bg-info {
            background-color: var(--light-blue) !important;
        }
        .small-box.bg-success {
            background-color: var(--light-green) !important;
        }
        .small-box.bg-warning {
            background-color: var(--golden-orange) !important;
        }
        .small-box.bg-danger {
            background-color: var(--coral-pink) !important;
        }

        /* Enhanced Search Box */
        .search-box {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            transition: all 0.2s ease;
        }
        .search-box:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.2rem rgba(152, 170, 231, 0.25);
        }

        /* Brand Logo Styling */
        .brand-link .brand-image {
            color: var(--light-blue) !important;
        }

        /* Alert Styling */
        .alert-success {
            background-color: var(--light-green);
            border-color: var(--light-green);
            color: var(--dark-gray);
        }
        .alert-danger {
            background-color: var(--coral-pink);
            border-color: var(--coral-pink);
            color: white;
        }
        .alert-warning {
            background-color: var(--golden-orange);
            border-color: var(--golden-orange);
            color: var(--dark-gray);
        }
        .alert-info {
            background-color: var(--light-blue);
            border-color: var(--light-blue);
            color: white;
        }

        /* Text Colors */
        .text-primary {
            color: var(--light-blue) !important;
        }
        .text-success {
            color: var(--light-green) !important;
        }
        .text-danger {
            color: var(--coral-pink) !important;
        }
        .text-warning {
            color: var(--golden-orange) !important;
        }
        .text-muted {
            color: var(--dark-gray) !important;
        }

        /* Progress Bar Styling */
        .progress-bar.bg-primary {
            background-color: var(--light-blue) !important;
        }
        .progress-bar.bg-success {
            background-color: var(--light-green) !important;
        }
        .progress-bar.bg-danger {
            background-color: var(--coral-pink) !important;
        }
        .progress-bar.bg-warning {
            background-color: var(--golden-orange) !important;
        }

        /* Page Title Styling */
        .page-title-custom {
            font-family: 'Poppins', 'Source Sans Pro', sans-serif;
            font-weight: 600;
            font-size: 2.2rem;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(73, 72, 80, 0.1);
            letter-spacing: -0.5px;
        }
        .page-title-custom i {
            color: var(--light-blue);
            margin-right: 12px;
            font-size: 2rem;
        }
        .page-title-custom:hover {
            color: var(--light-blue);
            transition: color 0.3s ease;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            /* Navbar Mobile Optimizations */
            .main-header {
                padding: 0.5rem 1rem;
            }
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            .navbar-nav .nav-link.btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                margin: 0.25rem 0;
            }
            .dropdown-menu {
                position: fixed !important;
                top: 60px !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                border-radius: 0 !important;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
            }
            
            /* Sidebar Mobile Optimizations */
            .main-sidebar {
                width: 100% !important;
                position: fixed !important;
                top: 0 !important;
                left: -100% !important;
                height: 100vh !important;
                z-index: 1050 !important;
                transition: left 0.3s ease !important;
            }
            .sidebar-open .main-sidebar {
                left: 0 !important;
            }
            .sidebar-collapse .main-sidebar {
                width: 100% !important;
                left: -100% !important;
            }
            .brand-link {
                padding: 1rem !important;
                justify-content: center !important;
            }
            .brand-image {
                height: 45px !important;
            }
            .brand-text {
                font-size: 1.4rem !important;
                font-weight: 600 !important;
            }
            .nav-sidebar .nav-link {
                padding: 1rem 1.5rem !important;
                font-size: 1.1rem !important;
                margin: 0.25rem 1rem !important;
            }
            .nav-sidebar .nav-link i {
                font-size: 1.3rem !important;
                margin-right: 1rem !important;
            }
            
            /* Content Area Mobile Optimizations */
            .content-wrapper {
                padding: 10px !important;
                margin-left: 0 !important;
            }
            .content-header {
                padding: 1rem !important;
            }
            .page-title-custom {
                font-size: 1.8rem !important;
                margin-bottom: 0.5rem !important;
            }
            .page-title-custom i {
                font-size: 1.5rem !important;
            }
            
            /* Cards Mobile Optimizations */
            .card {
                margin-bottom: 1rem !important;
                border-radius: 12px !important;
            }
            .card-header {
                padding: 1rem !important;
            }
            .card-body {
                padding: 1rem !important;
            }
            
            /* Statistics Cards Mobile */
            .small-box {
                margin-bottom: 1rem !important;
                min-height: 100px !important;
            }
            .small-box .inner {
                padding: 1rem !important;
            }
            .small-box .inner h3 {
                font-size: 1.8rem !important;
            }
            .small-box .inner p {
                font-size: 0.9rem !important;
            }
            
            /* Tables Mobile Optimizations */
            .table-responsive {
                border-radius: 12px !important;
                overflow: hidden !important;
            }
            .table {
                font-size: 0.9rem !important;
            }
            .table th,
            .table td {
                padding: 0.75rem 0.5rem !important;
                vertical-align: middle !important;
            }
            
            /* Forms Mobile Optimizations */
            .form-control,
            .form-select {
                font-size: 16px !important; /* Prevents zoom on iOS */
                padding: 0.75rem !important;
                border-radius: 8px !important;
            }
            .form-label {
                font-size: 0.95rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Buttons Mobile Optimizations */
            .btn {
                padding: 0.75rem 1.5rem !important;
                font-size: 0.9rem !important;
                border-radius: 8px !important;
                min-height: 44px !important; /* Touch target size */
            }
            .btn-sm {
                padding: 0.5rem 1rem !important;
                font-size: 0.85rem !important;
            }
            
            /* Pagination Mobile */
            .pagination {
                justify-content: center !important;
                flex-wrap: wrap !important;
                gap: 0.25rem !important;
            }
            .page-link {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.9rem !important;
                min-width: 2.5rem !important;
                justify-content: center !important;
            }
            .page-item {
                margin: 0 !important;
            }
            
            /* Alerts Mobile */
            .alert {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
                border-radius: 8px !important;
            }
            
            /* Modal Mobile Optimizations */
            .modal-dialog {
                margin: 0.5rem !important;
                max-width: calc(100% - 1rem) !important;
            }
            .modal-content {
                border-radius: 12px !important;
            }
            .modal-header {
                padding: 1rem !important;
            }
            .modal-body {
                padding: 1rem !important;
            }
            .modal-footer {
                padding: 1rem !important;
            }
            
            /* Footer Mobile */
            .main-footer {
                padding: 1rem !important;
                text-align: center !important;
            }
            .main-footer .float-right {
                float: none !important;
                margin-top: 0.5rem !important;
            }
            
            /* Charts Mobile */
            canvas {
                max-height: 300px !important;
            }
            
            /* Search and Filter Mobile */
            .search-box {
                width: 100% !important;
                margin-bottom: 1rem !important;
            }
            .filter-section {
                flex-direction: column !important;
            }
            .filter-section .form-group {
                margin-bottom: 1rem !important;
            }
            
            /* Equal Height Cards Mobile */
            .equal-height-cards .card {
                min-height: auto !important;
            }
            
            /* Breadcrumb Mobile */
            .breadcrumb {
                font-size: 0.85rem !important;
                padding: 0.5rem 0 !important;
            }
            
            /* Badge Mobile */
            .badge {
                font-size: 0.75rem !important;
                padding: 0.25rem 0.5rem !important;
            }
            
            /* Rating Stars Mobile */
            .rating-stars {
                font-size: 1rem !important;
            }
            
            /* Overlay for sidebar */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            }
            .sidebar-open .sidebar-overlay {
                display: block;
            }
        }
        
        /* Extra Small Devices */
        @media (max-width: 576px) {
            .page-title-custom {
                font-size: 1.5rem !important;
            }
            .small-box .inner h3 {
                font-size: 1.5rem !important;
            }
            .table {
                font-size: 0.8rem !important;
            }
            .btn {
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }
            .navbar-nav .nav-link {
                text-align: center !important;
            }
        }
        
        /* Prevent zoom on input focus (iOS) */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            select,
            textarea,
            input {
                font-size: 16px !important;
            }
        }
        
        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .nav-link,
            .btn,
            .form-control,
            .form-select {
                min-height: 44px !important;
            }
            
            .table th,
            .table td {
                min-height: 44px !important;
            }
        }
        
        /* Session Expired Alert Styling */
        .session-expired-popup {
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
        }
        
        .session-expired-title {
            color: var(--dark-gray) !important;
            font-family: 'Poppins', sans-serif !important;
            font-weight: 600 !important;
            font-size: 1.5rem !important;
            margin-bottom: 1rem !important;
        }
        
        .session-expired-content {
            color: var(--dark-gray) !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
        }
        
        /* Custom hourglass icon styling */
        .swal2-icon.swal2-warning {
            border-color: var(--golden-orange) !important;
            color: var(--golden-orange) !important;
        }
        
        .swal2-icon.swal2-warning .swal2-icon-content {
            color: var(--golden-orange) !important;
        }
        
        /* Mobile responsive session alert */
        @media (max-width: 768px) {
            .session-expired-popup {
                margin: 1rem !important;
                width: calc(100% - 2rem) !important;
            }
            
            .session-expired-title {
                font-size: 1.3rem !important;
            }
            
            .session-expired-content {
                font-size: 0.95rem !important;
            }
        }
        
        /* Mobile Scrolling Improvements */
        @media (max-width: 768px) {
            html, body {
                overflow-x: hidden !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .content-wrapper {
                -webkit-overflow-scrolling: touch !important;
            }
            
            /* Ensure proper scrolling on mobile */
            .card, .table-responsive, .form-section {
                scroll-margin-top: 20px !important;
            }
            
            /* Touch feedback for mobile interactions */
            .card.touching, .table-responsive.touching, .form-section.touching {
                transform: scale(0.98) !important;
                transition: transform 0.1s ease !important;
            }
            
            .btn.touching {
                transform: scale(0.95) !important;
                transition: transform 0.1s ease !important;
            }
            
            /* Improve touch targets */
            .btn, .nav-link, .form-control, .form-select {
                min-height: 44px !important;
                touch-action: manipulation !important;
            }
            
            /* Better scrolling for mobile */
            .content-wrapper {
                position: relative !important;
                z-index: 1 !important;
            }
        }

        /* Mobile Table Scrolling Fixes */
        @media (max-width: 768px) {
            .table-responsive {
                -webkit-overflow-scrolling: touch !important;
                overflow-x: auto !important;
                overflow-y: visible !important;
                -webkit-scrollbar-width: thin !important;
                scrollbar-width: thin !important;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 6px !important;
            }
            
            .table-responsive::-webkit-scrollbar-track {
                background: #f1f1f1 !important;
                border-radius: 3px !important;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: #c1c1c1 !important;
                border-radius: 3px !important;
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8 !important;
            }
            
            .table {
                min-width: 600px !important; /* Ensures table doesn't shrink too much */
                white-space: nowrap !important;
            }
            
            .table th,
            .table td {
                white-space: nowrap !important;
                padding: 0.75rem 1rem !important;
                vertical-align: middle !important;
            }
            
            /* DataTables specific mobile fixes */
            .dataTables_wrapper {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .dataTables_scrollBody {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .dataTables_scrollHead {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            /* Card body scrolling fix */
            .card-body {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            /* Prevent horizontal scroll on body when table is scrolled */
            body {
                overflow-x: hidden !important;
            }
            
            /* Touch scrolling improvements */
            .table-responsive,
            .dataTables_wrapper,
            .card-body {
                /* touch-action: pan-x !important; */
                -webkit-overflow-scrolling: touch !important;
            }
        }
        
        /* Extra small devices table fixes */
        @media (max-width: 576px) {
            .table {
                min-width: 500px !important;
                font-size: 0.8rem !important;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.75rem !important;
            }
            
            .table-responsive {
                margin: 0 -0.5rem !important;
                padding: 0 0.5rem !important;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-bottom: 1px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: var(--dark-gray); font-weight: 500; padding: 12px 16px; border-radius: 8px; transition: all 0.3s ease; margin-right: 8px;">
                        <i class="fas fa-bars" style="font-size: 1.1rem;"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link" style="color: var(--dark-gray); font-weight: 500; padding: 12px 16px; border-radius: 8px; transition: all 0.3s ease; display: flex; align-items: center;">
                        <i class="fas fa-home" style="margin-right: 8px; color: var(--light-blue);"></i> 
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('survey.index') }}" target="_blank" style="background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%); color: white; border-radius: 8px; padding: 10px 16px; margin-right: 12px; font-weight: 500; transition: all 0.3s ease; border: none; display: flex; align-items: center;">
                        <i class="fas fa-external-link-alt" style="margin-right: 8px;"></i> 
                        <span>Public Survey</span>
                    </a>
                </li>
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" style="color: var(--dark-gray); font-weight: 500; padding: 12px 16px; border-radius: 8px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(152, 170, 231, 0.1);">
                        <i class="fas fa-user-circle" style="margin-right: 8px; color: var(--light-blue); font-size: 1.1rem;"></i> 
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 0.8rem; color: var(--dark-gray);"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="border: none; box-shadow: 0 8px 25px rgba(0,0,0,0.15); border-radius: 12px; padding: 8px; min-width: 200px;">
                        <li>
                            <a class="dropdown-item" href="#" onclick="logout()" style="color: var(--dark-gray); padding: 12px 16px; border-radius: 8px; transition: all 0.3s ease; display: flex; align-items: center;">
                                <i class="fas fa-sign-out-alt" style="margin-right: 12px; color: var(--coral-pink);"></i> 
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endauth
            </ul>
        </nav>

        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay"></div>

        <!-- Main Sidebar Container -->
        @auth
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: var(--dark-gray) !important;">
            <!-- Sidebar -->
            <div class="sidebar" style="background-color: var(--dark-gray);">
                <!-- Brand Logo at Top -->
                <div class="text-center" style="padding: 25px 15px 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                    <a href="{{ route('dashboard') }}" style="text-decoration: none; display: block;">
                        <img src="{{ asset('images/logo.png') }}" alt="PRMSU CCIT" style="height: 150px; width: auto; margin-bottom: 10px; filter: brightness(1.1) contrast(1.1);">
                        <div style="color: white; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-top: 5px;">PRMSU CCIT</div>
                    </a>
                </div>
                
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" style="padding: 0px;">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-tachometer-alt" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-chalkboard-teacher" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Faculty</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-book" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Course Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-user-graduate" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Student Management</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-users" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('survey-questions.index') }}" class="nav-link {{ request()->routeIs('survey-questions.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-question-circle" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Survey Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-chart-bar" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sentiment-words.index') }}" class="nav-link {{ request()->routeIs('sentiment-words.*') ? 'active' : '' }}" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-brain" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Sentiment Words</p>
                            </a>
                        </li>
                        
                        <!-- Logout Section -->
                        <li class="nav-item" style="margin-top: 50px;">
                            <a href="#" onclick="logout()" class="nav-link" style="color: white; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease;">
                                <i class="nav-icon fas fa-sign-out-alt" style="color: white; margin-right: 12px;"></i>
                                <p style="color: white; font-family: 'Poppins', sans-serif; font-weight: 400;">Log out</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        @endauth

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="page-title-custom">
                                <i class="fas fa-@yield('icon', 'home')"></i>
                                @yield('page-title', 'Dashboard')
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                <span class="text-muted">PRMSU CCIT</span>
            </div>
            <strong>Copyright &copy; {{ date('Y') }}</strong> All rights reserved.
        </footer>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        // Global success/error message handlers
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#8FCFA8',
                color: '#494850'
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                background: '#F16E70',
                color: '#fff'
            });
        }

        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Enhanced modal functionality
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('input:first').focus();
            });

            // Enhanced form validation styling
            $('.form-control').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                if (!$(this).val()) {
                    $(this).parent().removeClass('focused');
                }
            });

            // Mobile sidebar functionality
            $('[data-widget="pushmenu"]').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-open');
            });

            // Close sidebar when clicking overlay
            $('.sidebar-overlay').on('click', function() {
                $('body').removeClass('sidebar-open');
            });

            // Close sidebar on window resize
            $(window).on('resize', function() {
                if ($(window).width() > 768) {
                    $('body').removeClass('sidebar-open');
                }
            });

            // Prevent body scroll when sidebar is open on mobile
            $('body').on('touchmove', function(e) {
                if ($('body').hasClass('sidebar-open')) {
                    e.preventDefault();
                }
            });

            // Session expiration detection
            let sessionTimeout;
            const SESSION_TIMEOUT = 30 * 60 * 1000; // 30 minutes in milliseconds

            function resetSessionTimer() {
                clearTimeout(sessionTimeout);
                sessionTimeout = setTimeout(function() {
                    showSessionExpiredAlert();
                }, SESSION_TIMEOUT);
            }

            function showSessionExpiredAlert() {
                Swal.fire({
                    title: 'Your session has expired',
                    text: 'Due to inactivity, your session has expired. Please refresh to continue.',
                    icon: 'warning',
                    iconColor: '#F5B445',
                    background: '#ffffff',
                    confirmButtonColor: '#98AAE7',
                    confirmButtonText: 'Refresh Page',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCancelButton: false,
                    customClass: {
                        popup: 'session-expired-popup',
                        title: 'session-expired-title',
                        content: 'session-expired-content'
                    },
                    didOpen: () => {
                        // Add custom styling
                        const popup = Swal.getPopup();
                        popup.style.borderRadius = '12px';
                        popup.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }

            // Reset timer on user activity
            $(document).on('mousemove keypress click scroll', function() {
                resetSessionTimer();
            });

            // Initialize session timer
            resetSessionTimer();

            // Check for server-side session expiration
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 419) { // CSRF token mismatch (session expired)
                    showSessionExpiredAlert();
                }
            });

            // Mobile-specific touch handlers for better scrolling
            if (window.innerWidth <= 768) {
                // Add touch event listeners for cards and tables
                // $('.card, .table-responsive, .form-section').on('touchstart', function() {
                //     $(this).addClass('touching');
                // }).on('touchend', function() {
                //     $(this).removeClass('touching');
                // });
                
                // // Improve button interactions on mobile
                // $('.btn, .nav-link').on('touchstart', function() {
                //     $(this).addClass('touching');
                // }).on('touchend', function() {
                //     $(this).removeClass('touching');
                // });
                
                // // Add smooth scrolling for form controls
                // $('.form-control, .form-select').on('focus', function() {
                //     setTimeout(() => {
                //         scrollToElement($(this), 150);
                //     }, 300);
                // });
            }
            
            // Handle window resize to update mobile functionality
            $(window).on('resize', function() {
                // Re-initialize mobile touch handlers if needed
                if (window.innerWidth <= 768) {
                    // Ensure mobile touch handlers are active
                    $('.card, .table-responsive, .form-section').off('touchstart touchend');
                    $('.card, .table-responsive, .form-section').on('touchstart', function() {
                        $(this).addClass('touching');
                    }).on('touchend', function() {
                        $(this).removeClass('touching');
                    });
                    
                    $('.btn, .nav-link').off('touchstart touchend');
                    $('.btn, .nav-link').on('touchstart', function() {
                        $(this).addClass('touching');
                    }).on('touchend', function() {
                        $(this).removeClass('touching');
                    });
                }
            });
        });

        function logout() {
            Swal.fire({
                title: 'Are you sure you want to log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F16E70',
                cancelButtonColor: '#494850',
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('logout') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccess(response.message);
                                window.location.href = "{{ url('/login') }}";
                            } else {
                                showError(response.message || 'Logout failed.');
                            }
                        },
                        error: function(xhr, status, error) {
                            showError('Network error or server issue.');
                        }
                    });
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html> 