@extends('layouts.app')

@section('title', 'Courses - Student Feedback System')

@section('page-title', 'Course Management')
@section('icon', 'book')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Courses</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-2"></i>
                    Courses List
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="fas fa-plus mr-1"></i>Add Course
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Controls -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control search-box" id="searchInput" placeholder="Search courses...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="teacherFilter">
                            <option value="">All Faculty</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary btn-block" id="clearFilters">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>

                <!-- Subjects Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="subjectsTable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Teachers</th>
                                <th>Description</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                            <tr>
                                <td>
                                    <strong>{{ $subject->subject_code }}</strong>
                                </td>
                                <td>{{ $subject->name }}</td>
                                <td>
                                    @if($subject->teachers->count() > 0)
                                        @foreach($subject->teachers as $teacher)
                                            <span class="badge badge-info">
                                                {{ $teacher->name }}
                                                @if($teacher->pivot->is_primary)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endif
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No teachers assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->description)
                                        {{ Str::limit($subject->description, 50) }}
                                    @else
                                        <span class="text-muted">No description</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->surveys_avg_rating)
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $subject->surveys_avg_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $subject->surveys_avg_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ number_format($subject->surveys_avg_rating, 1) }}</small>
                                    @else
                                        <span class="text-muted">No ratings</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $subject->surveys_count }}</span>
                                </td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewSubject({{ $subject->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editSubject({{ $subject->id }})" title="Edit Subject">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteSubject({{ $subject->id }})" title="Delete Subject">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No subjects found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $subjects->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus mr-2"></i>Add New Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSubjectForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject_code">Subject Code</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Subject Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="teacher_ids">Assigned Teachers</label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach($teachers as $teacher)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input teacher-checkbox" 
                                           id="teacher_{{ $teacher->id }}" 
                                           name="teacher_ids[]" 
                                           value="{{ $teacher->id }}">
                                    <label class="form-check-label" for="teacher_{{ $teacher->id }}">
                                        {{ $teacher->name }} ({{ $teacher->department }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Select at least one teacher</small>
                    </div>
                    <div class="form-group">
                        <label for="primary_teacher_id">Primary Teacher</label>
                        <select class="form-control" id="primary_teacher_id" name="primary_teacher_id" required>
                            <option value="">Select Primary Teacher</option>
                        </select>
                        <small class="text-muted">The primary teacher will be marked with a star</small>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Edit Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubjectForm">
                @csrf
                <input type="hidden" id="edit_subject_id" name="subject_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_subject_code">Subject Code</label>
                                <input type="text" class="form-control" id="edit_subject_code" name="subject_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Subject Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_teacher_ids">Assigned Teachers</label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach($teachers as $teacher)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input teacher-checkbox" 
                                           id="edit_teacher_{{ $teacher->id }}" 
                                           name="teacher_ids[]" 
                                           value="{{ $teacher->id }}">
                                    <label class="form-check-label" for="edit_teacher_{{ $teacher->id }}">
                                        {{ $teacher->name }} ({{ $teacher->department }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Select at least one teacher</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_primary_teacher_id">Primary Teacher</label>
                        <select class="form-control" id="edit_primary_teacher_id" name="primary_teacher_id" required>
                            <option value="">Select Primary Teacher</option>
                        </select>
                        <small class="text-muted">The primary teacher will be marked with a star</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        filterSubjects();
    });

    // Filter functionality
    $('#teacherFilter, #statusFilter').on('change', function() {
        filterSubjects();
    });

    // Clear filters
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#teacherFilter').val('');
        $('#statusFilter').val('');
        filterSubjects();
    });

    // Handle teacher checkbox changes for primary teacher selection
    $('.teacher-checkbox').on('change', function() {
        updatePrimaryTeacherOptions.call(this);
    });

    // Update primary teacher options when modal is shown
    $('#addSubjectModal').on('shown.bs.modal', function() {
        updatePrimaryTeacherOptions.call($(this).find('.teacher-checkbox:checked').first());
    });

    // Add subject form
    $('#addSubjectForm').submit(function(e) {
        e.preventDefault();
        
        // Validate that at least one teacher is selected
        const selectedTeachers = $('#addSubjectModal .teacher-checkbox:checked');
        if (selectedTeachers.length === 0) {
            showError('Please select at least one teacher.');
            return;
        }
        
        // Validate that primary teacher is selected
        const primaryTeacher = $('#primary_teacher_id').val();
        if (!primaryTeacher) {
            showError('Please select a primary teacher.');
            return;
        }
        
        $.ajax({
            url: '{{ route("subjects.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSubjectModal'));
                    modal.hide();
                    $('#addSubjectForm')[0].reset();
                    showSuccess(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    showError('Please check the form and try again.');
                } else {
                    showError('An error occurred while adding the subject.');
                }
            }
        });
    });

    // Edit subject form
    $('#editSubjectForm').submit(function(e) {
        e.preventDefault();
        
        // Validate that at least one teacher is selected
        const selectedTeachers = $('#editSubjectModal .teacher-checkbox:checked');
        if (selectedTeachers.length === 0) {
            showError('Please select at least one teacher.');
            return;
        }
        
        // Validate that primary teacher is selected
        const primaryTeacher = $('#edit_primary_teacher_id').val();
        if (!primaryTeacher) {
            showError('Please select a primary teacher.');
            return;
        }
        
        const subjectId = $('#edit_subject_id').val();
        
        $.ajax({
            url: `/subjects/${subjectId}`,
            method: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editSubjectModal'));
                    modal.hide();
                    showSuccess(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    showError('Please check the form and try again.');
                } else {
                    showError('An error occurred while updating the subject.');
                }
            }
        });
    });
});

