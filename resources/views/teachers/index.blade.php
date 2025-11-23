@extends('layouts.app')

@section('title', 'Faculty - Student Feedback System')

@section('page-title', 'Faculty Management')
@section('icon', 'chalkboard-teacher')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Faculty</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Faculty List
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="fas fa-plus mr-1"></i>Add Faculty
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
                            <input type="text" class="form-control search-box" id="searchInput" placeholder="Search faculty...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="departmentFilter">
                            <option value="">All Departments</option>
                            @foreach($departments ?? [] as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
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

                <!-- Teachers Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="teachersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Phone</th>
                                <th>Rating</th>
                                <th>Surveys</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                            <tr>
                                <td>
                                    <strong>{{ $teacher->name }}</strong>
                                    @if($teacher->bio)
                                        <br><small class="text-muted">{{ Str::limit($teacher->bio, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $teacher->email ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $teacher->department }}</span>
                                </td>
                                <td>{{ $teacher->phone ?: 'N/A' }}</td>
                                <td>
                                    @if($teacher->surveys_avg_rating)
                                        <div class="rating-stars">
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
                                        <small class="text-muted">{{ number_format($teacher->surveys_avg_rating, 1) }}</small>
                                    @else
                                        <span class="text-muted">No ratings</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $teacher->surveys_count }}</span>
                                </td>
                                <td>
                                    @if($teacher->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewTeacher({{ $teacher->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editTeacher({{ $teacher->id }})" title="Edit Teacher">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteTeacher({{ $teacher->id }})" title="Delete Teacher">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No teachers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $teachers->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus mr-2"></i>Add New Teacher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTeacherForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
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
                    <button type="submit" class="btn btn-primary">Add Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Edit Teacher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeacherForm">
                @csrf
                <input type="hidden" id="edit_teacher_id" name="teacher_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" >
                    </div>
                    <div class="form-group">
                        <label for="edit_department">Department</label>
                        <input type="text" class="form-control" id="edit_department" name="department" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="edit_bio">Bio</label>
                        <textarea class="form-control" id="edit_bio" name="bio" rows="3"></textarea>
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
                    <button type="submit" class="btn btn-warning">Update Teacher</button>
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
        filterTeachers();
    });

    // Filter functionality
    $('#departmentFilter, #statusFilter').on('change', function() {
        filterTeachers();
    });

    // Clear filters
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#departmentFilter').val('');
        $('#statusFilter').val('');
        filterTeachers();
    });

    // Add teacher form
    $('#addTeacherForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("teachers.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addTeacherModal'));
                    modal.hide();
                    $('#addTeacherForm')[0].reset();
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
                    showError('An error occurred while adding the teacher.');
                }
            }
        });
    });

    // Edit teacher form
    $('#editTeacherForm').submit(function(e) {
        e.preventDefault();
        
        const teacherId = $('#edit_teacher_id').val();
        
        $.ajax({
            url: `/teachers/${teacherId}`,
            method: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTeacherModal'));
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
                    showError('An error occurred while updating the teacher.');
                }
            }
        });
    });
});

function filterTeachers() {
    const search = $('#searchInput').val().toLowerCase();
    const department = $('#departmentFilter').val();
    const status = $('#statusFilter').val();
    
    $('#teachersTable tbody tr').each(function() {
        const row = $(this);
        const name = row.find('td:first').text().toLowerCase();
        const email = row.find('td:nth-child(2)').text().toLowerCase();
        const dept = row.find('td:nth-child(3)').text();
        const isActive = row.find('td:nth-child(7)').text().includes('Active');
        
        let show = true;
        
        if (search && !name.includes(search) && !email.includes(search)) {
            show = false;
        }
        
        if (department && !dept.includes(department)) {
            show = false;
        }
        
        if (status) {
            if (status === 'active' && !isActive) show = false;
            if (status === 'inactive' && isActive) show = false;
        }
        
        row.toggle(show);
    });
}

function viewTeacher(id) {
    window.location.href = `/teachers/${id}`;
}

function editTeacher(id) {
    // Load teacher data and show edit modal
    $.ajax({
        url: `/teachers/${id}/edit`,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            $('#edit_teacher_id').val(id);
            $('#edit_name').val(data.name);
            $('#edit_email').val(data.email);
            $('#edit_department').val(data.department);
            $('#edit_phone').val(data.phone || '');
            $('#edit_bio').val(data.bio || '');
            $('#edit_is_active').prop('checked', data.is_active);
            
            const modal = new bootstrap.Modal(document.getElementById('editTeacherModal'));
            modal.show();
        },
        error: function(xhr) {
            showError('An error occurred while loading teacher data.');
        }
    });
}

function deleteTeacher(id) {
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
                url: `/teachers/${id}`,
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
                        showError('An error occurred while deleting the teacher.');
                    }
                }
            });
        }
    });
}
</script>
@endpush 