@extends('App.Layout')

@section('container')

<!-- [ Main Content ] start -->
<div class="pc-content">
  <!-- [ breadcrumb ] start -->
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url("/") }}">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0)">Users</a></li>
            <li class="breadcrumb-item" aria-current="page">Account Profile</li>
          </ul>
        </div>
        <div class="col-md-12">
          <div class="page-header-title">
            <h2 class="mb-0">Account Profile</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ breadcrumb ] end -->
  <!-- [ Main Content ] start -->
  <div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
      <div class="row">
        <div class="col-lg-5 col-xxl-3">
          <div class="card overflow-hidden">
            <div class="card-body position-relative">
              <div class="text-center mt-3">
                <div class="chat-avtar d-inline-flex mx-auto">
                  <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                    src="../assets/images/user/avatar-1.jpg" alt="User image">
                  <i class="chat-badge bg-success me-2 mb-2"></i>
                </div>
                <h5 class="mb-0">{{ $user->name ?? '' }}</h5>
                <p class="text-muted text-sm">{{ $user->designation ?? '' }}</p>
              </div>
            </div>
            <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0" id="user-set-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link list-group-item list-group-item-action active" id="user-set-profile-tab" data-bs-toggle="pill" href="#user-set-profile" role="tab" aria-controls="user-set-profile" aria-selected="true">
                <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Profile Overview</span>
              </a>
              <a class="nav-link list-group-item list-group-item-action" id="user-personal-information-tab" data-bs-toggle="pill" href="#user-personal-information" role="tab" aria-controls="user-personal-information" aria-selected="false">
                <span class="f-w-500"><i class="ph-duotone ph-clipboard-text m-r-10"></i>Edit Personal Information</span>
              </a>
              <a class="nav-link list-group-item list-group-item-action" id="user-set-passwort-tab" data-bs-toggle="pill" href="#user-set-passwort" role="tab" aria-controls="user-set-passwort" aria-selected="false">
                <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Change Login & Password</span>
              </a>
              <!--<a class="nav-link list-group-item list-group-item-action" id="payment-history-tab" data-bs-toggle="pill" href="#payment-history" role="tab" aria-controls="payment-history-tab" aria-selected="false">
                <span class="f-w-500"><i class="ph-duotone ph-notebook m-r-10"></i>Show Payment History</span>
              </a>
              <a class="nav-link list-group-item list-group-item-action" id="login-history-tab" data-bs-toggle="pill" href="#login-history" role="tab" aria-controls="login-history" aria-selected="false">
                <span class="f-w-500"><i class="ph-duotone ph-envelope-open m-r-10"></i>Login History</span>
              </a>-->
            </div>
          </div>
        </div>
        <div class="col-lg-7 col-xxl-9">
          <div class="tab-content" id="user-set-tabContent">
            <div class="tab-pane fade show active" id="user-set-profile" role="tabpanel" aria-labelledby="user-set-profile-tab">
              <div class="card">
                <div class="card-header">
                  <h5>Personal Details</h5>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 pt-0">
                      <div class="row">
                        <div class="col-md-6">
                          <p class="mb-1 text-muted">Full Name</p>
                          <p class="mb-0">{{ $user->name ?? '' }}</p>
                        </div>
                        <div class="col-md-6">
                          <p class="mb-1 text-muted">Designation</p>
                          <p class="mb-0">{{ $user->designation ?? '' }}</p>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row">
                        <div class="col-md-6">
                          <p class="mb-1 text-muted">Phone</p>
                          <p class="mb-0">+91-{{ $user->phone ?? '' }}</p>
                        </div>
                        <div class="col-md-6">
                          <p class="mb-1 text-muted">Email</p>
                          <p class="mb-0">{{ $user->email ?? '' }}</p>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row">
                        <div class="col-md-12">
                          <p class="mb-1 text-muted">Address</p>
                          <p class="mb-0">{{ $user->address ?? '' }}</p>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h5>About me</h5>
                </div>
                <div class="card-body">
                  <p class="mb-0">
                    {{ $user->bio ?? '' }}
                  </p>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="user-personal-information" role="tabpanel" aria-labelledby="user-personal-information">
				<form id="profileForm">
				@csrf
				  <div class="card">
					<div class="card-header">
					  <h5>Personal Information</h5>
					</div>
					<div class="card-body">
					  <div class="row">
						<div class="col-sm-6">
						  <div class="mb-3">
							<label class="form-label">Full Name <span class="text-danger">*</span></label>
							<input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
							<span class="text-danger error-text name_error"></span>
						  </div>
						</div>
						<div class="col-sm-6">
						  <div class="mb-3">
							<label class="form-label">Designation</label>
							<input type="text" name="designation" id="designation" class="form-control" value="{{ $user->designation }}">
						  </div>
						</div>
						<div class="col-sm-12">
						  <div class="mb-3">
							<label class="form-label">Bio</label>
							<textarea name="bio" id="bio" class="form-control">{{ $user->bio }}</textarea>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="card">
					<div class="card-header">
					  <h5>Contact Information</h5>
					</div>
					<div class="card-body">
					  <div class="row">
						<div class="col-sm-6">
						  <div class="mb-3">
							<label class="form-label">Contact Phone <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="phone" id="phone" value="{{ $user->phone }}">
							<span class="text-danger error-text phone_error"></span>
						  </div>
						</div>
						<div class="col-sm-6">
						  <div class="mb-3">
							<label class="form-label">Email <span class="text-danger">*</span></label>
							<input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
							<span class="text-danger error-text email_error"></span>
						  </div>
						</div>

						<div class="col-sm-12">
						  <div class="mb-0">
							<label class="form-label">Address</label>
							<textarea name="address" id="address" class="form-control">{{ $user->address }}</textarea>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="text-end btn-page">
					<a href="{{ url('/') }}" class="btn btn-secondary">Cancel</a>
					<button type="submit" class="btn btn-primary">Update Profile</button>
				  </div>
				</form>
            </div>
            <div class="tab-pane fade" id="user-set-passwort" role="tabpanel" aria-labelledby="user-set-passwort-tab">
				<form id="changeEmailForm">
				@csrf
				  <div class="card">
					<div class="card-header">
					  <h5>Change Email</h5>
					</div>
					<div class="card-body">
					  <ul class="list-group list-group-flush">
						<li class="list-group-item pt-0 px-0">
						  <div class="row align-items-center">							
							<label class="col-md-2 col-form-label">
							  Enter New Email <span class="text-danger">*</span>
							</label>

							<div class="col-md-7">
							  <input type="email" name="new_email" class="form-control">
							  <span class="text-danger error-text new_email_error"></span>
							</div>

							<div class="col-md-3 text-end">
							  <button type="submit" class="btn btn-primary w-100">
								Update Email
							  </button>
							</div>
						  </div>
						</li>
					  </ul>
					</div>
				  </div>
				</form>
				<form id="changePasswordForm">
				@csrf
				  <div class="card">
					<div class="card-header">
					  <h5>Change Password</h5>
					</div>
					<div class="card-body">
					  <ul class="list-group list-group-flush">
						<li class="list-group-item pt-0 px-0">
						  <div class="row mb-0">
							<label class="col-form-label col-md-2 col-sm-12 text-md-end">Current Password <span
								class="text-danger">*</span>
							</label>
							<div class="col-md-10 col-sm-12">
							  <input type="password" name="current_password" class="form-control">
							  <span class="text-danger error-text current_password_error"></span>
							  </div>
							</div>						  
						</li>
						<li class="list-group-item px-0">
						  <div class="row mb-0">
							<label class="col-form-label col-md-2 col-sm-12 text-md-end">New Password <span
								class="text-danger">*</span></label>
							<div class="col-md-10 col-sm-12">
							  <input type="password" name="new_password" class="form-control">
							  <span class="text-danger error-text new_password_error"></span>
							</div>
						  </div>
						</li>
						<li class="list-group-item pb-0 px-0">
						  <div class="row mb-0">
							<label class="col-form-label col-md-2 col-sm-12 text-md-end">Confirm Password <span
								class="text-danger">*</span></label>
							<div class="col-md-10 col-sm-12">
							  <input type="password" name="new_password_confirmation" class="form-control">
							  <span class="text-danger error-text new_password_confirmation_error"></span>
							</div>
						  </div>
						</li>
					  </ul>
					</div>
				  </div>
				  <div class="card">
					<div class="card-body text-end">
					  <a href="{{ url('/') }}" class="btn btn-secondary">Cancel</a>
					  <button type="submit" class="btn btn-primary">Change Password</button>
					</div>
				  </div>
			  </form>
            </div>
            <div class="tab-pane fade" id="payment-history" role="tabpanel" aria-labelledby="payment-history-tab">
              <div class="card">
                <div class="card-body">
                  <div class="row justify-content-between ali mb-3 g-3">
                    <div class="col-sm-auto">
                      <form class="form-search">
                        <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                        <input type="search" class="form-control" placeholder="Search...">
                        <button class="btn btn-light-secondary btn-search">Search</button>
                      </form>
                    </div>
                    <div class="col-sm-auto">
                      <input type="date" class="form-control">
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead>
                        <tr>
                          <th>date</th>
                          <th>Status</th>
                          <th>Amount</th>
                          <th>Txid</th>
                          <th class="text-center">Payment method</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>12/12/2024</td>
                          <td><i class="ph-duotone ph-x-circle text-danger f-24"></i></td>
                          <td>₹ 20,000</td>
                          <td>#63579067848912</td>
                          <td class="text-center"><img src="../assets/images/application/img-mastercard.svg" alt="img" class="img-fluid"></td>
                        </tr>
                        <tr>
                          <td>12/12/2024</td>
                          <td><i class="ph-duotone ph-clock-countdown text-warning f-24"></i></td>
                          <td>₹ 20,000</td>
                          <td>#63579067848912</td>
                          <td class="text-center"><img src="../assets/images/application/img-mastercard.svg" alt="img" class="img-fluid"></td>
                        </tr>
                        <tr>
                          <td>12/12/2024</td>
                          <td><i class="ph-duotone ph-check-circle text-success f-24"></i></td>
                          <td>₹ 20,000</td>
                          <td>#63579067848912</td>
                          <td class="text-center"><img src="../assets/images/application/img-mastercard.svg" alt="img" class="img-fluid"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="login-history" role="tabpanel" aria-labelledby="login-history">
              <div class="card">
                <div class="card-header">
                  <h5>Recognized Devices</h5>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 pt-0">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2">
                          <div class="d-flex align-items-center">
                            <div class="avtar bg-light-primary">
                              <i class="ph-duotone ph-desktop f-24"></i>
                            </div>
                            <div class="ms-2">
                              <p class="mb-1">Celt Desktop</p>
                              <p class="mb-0 text-muted">4351 Deans Lane</p>
                            </div>
                          </div>
                        </div>
                        <div class="">
                          <div class="text-success d-inline-block me-2">
                            <i class="fas fa-circle f-10 me-2"></i>
                            Current Active
                          </div>
                          <a href="#!" class="text-danger"><i class="feather icon-x-circle"></i></a>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2">
                          <div class="d-flex align-items-center">
                            <div class="avtar bg-light-primary">
                              <i class="ph-duotone ph-device-tablet-camera f-24"></i>
                            </div>
                            <div class="ms-2">
                              <p class="mb-1">Imco Tablet</p>
                              <p class="mb-0 text-muted">4185 Michigan Avenue</p>
                            </div>
                          </div>
                        </div>
                        <div class="">
                          <div class="text-muted d-inline-block me-2">
                            <i class="fas fa-circle f-10 me-2"></i>
                            Active 5 days ago
                          </div>
                          <a href="#!" class="text-danger"><i class="feather icon-x-circle"></i></a>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0 pb-0">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2">
                          <div class="d-flex align-items-center">
                            <div class="avtar bg-light-primary">
                              <i class="ph-duotone ph-device-mobile-camera f-24"></i>
                            </div>
                            <div class="ms-2">
                              <p class="mb-1">Albs Mobile</p>
                              <p class="mb-0 text-muted">3462 Fairfax Drive</p>
                            </div>
                          </div>
                        </div>
                        <div class="">
                          <div class="text-muted d-inline-block me-2">
                            <i class="fas fa-circle f-10 me-2"></i>
                            Active 1 month ago
                          </div>
                          <a href="#!" class="text-danger"><i class="feather icon-x-circle"></i></a>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h5>Active Sessions</h5>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 pt-0">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2">
                          <div class="d-flex align-items-center">
                            <div class="avtar bg-light-primary">
                              <i class="ph-duotone ph-desktop f-24"></i>
                            </div>
                            <div class="ms-2">
                              <p class="mb-1">Celt Desktop</p>
                              <p class="mb-0 text-muted">4351 Deans Lane</p>
                            </div>
                          </div>
                        </div>
                        <button class="btn btn-link-danger">Logout</button>
                      </div>
                    </li>
                    <li class="list-group-item px-0 pb-0">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2">
                          <div class="d-flex align-items-center">
                            <div class="avtar bg-light-primary">
                              <i class="ph-duotone ph-device-tablet-camera f-24"></i>
                            </div>
                            <div class="ms-2">
                              <p class="mb-1">Moon Tablet</p>
                              <p class="mb-0 text-muted">4185 Michigan Avenue</p>
                            </div>
                          </div>
                        </div>
                        <button class="btn btn-link-danger">Logout</button>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ sample-page ] end -->
  </div>
  <!-- [ Main Content ] end -->
