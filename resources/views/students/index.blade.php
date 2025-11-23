@extends('layouts.app')

@section('title', 'Students - Student Feedback System')

@section('page-title', 'Student Management')
@section('icon', 'user-graduate')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Students</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-2"></i>
                    Students List
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-plus mr-1"></i>Add Student
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
                            <input type="text" class="form-control search-box" id="searchInput" placeholder="Search students...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" id="yearFilter">
                            <option value="">All Years</option>
                            @foreach($years ?? [] as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" id="programFilter">
                            <option value="">All Programs</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary btn-block" id="clearFilters">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="studentsTable">
                        <thead>
                            <tr>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Year</th>
                                <th>Program</th>
                                <th>Surveys</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->student_number }}</strong>
                                </td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $student->year ?: 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $student->course ?: 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $student->surveys_count }}</span>
                                </td>
                                <td>
                                    @if($student->last_login_at)
                                        <small class="text-muted">{{ $student->last_login_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editStudent({{ $student->id }})" title="Edit Student">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteStudent({{ $student->id }})" title="Delete Student">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No students found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $students->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2"></i>Add New Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStudentForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_number">Student Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="student_number" name="student_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year">Year Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="year" name="year" required>
                                    <option value="">Select Year...</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course">Program <span class="text-danger">*</span></label>
                                <select class="form-control" id="course" name="course" required>
                                    <option value="">Select Program...</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Create Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i>Edit Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStudentForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_student_id" name="student_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_student_number">Student Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_student_number" name="student_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_year">Year Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_year" name="year" required>
                                    <option value="">Select Year...</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_course">Program <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_course" name="course" required>
                                    <option value="">Select Program...</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_password">Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" id="edit_password" name="password" minlength="6">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i>Update Student
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
    // Search functionality
    $('#searchInput').on('keyup', function() {
        filterTable();
    });
    
    $('#yearFilter, #programFilter').on('change', function() {
        filterTable();
    });
    
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#yearFilter').val('');
        $('#programFilter').val('');
        filterTable();
    });
    
    function filterTable() {
        const search = $('#searchInput').val().toLowerCase();
        const year = $('#yearFilter').val();
        const program = $('#programFilter').val();
        
        $('#studentsTable tbody tr').each(function() {
            const row = $(this);
            const text = row.text().toLowerCase();
            const rowYear = row.find('td:eq(3)').text().trim();
            const rowProgram = row.find('td:eq(4)').text().trim();
            
            const matchSearch = !search || text.includes(search);
            const matchYear = !year || rowYear === year;
            const matchProgram = !program || rowProgram === program;
            
            row.toggle(matchSearch && matchYear && matchProgram);
        });
    }
    
    // Add Student Form
    $('#addStudentForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Creating...');
        
        $.ajax({
            url: '{{ route("students.store") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                let errorMessage = 'An error occurred while creating the student.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
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
    
    // Edit Student
    window.editStudent = function(id) {
        $.ajax({
            url: `/students/${id}/edit`,
            method: 'GET',
            success: function(response) {
                const student = response;
                $('#edit_student_id').val(student.id);
                $('#edit_student_number').val(student.student_number);
                $('#edit_name').val(student.name);
                $('#edit_email').val(student.email || '');
                $('#edit_year').val(student.year);
                $('#edit_course').val(student.course);
                $('#edit_password').val('');
                $('#edit_password_confirmation').val('');
                $('#editStudentModal').modal('show');
            }
        });
    };
    
    // Update Student Form
    $('#editStudentForm').submit(function(e) {
        e.preventDefault();
        
        const studentId = $('#edit_student_id').val();
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating...');
        
        $.ajax({
            url: `/students/${studentId}`,
            method: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                let errorMessage = 'An error occurred while updating the student.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
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
    
    // Delete Student
    window.deleteStudent = function(id) {
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
                    url: `/students/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while deleting the student.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#F16E70'
                        });
                    }
                });
            }
        });
    };
});
</script>
@endpush