// Function to update primary teacher options based on selected teachers
function updatePrimaryTeacherOptions() {
    const modal = $(this).closest('.modal');
    const selectedTeachers = modal.find('.teacher-checkbox:checked');
    const primarySelect = modal.find('select[name*="primary_teacher_id"]');
    
    // Clear current options
    primarySelect.find('option:not(:first)').remove();
    
    // Add options for selected teachers
    selectedTeachers.each(function() {
        const teacherId = $(this).val();
        const teacherName = $(this).closest('.form-check').find('label').text().trim();
        primarySelect.append(`<option value="${teacherId}">${teacherName}</option>`);
    });
    
    // If only one teacher is selected, auto-select as primary
    if (selectedTeachers.length === 1) {
        primarySelect.val(selectedTeachers.val());
    } else if (selectedTeachers.length === 0) {
        // If no teachers selected, clear the primary teacher selection
        primarySelect.val('');
    }
}

function filterSubjects() {
    const search = $('#searchInput').val().toLowerCase();
    const teacherId = $('#teacherFilter').val();
    const status = $('#statusFilter').val();
    
    $('#subjectsTable tbody tr').each(function() {
        const row = $(this);
        const code = row.find('td:first').text().toLowerCase();
        const name = row.find('td:nth-child(2)').text().toLowerCase();
        const teacherCell = row.find('td:nth-child(3)');
        const isActive = row.find('td:nth-child(7)').text().includes('Active');
        
        let show = true;
        
        // Search filter
        if (search && !code.includes(search) && !name.includes(search)) {
            show = false;
        }
        
        // Teacher filter
        if (teacherId) {
            let hasTeacher = false;
            teacherCell.find('.badge').each(function() {
                const teacherName = $(this).text().trim();
                // Check if this teacher matches the selected teacher
                if (teacherName.includes($('#teacherFilter option:selected').text())) {
                    hasTeacher = true;
                    return false; // break the loop
                }
            });
            if (!hasTeacher) {
                show = false;
            }
        }
        
        // Status filter
        if (status) {
            if (status === 'active' && !isActive) show = false;
            if (status === 'inactive' && isActive) show = false;
        }
        
        row.toggle(show);
    });
}

function viewSubject(id) {
    window.location.href = `/subjects/${id}`;
}

function editSubject(id) {
    // Load subject data and show edit modal
    $.ajax({
        url: `/subjects/${id}/edit`,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            console.log('Subject data received:', data); // Debug log
            $('#edit_subject_id').val(id);
            $('#edit_subject_code').val(data.subject_code);
            $('#edit_name').val(data.name);
            $('#edit_description').val(data.description || '');
            $('#edit_is_active').prop('checked', data.is_active);
            
            // Clear all teacher checkboxes in the edit modal first
            $('#editSubjectModal .teacher-checkbox').prop('checked', false);
            
            // Check the assigned teachers
            console.log('Teachers data:', data.teachers); // Debug log
            if (data.teachers && data.teachers.length > 0) {
                data.teachers.forEach(function(teacher) {
                    console.log('Checking teacher:', teacher.id); // Debug log
                    $(`#edit_teacher_${teacher.id}`).prop('checked', true);
                });
                
                // Populate primary teacher dropdown
                const primarySelect = $('#edit_primary_teacher_id');
                primarySelect.find('option:not(:first)').remove();
                
                data.teachers.forEach(function(teacher) {
                    const teacherName = $(`#edit_teacher_${teacher.id}`).closest('.form-check').find('label').text().trim();
                    primarySelect.append(`<option value="${teacher.id}">${teacherName}</option>`);
                });
                
                // Set primary teacher
                const primaryTeacher = data.teachers.find(t => t.pivot.is_primary);
                if (primaryTeacher) {
                    primarySelect.val(primaryTeacher.id);
                }
            }
            
            const modal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
            modal.show();
        },
        error: function(xhr) {
            showError('An error occurred while loading subject data.');
        }
    });
}

function deleteSubject(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/subjects/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        showError(xhr.responseJSON.message);
                    } else {
                        showError('An error occurred while deleting the subject.');
                    }
                }
            });
        }
    });
}
</script>
@endpush 