</div>

<script>
$('#profileForm').submit(function(e){
    e.preventDefault();

    $('.error-text').text('');
	$("#loader").show();
    $.ajax({
        url: "{{ url('/update-profile') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(res){
			$("#loader").hide();
            if(res.success){
                showToast(res.message,'success');
            }
        },
        error: function(xhr){
			$("#loader").hide();
            if(xhr.status === 422){
                $.each(xhr.responseJSON.errors, function(key, value){
                    $('.'+key+'_error').text(value[0]);
                });
            }
        }
    });
});

/* CHANGE EMAIL */
$('#changeEmailForm').submit(function(e){
    e.preventDefault();
    $('.error-text').text('');
	$("#loader").show();
    $.ajax({
        url: "{{ url('/change-email') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){
            showToast(res.message,'success');
            $('#changeEmailForm')[0].reset();
			$("#loader").hide();
        },
        error: function(xhr){
			$("#loader").hide();
            if(xhr.status === 422){
                $.each(xhr.responseJSON.errors, function(key,value){
                    $('.'+key+'_error').text(value[0]);
                });
            }
        }
    });
});

/* CHANGE PASSWORD */
$('#changePasswordForm').submit(function(e){
    e.preventDefault();
    $('.error-text').text('');
	$("#loader").show();
    $.ajax({
        url: "{{ url('/password-change') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){
            showToast(res.message,'success');
            $('#changePasswordForm')[0].reset();
			$("#loader").hide();
        },
        error: function(xhr){
			$("#loader").hide();
            if(xhr.status === 422){
                $.each(xhr.responseJSON.errors, function(key,value){
                    $('.'+key+'_error').text(value[0]);
                });
            } else {
                showToast(xhr.responseJSON.message,'error');
            }
        }
    });
});
</script>

@endsection