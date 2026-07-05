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
                        <li class="breadcrumb-item"><a href="{{ route('user.LoanList') }}">Loan Accounts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Account Details</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Loan Account Details</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-transaction-modal"><i class="ti ti-square-plus f-20"></i> Add New Transaction</a>
                    <!--<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#upload-statement-modal"><i class="ti ti-file-upload f-20"></i> Upload Statement</a>-->

                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">

        <div class="col-md-5 col-xxl-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Loan Account Details</h5>
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical f-18"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">View</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete-loan-modal">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="card rounded-4 overflow-hidden" style="background-image: url(../assets/images/widget/img-card-bg.svg); background-size: cover;">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1 me-3">
                                        <p class="text-white text-sm text-opacity-50 mb-0">Bank Name</p>
                                        <h5 class="text-white">{{ $loan->bank_real_name }}</h5>
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
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avtar avtar-s bg-light-success flex-shrink-0">
                                            <i class="ph-duotone ph-trend-up f-20"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 text-muted">Total Paid Amount</p>
                                            <h6 class="mb-0">₹ {{ $totalPaid }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avtar avtar-s bg-light-danger flex-shrink-0">
                                            <i class="ph-duotone ph-trend-down f-20"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 text-muted">Total Remain Amount</p>
                                            <h6 class="mb-0">₹ {{ $remainingLoan }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="col-md-7 col-xxl-8">
            <div class="card table-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Transaction History</h5>
                    <button class="btn btn-sm btn-link-primary">View All</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date/Time</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($loan_ins as $loan_data)
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>{{ $loan_data->ins_date }}</td>
                                    <td>{{ $loan_data->message }}</td>
                                    <td>₹ {{ $loan_data->ins_amt }}</td>
                                    <td>
                                        <?php
                                        if ($loan_data->payment_mode == "Credit") {
                                        ?>
                                            <span class="badge text-bg-success">Credit</span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="badge text-bg-danger">Debit</span>
                                        <?php
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <a href="#" class="avtar avtar-xs btn-link-secondary" data-bs-toggle="modal" data-bs-target="#view-transaction-modal{{ $loan_data->id }}">
                                            <i class="ti ti-eye f-20"></i>
                                        </a>
                                        <a href="#" class="avtar avtar-xs btn-link-secondary" data-bs-toggle="modal" data-bs-target="#edit-transaction-modal{{ $loan_data->id }}">
                                            <i class="ti ti-edit f-20"></i>
                                        </a>
                                        <a href="javascript:void(0)" data-id="{{ ($loan_data->id) }}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-loan-trans" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                            <i class="ti ti-trash f-18"></i>
                                        </a>
                                    </td>
                                </tr>
                                {{-- Edit  --}}
                                <div class="modal custom-modal fade" id="edit-transaction-modal{{ $loan_data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Transaction</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            {{-- <form action="{{ route('update_installment') }}" method="post" name="editInsFrm" id="editInsFrm" enctype="multipart/form-data"> --}}
                                            <form method="post" name="editInsFrm" id="editInsFrm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" id="insId" value="{{ $loan_data->id }}">
                                                    <input type="hidden" name="loanId" id="loanId" value="{{ $loanId }}">
                                                    <input type="hidden" id="insDoc" value="">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="ins_date" class="form-label">Date<span class="text-danger">*</span></label>
                                                            <input type="date" name="ins_date" id="ins_date" value="{{ $loan_data->ins_date }}" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="message" class="form-label">Message<span class="text-danger">*</span></label>
                                                            <input type="text" name="message" id="message" value="{{ $loan_data->message }}" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="ins_amt" class="form-label">Amount<span class="text-danger">*</span></label>
                                                            <input type="text" name="ins_amt" id="ins_amt" value="{{ $loan_data->ins_amt }}" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="payment_mode" class="form-label">Account Transaction Type<span class="text-danger">*</span></label>
                                                            <select name="payment_mode" id="payment_mode" class="form-control" required>
                                                                <option value="">Select Transaction Type</option>
                                                                <!--<option value="Credit" {{ $loan_data->payment_mode == 'Credit' ? 'selected' : '' }}>Credit</option>-->
                                                                <option value="Debit" {{ $loan_data->payment_mode == 'Debit' ? 'selected' : '' }}>Debit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Transaction</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                {{-- view  --}}
                                <div class="modal custom-modal fade" id="view-transaction-modal{{ $loan_data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">View Transaction</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            {{-- <form action="{{ route('update_installment') }}" method="post" name="editInsFrm" id="editInsFrm" enctype="multipart/form-data"> --}}
                                            <form method="post" name="editInsFrm" id="editInsFrm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" id="insId" value="{{ $loan_data->id }}">
                                                    <input type="hidden" name="loanId" id="loanId" value="{{ $loanId }}">
                                                    <input type="hidden" id="insDoc" value="">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="ins_date" class="form-label">Date<span class="text-danger">*</span></label>
                                                            <input type="date" name="ins_date" id="ins_date" value="{{ $loan_data->ins_date }}" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="message" class="form-label">Message<span class="text-danger">*</span></label>
                                                            <input type="text" name="message" id="message" value="{{ $loan_data->message }}" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="ins_amt" class="form-label">Amount<span class="text-danger">*</span></label>
                                                            <input type="text" name="ins_amt" id="ins_amt" value="{{ $loan_data->ins_amt }}" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="payment_mode" class="form-label">Account Transaction Type<span class="text-danger">*</span></label>
                                                            <select name="payment_mode" id="payment_mode" class="form-control" required>
                                                                <option value="">Select Transaction Type</option>
                                                                <option value="Credit" {{ $loan_data->payment_mode == 'Credit' ? 'selected' : '' }}>Credit</option>
                                                                <option value="Debit" {{ $loan_data->payment_mode == 'Debit' ? 'selected' : '' }}>Debit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    {{-- <button type="submit" class="btn btn-primary">Update Transaction</button> --}}
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>



<div class="modal custom-modal fade" id="add-transaction-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0);" method="post" name="addInsFrm" id="addInsFrm" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Form Content -->

                    <input type="hidden" name="id" id="insId" value="">
                    <input type="hidden" name="loanId" id="loanId" value="{{ $loanId }}">
                    <input type="hidden" id="insDoc" value="">
                    @csrf
                    <!-- Bank Name -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bankName" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" name="ins_date" id="ins_date" class="form-control" placeholder="Enter Bank Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bankName" class="form-label">Message<span class="text-danger">*</span></label>
                            <input type="text" name="message" id="message" class="form-control" placeholder="Enter Message" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bankName" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="ins_amt" id="ins_amt" class="form-control" placeholder="Enter Amount" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transactionType" class="form-label">Account Transaction Type<span class="text-danger">*</span></label>
                            <select name="payment_mode" id="payment_mode" class="form-control" required>
                                <option value="">Select Transaction Type</option>
                                <!--<option value="Credit">Credit</option>-->
                                <option value="Debit">Debit</option>
                            </select>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal custom-modal fade" id="upload-statement-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- File Upload Form -->
                <form id="uploadStatementForm">
                    <div class="form-group mb-3">
                        <label for="statementFile" class="form-label">Upload Statement (PDF or Excel)</label>
                        <input type="file" id="statementFile" class="form-control" accept=".pdf,.xls,.xlsx" required>
                    </div>
                    <div id="filePreview" class="mt-3" style="display: none;">
                        <p><strong>Uploaded File:</strong> <span id="uploadedFileName"></span></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="uploadStatementForm">Upload Statement</button>
            </div>
        </div>
    </div>
</div>



<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Income</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal" class="w-100 btn btn-primary">
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
    document.getElementById("statementFile").addEventListener("change", function(event) {
        const file = event.target.files[0];
        const filePreview = document.getElementById("filePreview");
        const fileName = document.getElementById("uploadedFileName");

        if (file) {
            // Validate file type
            const allowedExtensions = ['application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            if (!allowedExtensions.includes(file.type)) {
                alert("Invalid file type. Please upload a PDF or Excel file.");
                showToast("Invalid file type. Please upload a PDF or Excel file.", "error");
                event.target.value = ""; // Clear the input
                filePreview.style.display = "none";
                return;
            }

            // Display file name
            filePreview.style.display = "block";
            fileName.textContent = file.name;
        } else {
            // Hide the preview section if no file is selected
            filePreview.style.display = "none";
        }
    });

    $("form#addInsFrm").bind("submit", function() {
        //e.preventDefault();

        var insId = "";
        var loanId = $("#loanId").val();

        let ins_date = $("#addInsFrm #ins_date").val();
        let payment_mode = $("#addInsFrm #payment_mode").val();
        let ins_amt = $("#addInsFrm #ins_amt").val();
        // let curr_amt = $("#addInsFrm #curr_amt").val();
        let message = $("#addInsFrm #message").val();
        // let ins_doc = $("#addInsFrm #ins_doc").prop("files")[0];

        var insData = new FormData();
        insData.append("ins_date", ins_date);
        insData.append("payment_mode", payment_mode);
        insData.append("ins_amt", ins_amt);
        // insData.append("curr_amt", curr_amt);
        insData.append("message", message);
        // insData.append("ins_doc", ins_doc);
        insData.append("id", insId);
        insData.append("loanId", loanId);


        var projurl = "/save_installment";

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: projurl,
            type: "POST",
            data: insData,
            contentType: false,
            processData: false,
            success: function(response) {
                // $("#addLoanLoader").hide();
                if (response.class == "succ") {
                    // $("#addInsFrm .message-container").html(
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
                        // $("#addInsFrm .message-container").html(
                        //     '<div class="err">' + obj + "</div>"
                        // );
                        showToast("Error: " + obj, "success");
                    });
                }
            },
        });
    });

    $(document).on("submit", "#editInsFrm", function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var projurl = "/update_installment";
        $.ajax({
            url: projurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === "success") {
                    // alert(response.message); 
                    // window.location.href = response.redirect;

                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    // alert("Error updating record.");
                    showToast("Error: " + obj, "error");
                }
            },
            error: function(xhr) {
                // console.log(xhr.responseText);
                // alert("An error occurred. Please try again.");
                showToast("An error occurred. Please try again.", "error");
            },
        });
    });

    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
	$(document).on("click", ".delete-loan-trans", function () {
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
                url: '/loan_trans_delete/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    // alert(response)
                    // alert(response.message); 
                    // location.reload(); 

                    showToast(response.message, "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    // alert("Error deleting Lone!");
                    showToast("Error deleting Lone!", "error");
                }
            });
        }
    });
</script>
@endsection