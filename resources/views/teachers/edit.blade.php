@extends('layouts.app')

@section('title', 'Edit Teacher - Student Feedback System')

@section('page-title', 'Edit Teacher')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Teachers</a></li>
<li class="breadcrumb-item active">Edit Teacher</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Teacher: {{ $teacher->name }}
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Please use the "Edit" button on the Teachers page to modify teacher information.
                    This page will redirect you back to the teachers list.
                </p>
                <a href="{{ route('teachers.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>Back to Teachers
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Redirect to teachers index after a short delay
    setTimeout(function() {
        window.location.href = '{{ route("teachers.index") }}';
    }, 2000);
</script>
@endsection 