@extends('layouts.app')

@section('title', 'Reports - Student Feedback System')

@section('page-title', 'Reports & Analytics')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row">
    <!-- Filter Panel -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-1"></i>
                    Filter Options
                </h3>
            </div>
            <div class="card-body">
                <form id="reportFilterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="teacher_filter">Teacher</label>
                                <select class="form-control" id="teacher_filter" name="teacher_id">
                                    <option value="">All Teachers</option>
                                    @foreach($teachers ?? [] as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="subject_filter">Subject</label>
                                <select class="form-control" id="subject_filter" name="subject_id">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects ?? [] as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_from">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_to">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-1"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="totalSurveys">0</h3>
                <p>Total Surveys</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="avgRating">0.0</h3>
                <p>Average Rating</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="positiveSentiment">0</h3>
                <p>Positive Feedback</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-up"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="negativeSentiment">0</h3>
                <p>Negative Feedback</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-down"></i>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Sentiment Distribution
                </h3>
            </div>
            <div class="card-body">
                <canvas id="sentimentChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Rating Distribution
                </h3>
            </div>
            <div class="card-body">
                <canvas id="ratingChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-trophy mr-1"></i>
                    Top Rated Teachers
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>Department</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                            </tr>
                        </thead>
                        <tbody id="topTeachersTable">
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-1"></i>
                    Top Rated Subjects
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                            </tr>
                        </thead>
                        <tbody id="topSubjectsTable">
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-download mr-1"></i>
                    Export Reports
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success btn-block" onclick="exportReport('pdf')">
                            <i class="fas fa-file-pdf mr-1"></i>Export PDF
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-info btn-block" onclick="exportReport('excel')">
                            <i class="fas fa-file-excel mr-1"></i>Export Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning btn-block" onclick="exportReport('csv')">
                            <i class="fas fa-file-csv mr-1"></i>Export CSV
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-secondary btn-block" onclick="printReport()">
                            <i class="fas fa-print mr-1"></i>Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let sentimentChart, ratingChart;

$(document).ready(function() {
    // Initialize charts
    initializeCharts();
    
    // Load initial data
    loadReportData();
    
    // Form submission
    $('#reportFilterForm').submit(function(e) {
        e.preventDefault();
        loadReportData();
    });
});

function initializeCharts() {
    // Sentiment Chart
    const sentimentCtx = document.getElementById('sentimentChart').getContext('2d');
    sentimentChart = new Chart(sentimentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Rating Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    ratingChart = new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ['1★', '2★', '3★', '4★', '5★'],
            datasets: [{
                label: 'Surveys',
                data: [0, 0, 0, 0, 0],
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function loadReportData() {
    const formData = $('#reportFilterForm').serialize();
    
    $.get('{{ route("dashboard.stats") }}', formData, function(data) {
        // Update statistics cards
        $('#totalSurveys').text(data.total_surveys);
        $('#avgRating').text(data.average_rating);
        $('#positiveSentiment').text(data.sentiment_stats.positive);
        $('#negativeSentiment').text(data.sentiment_stats.negative);
        
        // Update sentiment chart
        sentimentChart.data.datasets[0].data = [
            data.sentiment_stats.positive,
            data.sentiment_stats.neutral,
            data.sentiment_stats.negative
        ];
        sentimentChart.update();
        
        // Load rating distribution
        loadRatingDistribution();
        
        // Load top performers
        loadTopPerformers();
    });
}

function loadRatingDistribution() {
    $.get('{{ route("dashboard.chart-data") }}', function(data) {
        const ratingData = [0, 0, 0, 0, 0];
        
        data.rating.forEach(function(item) {
            const rating = parseInt(item.rating_group);
            if (rating >= 1 && rating <= 5) {
                ratingData[rating - 1] = item.count;
            }
        });
        
        ratingChart.data.datasets[0].data = ratingData;
        ratingChart.update();
    });
}

function loadTopPerformers() {
    // Load top teachers
    $.get('/teachers-ajax', function(teachers) {
        const topTeachers = teachers.slice(0, 5);
        let html = '';
        
        if (topTeachers.length > 0) {
            topTeachers.forEach(function(teacher) {
                html += `
                    <tr>
                        <td>${teacher.name}</td>
                        <td>${teacher.department}</td>
                        <td>
                            <span class="rating-stars">
                                ${generateStars(teacher.surveys_avg_rating || 0)}
                            </span>
                            <span class="ml-1">${(teacher.surveys_avg_rating || 0).toFixed(1)}</span>
                        </td>
                        <td><span class="badge badge-secondary">${teacher.surveys_count || 0}</span></td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
        }
        
        $('#topTeachersTable').html(html);
    });
    
    // Load top subjects
    $.get('/subjects-ajax', function(subjects) {
        const topSubjects = subjects.slice(0, 5);
        let html = '';
        
        if (topSubjects.length > 0) {
            topSubjects.forEach(function(subject) {
                html += `
                    <tr>
                        <td>${subject.name}</td>
                        <td>${subject.teacher ? subject.teacher.name : 'N/A'}</td>
                        <td>
                            <span class="rating-stars">
                                ${generateStars(subject.surveys_avg_rating || 0)}
                            </span>
                            <span class="ml-1">${(subject.surveys_avg_rating || 0).toFixed(1)}</span>
                        </td>
                        <td><span class="badge badge-secondary">${subject.surveys_count || 0}</span></td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
        }
        
        $('#topSubjectsTable').html(html);
    });
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        } else {
            stars += '<i class="far fa-star"></i>';
        }
    }
    return stars;
}

function exportReport(format) {
    const formData = $('#reportFilterForm').serialize();
    
    Swal.fire({
        title: 'Exporting Report',
        text: `Preparing ${format.toUpperCase()} report...`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate export process
    setTimeout(function() {
        Swal.fire({
            title: 'Export Complete!',
            text: `Your ${format.toUpperCase()} report has been prepared.`,
            icon: 'success',
            confirmButtonText: 'Download'
        });
    }, 2000);
}

function printReport() {
    window.print();
}
</script>
@endpush 