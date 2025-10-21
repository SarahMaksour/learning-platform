@if (session('success'))
<div class="main-content">
    <div class="alert alert-success alert-dismissible custom-alert" role="alert">
      <h4 class="alert-heading d-flex align-items-center"><span class="alert-icon rounded-circle"><i class="bx bx-badge-check"></i></span>Success</h4>
      <hr>
      <p class="mb-0">{{session('success')}}</p>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      </button>
    </div>
  </div>
@endif
@if (session('error'))
<div class="main-content">
    <div class="alert alert-danger alert-dismissible custom-alert" role="alert">
      <h4 class="alert-heading d-flex align-items-center"><span class="alert-icon rounded-circle"><i class="bx bx-error-alt"></i></span>Error</h4>
      <hr>
      <p class="mb-0">{{session('error')}}</p>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      </button>
    </div>
  </div>
@endif
@if ($errors->any())
<div class="main-content">
    <div class="alert alert-warning alert-dismissible custom-alert" role="alert">
      <h4 class="alert-heading d-flex align-items-center"><span class="alert-icon rounded-circle"><i class="bx bx-info-circle"></i></span>Note</h4>
      <hr>
          <ul class="mb-0 ms-4">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      </button>
    </div>
  </div>
@endif

<style>

.main-content {
    margin-left: 250px;
    padding: 20px;
}

.custom-alert {
    z-index: 1050;
    position: relative;
}

</style>