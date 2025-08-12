@extends('layouts.app')

@section('title', 'Teacher Details - Student Feedback System')

@section('page-title', 'Teacher Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Teachers</a></li>
<li class="breadcrumb-item active">{{ $teacher->name }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Teacher Information -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Teacher Information
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-chalkboard-teacher fa-4x text-primary"></i>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $teacher->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $teacher->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Department:</strong></td>
                        <td><span class="badge badge-info">{{ $teacher->department }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $teacher->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($teacher->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Joined:</strong></td>
                        <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>

                @if($teacher->bio)
                    <hr>
                    <h6><strong>Bio:</strong></h6>
                    <p class="text-muted">{{ $teacher->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="col-lg-8">
        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $teacher->total_surveys }}</h3>
                        <p>Total Surveys</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($teacher->average_rating, 1) }}</h3>
                        <p>Average Rating</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $teacher->subjects_count ?? 0 }}</h3>
                        <p>Subjects Taught</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sentiment Analysis -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Sentiment Analysis
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <canvas id="teacherSentimentPieChart" style="height: 200px;"></canvas>
                    </div>
                </div>
                <div class="row mt-3 text-center">
                    <div class="col-4">
                        <div class="text-success">
                            <i class="fas fa-thumbs-up fa-2x"></i>
                            <h5>{{ $sentimentStats['positive']['count'] }}</h5>
                            <small>Positive ({{ $sentimentStats['positive']['percentage'] }}%)</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-warning">
                            <i class="fas fa-minus-circle fa-2x"></i>
                            <h5>{{ $sentimentStats['neutral']['count'] }}</h5>
                            <small>Neutral ({{ $sentimentStats['neutral']['percentage'] }}%)</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-danger">
                            <i class="fas fa-thumbs-down fa-2x"></i>
                            <h5>{{ $sentimentStats['negative']['count'] }}</h5>
                            <small>Negative ({{ $sentimentStats['negative']['percentage'] }}%)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subjects Taught -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-1"></i>
                    Subjects Taught
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Description</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacher->subjects as $subject)
                            <tr>
                                <td><strong>{{ $subject->subject_code }}</strong></td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ Str::limit($subject->description, 50) }}</td>
                                <td>
                                    @if($subject->surveys_avg_rating)
                                        <span class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $subject->surveys_avg_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $subject->surveys_avg_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="ml-1">{{ number_format($subject->surveys_avg_rating, 1) }}</span>
                                    @else
                                        <span class="text-muted">No ratings</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-secondary">{{ $subject->surveys_count }}</span></td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No subjects assigned yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Surveys -->
<div class="row">
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
                                <th>Subject</th>
                                <th>Rating</th>
                                <th>Sentiment</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacher->surveys->take(10) as $survey)
                            <tr class="survey-row" data-survey-id="{{ $survey->id }}" style="cursor: pointer;">
                                <td>{{ $survey->created_at->format('M d, Y') }}</td>
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
                                <td colspan="5" class="text-center">No surveys submitted yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Survey Responses Modal -->
<div class="modal fade" id="surveyResponsesModal" tabindex="-1" aria-labelledby="surveyResponsesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="surveyResponsesModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Survey Responses
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="surveyResponsesContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading survey responses...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Teacher Sentiment Pie Chart
    const teacherSentimentCtx = document.getElementById('teacherSentimentPieChart').getContext('2d');
    new Chart(teacherSentimentCtx, {
        type: 'pie',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [
                    {{ $sentimentStats['positive']['count'] }}, 
                    {{ $sentimentStats['neutral']['count'] }}, 
                    {{ $sentimentStats['negative']['count'] }}
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

    // Survey row click handler
    $('.survey-row').click(function() {
        const surveyId = $(this).data('survey-id');
        const modal = $('#surveyResponsesModal');
        
        // Show modal with loading state
        modal.modal('show');
        
        // Load survey responses via AJAX
        $.ajax({
            url: `/surveys/${surveyId}/responses`,
            method: 'GET',
            success: function(response) {
                $('#surveyResponsesContent').html(response);
            },
            error: function(xhr) {
                $('#surveyResponsesContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load survey responses. Please try again.
                    </div>
                `);
            }
        });
    });

    // Hover effect for survey rows
    $('.survey-row').hover(
        function() {
            $(this).addClass('table-hover');
        },
        function() {
            $(this).removeClass('table-hover');
        }
    );
});
</script>
@endpush 