<!-- Survey Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Survey Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Teacher:</strong><br>
                        <span class="text-primary">{{ $survey->teacher->name }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Subject:</strong><br>
                        <span class="text-info">{{ $survey->subject->name }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Rating:</strong><br>
                        <span class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $survey->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - 0.5 <= $survey->rating)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="ms-1">{{ $survey->rating }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Sentiment:</strong><br>
                        <span class="badge {{ $survey->sentiment_badge_class }}">
                            {{ $survey->sentiment_label }}
                        </span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <strong>Date:</strong><br>
                        <span class="text-muted">{{ $survey->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Student:</strong><br>
                        <span class="text-muted">{{ $survey->student_name ?: 'Anonymous' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        <span class="text-muted">{{ $survey->student_email ?: 'Not provided' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Survey Responses -->
<div class="row">
    <!-- Option Questions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-check-circle me-2 text-primary"></i>Rating Questions (1-5 Scale)
                </h6>
            </div>
            <div class="card-body">
                @if($optionResponses->count() > 0)
                    @foreach($optionResponses as $response)
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong class="text-primary">{{ $loop->iteration }}.</strong>
                            <span class="badge bg-primary">{{ $response->answer }}/5</span>
                        </div>
                        <p class="mb-2">{{ $response->question->question_text }}</p>
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
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>No rating questions answered</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Comment Questions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-comment me-2 text-success"></i>Comment Questions
                </h6>
            </div>
            <div class="card-body">
                @if($commentResponses->count() > 0)
                    @foreach($commentResponses as $response)
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong class="text-success">{{ $loop->iteration }}.</strong>
                            <span class="badge bg-success">Comment</span>
                        </div>
                        <p class="mb-2"><strong>Question:</strong></p>
                        <p class="mb-3 text-muted">{{ $response->question->question_text }}</p>
                        <p class="mb-0"><strong>Answer:</strong></p>
                        <div class="bg-light p-2 rounded">
                            {{ $response->answer ?: 'No response provided' }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-comment-slash fa-2x mb-2"></i>
                        <p>No comment questions answered</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Feedback -->
@if($survey->feedback_text)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-comments me-2 text-info"></i>Additional Feedback
                </h6>
            </div>
            <div class="card-body">
                <div class="bg-light p-3 rounded">
                    {{ $survey->feedback_text }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.rating-display {
    font-size: 0.9rem;
}

.rating-display .fas.fa-star,
.rating-display .far.fa-star {
    font-size: 0.8rem;
}

.card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.border.rounded {
    border-color: #e9ecef !important;
    background-color: #f8f9fa;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
