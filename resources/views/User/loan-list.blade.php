@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Loan Accounts</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Loan Account List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-loan-account-modal">
                        <i class="ti ti-square-plus f-20"></i> Add New Loan Account
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">

        @foreach ($loans as $loan)
        <div class="col-md-5 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Loan Account Details</h5>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item view-loan" href="javascript:void(0)" data-id="{{ $loan->id }}">View</a>
								<a class="dropdown-item edit-loan" href="javascript:void(0)" data-id="{{ $loan->id }}">Edit</a>
                                <a class="dropdown-item delete-loan" 
								   href="javascript:void(0)" 
								   data-id="{{ $loan->id }}"
								   data-bs-toggle="modal" 
								   data-bs-target="#delete-bank-modal">
								   Delete
								</a>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-4 overflow-hidden" style="background-image: url(../assets/images/widget/img-card-bg.svg); background-size: cover;">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1 me-3">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Bank Name</p>
                                    <h5 class="text-white">{{ $loan->bank_name }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-100 mb-0">Loan Account Number</p>
                                    <h4 class="text-white mb-3">{{ $loan->loan_ac_no }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">IFSC Code</p>
                                    <h6 class="text-white mb-0">{{ $loan->ifsc_code }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Branch</p>
                                    <h6 class="text-white mb-0">{{ $loan->branch }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Loan Type</p>
                                    <h6 class="text-white mb-0">{{ $loan->lone_type }}</h6>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Account Holder Name</p>
                                    <h6 class="text-white mb-3">{{ $loan->app_name }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Total Loan Amount</p>
                                    <h6 class="text-white mb-0">₹{{ $loan->total_lone_amount }}</h6>
                                </div>
                                <div class="col-auto">
                                    <p class="text-white text-sm text-opacity-50 mb-0">Remain Loan Amount</p>
                                    <h6 class="text-white mb-0">₹{{ $loan->remains_loan_amount }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ url('/loan-account-details/'.base64_encode($loan->id)) }}" class="btn btn-primary w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="add-loan-account-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Loan Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0);" method="post" name="addLoanFrm" id="addLoanFrm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="loanId" value="">
                @csrf
                <div class="modal-body">
                    <!-- Form Content -->

                    <!-- Bank Name -->
                    <div class="row">
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Bank Name<span class="text-danger">*</span></label>
                            {{-- <input type="text" id="bankName" class="form-control" placeholder="Enter Bank Name" required> --}}
                            <select name="bank_name" id="bank_name" class="form-control" required>
                                <option value="">Select Bank Name</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Branch<span class="text-danger">*</span></label>
                            <input type="text" id="branch" name="branch" class="form-control" placeholder="Enter Branch" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Account Holder Name<span class="text-danger">*</span></label>
                            <input type="text" name="app_name" id="app_name" class="form-control" placeholder="Enter Account Holder Name" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Loan Account Number<span class="text-danger">*</span></label>
                            <input type="text" name="loan_ac_no" id="loan_ac_no" class="form-control" placeholder="Enter Loan Account Number" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">IFSC Code<span class="text-danger">*</span></label>
                            <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" placeholder="Enter IFSC Code" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Loan Type</label>
                            <input type="text" id="lone_type" name="lone_type" class="form-control" placeholder="Enter Loan Type" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">UPI ID</label>
                            <input type="text" id="upi_id" name="upi_id" class="form-control" placeholder="Enter UPI ID" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Total Loan Ammount<span class="text-danger">*</span></label>
                            <input type="text" id="total_lone_amount" name="total_lone_amount" class="form-control" placeholder="Enter Total Loan Amount" required>
                        </div>
                        <div class="col-md-4  mb-3">
                            <label for="bankName" class="form-label">Remains Loan Ammount<span class="text-danger">*</span></label>
                            <input type="text" id="remains_loan_amount" name="remains_loan_amount" class="form-control" placeholder="Enter Remain Loan Amount" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="loanSubmitBtn">Add Loan Account</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal custom-modal fade" id="delete-bank-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
				<input type="hidden" id="deleteLoanId">
                <div class="form-header text-center">
                    <h3>Delete Loan Account</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDeleteLoan" data-bs-dismiss="modal" class="w-100 btn btn-primary">
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

	/* ---------- VIEW LOAN ---------- */
	$(document).on("click", ".view-loan", function () {
		let id = $(this).data("id");

		fetchLoan(id, true); // true = readonly
	});

	/* ---------- EDIT LOAN ---------- */
	$(document).on("click", ".edit-loan", function () {
		let id = $(this).data("id");

		fetchLoan(id, false); // false = editable
	});

	/* ---------- FETCH LOAN ---------- */
	function fetchLoan(id, isView = false) {
		$.get("/get-loan/" + id, function (data) {

			$("#loanId").val(data.id);
			$("#bank_name").val(data.bank_name);
			$("#branch").val(data.branch);
			$("#app_name").val(data.app_name);
			$("#loan_ac_no").val(data.loan_ac_no);
			$("#ifsc_code").val(data.ifsc_code);
			$("#lone_type").val(data.lone_type);
			$("#upi_id").val(data.upi_id);
			$("#total_lone_amount").val(data.total_lone_amount);
			$("#remains_loan_amount").val(data.remains_loan_amount);

			if (isView) {
				$("#addLoanFrm input, #addLoanFrm select").prop("disabled", true);
				$("#loanSubmitBtn").hide();
				$(".modal-title").text("View Loan Account");
			} else {
				$("#addLoanFrm input, #addLoanFrm select").prop("disabled", false);
				$("#loanSubmitBtn").show().text("Update Loan Account");
				$(".modal-title").text("Update Loan Account");
			}

			$("#add-loan-account-modal").modal("show");
		});
	}

	/* ---------- RESET MODAL WHEN CLOSED ---------- */
	$("#add-loan-account-modal").on("hidden.bs.modal", function () {
		$("#addLoanFrm")[0].reset();
		$("#loanId").val("");
		$("#addLoanFrm input, #addLoanFrm select").prop("disabled", false);
		$("#loanSubmitBtn").show().text("Add Loan Account");
		$(".modal-title").text("Add New Loan Account");
	});
	
	
    $("form#addLoanFrm").bind("submit", function() {
        //e.preventDefault();

        var loanId = $("#loanId").val();
        if (loanId == "") {
            var projurl = "/save_loan";
        } else {
            var projurl = "/update_loan";
        }
        var projectData = $("form#addLoanFrm").serialize();

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: projurl,
            type: "POST",
            data: projectData,
            success: function(response) {
                // $("#addLoanLoader").hide();
                if (response.class == "succ") {
                    // $("#addLoanFrm .message-container").html(
                    //     '<div class="' +
                    //         response.class +
                    //         '">' +
                    //         response.message +
                    //         "</div>"
                    // );
                    // window.location.href = response.redirect;

                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);

                } else {
                    $.each(response, function(idx, obj) {
                        // $("#addLoanFrm .message-container").html(
                        //     '<div class="err">' + obj + "</div>"
                        // );
                        showToast("Error: " + obj, "success");
                    });
                }
            },
        });
    });
	
	$(document).on("click", ".delete-loan", function () {
		let id = $(this).data("id");
		$("#deleteLoanId").val(id);
	});

	/* confirm delete */
	$(document).on("click", "#confirmDeleteLoan", function () {

		let id = $("#deleteLoanId").val();

		$.ajax({
			url: "/delete-loan/" + id,
			type: "DELETE",
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			success: function (response) {
				if (response.class == "succ") {
					showToast(response.message, "success");
					setTimeout(() => {
						window.location.href = response.redirect;
					}, 1500);
				}
			}
		});
	});
</script>

@endsection