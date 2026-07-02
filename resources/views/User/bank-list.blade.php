@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Cash & Banking</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bank Account Master</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-bank-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h2 class="mb-0">Bank Account Master</h2>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-bank-modal">
                        <i class="ti ti-square-plus f-20"></i> Add New Bank
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        @foreach ($banks as $val)

        <div class="col-md-5 col-xxl-4">
            <div class="card bank-account-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">{{ $val->bank_name }} Details</h5>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('user.BankDetails', ['id' => base64_encode($val->id)]) }}">View</a>
                                @if ($req_type != 1)
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit-bank-modal{{ $val->id }}">Edit</a>
                                {{-- <a class="dropdown-item" href="#" data-id="{{ $val->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#delete_modal">Delete</a> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-4 overflow-hidden" style="background-image: url(../assets/images/widget/img-card-bg.svg); background-size: cover;">
                        <div class="card-body">
							@if($hasProprietorship)
							<div class="d-flex">
                                <div class="flex-grow-1 me-3">
                                    <p class="text-white text-sm text-opacity-50 mb-0">PROPRIETORSHIP COMPANY</p>
                                    <h5 class="text-white">{{ $val->comp_name }}</h5>
                                </div>
                            </div>
							@endif
                            <div class="d-flex">
                                <div class="flex-grow-1 me-3">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Bank Name</p>
                                    <h5 class="text-white">{{ $val->bank_name }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-100 mb-0">Account Name</p>
                                    <h4 class="text-white mb-3">{{ $val->bank_ac_no }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">IFSC Code</p>
                                    <h6 class="text-white mb-0">{{ $val->ifsc_code }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Branch</p>
                                    <h6 class="text-white mb-0">{{ $val->bank_branch }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Swift Code</p>
                                    <h6 class="text-white mb-0">{{ $val->swift_code }}</h6>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Account Name</p>
                                    <h6 class="text-white mb-3">{{ $val->accholder_name }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Opening Balance</p>
                                    <h6 class="text-white mb-0">₹{{ $val->curr_bal }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ url('/bank-details/'.base64_encode($val->id)) }}" class="btn btn-primary w-100">View Details</a>
                    </div>
                </div>
            </div>


        </div>
        {{-- Edit Modal --}}
        <div class="modal custom-modal fade" id="edit-bank-modal{{ $val->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Bank Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="upadteBankForm" method="post" name="upadteBankForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="bankId" value="{{ $val->id }}">
                        <div class="modal-body">

                            <!-- Bank Name -->
                            <div class="row">
								<div class="mb-3 col-md-3">
									<label class="form-label">Proprietorship Company</label>
									<select name="propId" class="form-control">
										<option value="">Select Company</option>
										@foreach($val->proprietorships as $company)
											<option value="{{ $company->id }}" <?=($val->propId == $company->id) ? 'selected' : '' ?>>
												{{ $company->comp_name }}
											</option>
										@endforeach
									</select>
								</div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">Bank Name<span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ $val->bank_name }}" class="form-control" placeholder="Enter Bank Name" required>
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">Branch<span class="text-danger">*</span></label>
                                    <input type="text" name="bank_branch" id="bank_branch" value="{{ $val->bank_branch }}" class="form-control" placeholder="Enter Branch" required>
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">Account Name<span class="text-danger">*</span></label>
                                    <input type="text" name="accholder_name" id="accholder_name" value="{{ $val->accholder_name }}" class="form-control" placeholder="Enter Account Name" required>
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">Account Number<span class="text-danger">*</span></label>
                                    <input type="text" name="bank_ac_no" id="bank_ac_no" value="{{ $val->bank_ac_no }}" class="form-control" placeholder="Enter Account Number" required>
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">IFSC Code<span class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code" id="ifsc_code" value="{{ $val->ifsc_code }}" class="form-control" placeholder="Enter IFSC Code" required>
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">Swift Code</label>
                                    <input type="text" name="swift_code" id="swift_code" value="{{ $val->swift_code }}" class="form-control" placeholder="Enter Swift Code" >
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">UPI ID</label>
                                    <input type="text" name="upi_id" id="upi_id" value="{{ $val->upi_id }}" class="form-control" placeholder="Enter UPI ID" >
                                </div>
                                <div class="col-md-4  mb-3">
                                    <label for="bankName" class="form-label">Current Bank Balance<span class="text-danger">*</span></label>
                                    <input type="text" name="curr_bal" id="curr_bal" value="{{ $val->curr_bal }}" class="form-control" placeholder="Enter Current Bank Balance" required>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="upadteBankForm">Update Bank Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="add-bank-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addBankForm" method="post" name="addBankFrm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="bankId" value="">
                <div class="modal-body">

                    <!-- Bank Name -->
                    <div class="row">
						<div class="col-md-4  mb-3">
							<label class="form-label">Proprietorship Company</label>
							<select name="propId" class="form-control">
								<option value="">Select Company</option>
								@foreach($proprietorships as $company)
									<option value="{{ $company->id }}">
										{{ $company->comp_name }}
									</option>
								@endforeach
							</select>
						</div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Bank Name<span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter Bank Name" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Branch<span class="text-danger">*</span></label>
                            <input type="text" name="bank_branch" id="bank_branch" class="form-control" placeholder="Enter Branch" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Account Name<span class="text-danger">*</span></label>
                            <input type="text" name="accholder_name" id="accholder_name" class="form-control" placeholder="Enter Account Name" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Account Number<span class="text-danger">*</span></label>
                            <input type="text" name="bank_ac_no" id="bank_ac_no" class="form-control" placeholder="Enter Account Number" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">IFSC Code<span class="text-danger">*</span></label>
                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="Enter IFSC Code" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Swift Code</label>
                            <input type="text" name="swift_code" id="swift_code" class="form-control" placeholder="Enter Swift Code" >
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">UPI ID</label>
                            <input type="text" name="upi_id" id="upi_id" class="form-control" placeholder="Enter UPI ID" >
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Current Bank Balance<span class="text-danger">*</span></label>
                            <input type="text" name="curr_bal" id="curr_bal" class="form-control" placeholder="Enter Current Bank Balance" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Bank Account</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Customer</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("form#upadteBankForm").bind("submit", function(e) {
        e.preventDefault();

        var bankId = $("#bankId").val();
        var bankurl = "/update_bank";

        var bankData = $(this).serialize();
        // console.log(bankData);

        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: bankurl,
            type: "POST",
            data: bankData,
            success: function(response) {
                $("#loader").hide();
                //console.log(response);
                if (response.class == "succ") {
                    // $(".message-container").html('<div class="' + response.class + '">' + response.message + "</div>");
                    // window.location.href = response.redirect;

                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);

                } else {
                    $("#loader").hide();
                    // $.each(response, function (idx, obj) {
                    //     $(".message-container").html('<div class="err">' + obj + "</div>");
                    // });
                    showToast("Error: While Bank Add", "error");
                }
            },
        });
    });

    $("form#addBankForm").bind("submit", function(e) {
        e.preventDefault();

        var bankId = $("#bankId").val();
        var bankurl = "/save_bank"; // Ensure correct URL

        var bankData = $(this).serialize();
        // console.log(bankData);
        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: bankurl,
            type: "POST",
            data: bankData,
            success: function(response) {
                $("#loader").hide();
                //console.log(response);
                if (response.class == "succ") {
                    // $(".message-container").html('<div class="' + response.class + '">' + response.message + "</div>");
                    // window.location.href = response.redirect;

                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    $("#loader").hide();
                    showToast("Error: While Bank Add", "error");
                    // $.each(response, function (idx, obj) {
                    //     $(".message-container").html('<div class="err">' + obj + "</div>");
                    // });
                }
            },
        });
    });

    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $('.delete-btn').on('click', function() {
        deleteId = $(this).data('id');
    });

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        //alert(deleteId);

        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/bank_delete/' + deleteId,
                type: 'DELETE',
                success: function(response) {

                    // alert(response.message); 
                    // location.reload(); 

                    showToast(response.message, "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                },
                error: function(xhr) {
                    // alert("Error deleting customer!");
                    showToast("Error deleting customer!", "error");
                }
            });
        }
    });

    function startBankTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Bank Account Master Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage bank details, branches, and balances.</p></div>'
                },
                {
                    title: 'Bank Account Master',
                    intro: 'Manage bank details, branches, and balances.'
                },
                {
                    element: 'a[data-bs-target="#add-bank-modal"]', title: 'Add New Bank',
                    intro: 'Click here to register a new bank account.'
                },
                {
                    element: '.bank-account-card', title: 'Bank Account Card',
                    intro: 'View account details, branch codes, and current balances.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-bank-tour').on('click', function(e) {
            e.preventDefault();
            startBankTour();
        });
    });
</script>

@endsection