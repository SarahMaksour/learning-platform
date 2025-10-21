@extends('new-dashboard.layouts.app_dashborad')

@section('title', 'Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header">Settings</h5>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Theme</label>
                    <select name="theme" class="form-control">
                        <option value="light" {{ $user->theme == 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ $user->theme == 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
