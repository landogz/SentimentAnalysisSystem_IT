@extends('layouts.app')

@section('title', 'Edit Sentiment Word')
@section('icon', 'edit')
@section('page-title', 'Edit Sentiment Word')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sentiment-words.index') }}">Sentiment Words</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit"></i> Edit Sentiment Word
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('sentiment-words.update', $sentimentWord) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="word" class="form-label">Word <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('word') is-invalid @enderror" 
                                       id="word" name="word" value="{{ old('word', $sentimentWord->word) }}" 
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
                                       id="negation" name="negation" value="{{ old('negation', $sentimentWord->negation) }}" 
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
                                    <option value="positive" {{ old('type', $sentimentWord->type) == 'positive' ? 'selected' : '' }}>
                                        Positive
                                    </option>
                                    <option value="negative" {{ old('type', $sentimentWord->type) == 'negative' ? 'selected' : '' }}>
                                        Negative
                                    </option>
                                    <option value="neutral" {{ old('type', $sentimentWord->type) == 'neutral' ? 'selected' : '' }}>
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
                                       id="score" name="score" value="{{ old('score', $sentimentWord->score) }}" 
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
                                    <option value="en" {{ old('language', $sentimentWord->language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="tl" {{ old('language', $sentimentWord->language) == 'tl' ? 'selected' : '' }}>Tagalog</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $sentimentWord->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <small class="form-text text-muted">Inactive words will not be used in sentiment analysis</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Current Word Information</label>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="mb-1">Word</h6>
                                        <strong>{{ $sentimentWord->word }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-{{ $sentimentWord->type == 'positive' ? 'success' : ($sentimentWord->type == 'negative' ? 'danger' : 'warning') }} text-white">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="mb-1">Type</h6>
                                        <strong>{{ ucfirst($sentimentWord->type) }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="mb-1">Score</h6>
                                        <strong>{{ $sentimentWord->score }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="mb-1">Language</h6>
                                        <strong>{{ strtoupper($sentimentWord->language) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Word
                        </button>
                        <a href="{{ route('sentiment-words.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('sentiment-words.show', $sentimentWord) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View Details
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
                    <i class="fas fa-info-circle"></i> Word Details
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $sentimentWord->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $sentimentWord->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $sentimentWord->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{ $sentimentWord->is_active ? 'success' : 'danger' }}">
                                {{ $sentimentWord->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                </table>
                
                <hr>
                
                <h6>Quick Actions</h6>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteWord()">
                        <i class="fas fa-trash"></i> Delete Word
                    </button>
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
                <p>Are you sure you want to delete the word "<strong>{{ $sentimentWord->word }}</strong>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('sentiment-words.destroy', $sentimentWord) }}" method="POST" style="display: inline;">
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
    // Auto-lowercase the word input
    $('#word').on('input', function() {
        $(this).val($(this).val().toLowerCase());
    });
    
    // Update score based on type selection
    $('#type').change(function() {
        const type = $(this).val();
        const scoreInput = $('#score');
        
        switch(type) {
            case 'positive':
                if (scoreInput.val() < 0) scoreInput.val(2.0);
                break;
            case 'negative':
                if (scoreInput.val() > 0) scoreInput.val(-2.0);
                break;
            case 'neutral':
                scoreInput.val(0.0);
                break;
        }
    });
});

function deleteWord() {
    $('#deleteModal').modal('show');
}
</script>
@endpush 