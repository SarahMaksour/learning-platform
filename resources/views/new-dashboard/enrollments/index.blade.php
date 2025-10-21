@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Enrollments')
@section('content')
@include('components.alert')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-3">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Enrollments</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        @forelse($enrollments as $enrollment)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $enrollment->student->name }}</h5>
                    <p class="mb-1"><strong>Email:</strong> {{ $enrollment->student->email }}</p>

                    <hr>

                    <h6>Courses:</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $enrollment->course->title }}</span>
                                <span>{{ $enrollment->progressPercentage }}%</span>
                            </div>
                            <div class="progress mt-1" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->progressPercentage }}%;" aria-valuenow="{{ $enrollment->progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('enrollments.show', $enrollment) }}" class="btn btn-primary btn-sm">View Enrollment</a>
                        <a href="{{ route('users.show', $enrollment->student) }}" class="btn btn-outline-secondary btn-sm">Student Profile</a>
                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-outline-info btn-sm">Course Page</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-muted">No enrollments found.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $enrollments->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $enrollments->previousPageUrl() }}">
                    <i class="tf-icon bx bx-chevrons-left bx-sm"></i>
                </a>
            </li>

            @for ($i = 1; $i <= $enrollments->lastPage(); $i++)
            <li class="page-item {{ $enrollments->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $enrollments->url($i) }}">{{ $i }}</a>
            </li>
            @endfor

            <li class="page-item {{ $enrollments->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $enrollments->nextPageUrl() }}">
                    <i class="tf-icon bx bx-chevrons-right bx-sm"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
@endsection
