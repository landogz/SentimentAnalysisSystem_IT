@extends('layouts.app')

@section('title', 'Edit Subject - Student Feedback System')

@section('page-title', 'Edit Subject')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
<li class="breadcrumb-item active">Edit Subject</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Subject: {{ $subject->name }}
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Please use the "Edit" button on the Subjects page to modify subject information.
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