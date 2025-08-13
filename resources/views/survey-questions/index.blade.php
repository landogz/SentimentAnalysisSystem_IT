@extends('layouts.app')

@section('title', 'Survey Questions')

@section('icon', 'question-circle')

@section('page-title', 'Survey Questions Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Survey Questions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-question-circle me-2"></i>
                    Survey Questions
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
                        <i class="fas fa-plus me-2"></i>Add New Question
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="questionsTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Question</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            <tr data-question-id="{{ $question->id }}">
                                <td>{{ $question->order_number }}</td>
                                <td>{{ Str::limit($question->question_text, 100) }}</td>
                                <td>
                                    <span class="badge {{ $question->question_type === 'option' ? 'badge-primary' : 'badge-info' }}">
                                        {{ $question->question_type_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $question->status_badge_class }}">
                                        {{ $question->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-question" 
                                                data-question="{{ $question->id }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editQuestionModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success toggle-status" 
                                                data-question="{{ $question->id }}">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-question" 
                                                data-question="{{ $question->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Question Modal -->
<div class="modal fade" id="createQuestionModal" tabindex="-1" aria-labelledby="createQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createQuestionModalLabel">
                    <i class="fas fa-plus me-2"></i>Add New Question
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createQuestionForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="question_text" class="form-label">Question Text</label>
                                <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="question_type" class="form-label">Question Type</label>
                                <select class="form-select" id="question_type" name="question_type" required>
                                    <option value="">Select Type</option>
                                    <option value="option">Option Selection</option>
                                    <option value="comment">Comment Input</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_number" class="form-label">Order Number</label>
                                <input type="number" class="form-control" id="order_number" name="order_number" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select" id="is_active" name="is_active" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Question Modal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuestionModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Question
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editQuestionForm">
                <input type="hidden" id="edit_question_id" name="question_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="edit_question_text" class="form-label">Question Text</label>
                                <textarea class="form-control" id="edit_question_text" name="question_text" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_question_type" class="form-label">Question Type</label>
                                <select class="form-select" id="edit_question_type" name="question_type" required>
                                    <option value="">Select Type</option>
                                    <option value="option">Option Selection</option>
                                    <option value="comment">Comment Input</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_order_number" class="form-label">Order Number</label>
                                <input type="number" class="form-control" id="edit_order_number" name="order_number" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_is_active" class="form-label">Status</label>
                                <select class="form-select" id="edit_is_active" name="is_active" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // CSRF Token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Create Question
    $('#createQuestionForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        
        $.ajax({
            url: '{{ route("survey-questions.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#98AAE7'
                    }).then(function() {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while creating the question.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#F16E70'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Edit Question - Load data
    $('.edit-question').click(function() {
        const questionId = $(this).data('question');
        const row = $(`tr[data-question-id="${questionId}"]`);
        
        $('#edit_question_id').val(questionId);
        $('#edit_question_text').val(row.find('td:eq(1)').text().trim());
        $('#edit_question_type').val(row.find('.badge').text().toLowerCase() === 'option selection' ? 'option' : 'comment');
        $('#edit_order_number').val(row.find('td:eq(0)').text().trim());
        $('#edit_is_active').val(row.find('.badge-success').length > 0 ? '1' : '0');
    });

    // Update Question
    $('#editQuestionForm').submit(function(e) {
        e.preventDefault();
        
        const questionId = $('#edit_question_id').val();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        $.ajax({
            url: `/survey-questions/${questionId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#98AAE7'
                    }).then(function() {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while updating the question.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#F16E70'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Toggle Status
    $('.toggle-status').click(function() {
        const questionId = $(this).data('question');
        const button = $(this);
        
        $.ajax({
            url: `/survey-questions/${questionId}/toggle-status`,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the status.',
                    confirmButtonColor: '#F16E70'
                });
            }
        });
    });

    // Delete Question
    $('.delete-question').click(function() {
        const questionId = $(this).data('question');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F16E70',
            cancelButtonColor: '#494850',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/survey-questions/${questionId}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                confirmButtonColor: '#98AAE7'
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting the question.',
                            confirmButtonColor: '#F16E70'
                        });
                    }
                });
            }
        });
    });

    // Reset modal forms
    $('#createQuestionModal, #editQuestionModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });
});
</script>
@endpush
