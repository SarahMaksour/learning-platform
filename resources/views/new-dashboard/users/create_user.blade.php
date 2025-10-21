@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Create User')
@section('content')
@extends('components.alert')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Basic with Icons -->
  <div class="col-xxl">
        <div class="card mb-6">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Create New User</h5>
            {{-- <small class="text-muted float-end">Merged input group</small> --}}
          </div>
          <div class="card-body">
            <form action="{{route('users.store')}}" method="POST" id="create_user">
              @csrf
              <div class="row mb-6">
                <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">First Name</label>
                <div class="col-sm-10">
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                    <input name="first_name" type="text" class="form-control" id="basic-icon-default-fullname" placeholder="John" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2">
                  </div>
                </div>
              </div>
              <div class="row mb-6">
                <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Last Name</label>
                <div class="col-sm-10">
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                    <input name="last_name" type="text" class="form-control" id="basic-icon-default-fullname" placeholder="Doe" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2">
                  </div>
                </div>
              </div>
              <div class="row mb-6">
                <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email</label>
                <div class="col-sm-10">
                  <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                    <input name="email" type="email" id="basic-icon-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-icon-default-email2">
                    <span id="basic-icon-default-email2" class="input-group-text">@example.com</span>
                  </div>
                  <div class="form-text">You can use letters, numbers &amp; periods</div>
                </div>
              </div>
              
              <div class="row mb-6">
                <label class="col-sm-2 col-form-label" for="basic-icon-default-phone">Password</label>
                <div class="col-sm-10">
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                    <input name="password" type="password" id="basic-icon-default-phone" class="form-control phone-mask" placeholder="Ak@3#1sr20aw$%" aria-label="Ak@3#1sr20aw$%" aria-describedby="basic-icon-default-phone2">
                  </div>
                </div>
              </div>
              <div class="row mb-6">
                <label class="col-sm-2 col-form-label" for="basic-icon-default-phone">Confirm Password</label>
                <div class="col-sm-10">
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                    <input name="password_confirmation" type="password" id="basic-icon-default-phone" class="form-control phone-mask" placeholder="Ak@3#1sr20aw$%" aria-label="Ak@3#1sr20aw$%" aria-describedby="basic-icon-default-phone2">
                  </div>
                </div>
              </div>

              <div class="row mb-6">
                <label for="exampleFormControlSelect1" class="col-sm-2 col-form-label">Role</label>
                <div class="col-sm-10">
                    <select name="role" class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                        <option selected>Open To Select A Role</option>
                        @foreach ($roles as $role)
                        <option value="{{$role->name}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
              </div>
                <input type="hidden" name="redirect_to" id="redirect_to" value="">
              <div class="row justify-content-end">
                <div class="col-sm-10">
                  <button type="submit" id = "submit_redirect_index" class="btn btn-primary">Create</button>
                  <button type="submit" id="submit_redirect_create" class="btn btn-light">Create & Create Another one</button>
                  <a href="{{route('users.index')}}" class="btn btn-light">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  </div>
  
<script>
  document.getElementById('submit_redirect_index').addEventListener('click', function(event) {
      document.getElementById('redirect_to').value = 'index';
      document.getElementById('create_user').submit();
  });
  document.getElementById('submit_redirect_create').addEventListener('click', function(event) {
      document.getElementById('redirect_to').value = 'create';
      document.getElementById('create_user').submit();
  });
</script>
@endsection