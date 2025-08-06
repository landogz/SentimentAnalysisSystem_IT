@extends('layouts.app')

@section('title', 'Subject Details - Student Feedback System')

@section('page-title', 'Subject Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
<li class="breadcrumb-item active">{{ $subject->name }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Subject Information -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-1"></i>
                    Subject Information
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-book fa-4x text-primary"></i>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Subject Code:</strong></td>
                        <td><span class="badge badge-info">{{ $subject->subject_code }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $subject->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Teachers:</strong></td>
                        <td>
                            @if($subject->teachers->count() > 0)
                                @foreach($subject->teachers as $teacher)
                                    <span class="badge badge-info">
                                        {{ $teacher->name }}
                                        @if($teacher->pivot->is_primary)
                                            <i class="fas fa-star text-warning"></i>
                                        @endif
                                    </span>
                                    <br><small class="text-muted">{{ $teacher->department }}</small>
                                    @if(!$loop->last)<br>@endif
                                @endforeach
                            @else
                                <span class="text-muted">No teachers assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($subject->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $subject->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>

                @if($subject->description)
                    <hr>
                    <h6><strong>Description:</strong></h6>
                    <p class="text-muted">{{ $subject->description }}</p>
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
                        <h3>{{ $subject->total_surveys }}</h3>
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
                        <h3>{{ number_format($subject->average_rating, 1) }}</h3>
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
                        <h3>{{ $sentimentStats['total'] }}</h3>
                        <p>Total Feedback</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comments"></i>
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
                        <canvas id="subjectSentimentPieChart" style="height: 200px;"></canvas>
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

<!-- Assigned Teachers -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chalkboard-teacher mr-1"></i>
                    Assigned Teachers
                </h3>
            </div>
            <div class="card-body">
                @if($subject->teachers->count() > 0)
                    @foreach($subject->teachers as $teacher)
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h5>
                                    {{ $teacher->name }}
                                    @if($teacher->pivot->is_primary)
                                        <span class="badge badge-warning">Primary</span>
                                    @endif
                                </h5>
                                <p class="text-muted mb-2">{{ $teacher->department }}</p>
                                @if($teacher->bio)
                                    <p class="text-muted">{{ $teacher->bio }}</p>
                                @endif
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <small class="text-muted">Email</small>
                                        <p>{{ $teacher->email }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Phone</small>
                                        <p>{{ $teacher->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Status</small>
                                        <p>
                                            @if($teacher->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="border rounded p-3">
                                    <h6>Teacher Rating</h6>
                                    @if($teacher->surveys_avg_rating)
                                        <div class="rating-stars mb-2">
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
                                        <h4>{{ number_format($teacher->surveys_avg_rating, 1) }}</h4>
                                        <small class="text-muted">{{ $teacher->surveys_count }} surveys</small>
                                    @else
                                        <p class="text-muted">No ratings yet</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                @else
                    <p class="text-muted text-center">No teachers assigned to this subject.</p>
                @endif
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
                                <th>Student</th>
                                <th>Rating</th>
                                <th>Sentiment</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subject->surveys->take(10) as $survey)
                            <tr>
                                <td>{{ $survey->created_at->format('M d, Y') }}</td>
                                <td>{{ $survey->student_name ?? 'Anonymous' }}</td>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Subject Sentiment Pie Chart
    const subjectSentimentCtx = document.getElementById('subjectSentimentPieChart').getContext('2d');
    new Chart(subjectSentimentCtx, {
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
});
</script>
@endpush 