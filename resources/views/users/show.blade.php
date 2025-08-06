@extends('layouts.app')

@section('title', 'User Details - Student Feedback System')

@section('page-title', 'User Details')
@section('icon', 'user')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
<li class="breadcrumb-item active">User Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-2"></i>User Details: {{ $user->name }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i>Edit User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Full Name</label>
                            <p class="form-control-static">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Email</label>
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Account Created</label>
                            <p class="form-control-static">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Last Updated</label>
                            <p class="form-control-static">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Last Login</label>
                            <p class="form-control-static">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('F d, Y \a\t g:i A') }}
                                @else
                                    <span class="text-muted">Never logged in</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <p class="form-control-static">
                                @if($user->id === auth()->id())
                                    <span class="badge badge-success">Current User</span>
                                @else
                                    <span class="badge badge-info">Active</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Users
                    </a>
                    @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-danger" onclick="deleteUser({{ $user->id }})">
                            <i class="fas fa-trash mr-1"></i>Delete User
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteUser(id) {
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
                url: `/users/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess(response.message);
                        setTimeout(function() {
                            window.location.href = '{{ route("users.index") }}';
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        showError(xhr.responseJSON.message);
                    } else {
                        showError('An error occurred while deleting the user.');
                    }
                }
            });
        }
    });
}
</script>
@endpush 