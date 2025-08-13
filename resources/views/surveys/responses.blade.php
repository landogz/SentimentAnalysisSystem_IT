<!-- Survey Information Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2">
                            <i class="fas fa-clipboard-list text-primary me-2"></i>
                            Survey Responses
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Teacher</small><br>
                                <strong class="text-primary">{{ $survey->teacher->name }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Subject</small><br>
                                <strong class="text-info">{{ $survey->subject->name }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Date</small><br>
                                <strong>{{ $survey->created_at->format('M d, Y g:i A') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex flex-column align-items-end">
                            <div class="mb-2">
                                <span class="badge {{ $survey->sentiment_badge_class }} fs-6 px-3 py-2">
                                    <i class="fas fa-heart me-1"></i>
                                    {{ ucfirst($survey->sentiment) }}
                                </span>
                            </div>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $survey->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $survey->rating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-2 fw-bold">{{ $survey->rating }}/5.0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-tabs nav-fill" id="surveyTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-chart-bar me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="part1-tab" data-bs-toggle="tab" data-bs-target="#part1" type="button" role="tab">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Part 1
                    <span class="badge bg-primary ms-1">{{ $part1Responses->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="part2-tab" data-bs-toggle="tab" data-bs-target="#part2" type="button" role="tab">
                    <i class="fas fa-chart-line me-2"></i>Part 2
                    <span class="badge bg-info ms-1">{{ $part2Responses->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="part3-tab" data-bs-toggle="tab" data-bs-target="#part3" type="button" role="tab">
                    <i class="fas fa-comments me-2"></i>Part 3
                    <span class="badge bg-success ms-1">{{ $part3Responses->count() }}</span>
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content" id="surveyTabsContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="card-title mb-4">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Survey Analysis Summary
                        </h6>
                        
                        <!-- Analysis Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="analysis-card analysis-card-primary">
                                    <div class="text-center p-3">
                                        <div class="analysis-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <h4 class="text-primary mb-1">{{ number_format($part1Average, 1) }}</h4>
                                        <p class="mb-0 fw-bold text-dark">Part 1: Instructor</p>
                                        <small class="text-muted">Evaluation Score</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="analysis-card analysis-card-info">
                                    <div class="text-center p-3">
                                        <div class="analysis-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <h4 class="text-info mb-1">{{ number_format($part2Average, 1) }}</h4>
                                        <p class="mb-0 fw-bold text-dark">Part 2: Difficulty</p>
                                        <small class="text-muted">Level Score</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="analysis-card analysis-card-success">
                                    <div class="text-center p-3">
                                        <div class="analysis-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <h4 class="text-success mb-1">{{ number_format($part3Score, 1) }}</h4>
                                        <p class="mb-0 fw-bold text-dark">Part 3: Comments</p>
                                        <small class="text-muted">Sentiment Score</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="analysis-card analysis-card-warning">
                                    <div class="text-center p-3">
                                        <div class="analysis-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h4 class="text-warning mb-1">{{ number_format($survey->rating, 1) }}</h4>
                                        <p class="mb-0 fw-bold text-dark">Overall Rating</p>
                                        <small class="text-muted">Combined Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h6 class="text-muted mb-3">Student Information</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Name</small><br>
                                            <strong>{{ $survey->student_name ?: 'Anonymous' }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Email</small><br>
                                            <strong>{{ $survey->student_email ?: 'Not provided' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h6 class="text-muted mb-3">Sentiment Analysis</h6>
                                    <div class="sentiment-display">
                                        <span class="badge {{ $survey->sentiment_badge_class }} fs-6 px-3 py-2">
                                            <i class="fas fa-heart me-1"></i>
                                            Overall Sentiment: {{ ucfirst($survey->sentiment) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Part 1 Tab -->
    <div class="tab-pane fade" id="part1" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Part 1: Instructor Evaluation
                            <span class="badge bg-light text-primary ms-2">{{ number_format($part1Average, 1) }}/5.0</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($part1Responses->count() > 0)
                            <div class="row">
                                @foreach($part1Responses as $response)
                                <div class="col-md-6 mb-3">
                                    <div class="response-card border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-primary">Q{{ $response->question->order_number }}</span>
                                            <span class="badge bg-secondary">{{ $response->answer }}/5</span>
                                        </div>
                                        <p class="mb-2 text-muted small">{{ $response->question->question_text }}</p>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $response->answer)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No Part 1 responses available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Part 2 Tab -->
    <div class="tab-pane fade" id="part2" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Part 2: Difficulty Level
                            <span class="badge bg-light text-info ms-2">{{ number_format($part2Average, 1) }}/5.0</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($part2Responses->count() > 0)
                            <div class="row">
                                @foreach($part2Responses as $response)
                                <div class="col-md-6 mb-3">
                                    <div class="response-card border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-info">Q{{ $response->question->order_number }}</span>
                                            <span class="badge bg-secondary">{{ $response->answer }}/5</span>
                                        </div>
                                        <p class="mb-2 text-muted small">{{ $response->question->question_text }}</p>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $response->answer)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No Part 2 responses available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Part 3 Tab -->
    <div class="tab-pane fade" id="part3" role="tabpanel">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-comments me-2"></i>
                            Part 3: Open Comments
                            <span class="badge bg-light text-success ms-2">Sentiment: {{ ucfirst($part3Sentiment) }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($part3Responses->count() > 0)
                            @foreach($part3Responses as $response)
                            <div class="comment-card border rounded p-4 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-success">Q{{ $response->question->order_number }}</span>
                                    <span class="badge bg-secondary">Comment</span>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Question:</h6>
                                    <p class="mb-0">{{ $response->question->question_text }}</p>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-2">Response:</h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $response->answer ?: 'No response provided' }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No Part 3 responses available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Feedback -->
@if($survey->feedback_text)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-comment-dots me-2"></i>
                    Additional Feedback
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="bg-light p-3 rounded">
                    {{ $survey->feedback_text }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
/* Professional Tab Styling */
.nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 0;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: #dee2e6;
    color: #495057;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #0d6efd;
    color: #0d6efd;
    background-color: transparent;
}

/* Analysis Cards */
.analysis-card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background-color: #ffffff;
    border: 1px solid #e9ecef;
}

.analysis-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.analysis-card-primary {
    border-left: 4px solid #0d6efd;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.analysis-card-info {
    border-left: 4px solid #0dcaf0;
    background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
}

.analysis-card-success {
    border-left: 4px solid #198754;
    background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);
}

.analysis-card-warning {
    border-left: 4px solid #ffc107;
    background: linear-gradient(135deg, #fffbf0 0%, #ffffff 100%);
}

.analysis-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
}

/* Response Cards */
.response-card {
    background-color: #f8f9fa;
    transition: transform 0.2s ease;
}

.response-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.comment-card {
    background-color: #f8f9fa;
    transition: transform 0.2s ease;
}

.comment-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Rating Display */
.rating-display {
    font-size: 0.9rem;
}

.rating-display .fas.fa-star,
.rating-display .far.fa-star {
    font-size: 0.8rem;
}

/* Info Sections */
.info-section {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.sentiment-display {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

/* Card Styling */
.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border: none;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* Badge Styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    
    .analysis-card {
        margin-bottom: 1rem;
    }
    
    .response-card {
        margin-bottom: 1rem;
    }
}
</style>
