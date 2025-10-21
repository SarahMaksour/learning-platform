@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Lesson Details')
@section('content')
@include('components.alert')

<section>
    <div class="container py-3">
        <div class="row mb-3">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.index') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('lessons.index') }}">Lessons</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $content->title ?? 'Lesson' }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ $content->title ?? 'No Title' }}</h3>
                        @if($lesson->contentable_type === 'App\Models\Video')
                            <video class="w-100 mb-3" controls>
                                <source src="{{ $content->video_path }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <p>{{ $content->description ?? 'No description available.' }}</p>
                            <p><strong>Duration:</strong> {{ gmdate("H:i:s", $content->duration) }}</p>
                        @elseif($lesson->contentable_type === 'App\Models\Quiz')
                            <p><strong>Quiz Title:</strong> {{ $content->title }}</p>
                            <p><strong>Type:</strong> {{ ucfirst($content->type) }}</p>
                            <p><strong>Total Points:</strong> {{ $content->total_point }}</p>
                            <p><strong>Course:</strong> {{ $lesson->course->title }}</p>
                        @else
                            <p>No preview available for this content type.</p>
                        @endif
                        <hr>
                        <p><strong>Created at:</strong> {{ $lesson->created_at }}</p>
                        <p><strong>Updated at:</strong> {{ $lesson->updated_at }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Actions</h5>
                        <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-warning mb-2 w-100"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('lessons.destroy', $lesson) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger w-100" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5>Course Info</h5>
                        <p><strong>Course:</strong> {{ $lesson->course->title }}</p>
                        <p><strong>Instructor:</strong> {{ $lesson->course->instructor->name }}</p>
                        <p><strong>Number of Lessons:</strong> {{ $lesson->course->contents()->count() }}</p>
                        <p><strong>Average Rating:</strong> {{ number_format($lesson->course->reviews()->avg('rating'), 1) ?? '0' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
