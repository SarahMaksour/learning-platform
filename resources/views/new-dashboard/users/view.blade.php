@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Show User')
@section('content')
@include('components.alert')

<section>
    <div class="container py-3">
        {{-- Breadcrumb --}}
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Profile: {{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="{{ $userDetails->image ?? 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp' }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3">{{ $user->name}}</h5>

                        <div class="d-flex justify-content-center mb-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning me-1"><i class="fas fa-pencil-alt"></i> Edit</a>

                            <div class="btn-group">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('remove_to_trush_user_{{$user->id}}').submit();">
                                            <form id="remove_to_trush_user_{{$user->id}}" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            Remove to trash
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('force_delete_user_{{$user->id}}').submit();">
                                            <form id="force_delete_user_{{$user->id}}" action="{{ route('users.forceDelete',['id'=>$user->id,'redirect'=>url()->previous()]) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            Delete permanently
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Subscriptions --}}
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-3">
                            <li class="list-group-item bg-light text-center"><strong>Active Subscriptions</strong></li>
                            @forelse($subscriptions as $subscription)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $subscription->course->title }}</span>
                                    <span>{{ $subscription->created_at->format('d M Y') }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted text-center">Nothing To Show</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-8">
                {{-- User Info --}}
                <div class="card mb-4">
                    <div class="card-body">
                     
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Name</strong></div>
                            <div class="col-sm-9">{{ $user->name}}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Email</strong></div>
                            <div class="col-sm-9">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Role</strong></div>
                            <div class="col-sm-9">{{ $user->role }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3"><strong>Specialization</strong></div>
                            <div class="col-sm-9">{{ $userDetails->specialization ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Ratings --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Course Ratings</h6>
                                @forelse($serviceRatings as $rating)
                                    <div class="mb-2">
                                        <strong>{{ $rating->course->title }}</strong>
                                        <div class="rating">
                                            @for($i=1;$i<=5;$i++)
                                                <span style="color:{{ $i <= $rating->rating ? '#ffc107' : '#ccc' }}">&#9733;</span>
                                            @endfor
                                        </div>
                                        <small>Comment: {{ $rating->comment ?? '-' }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted">Nothing To Show</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Trainer Ratings</h6>
                                @forelse($userRatings as $rating)
                                    <div class="mb-2">
                                        <strong>{{ $rating->user->name }} </strong>
                                        <div class="rating">
                                            @for($i=1;$i<=5;$i++)
                                                <span style="color:{{ $i <= $rating->rating ? '#ffc107' : '#ccc' }}">&#9733;</span>
                                            @endfor
                                        </div>
                                        <small>Comment: {{ $rating->comment ?? '-' }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted">Nothing To Show</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Courses --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h6>{{ $user->role == 'teacher' ? 'My Courses' : 'Enrolled Courses' }}</h6>
                        @forelse($myCourses as $course)
                            <div class="mb-2">
                                <strong>{{ $course->title }}</strong>
                                @if($user->role == 'teacher')
                                    <small>Students: {{ $course->enrollments->count() }}</small>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">Nothing To Show</p>
                        @endforelse
                    </div>
                </div>

                {{-- Certificates --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Certificates</h6>
                        @forelse($certificates as $cert)
                            <div class="mb-2">
                                <a href="{{ asset($cert->path) }}" target="_blank">{{ $cert->course->title }} Certificate</a>
                            </div>
                        @empty
                            <p class="text-muted">Nothing To Show</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.rating span {
    font-size: 1.5rem;
    margin-right: 2px;
}
</style>
@endsection
