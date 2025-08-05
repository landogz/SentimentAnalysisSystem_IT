@extends('layouts.app')

@section('title', 'Dashboard - Student Feedback System')

@section('page-title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalSurveys) }}</h3>
                <p>Total Surveys</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalTeachers) }}</h3>
                <p>Total Teachers</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="{{ route('teachers.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($totalSubjects) }}</h3>
                <p>Total Subjects</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('subjects.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($averageRating, 1) }}</h3>
                <p>Average Rating</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sentiment Statistics -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Sentiment Analysis
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-success">
                                <i class="fas fa-thumbs-up fa-2x"></i>
                            </div>
                            <h4>{{ $sentimentStats['positive'] }}</h4>
                            <small>Positive</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-warning">
                                <i class="fas fa-minus-circle fa-2x"></i>
                            </div>
                            <h4>{{ $sentimentStats['neutral'] }}</h4>
                            <small>Neutral</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-danger">
                                <i class="fas fa-thumbs-down fa-2x"></i>
                            </div>
                            <h4>{{ $sentimentStats['negative'] }}</h4>
                            <small>Negative</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Rated Teachers -->
    <div class="col-lg-8">
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
                        <tbody>
                            @forelse($topTeachers as $teacher)
                            <tr>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->department }}</td>
                                <td>
                                    <span class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $teacher->surveys_avg_rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $teacher->surveys_avg_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span class="ml-1">{{ number_format($teacher->surveys_avg_rating, 1) }}</span>
                                </td>
                                <td>{{ $teacher->surveys_count }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No teachers with surveys yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Charts -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Monthly Survey Trends
                </h3>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

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
</div>

<div class="row">
    <!-- Recent Surveys -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Recent Surveys
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Teacher</th>
                                <th>Subject</th>
                                <th>Rating</th>
                                <th>Sentiment</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSurveys as $survey)
                            <tr>
                                <td>{{ $survey->created_at->format('M d, Y') }}</td>
                                <td>{{ $survey->teacher->name }}</td>
                                <td>{{ $survey->subject->name }}</td>
                                <td>
                                    <span class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $survey->rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $survey->rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span class="ml-1">{{ $survey->rating }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $survey->sentiment_badge_class }}">
                                        {{ $survey->sentiment_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($survey->feedback_text)
                                        <span class="text-muted">{{ Str::limit($survey->feedback_text, 50) }}</span>
                                    @else
                                        <span class="text-muted">No feedback</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No surveys submitted yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyTrends);
    
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => {
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return monthNames[item.month - 1];
            }),
            datasets: [{
                label: 'Surveys',
                data: monthlyData.map(item => item.count),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.1
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

    // Sentiment Chart
    const sentimentCtx = document.getElementById('sentimentChart').getContext('2d');
    const sentimentChart = new Chart(sentimentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [
                    {{ $sentimentStats['positive'] }},
                    {{ $sentimentStats['neutral'] }},
                    {{ $sentimentStats['negative'] }}
                ],
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
});
</script>
@endpush 