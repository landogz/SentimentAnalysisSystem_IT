@extends('layouts.app')

@section('title', 'Sentiment Word Details')
@section('icon', 'eye')
@section('page-title', 'Sentiment Word Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sentiment-words.index') }}">Sentiment Words</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye"></i> Word Details
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Word:</strong></td>
                                <td>
                                    <span class="badge bg-info fs-6">{{ $sentimentWord->word }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $sentimentWord->type == 'positive' ? 'success' : ($sentimentWord->type == 'negative' ? 'danger' : 'warning') }} fs-6">
                                        {{ ucfirst($sentimentWord->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Score:</strong></td>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $sentimentWord->score }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Language:</strong></td>
                                <td>
                                    <span class="badge bg-secondary fs-6">{{ strtoupper($sentimentWord->language) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $sentimentWord->is_active ? 'success' : 'danger' }} fs-6">
                                        {{ $sentimentWord->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $sentimentWord->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $sentimentWord->created_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Updated:</strong></td>
                                <td>{{ $sentimentWord->updated_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Age:</strong></td>
                                <td>{{ $sentimentWord->created_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <h5>Score Analysis</h5>
                        <div class="progress mb-3" style="height: 25px;">
                            @php
                                $percentage = (($sentimentWord->score + 5) / 10) * 100;
                                $color = $sentimentWord->score > 0 ? 'success' : ($sentimentWord->score < 0 ? 'danger' : 'warning');
                            @endphp
                            <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                                 style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $sentimentWord->score }}
                            </div>
                        </div>
                        <small class="text-muted">Score range: -5.0 to 5.0</small>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Quick Actions</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('sentiment-words.edit', $sentimentWord) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger" onclick="deleteWord()">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <a href="{{ route('sentiment-words.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Statistics
                </h3>
            </div>
            <div class="card-body">
                @php
                    $totalWords = \App\Models\SentimentWord::count();
                    $typeCount = \App\Models\SentimentWord::where('type', $sentimentWord->type)->count();
                    $languageCount = \App\Models\SentimentWord::where('language', $sentimentWord->language)->count();
                    $activeCount = \App\Models\SentimentWord::where('is_active', true)->count();
                @endphp
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="card bg-primary text-white mb-2">
                            <div class="card-body p-2">
                                <h6 class="mb-0">{{ $totalWords }}</h6>
                                <small>Total Words</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-{{ $sentimentWord->type == 'positive' ? 'success' : ($sentimentWord->type == 'negative' ? 'danger' : 'warning') }} text-white mb-2">
                            <div class="card-body p-2">
                                <h6 class="mb-0">{{ $typeCount }}</h6>
                                <small>{{ ucfirst($sentimentWord->type) }} Words</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-info text-white mb-2">
                            <div class="card-body p-2">
                                <h6 class="mb-0">{{ $languageCount }}</h6>
                                <small>{{ strtoupper($sentimentWord->language) }} Words</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-success text-white mb-2">
                            <div class="card-body p-2">
                                <h6 class="mb-0">{{ $activeCount }}</h6>
                                <small>Active Words</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb"></i> Similar Words
                </h3>
            </div>
            <div class="card-body">
                @php
                    $similarWords = \App\Models\SentimentWord::where('type', $sentimentWord->type)
                        ->where('language', $sentimentWord->language)
                        ->where('id', '!=', $sentimentWord->id)
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($similarWords->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($similarWords as $word)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $word->word }}</span>
                                <span class="badge bg-primary">{{ $word->score }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No similar words found.</p>
                @endif
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
function deleteWord() {
    $('#deleteModal').modal('show');
}
</script>
@endpush 