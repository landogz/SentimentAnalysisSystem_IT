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
                    Survey Questions Management
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
                        <i class="fas fa-plus me-2"></i>Add New Question
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Part 1: Instructor Evaluation -->
                <div class="mb-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-star me-2"></i>
                        Part 1: Instructor Evaluation
                        <span class="badge badge-primary ms-2">{{ $questionsByPart['part1']->count() ?? 0 }} Questions</span>
                    </h4>
                    <div class="alert alert-info">
                        <strong>Rating Scale:</strong> 5 (Outstanding) | 4 (Very Satisfactory) | 3 (Satisfactory) | 2 (Fair) | 1 (Poor)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th width="80">Order</th>
                                    <th width="100">Section</th>
                                    <th>Question</th>
                                    <th width="120">Type</th>
                                    <th width="100">Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($questionsByPart['part1']))
                                    @foreach($questionsByPart['part1'] as $question)
                                    @php
                                        $section = $question->section_label;
                                    @endphp
                                    <tr data-question-id="{{ $question->id }}" 
                                        data-question-text="{{ $question->question_text }}"
                                        data-question-type="{{ $question->question_type }}"
                                        data-part="{{ $question->part }}"
                                        data-section="{{ $question->section }}"
                                        data-order="{{ $question->order_number }}"
                                        data-active="{{ $question->is_active ? '1' : '0' }}">
                                        <td>{{ $question->order_number }}</td>
                                        <td><span class="badge badge-secondary">{{ $section }}</span></td>
                                        <td>{{ $question->question_text }}</td>
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
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No questions in Part 1</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Part 2: Difficulty Level -->
                <div class="mb-4">
                    <h4 class="text-warning mb-3">
                        <i class="fas fa-chart-line me-2"></i>
                        Part 2: Difficulty Level
                        <span class="badge badge-warning ms-2">{{ $questionsByPart['part2']->count() ?? 0 }} Questions</span>
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-warning">
                                <tr>
                                    <th width="80">Order</th>
                                    <th width="100">Section</th>
                                    <th>Question</th>
                                    <th width="120">Type</th>
                                    <th width="100">Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($questionsByPart['part2']))
                                    @foreach($questionsByPart['part2'] as $question)
                                    <tr data-question-id="{{ $question->id }}"
                                        data-question-text="{{ $question->question_text }}"
                                        data-question-type="{{ $question->question_type }}"
                                        data-part="{{ $question->part }}"
                                        data-section="{{ $question->section }}"
                                        data-order="{{ $question->order_number }}"
                                        data-active="{{ $question->is_active ? '1' : '0' }}">
                                        <td>{{ $question->order_number }}</td>
                                        <td><span class="badge badge-secondary">{{ $question->section_label }}</span></td>
                                        <td>{{ $question->question_text }}</td>
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
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No questions in Part 2</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Part 3: Open Comments -->
                <div class="mb-4">
                    <h4 class="text-info mb-3">
                        <i class="fas fa-comments me-2"></i>
                        Part 3: Open Comments
                        <span class="badge badge-info ms-2">{{ $questionsByPart['part3']->count() ?? 0 }} Questions</span>
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-info">
                                <tr>
                                    <th width="80">Order</th>
                                    <th width="100">Section</th>
                                    <th>Question</th>
                                    <th width="120">Type</th>
                                    <th width="100">Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($questionsByPart['part3']))
                                    @foreach($questionsByPart['part3'] as $question)
                                    <tr data-question-id="{{ $question->id }}"
                                        data-question-text="{{ $question->question_text }}"
                                        data-question-type="{{ $question->question_type }}"
                                        data-part="{{ $question->part }}"
                                        data-section="{{ $question->section }}"
                                        data-order="{{ $question->order_number }}"
                                        data-active="{{ $question->is_active ? '1' : '0' }}">
                                        <td>{{ $question->order_number }}</td>
                                        <td><span class="badge badge-secondary">{{ $question->section_label }}</span></td>
                                        <td>{{ $question->question_text }}</td>
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
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No questions in Part 3</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
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
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="create_question_text" class="form-label">Question Text</label>
                                <textarea class="form-control" id="create_question_text" name="question_text" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_question_type" class="form-label">Question Type</label>
                                <select class="form-select" id="create_question_type" name="question_type" required>
                                    <option value="">Select Type</option>
                                    <option value="option">Option Selection</option>
                                    <option value="comment">Comment Input</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_part" class="form-label">Part</label>
                                <select class="form-select" id="create_part" name="part" required>
                                    <option value="">Select Part</option>
                                    <option value="part1">Part 1 - Instructor Evaluation</option>
                                    <option value="part2">Part 2 - Difficulty Level</option>
                                    <option value="part3">Part 3 - Open Comments</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_section" class="form-label">Section</label>
                                <select class="form-select" id="create_section" name="section">
                                    <option value="">Select Section</option>
                                    <optgroup label="Part 1">
                                        <option value="A">A. Commitment</option>
                                        <option value="B">B. Knowledge of Subject</option>
                                        <option value="C">C. Teaching for Independent Learning</option>
                                        <option value="D">D. Management of Learning</option>
                                    </optgroup>
                                    <optgroup label="Part 2">
                                        <option value="A2">A. Perception and Dependence on Instructors</option>
                                        <option value="B2">B. Home and School Environment Support</option>
                                        <option value="C2">C. Exposure, Resources, and Motivation</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_order_number" class="form-label">Order Number</label>
                                <input type="number" class="form-control" id="create_order_number" name="order_number" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_is_active" class="form-label">Status</label>
                                <select class="form-select" id="create_is_active" name="is_active" required>
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
                @csrf
                <input type="hidden" id="edit_question_id" name="question_id">
                <input type="hidden" name="_method" value="PUT">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_part" class="form-label">Part</label>
                                <select class="form-select" id="edit_part" name="part" required>
                                    <option value="">Select Part</option>
                                    <option value="part1">Part 1 - Instructor Evaluation</option>
                                    <option value="part2">Part 2 - Difficulty Level</option>
                                    <option value="part3">Part 3 - Open Comments</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_section" class="form-label">Section</label>
                                <select class="form-select" id="edit_section" name="section">
                                    <option value="">Select Section</option>
                                    <optgroup label="Part 1">
                                        <option value="A">A. Commitment</option>
                                        <option value="B">B. Knowledge of Subject</option>
                                        <option value="C">C. Teaching for Independent Learning</option>
                                        <option value="D">D. Management of Learning</option>
                                    </optgroup>
                                    <optgroup label="Part 2">
                                        <option value="A2">A. Perception and Dependence on Instructors</option>
                                        <option value="B2">B. Home and School Environment Support</option>
                                        <option value="C2">C. Exposure, Resources, and Motivation</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_order_number" class="form-label">Order Number</label>
                                <input type="number" class="form-control" id="edit_order_number" name="order_number" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
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
        
        // Get data from data attributes
        const questionText = row.data('question-text');
        const questionType = row.data('question-type');
        const part = row.data('part');
        const section = row.data('section');
        const orderNumber = row.data('order');
        const isActive = row.data('active');
        
        // Populate form fields
        $('#edit_question_id').val(questionId);
        $('#edit_question_text').val(questionText);
        $('#edit_question_type').val(questionType);
        $('#edit_part').val(part);
        $('#edit_section').val(section);
        $('#edit_order_number').val(orderNumber);
        $('#edit_is_active').val(isActive);
        
        console.log('Loading question for edit:', {
            id: questionId,
            part: part,
            text: questionText,
            type: questionType,
            order: orderNumber,
            active: isActive
        });
        
        // Verify form fields are populated
        console.log('Form field values after population:');
        console.log('question_text:', $('#edit_question_text').val());
        console.log('question_type:', $('#edit_question_type').val());
        console.log('part:', $('#edit_part').val());
        console.log('order_number:', $('#edit_order_number').val());
        console.log('is_active:', $('#edit_is_active').val());
    });

    // Update Question
    $('#editQuestionForm').submit(function(e) {
        e.preventDefault();
        
        const questionId = $('#edit_question_id').val();
        const questionText = $('#edit_question_text').val().trim();
        const questionType = $('#edit_question_type').val();
        const part = $('#edit_part').val();
        const section = $('#edit_section').val();
        const orderNumber = $('#edit_order_number').val();
        const isActive = $('#edit_is_active').val();
        
        // Validate required fields
        if (!questionText) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Question text is required.',
                confirmButtonColor: '#F16E70'
            });
            return;
        }
        
        if (!questionType) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Question type is required.',
                confirmButtonColor: '#F16E70'
            });
            return;
        }
        
        if (!part) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Part is required.',
                confirmButtonColor: '#F16E70'
            });
            return;
        }
        
        if (!orderNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Order number is required.',
                confirmButtonColor: '#F16E70'
            });
            return;
        }
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Debug: Log form data
        console.log('Updating question:', questionId);
        console.log('Form data before submission:');
        console.log('Raw form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Also log the individual field values
        console.log('Individual field values:');
        console.log('question_text:', questionText);
        console.log('question_type:', questionType);
        console.log('part:', part);
        console.log('order_number:', orderNumber);
        console.log('is_active:', isActive);
        console.log('_method:', formData.get('_method'));
        console.log('_token:', formData.get('_token'));
        
        // Test: Create a simple object to send instead of FormData
        const testData = {
            question_text: questionText,
            question_type: questionType,
            part: part,
            section: section,
            order_number: orderNumber,
            is_active: isActive,
            _method: 'PUT',
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        console.log('Test data object:', testData);
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        $.ajax({
            url: `/survey-questions/${questionId}`,
            method: 'PUT',
            data: testData, // Use test data object instead of FormData
            // processData: false, // Remove these for JSON data
            // contentType: false,
            success: function(response) {
                console.log('Success response:', response);
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
                console.log('Error response status:', xhr.status);
                console.log('Error response text:', xhr.responseText);
                console.log('Error response JSON:', xhr.responseJSON);
                
                let errorMessage = 'An error occurred while updating the question.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    console.log('Validation errors:', errors);
                    
                    // Format validation errors
                    const errorList = [];
                    for (const field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            errorList.push(`${field}: ${errors[field].join(', ')}`);
                        }
                    }
                    errorMessage = errorList.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                console.error('Update error details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    response: xhr.responseJSON
                });
                
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
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
    
    // Ensure form is ready when edit modal is shown
    $('#editQuestionModal').on('show.bs.modal', function() {
        // Form will be populated by the edit button click handler
        console.log('Edit modal is being shown');
    });
});
</script>
@endpush
