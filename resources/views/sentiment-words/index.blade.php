@extends('layouts.app')

@section('title', 'Sentiment Words Management')
@section('icon', 'brain')
@section('page-title', 'Sentiment Words Management')

@section('breadcrumb')
<li class="breadcrumb-item active">Sentiment Words</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Sentiment Words Management</h4>
                <a href="{{ route('sentiment-words.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Word
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <form method="GET" action="{{ route('sentiment-words.index') }}" class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search words..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="positive" {{ request('type') == 'positive' ? 'selected' : '' }}>Positive</option>
                            <option value="negative" {{ request('type') == 'negative' ? 'selected' : '' }}>Negative</option>
                            <option value="neutral" {{ request('type') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="language" class="form-select">
                            <option value="">All Languages</option>
                            <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="tl" {{ request('language') == 'tl' ? 'selected' : '' }}>Tagalog</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="active" class="form-select">
                            <option value="">All Status</option>
                            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search & Filter
                        </button>
                        <a href="{{ route('sentiment-words.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>

                <!-- Statistics -->
                <div class="row mb-4">
                    @php
                        $totalWords = \App\Models\SentimentWord::count();
                        $positiveWords = \App\Models\SentimentWord::where('type', 'positive')->count();
                        $negativeWords = \App\Models\SentimentWord::where('type', 'negative')->count();
                        $neutralWords = \App\Models\SentimentWord::where('type', 'neutral')->count();
                    @endphp
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5>{{ $totalWords }}</h5>
                                <small>Total Words</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>{{ $positiveWords }}</h5>
                                <small>Positive Words</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h5>{{ $negativeWords }}</h5>
                                <small>Negative Words</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>{{ $neutralWords }}</h5>
                                <small>Neutral Words</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Results Info -->
                @if(request('search') || request('type') || request('language') || request('active'))
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-search"></i>
                        <strong>Search Results:</strong> 
                        Found {{ $words->total() }} word(s) 
                        @if(request('search'))
                            matching "{{ request('search') }}"
                        @endif
                        @if(request('type') || request('language') || request('active'))
                            with applied filters
                        @endif
                        <a href="{{ route('sentiment-words.index') }}" class="float-end">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                    </div>
                @endif



                <!-- Words Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Word</th>
                                <th>Negation</th>
                                <th>Type</th>
                                <th>Score</th>
                                <th>Language</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($words as $word)
                                <tr>
                                    <td>
                                        <strong>{{ $word->word }}</strong>
                                    </td>
                                    <td>
                                        @if($word->negation)
                                            <span class="badge bg-secondary">{{ $word->negation }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $word->type == 'positive' ? 'success' : ($word->type == 'negative' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($word->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $word->score > 0 ? 'success' : ($word->score < 0 ? 'danger' : 'warning') }}">
                                            {{ $word->score }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ strtoupper($word->language) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $word->is_active ? 'success' : 'danger' }}">
                                            {{ $word->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $word->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sentiment-words.show', $word) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sentiment-words.edit', $word) }}" 
                                               class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteWord({{ $word->id }}, '{{ $word->word }}')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No sentiment words found</h5>
                                            <p class="text-muted">Start by adding your first sentiment word.</p>
                                            <a href="{{ route('sentiment-words.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add First Word
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($words->hasPages())
                    <div class="mt-3">
                        {{ $words->appends(request()->query())->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Test Analysis Modal -->
<div class="modal fade" id="testAnalysisModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Sentiment Analysis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="testText" class="form-label">Text to Analyze</label>
                    <textarea class="form-control" id="testText" rows="3" placeholder="Enter text to analyze..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="testLanguage" class="form-label">Language</label>
                        <select class="form-select" id="testLanguage">
                            <option value="en">English</option>
                            <option value="tl">Tagalog</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="testTranslate">
                            <label class="form-check-label" for="testTranslate">
                                Translate to English for analysis
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" id="analyzeBtn">
                        <i class="fas fa-search"></i> Analyze
                    </button>
                </div>
                
                <div id="analysisResults" class="mt-4" style="display: none;">
                    <h6>Analysis Results:</h6>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Sentiment:</strong> <span id="resultSentiment"></span></p>
                                    <p><strong>Score:</strong> <span id="resultScore"></span></p>
                                    <p><strong>Rating:</strong> <span id="resultRating"></span>/5</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Positive Score:</strong> <span id="resultPositive"></span></p>
                                    <p><strong>Negative Score:</strong> <span id="resultNegative"></span></p>
                                    <p><strong>Neutral Score:</strong> <span id="resultNeutral"></span></p>
                                </div>
                            </div>
                            <div id="translationInfo" style="display: none;">
                                <hr>
                                <p><strong>Original Text:</strong> <span id="originalText"></span></p>
                                <p><strong>Translated Text:</strong> <span id="translatedText"></span></p>
                            </div>
                            <div id="matchedWords" style="display: none;">
                                <hr>
                                <h6>Matched Words:</h6>
                                <div id="matchedWordsList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the word "<strong id="deleteWordName"></strong>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality enhancements
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $(this).closest('form').submit();
        }
    });
    
    // Auto-submit form when search field changes (with debounce)
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            $(this).closest('form').submit();
        }, 500); // 500ms delay
    });
    
    // Test analysis functionality
    $('#analyzeBtn').click(function() {
        const text = $('#testText').val();
        const language = $('#testLanguage').val();
        const translate = $('#testTranslate').is(':checked');
        
        if (!text.trim()) {
            Swal.fire('Error', 'Please enter text to analyze', 'error');
            return;
        }
        
        $.ajax({
            url: '{{ route("sentiment-words.test-analysis") }}',
            method: 'POST',
            data: {
                text: text,
                language: language,
                translate: translate
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    displayAnalysisResults(response.data);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to analyze text', 'error');
            }
        });
    });
    
    // Display analysis results
    function displayAnalysisResults(data) {
        $('#resultSentiment').text(data.sentiment);
        $('#resultScore').text(data.score);
        $('#resultRating').text(data.rating);
        $('#resultPositive').text(data.positive_score);
        $('#resultNegative').text(data.negative_score);
        $('#resultNeutral').text(data.neutral_score);
        
        // Show translation info if available
        if (data.translation_success && data.translated_text) {
            $('#originalText').text(data.original_text);
            $('#translatedText').text(data.translated_text);
            $('#translationInfo').show();
        } else {
            $('#translationInfo').hide();
        }
        
        // Show matched words
        if (data.matched_words && data.matched_words.length > 0) {
            let matchedWordsHtml = '';
            data.matched_words.forEach(function(match) {
                const badgeClass = match.type === 'positive' ? 'success' : (match.type === 'negative' ? 'danger' : 'warning');
                matchedWordsHtml += `<span class="badge bg-${badgeClass} me-1 mb-1">${match.word} (${match.score})</span>`;
            });
            $('#matchedWordsList').html(matchedWordsHtml);
            $('#matchedWords').show();
        } else {
            $('#matchedWords').hide();
        }
        
        $('#analysisResults').show();
    }
});

function deleteWord(id, wordName) {
    $('#deleteWordName').text(wordName);
    $('#deleteForm').attr('action', `/sentiment-words/${id}`);
    $('#deleteModal').modal('show');
}

// Add floating action button for test analysis
$(document).ready(function() {
    $('body').append(`
        <div class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1000;">
            <button class="btn btn-primary rounded-circle shadow-lg" style="width: 60px; height: 60px; border: none;" 
                    onclick="$('#testAnalysisModal').modal('show')" title="Test Analysis">
                <i class="fas fa-brain" style="font-size: 1.5rem;"></i>
            </button>
        </div>
    `);
});
</script>
@endpush 