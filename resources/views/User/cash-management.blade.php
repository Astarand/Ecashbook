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
                            <li class="breadcrumb-item active" aria-current="page">Cash Management</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-cash-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h2 class="mb-0">Cash Management</h2>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-loan-account-modal">
                        <i class="ti ti-square-plus f-20"></i> Add New Transaction
                    </a>

                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <div class="col-md-6 col-xl-6">
            <div class="card statistics-card-1 overflow-hidden cash-in-hand-card">
                <div class="card-body">
                    <!-- <img src="../assets/images/widget/img-status-7.svg" alt="img" class="img-fluid img-bg"> -->
                    <div class="d-flex align-items-center">
                        <img src="../assets/images/widget/money.png" alt="img" class="img-fluid">
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-3 text-muted">Total Cash In Hand</h3>
                            <div class="d-inline-flex align-items-center">
                                <h5 class="f-w-300 d-flex align-items-center m-b-0">As on : <?php echo date("d-m-Y"); ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-5 text-center">
                        <div class="col-12">
                            <p class="mb-0 text-muted">Total Cash in Hand</p>
                            <h5 class="mb-0">₹ {{ $cash_in_hand }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="card statistics-card-1 overflow-hidden cash-details-card">
                <div class="card-body">
                    <!-- <img src="../assets/images/widget/img-status-9.svg" alt="img" class="img-fluid img-bg"> -->
                    <div class="d-flex align-items-center">
                        <img src="../assets/images/widget/cash-flow.png" alt="img" class="img-fluid">
                        <div class="flex-grow-1 ms-3 mt-3">
                            <h3 class="mb-0 text-muted">Cash Details</h3>
                        </div>
                    </div>
                    <div class="row g-3 mt-5 text-center">
                        <div class="col-4">
                            <p class="mb-0 text-muted">As On</p>
                            <h5 class="mb-0 text-primary"><?php echo date("d-m-Y"); ?></h5>
                        </div>
                        <div class="col-4 border-start">
                            <p class="mb-0 text-muted">Credit Amount</p>
                            <h5 class="mb-0 text-success">₹ {{ $total_credit }}</h5>
                        </div>
                        <div class="col-4 border-start">
                            <p class="mb-0 text-muted">Debit Amount</p>
                            <h5 class="mb-0 text-danger">₹ {{ $total_debit }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        // echo '<pre>';
        // print_r($cash_trans_data);
    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card table-card">
                <div class="card-body table-card w-100">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								<th>PROPRIETORSHIP COMPANY</th>
                                <th>Date</th>
                                <th>Transaction Type</th>
                                <th>Particulars</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cash_trans_data as $key => $data)
                                <tr class="{{ $data->cd_type == 'dr' ? 'bg-danger bg-opacity-25' : 'bg-success bg-opacity-25' }}">
                                    <td class="text-end">{{ $key + 1 }}</td>
									<td><span class="text-muted text-hover-primary">
                                        {{ $data->comp_name }}</span>
                                    </td>
                                    <td><span class="text-muted text-hover-primary">{{ \Carbon\Carbon::parse($data->cd_date)->format('d-m-Y') }}</span></td>
                                    <td><a class="text-muted text-hover-primary" href="#">
                                        {{ $data->cd_type == 'dr' ? 'Debit' : 'Credit' }}</a>
                                    </td>
                                    <td><span class="text-muted text-hover-primary">
                                        {{ $data->particulars }}</span>
                                    </td>
                                    <td><span class="text-muted text-hover-primary">₹{{ number_format($data->cd_amount, 2) }}</span></td>
                                    <td>
                                        <span><i class="ti ti-dots-vertical f-20"></i></span>
                                        <div class="prod-action-links">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                    <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                    data-bs-toggle="modal" data-bs-target="#view_loan_account_modal{{ $data->id }}">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                </li>   
                                                @if ($req_type != 1)
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                        <a href="#!" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal" data-bs-target="#edit_loan_account_modal{{ $data->id }}">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                        <a href="" data-id="{{ base64_encode($data->id) }}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                            <i class="ti ti-trash f-18"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                {{-- Edit Modal --}}
                                <div class="modal custom-modal fade" id="edit_loan_account_modal{{ $data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Cash Transaction </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            {{-- <form  action="javascript:void(0);" method="POST" name="editCashCreditFrm" id="editCashCreditFrm" enctype="multipart/form-data"> --}}
                                            <form  action="{{ route('update_cash_credit') }}" method="POST" name="editCashCreditFrm" id="editCashCreditFrm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="cId" id="cId" value="{{ $data->id }}" >
                                                <div class="modal-body">
                                                    <!-- Form Content -->
                                                    
                                                        <!-- Bank Name -->
                                                        <div class="row">
															<div class="col-md-6  mb-3">
																<label class="form-label">Proprietorship Company</label>
																<select name="propId" class="form-control">
																	<option value="">{{ parentCompanyName() }}</option>
																	@foreach($proprietorships as $company)
																		<option value="{{ $company->id }}" <?=($data->propId == $company->id) ? 'selected' : '' ?>>
																			{{ $company->comp_name }}
																		</option>
																	@endforeach
																</select>
															</div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Date<span class="text-danger">*</span></label>
                                                                <input type="date" name="cd_date" id="cd_date" value="{{ $data->cd_date }}" class="form-control" placeholder="Enter Bank Name" required>
                                                            </div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Transaction Type<span class="text-danger">*</span></label>
                                                                <select class="form-select" name="cd_type" id="cd_type" required>
                                                                    <option disabled {{ !isset($data->cd_type) ? 'selected' : '' }}>Select Transaction Type</option>
                                                                    <option value="cr" {{ isset($data->cd_type) && $data->cd_type == 'cr' ? 'selected' : '' }}>Credit</option>
                                                                    <option value="dr" {{ isset($data->cd_type) && $data->cd_type == 'dr' ? 'selected' : '' }}>Debit</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Particulars<span class="text-danger">*</span></label>
                                                                <input type="text" name="particulars" value="{{ $data->particulars }}" id="particulars" class="form-control" placeholder="Enter Account Holder Name" required>
                                                            </div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Amount<span class="text-danger">*</span></label>
                                                                <input type="text" name="cd_amount" id="cd_amount" value="{{ $data->cd_amount }}" class="form-control" placeholder="Enter Loan Account Number" required>
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" >Update Transaction</button>
                                                    {{-- <button type="submit" data-bs-dismiss="modal" class="btn btn-primary paid-continue-btn">Save</button> --}}
                                
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- View Modal --}}
                                <div class="modal custom-modal fade" id="view_loan_account_modal{{ $data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">View Cash Transaction</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            {{-- <form  action="javascript:void(0);" method="POST" name="editCashCreditFrm" id="editCashCreditFrm" enctype="multipart/form-data"> --}}
                                            <form  action="{{ route('update_cash_credit') }}" method="POST" name="editCashCreditFrm" id="editCashCreditFrm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="cId" id="cId" value="{{ $data->id }}" >
                                                <div class="modal-body">
                                                    <!-- Form Content -->
                                                    
                                                        <!-- Bank Name -->
                                                        <div class="row">
															<div class="col-md-6  mb-3">
																<label class="form-label">Proprietorship Company</label>
																<select name="propId" class="form-control">
																	<option value="">{{ parentCompanyName() }}</option>
																	@foreach($proprietorships as $company)
																		<option value="{{ $company->id }}" <?=($data->propId == $company->id) ? 'selected' : '' ?>>
																			{{ $company->comp_name }}
																		</option>
																	@endforeach
																</select>
															</div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Date<span class="text-danger">*</span></label>
                                                                <input type="date" name="cd_date" id="cd_date" value="{{ $data->cd_date }}" class="form-control" placeholder="Enter Bank Name" required>
                                                            </div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Transaction Type<span class="text-danger">*</span></label>
                                                                <select class="form-select" name="cd_type" id="cd_type" required>
                                                                    <option disabled {{ !isset($data->cd_type) ? 'selected' : '' }}>Select Transaction Type</option>
                                                                    <option value="cr" {{ isset($data->cd_type) && $data->cd_type == 'cr' ? 'selected' : '' }}>Credit</option>
                                                                    <option value="dr" {{ isset($data->cd_type) && $data->cd_type == 'dr' ? 'selected' : '' }}>Debit</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Particulars<span class="text-danger">*</span></label>
                                                                <input type="text" name="particulars" value="{{ $data->particulars }}" id="particulars" class="form-control" placeholder="Enter Account Holder Name" required>
                                                            </div>
                                                            <div class="col-md-6  mb-3">
                                                                <label for="bankName" class="form-label">Amount<span class="text-danger">*</span></label>
                                                                <input type="text" name="cd_amount" id="cd_amount" value="{{ $data->cd_amount }}" class="form-control" placeholder="Enter Loan Account Number" required>
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    {{-- <button type="submit" class="btn btn-primary" >Add Transaction</button> --}}
                                                    {{-- <button type="submit" data-bs-dismiss="modal" class="btn btn-primary paid-continue-btn">Save</button> --}}
                                
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </tbody>
                        
                        <!-- Pagination Links -->
                        {{-- <div class="d-flex justify-content-center mt-3">
                            {{ $cash_trans_data->links() }}
                        </div> --}}
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="add-loan-account-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Cash Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="javascript:void(0);" method="POST" name="addCashCreditFrm" id="addCashCreditFrm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Form Content -->
                    
                        <!-- Bank Name -->
                        <div class="row">
							<div class="col-md-6  mb-3">
								<label class="form-label">Proprietorship Company</label>
								<select name="propId" class="form-control">
									<option value="">{{ parentCompanyName() }}</option>
									@foreach($proprietorships as $company)
										<option value="{{ $company->id }}">
											{{ $company->comp_name }}
										</option>
									@endforeach
								</select>
							</div>
                            <div class="col-md-6  mb-3">
                                <label for="bankName" class="form-label">Date<span class="text-danger">*</span></label>
                                <input type="date" name="cd_date" id="cd_date" class="form-control" placeholder="Enter Bank Name" required>
                            </div>
                            <div class="col-md-6  mb-3">
                                <label for="bankName" class="form-label">Transaction Type<span class="text-danger">*</span></label>
                                <select class="form-select" name="cd_type" id="cd_type" required>
                                    <option selected>Select Transaction Type</option>
                                    <option value="cr">Credit</option>
                                    <option value="dr">Debit</option>
                                </select>
                            </div>
                            <div class="col-md-6  mb-3">
                                <label for="bankName" class="form-label">Particulars<span class="text-danger">*</span></label>
                                <input type="text" name="particulars" id="particulars" class="form-control" placeholder="Enter Account Holder Name" required>
                            </div>
                            <div class="col-md-6  mb-3">
                                <label for="bankName" class="form-label">Amount<span class="text-danger">*</span></label>
                                <input type="text" name="cd_amount" id="cd_amount" class="form-control" placeholder="Enter Amount" required>
                            </div>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" >Add Transaction</button>
                    {{-- <button type="submit" data-bs-dismiss="modal" class="btn btn-primary paid-continue-btn">Save</button> --}}

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCashModal" tabindex="-1" aria-labelledby="editCashModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCashModalLabel">Update In-Hand Cash</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="cashInput" class="form-label">Update In-Hand Cash</label>
            <input type="number" class="form-control" id="cashInput" placeholder="Enter new amount">
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Transaction </h3>
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
    $("form#addCashCreditFrm").bind("submit", function () {
            
        var cId = "";

        if (cId == "") {
            var surl = "/save_cash_credit";
        } else {
            var surl = "/save_cash_credit";
        }
        var cData = $("form#addCashCreditFrm").serialize();
        //alert("hi");
        $("#loader").show();
        $.ajax({
            headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            url: surl,
            type: "POST",
            data: cData,
            success: function (response) {
                $("#loader").hide();
                //console.log(response);
                
                
                if (response.class == "succ") {
                    // $("#addCashCreditFrm .message-container").html(
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
                    $.each(response, function (idx, obj) {
                        // $("#addCashCreditFrm .message-container").html(
                        //     '<div class="err">' + obj + "</div>"
                        // );
                        showToast("Error: " + obj, "error");
                    });
                }
            },
        });            
    });

    // $("form#editCashCreditFrm").bind("submit", function () {
            
    //     var cId = $("#cId").val();

    //     if (cId == "") {
    //         var surl = "/update_cash_credit";
    //     } else {
    //         var surl = "/update_cash_credit";
    //     }
    //     var cData = $("form#editCashCreditFrm").serialize();
    //     //alert("hi");
    //     $.ajax({
    //         headers: {
    //                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //             },
    //         url: surl,
    //         type: "POST",
    //         data: cData,
    //         success: function (response) {
    //             console.log(response);
                
                
    //             // if (response.class == "succ") {
    //             //     $("#addCashCreditFrm .message-container").html(
    //             //         '<div class="' +
    //             //             response.class +
    //             //             '">' +
    //             //             response.message +
    //             //             "</div>"
    //             //     );
    //             //     window.location.href = response.redirect;
    //             // } else {
    //             //     $.each(response, function (idx, obj) {
    //             //         $("#addCashCreditFrm .message-container").html(
    //             //             '<div class="err">' + obj + "</div>"
    //             //         );
    //             //     });
    //             // }
    //         },
    //     });            
    // });

    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $('.delete-btn').on('click', function () {
        deleteId = $(this).data('id'); 
    });

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function () {
        //alert(deleteId);
        
        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/cash_trans_delete/' + deleteId,  // Update with your delete route
                type: 'DELETE',
                success: function (response) {
                    // alert(response)
                    // alert(response.message); 
                    // location.reload(); 

                    showToast(response.message, "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function (xhr) {
                    alert("Error deleting Lone!");
                    showToast("Error deleting Lone!", "error");
                }
            });
        }
    });
    function startCashTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Cash Management Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-wallet" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Cash Management dashboard. Here you can track your business cash flows, cash-in-hand balances, and transactions.</p></div>'
                },
                {
                    element: '.cash-in-hand-card',
                    title: 'Total Cash in Hand',
                    intro: 'This card displays your current total cash balance available on hand.'
                },
                {
                    element: '.cash-details-card',
                    title: 'Cash Flow Details',
                    intro: 'View the summary of all Credit (received) and Debit (spent) cash transactions.'
                },
                {
                    element: 'a[data-bs-target="#add-loan-account-modal"]',
                    title: 'Add Transaction',
                    intro: 'Click here to record a new cash credit or debit transaction.'
                },
                {
                    element: '.table-card',
                    title: 'Transaction History',
                    intro: 'Browse, search, edit, or delete recent cash transactions from this history table.'
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
        $('#start-cash-tour').on('click', function(e) {
            e.preventDefault();
            startCashTour();
        });
    });
</script>

@endsection