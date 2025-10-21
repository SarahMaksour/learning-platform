@extends('new-dashboard.layouts.app_dashborad')
@section('title', 'Trashed Users')
@section('content')
@extends('components.alert')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row mb-3">
    <!-- Breadcrumb -->
    <div class="col-md-8">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1">
          <li class="breadcrumb-item">
            <a href="{{route('dashboard.index')}}">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{route('users.index')}}">Users</a>
          </li>
          <li class="breadcrumb-item active">Trash</li>
        </ol>
      </nav>
    </div>

    <!-- Filter Button -->
    <div class="col-md-4 d-flex justify-content-end align-items-center">
      <a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        Filter
      </a>

      <!-- Create User -->
      <a class="btn btn-primary me-1" href="{{route('users.create')}}" role="button">
        Create New User
      </a>
    </div>
  </div>
  
<!-- Filter Content -->
<div class="collapse" id="collapseExample">
  <div class="d-flex p-4">
    <div class="card mb-6 w-100">
      <h4 class="card-header">Filter</h4>
      <form id="FilterForm" action="{{ route('users.trashed') }}" method="GET">
        <div class="card-body">
          <div class="row mb-4 d-flex align-items-center">
            <!-- Search By Name -->
            <div class="col-sm-4 d-flex align-items-center">
              <label class="col-form-label me-2" for="basic-icon-default-fullname2">Name</label>
              <div class="input-group input-group-merge flex-grow-1">
                <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-search"></i></span>
                <input name="name" value="{{request('name')}}" type="text" class="form-control" id="basic-icon-default-fullname2" placeholder="Search Something" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2">
              </div>
            </div>

            <!-- Search By Email -->
            <div class="col-sm-4 d-flex align-items-center">
              <label class="col-form-label me-2" for="basic-icon-default-fullname2">Email</label>
              <div class="input-group input-group-merge flex-grow-1">
                <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-search"></i></span>
                <input name="email" value="{{request('email')}}" type="email" class="form-control" id="basic-icon-default-fullname2" placeholder="Search Something" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2">
              </div>
            </div>
          </div>
          <div class="row mt-4 d-flex align-items-center">
            <!-- Entries Number Dropdown -->
            <div class="col-sm-2 d-flex align-items-center">
              <input type="hidden" name="entries_number" value="{{request('entries_number')}}" id="entries_number">
              <div class="btn-group me-2">
                <button class="btn btn-primary dropdown-toggle" type="button" id="entriesDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Entries Number
                </button>
                <ul class="dropdown-menu" aria-labelledby="entriesDropdown">
                  <li><a class="dropdown-item {{request('entries_number') == 5 ? 'active' : ''}}" href="javascript:void(0);" onclick="selectEntries('5')">5</a></li>
                  <li><a class="dropdown-item {{request('entries_number') == 10 ? 'active' : ''}}" href="javascript:void(0);" onclick="selectEntries('10')">10</a></li>
                  <li><a class="dropdown-item {{request('entries_number') == 15 ? 'active' : ''}}" href="javascript:void(0);" onclick="selectEntries('15')">15</a></li>
                  <li><a class="dropdown-item {{request('entries_number') == 20 ? 'active' : ''}}"  href="javascript:void(0);" onclick="selectEntries('20')">20</a></li>
                </ul>
              </div>
            </div>

            <!-- Role Dropdown -->
            <div class="col-sm-4 d-flex align-items-center">
              <input type="hidden" value="{{request('role')}}" name="role" id="role">
              <div class="btn-group me-2">
                <button class="btn btn-primary dropdown-toggle" type="button" id="roleDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Role
                </button>
                <ul class="dropdown-menu" aria-labelledby="roleDropdown">
                  <li><a class="dropdown-item {{request('role') == 'All' ? 'active' : ''}}" href="javascript:void(0);" onclick="selectRole('All')">All</a></li>
                  <li><a class="dropdown-item {{request('role') == 'admin' ? 'active' : ''}}" href="javascript:void(0);" onclick="selectRole('admin')">Admin</a></li>
                  <li><a class="dropdown-item {{request('role') == 'trainer' ? 'active' : ''}}" href="javascript:void(0);" onclick="selectRole('trainer')">Trainer</a></li>
                  <li><a class="dropdown-item {{request('role') == 'user' ? 'active' : ''}}"  href="javascript:void(0);" onclick="selectRole('user')">User</a></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Apply Button -->
          <div class="row">
            <div class="col-sm-12 d-flex justify-content-end">
              <button class="btn btn-light me-1" onclick="resetFilters()">Reset</button>
              <button class="btn btn-primary me-1">APPLY</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

  
  
    <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
              <tr>
                <td><i class="fab fa-angular fa-xl text-danger me-4"></i> <span>{{$user->id}}</span></td>
                <td>{{$user->getFullName()}}</td>
                <td>
                  {{$user->email}}
                  </ul>
                </td>
                <td>
                   <span class="badge bg-label-dark me-1">{{$user->role}}</span>
                
                </td>
                <td>
             
                  </div>
                  </td>
                </tr>
            @endforeach
          </tbody>
        </table>
   
      </div>
</div>
<script>
  function selectRole(value) {
    document.getElementById('role').value = value;
  }

  function selectEntries(value) {
    document.getElementById('entries_number').value = value;
  }

  function resetFilters() {

    // Get the filter form
    var form = document.getElementById('FilterForm');

    // Clear all input fields
      var inputs = form.getElementsByTagName('input');

      for (var i = 0; i < inputs.length; i++)
      {
          inputs[i].value = ''; 
      }

      // Reload the page without any query parameters
      window.location.href = form.action;
}
</script>
@endsection