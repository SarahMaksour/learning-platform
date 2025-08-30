@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Lessons')
@section('content')
@include('components.alert')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-3">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Lessons</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 d-flex justify-content-end align-items-center">
            <a href="{{ route('lessons.create') }}" class="btn btn-primary">Create Lesson</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Course</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->id }}</td>
                    <td>{{  $lesson->contentable->title ?? $lesson->title }}</td>
                    <td>{{ class_basename($lesson->contentable_type) }}</td>
                    <td> @if($lesson->contentable_type == 'App\Models\Video')
            {{ gmdate("H:i:s", $lesson->contentable->duration) }}
        @else
            -
        @endif
    </td>
                    <td>{{ $lesson->course->title }}</td>
                    <td>{{ $lesson->created_at }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('lessons.show', $lesson) }}"><i class="bx bx-show me-1"></i>Show</a>
                                <a class="dropdown-item" href="{{ route('lessons.edit', $lesson) }}"><i class="bx bx-edit-alt me-1"></i>Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="document.getElementById('delete-lesson-{{ $lesson->id }}').submit();">
                                    <i class="bx bx-trash me-1"></i>Delete
                                    <form id="delete-lesson-{{ $lesson->id }}" action="{{ route('lessons.destroy', $lesson) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No Lessons Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $lessons->links() }}
    </div>
</div>
@endsection
