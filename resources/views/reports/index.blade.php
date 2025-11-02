@extends('layouts.app')

@section('title', 'Reports - Student Feedback System')

@section('page-title', 'Reports & Analytics')
@section('icon', 'chart-bar')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row">
    <!-- Filter Panel -->
    <div class="col-12">
        <div class="card card-outline" style="border-color: var(--light-blue);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-2"></i>
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
                                <button type="submit" class="btn btn-block" style="background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%); color: white; border: none;">
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
        <div class="small-box" style="background: linear-gradient(135deg, #8FCFA8 0%, #7bb894 100%); color: #494850;">
            <div class="inner">
                <h3 id="totalSurveys">{{ number_format($totalSurveys) }}</h3>
                <p>Total Surveys</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #98AAE7 0%, #7a8cd6 100%); color: #494850;">
            <div class="inner">
                <h3 id="avgRating">{{ number_format($averageRating, 1) }}</h3>
                <p>Average Rating</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #F5B445 0%, #e4a23d 100%); color: #494850;">
            <div class="inner">
                <h3 id="positiveSentiment">{{ $sentimentStats['positive'] }}</h3>
                <p>Positive Feedback</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-up"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #F16E70 0%, #e55a5c 100%); color: #494850;">
            <div class="inner">
                <h3 id="negativeSentiment">{{ $sentimentStats['negative'] }}</h3>
                <p>Negative Feedback</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-down"></i>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-lg-6">
        <div class="card card-outline" style="border-color: var(--light-green);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Sentiment Analysis
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <canvas id="sentimentPieChart" style="height: 200px;"></canvas>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-success mb-2">
                                <i class="fas fa-thumbs-up fa-2x"></i>
                            </div>
                            <h5 class="text-success" id="positiveCount">{{ $sentimentStats['positive'] }}</h5>
                            <small class="text-muted">Positive</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-minus-circle fa-2x"></i>
                            </div>
                            <h5 class="text-warning" id="neutralCount">{{ $sentimentStats['neutral'] }}</h5>
                            <small class="text-muted">Neutral</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-danger mb-2">
                                <i class="fas fa-thumbs-down fa-2x"></i>
                            </div>
                            <h5 class="text-danger" id="negativeCount">{{ $sentimentStats['negative'] }}</h5>
                            <small class="text-muted">Negative</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-outline" style="border-color: var(--golden-orange);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--golden-orange) 0%, #e4a23d 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Rating Distribution
                </h3>
            </div>
            <div class="card-body">
                <canvas id="ratingChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Course and Year Distribution Charts -->
    <div class="col-lg-6">
        <div class="card card-outline" style="border-color: var(--light-blue);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Survey Distribution by Course
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <canvas id="coursePieChart" style="height: 200px;"></canvas>
                    </div>
                </div>
                <div class="row mt-3">
                    @if(count($courseChartData['labels']) > 0)
                        @php
                            $courseCount = count($courseChartData['labels']);
                            $colSize = $courseCount == 1 ? 12 : ($courseCount == 2 ? 6 : ($courseCount == 3 ? 4 : 3));
                        @endphp
                        @foreach($courseChartData['labels'] as $index => $course)
                            <div class="col-{{ $colSize }} col-md-{{ $colSize }}">
                                <div class="text-center">
                                    <div class="mb-2" style="color: var(--light-blue);">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                    <h5 id="courseCount{{ $index }}" style="color: var(--light-blue);">{{ $courseChartData['data'][$index] }}</h5>
                                    <small class="text-muted">{{ $course }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-muted text-center">No course data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-outline" style="border-color: var(--light-green);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Survey Distribution by Year Level
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <canvas id="yearPieChart" style="height: 200px;"></canvas>
                    </div>
                </div>
                <div class="row mt-3">
                    @if(count($yearChartData['labels']) > 0)
                        @foreach($yearChartData['labels'] as $index => $year)
                            <div class="col-3 col-md-3">
                                <div class="text-center">
                                    <div class="mb-2" style="color: var(--light-green);">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                    <h5 id="yearCount{{ $index }}" style="color: var(--light-green);">{{ $yearChartData['data'][$index] }}</h5>
                                    <small class="text-muted">{{ $year }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-muted text-center">No year data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <!-- <div class="col-lg-6">
        <div class="card card-outline" style="border-color: var(--light-green);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-trophy mr-2"></i>
                    Top Rated Teachers
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>Department</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topTeachers as $teacher)
                                <tr>
                                    <td>
                                        <strong>{{ $teacher->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: var(--light-blue); color: white;">{{ $teacher->department }}</span>
                                    </td>
                                    <td>
                                        @if($teacher->surveys_avg_rating)
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $teacher->surveys_avg_rating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $teacher->surveys_avg_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted">{{ number_format($teacher->surveys_avg_rating, 1) }}</small>
                                        @else
                                            <span class="text-muted">No ratings</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: var(--dark-gray); color: white;">{{ $teacher->surveys_count }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No teachers with surveys yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-outline" style="border-color: var(--light-blue);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-book mr-2"></i>
                    Top Rated Subjects
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Teachers</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSubjects as $subject)
                                <tr>
                                    <td>
                                        <strong>{{ $subject->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $subject->subject_code }}</small>
                                    </td>
                                    <td>
                                        @if($subject->teachers->count() > 0)
                                            @foreach($subject->teachers as $teacher)
                                                <span class="badge" style="background-color: var(--light-blue); color: white;">
                                                    {{ $teacher->name }}
                                                    @if($teacher->pivot->is_primary)
                                                        <i class="fas fa-star" style="color: var(--golden-orange);"></i>
                                                    @endif
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No teachers assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subject->surveys_avg_rating)
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $subject->surveys_avg_rating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $subject->surveys_avg_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted">{{ number_format($subject->surveys_avg_rating, 1) }}</small>
                                        @else
                                            <span class="text-muted">No ratings</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: var(--dark-gray); color: white;">{{ $subject->surveys_count }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No subjects with surveys yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Export Options -->
    <!-- <div class="col-12">
        <div class="card card-outline" style="border-color: var(--dark-gray);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--dark-gray) 0%, #5a5a6a 100%); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-download mr-2"></i>
                    Export Reports
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block" onclick="exportReport('pdf')" style="background: linear-gradient(135deg, var(--light-green) 0%, #7bb894 100%); color: white; border: none;">
                            <i class="fas fa-file-pdf mr-1"></i>Export PDF
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block" onclick="exportReport('excel')" style="background: linear-gradient(135deg, var(--light-blue) 0%, #7a8cd6 100%); color: white; border: none;">
                            <i class="fas fa-file-excel mr-1"></i>Export Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block" onclick="exportReport('csv')" style="background: linear-gradient(135deg, var(--golden-orange) 0%, #e4a23d 100%); color: white; border: none;">
                            <i class="fas fa-file-csv mr-1"></i>Export CSV
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block" onclick="printReport()" style="background: linear-gradient(135deg, var(--coral-pink) 0%, #e55a5c 100%); color: white; border: none;">
                            <i class="fas fa-print mr-1"></i>Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection

@push('scripts')
<script>
let sentimentChart, ratingChart, courseChart, yearChart;

$(document).ready(function() {
    // Initialize charts
    initializeCharts();
    
    // Form submission
    $('#reportFilterForm').submit(function(e) {
        e.preventDefault();
        loadReportData();
    });
});

function initializeCharts() {
    // Sentiment Pie Chart
    const sentimentCtx = document.getElementById('sentimentPieChart').getContext('2d');
    sentimentChart = new Chart(sentimentCtx, {
        type: 'pie',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [
                    {{ $sentimentStats['positive'] }},
                    {{ $sentimentStats['neutral'] }},
                    {{ $sentimentStats['negative'] }}
                ],
                backgroundColor: ['#8FCFA8', '#F5B445', '#F16E70'], // Green, Yellow, Red
                borderWidth: 0
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

    // Rating Chart - Initialize with data from controller
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    
    // Prepare rating distribution data
    const ratingData = [0, 0, 0, 0, 0]; // Initialize with zeros for all 5 ratings
    @if(isset($ratingDistribution))
        @foreach($ratingDistribution as $item)
            @if($item->rating_group >= 1 && $item->rating_group <= 5)
                ratingData[{{ $item->rating_group - 1 }}] = {{ $item->count }};
            @endif
        @endforeach
    @endif
    
    ratingChart = new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ['1★', '2★', '3★', '4★', '5★'],
            datasets: [{
                label: 'Number of Surveys',
                data: ratingData,
                backgroundColor: [
                    '#F16E70', // Coral Pink for 1 star
                    '#F5B445', // Golden Orange for 2 stars
                    '#98AAE7', // Light Blue for 3 stars
                    '#8FCFA8', // Light Green for 4 stars
                    '#494850'  // Dark Gray for 5 stars
                ],
                borderColor: [
                    '#F16E70',
                    '#F5B445',
                    '#98AAE7',
                    '#8FCFA8',
                    '#494850'
                ],
                borderWidth: 1
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
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' surveys';
                        }
                    }
                }
            }
        }
    });

    // Course Distribution Pie Chart
    const courseCanvas = document.getElementById('coursePieChart');
    if (courseCanvas && typeof Chart !== 'undefined') {
        const courseData = @json($courseChartData);
        if (courseData && courseData.labels && courseData.labels.length > 0 && courseData.data && courseData.data.length > 0) {
            try {
                const courseCtx = courseCanvas.getContext('2d');
                courseChart = new Chart(courseCtx, {
                    type: 'pie',
                    data: {
                        labels: courseData.labels,
                        datasets: [{
                            data: courseData.data,
                            backgroundColor: [
                                '#98AAE7',
                                '#8FCFA8',
                                '#F5B445',
                                '#F16E70'
                            ],
                            borderWidth: 0
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
            } catch (e) {
                console.error('Error initializing course chart:', e);
            }
        }
    }

    // Year Distribution Pie Chart
    const yearCanvas = document.getElementById('yearPieChart');
    if (yearCanvas && typeof Chart !== 'undefined') {
        const yearData = @json($yearChartData);
        if (yearData && yearData.labels && yearData.labels.length > 0 && yearData.data && yearData.data.length > 0) {
            try {
                const yearCtx = yearCanvas.getContext('2d');
                yearChart = new Chart(yearCtx, {
                    type: 'pie',
                    data: {
                        labels: yearData.labels,
                        datasets: [{
                            data: yearData.data,
                            backgroundColor: [
                                '#98AAE7',
                                '#8FCFA8',
                                '#F5B445',
                                '#F16E70'
                            ],
                            borderWidth: 0
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
            } catch (e) {
                console.error('Error initializing year chart:', e);
            }
        }
    }
}

function loadReportData() {
    const formData = $('#reportFilterForm').serialize();
    
    $.get('{{ route("reports.filtered-stats") }}', formData, function(data) {
        // Update statistics cards
        $('#totalSurveys').text(data.total_surveys);
        $('#avgRating').text(data.average_rating);
        $('#positiveSentiment').text(data.sentiment_stats.positive);
        $('#negativeSentiment').text(data.sentiment_stats.negative);
        
        // Update sentiment pie chart
        sentimentChart.data.datasets[0].data = [
            data.sentiment_stats.positive,
            data.sentiment_stats.neutral,
            data.sentiment_stats.negative
        ];
        sentimentChart.update();
        
        // Update sentiment statistics display
        $('#positiveCount').text(data.sentiment_stats.positive);
        $('#neutralCount').text(data.sentiment_stats.neutral);
        $('#negativeCount').text(data.sentiment_stats.negative);
        
        // Update course chart if it exists and has data
        if (data.course_chart && data.course_chart.labels && data.course_chart.labels.length > 0) {
            if (courseChart) {
                try {
                    courseChart.data.labels = data.course_chart.labels;
                    courseChart.data.datasets[0].data = data.course_chart.data;
                    courseChart.update();
                } catch (e) {
                    console.error('Error updating course chart:', e);
                }
            }
            
            // Update course counts
            data.course_chart.labels.forEach((course, index) => {
                const countElement = $('#courseCount' + index);
                if (countElement.length) {
                    countElement.text(data.course_chart.data[index]);
                }
            });
        }
        
        // Update year chart if it exists and has data
        if (data.year_chart && data.year_chart.labels && data.year_chart.labels.length > 0) {
            if (yearChart) {
                try {
                    yearChart.data.labels = data.year_chart.labels;
                    yearChart.data.datasets[0].data = data.year_chart.data;
                    yearChart.update();
                } catch (e) {
                    console.error('Error updating year chart:', e);
                }
            }
            
            // Update year counts
            data.year_chart.labels.forEach((year, index) => {
                const countElement = $('#yearCount' + index);
                if (countElement.length) {
                    countElement.text(data.year_chart.data[index]);
                }
            });
        }
        
        // Load rating distribution
        loadRatingDistribution();
    });
}

function loadRatingDistribution() {
    const formData = $('#reportFilterForm').serialize();
    
    $.get('{{ route("reports.rating-distribution") }}', formData, function(data) {
        ratingChart.data.datasets[0].data = data;
        ratingChart.update();
    });
}

function exportReport(format) {
    const formData = $('#reportFilterForm').serialize();
    
    Swal.fire({
        title: 'Generating Report',
        text: `Preparing ${format.toUpperCase()} report...`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '{{ route("reports.export") }}',
        method: 'POST',
        data: formData + '&format=' + format,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                if (format === 'csv') {
                    // For CSV, create a download link
                    const blob = new Blob([response], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `prmsu-ccit-feedback-report-${new Date().toISOString().slice(0,10)}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    Swal.fire({
                        title: 'Export Complete!',
                        text: 'CSV report has been downloaded successfully.',
                        icon: 'success',
                        confirmButtonColor: '#8FCFA8'
                    });
                } else {
                    // For PDF and Excel, show success message
                    Swal.fire({
                        title: 'Export Complete!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Download',
                        confirmButtonColor: '#8FCFA8',
                        showCancelButton: true,
                        cancelButtonText: 'Close'
                    }).then((result) => {
                        if (result.isConfirmed && response.download_url) {
                            window.open(response.download_url, '_blank');
                        }
                    });
                }
            } else {
                Swal.fire({
                    title: 'Export Failed',
                    text: response.message || 'Failed to generate report.',
                    icon: 'error',
                    confirmButtonColor: '#F16E70'
                });
            }
        },
        error: function(xhr, status, error) {
            let errorMessage = 'Failed to generate report.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 419) {
                errorMessage = 'Session expired. Please refresh the page and try again.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please try again later.';
            }
            
            Swal.fire({
                title: 'Export Failed',
                text: errorMessage,
                icon: 'error',
                confirmButtonColor: '#F16E70'
            });
        }
    });
}

function printReport() {
    window.print();
}
</script>
@endpush 