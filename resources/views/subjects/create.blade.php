@extends('layouts.app')

@section('title', 'Add Subject - Student Feedback System')

@section('page-title', 'Add Subject')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
<li class="breadcrumb-item active">Add Subject</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-1"></i>
                    Add New Subject
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Please use the "Add Subject" button on the Subjects page to create new subjects.
                    This page will redirect you back to the subjects list.
                </p>
                <a href="{{ route('subjects.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i>Back to Subjects
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Redirect to subjects index after a short delay
    setTimeout(function() {
        window.location.href = '{{ route("subjects.index") }}';
    }, 2000);
</script>
@endsection 