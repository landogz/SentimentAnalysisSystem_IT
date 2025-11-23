@extends('layouts.app')

@section('title', 'Student Details - Student Feedback System')

@section('page-title', 'Student Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
<li class="breadcrumb-item active">{{ $student->name }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Student Information -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Student Information
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-graduate fa-4x text-primary"></i>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Student Number:</strong></td>
                        <td><strong>{{ $student->student_number }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $student->email ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Year Level:</strong></td>
                        <td><span class="badge badge-info">{{ $student->year ?: 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Program:</strong></td>
                        <td><span class="badge badge-primary">{{ $student->course ?: 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Total Surveys:</strong></td>
                        <td><span class="badge badge-secondary">{{ $student->surveys_count }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Last Login:</strong></td>
                        <td>
                            @if($student->last_login_at)
                                {{ $student->last_login_at->format('M d, Y g:i A') }}
                                <br><small class="text-muted">{{ $student->last_login_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Registered:</strong></td>
                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i>Edit Student
                </a>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Student Surveys -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Survey History
                </h3>
            </div>
            <div class="card-body">
                @if($student->surveys->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Faculty</th>
                                    <th>Course</th>
                                    <th>Rating</th>
                                    <th>Sentiment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->surveys as $survey)
                                <tr>
                                    <td>
                                        <strong>{{ $survey->teacher->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $survey->teacher->department }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $survey->subject->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $survey->subject->subject_code }}</small>
                                    </td>
                                    <td>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $survey->rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $survey->rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ number_format($survey->rating, 1) }}</small>
                                    </td>
                                    <td>
                                        @if($survey->sentiment === 'positive')
                                            <span class="badge badge-success">{{ ucfirst($survey->sentiment) }}</span>
                                        @elseif($survey->sentiment === 'negative')
                                            <span class="badge badge-danger">{{ ucfirst($survey->sentiment) }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ ucfirst($survey->sentiment) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $survey->created_at->format('M d, Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        This student has not submitted any surveys yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

