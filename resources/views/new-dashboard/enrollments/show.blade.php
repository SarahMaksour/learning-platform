@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Enrollment Details')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}">Enrollments</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }} - {{ $course->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Student Info Card --}}
    <div class="card mb-4 text-center">
        <div class="card-body">
            <img src="{{ $user->UserDetail->image ?? asset('images/default-avatar.png') }}" class="rounded-circle mb-3" style="width: 120px;">
            <h4>{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Course Info & Summary --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>Course: {{ $course->title }}</h5>
            <span class="badge bg-primary">{{ $completedLessons }}/{{ $totalLessons }} Lessons Completed</span>
            <span class="badge bg-info">{{ $progressPercentage }}% Progress</span>
        </div>
        <div class="card-body">
            <p>{{ $course->description }}</p>
        </div>
    </div>

    {{-- Lessons Progress --}}
    <div class="row row-cols-1 row-cols-md-2 g-3">
        @foreach($contents as $content)
            @php
                $progress = optional($content->studentProgress->first())->is_passed ? 100 : 0;
                if($progress >= 80) $color = 'bg-success';
                elseif($progress >= 50) $color = 'bg-warning';
                else $color = 'bg-danger';
                $icon = $content->contentable_type === 'App\Models\Video' ? 'bx bx-video' : ($content->contentable_type === 'App\Models\Quiz' ? 'bx bx-task' : 'bx bx-file');
            @endphp
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6><i class="{{ $icon }}"></i> {{ $content->contentable->title ?? 'Lesson' }}</h6>
                            <span class="badge {{ $color }} text-white">{{ $progress }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar {{ $color }}" style="width: {{ $progress }}%"></div>
                        </div>
                        @if($content->contentable_type === 'App\Models\Quiz')
                            <p class="mt-2 mb-0 text-muted">Total Points: {{ $content->contentable->total_point ?? 0 }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Links --}}
    <div class="mt-4 text-center">
        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary me-2">Student Profile</a>
        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary">Course Page</a>
    </div>

</div>

@endsection
