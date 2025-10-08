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
                    All Surveys
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="searchInput">Search:</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search surveys...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sentimentFilter">Sentiment:</label>
                            <select class="form-control" id="sentimentFilter">
                                <option value="">All</option>
                                <option value="positive">Positive</option>
                                <option value="negative">Negative</option>
                                <option value="neutral">Neutral</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ratingMin">Min Rating:</label>
                            <select class="form-control" id="ratingMin">
                                <option value="">Any</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ratingMax">Max Rating:</label>
                            <select class="form-control" id="ratingMax">
                                <option value="">Any</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-primary" id="applyFilters">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                                <button type="button" class="btn btn-secondary" id="clearFilters">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- DataTable -->
                <div class="table-responsive">
                    <table class="table table-striped" id="surveysTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Teacher</th>
                                <th>Rating</th>
                                <th>Sentiment</th>
                                <th>Feedback</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
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

    // Initialize DataTable
    var surveysTable = $('#surveysTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("subjects.surveys-ajax", $subject->id) }}',
            type: 'GET',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.sentiment = $('#sentimentFilter').val();
                d.rating_min = $('#ratingMin').val();
                d.rating_max = $('#ratingMax').val();
            }
        },
        columns: [
            { data: 'date', name: 'created_at' },
            { data: 'student', name: 'student_name' },
            { data: 'teacher', name: 'teacher.name' },
            { data: 'rating', name: 'rating', orderable: false },
            { data: 'sentiment', name: 'sentiment', orderable: false },
            { data: 'feedback', name: 'feedback_text', orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: "Loading surveys...",
            emptyTable: "No surveys found for this subject.",
            zeroRecords: "No surveys match your search criteria."
        },
        dom: 'Bfrtip'
    });

    // Apply filters button
    $('#applyFilters').click(function() {
        surveysTable.ajax.reload();
    });

    // Clear filters button
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#sentimentFilter').val('');
        $('#ratingMin').val('');
        $('#ratingMax').val('');
        surveysTable.ajax.reload();
    });

    // Search on Enter key
    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            surveysTable.ajax.reload();
        }
    });

    // Survey view button click handler
    $(document).on('click', '.view-survey', function() {
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
});
</script>
@endpush 