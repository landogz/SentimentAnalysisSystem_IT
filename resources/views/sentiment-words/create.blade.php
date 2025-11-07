@extends('layouts.app')

@section('title', 'Add Sentiment Word')
@section('icon', 'plus')
@section('page-title', 'Add New Sentiment Word')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sentiment-words.index') }}">Sentiment Words</a></li>
<li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus"></i> Add New Sentiment Word
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('sentiment-words.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="word" class="form-label">Word <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('word') is-invalid @enderror" 
                                       id="word" name="word" value="{{ old('word') }}" 
                                       placeholder="Enter word" required>
                                @error('word')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="negation" class="form-label">Negation (Opposite Word)</label>
                                <input type="text" class="form-control @error('negation') is-invalid @enderror" 
                                       id="negation" name="negation" value="{{ old('negation') }}" 
                                       placeholder="Enter opposite word (optional)">
                                <small class="form-text text-muted">e.g., "beautiful" → "ugly", "good" → "bad"</small>
                                @error('negation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="positive" {{ old('type') == 'positive' ? 'selected' : '' }}>
                                        Positive
                                    </option>
                                    <option value="negative" {{ old('type') == 'negative' ? 'selected' : '' }}>
                                        Negative
                                    </option>
                                    <option value="neutral" {{ old('type') == 'neutral' ? 'selected' : '' }}>
                                        Neutral
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="score" class="form-label">Score <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('score') is-invalid @enderror" 
                                       id="score" name="score" value="{{ old('score', 1.0) }}" 
                                       min="-5" max="5" step="1" required>
                                <small class="form-text text-muted">Score range: -5.0 to 5.0</small>
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="language" class="form-label">Language <span class="text-danger">*</span></label>
                                <select class="form-select @error('language') is-invalid @enderror" 
                                        id="language" name="language" required>
                                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="tl" {{ old('language') == 'tl' ? 'selected' : '' }}>Tagalog</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Score Guidelines</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1">Positive Words</h6>
                                        <small>1.0 - 5.0: Good, Great, Excellent</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1">Negative Words</h6>
                                        <small>-1.0 - -5.0: Poor, Bad, Terrible</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1">Neutral Words</h6>
                                        <small>0.0: Okay, Fine, Average</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Word
                        </button>
                        <a href="{{ route('sentiment-words.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Tips
                </h3>
            </div>
            <div class="card-body">
                <h6>Adding Sentiment Words</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Use lowercase words</li>
                    <li><i class="fas fa-check text-success"></i> Choose appropriate scores</li>
                    <li><i class="fas fa-check text-success"></i> Select correct language</li>
                    <li><i class="fas fa-check text-success"></i> Consider word context</li>
                    <li><i class="fas fa-check text-success"></i> Add negation pairs for better analysis</li>
                </ul>
                
                <hr>
                
                <h6>Negation Examples</h6>
                <div class="small">
                    <strong>Word Pairs:</strong><br>
                    • Beautiful → Ugly<br>
                    • Good → Bad<br>
                    • Excellent → Terrible<br>
                    • Happy → Sad<br>
                    • Smart → Stupid<br><br>
                    
                    <strong>How it works:</strong><br>
                    • "Not beautiful" = Negative<br>
                    • "Not ugly" = Positive<br>
                    • "Not good" = Negative<br>
                    • "Not bad" = Positive
                </div>
                
                <hr>
                
                <h6>Score Examples</h6>
                <div class="small">
                    <strong>Positive:</strong><br>
                    • Excellent (5.0)<br>
                    • Great (3.0)<br>
                    • Good (1.0)<br><br>
                    
                    <strong>Negative:</strong><br>
                    • Terrible (-5.0)<br>
                    • Bad (-3.0)<br>
                    • Poor (-1.0)<br><br>
                    
                    <strong>Neutral:</strong><br>
                    • Okay (0.0)<br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-lowercase the word input
    $('#word').on('input', function() {
        $(this).val($(this).val().toLowerCase());
    });
    
    // Auto-lowercase the negation input
    $('#negation').on('input', function() {
        $(this).val($(this).val().toLowerCase());
    });
    
    // Update score based on type selection
    $('#type').change(function() {
        const type = $(this).val();
        const scoreInput = $('#score');
        
        switch(type) {
            case 'positive':
                scoreInput.val(2.0);
                break;
            case 'negative':
                scoreInput.val(-2.0);
                break;
            case 'neutral':
                scoreInput.val(0.0);
                break;
        }
    });
});
</script>
@endpush 