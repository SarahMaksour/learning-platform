@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Course Details')
@section('content')
@include('components.alert')

<div class="container py-3">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3 mb-4">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
            <li class="breadcrumb-item active">{{ $course->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Left Column: Image + Stats -->
        <div class="col-lg-4">
            <div class="card mb-4">
<img src="{{ $course->image ? $course->image : asset('images/dart.png') }}" class="card-img-top" alt="Course Image">

<div class="card-body text-center">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="text-muted">{{ $course->description }}</p>
                    <hr>
                    <p><strong>Instructor:</strong> {{ $course->instructor->name }}</p>
                    <p><strong>Lessons:</strong> {{ $lessons->count() }}</p>
                    <p><strong>Students:</strong> {{ $students->count() }}</p>
                    <p><strong>Average Rating:</strong> {{ number_format($averageRating, 1) }} ⭐</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Lessons + Reviews -->
        <div class="col-lg-8">
            <!-- Lessons Accordion -->
            <div class="card mb-4">
                <div class="card-header">Lessons</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($lessons as $lesson)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $lesson->contentable->title ?? 'Untitled Lesson' }}
                            <span class="badge bg-primary rounded-pill">{{ $lesson->contentable_type }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">No lessons available</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Reviews -->
            <div class="card mb-4">
                <div class="card-header">Reviews</div>
                <div class="card-body">
                    @forelse($reviews as $review)
                    <div class="mb-3">
                        <strong>{{ $review->user->name }}</strong>
                        <span class="text-warning">{{ str_repeat('⭐', $review->rating) }}</span>
                        <p class="mb-0">{{ $review->comment ?? 'No comment' }}</p>
                    </div>
                    <hr>
                    @empty
                    <p class="text-center text-muted">No reviews yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Students List -->
            <div class="card mb-4">
                <div class="card-header">Enrolled Students</div>
                <div class="card-body">
                    @forelse($students as $enrollment)
                    <p>{{ $enrollment->student->name }} ({{ $enrollment->student->email }})</p>
                    @empty
                    <p class="text-center text-muted">No students enrolled yet</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
