@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Cash & Banking</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.BankList') }}">Bank Account Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Bank Details</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-bank-details-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Bank Details</h2>
                    </div>
                </div>
                @if ($req_type != 1)
                <div class="col-md-8 text-end mt-2">
                    <a href="#" class="btn btn-primary" id="add-transaction-btn" data-bs-toggle="modal" data-bs-target="#add-transaction-modal"><i class="ti ti-square-plus f-20"></i> Add New Transaction</a>
                    <a href="#" class="btn btn-primary" id="upload-statement-btn" data-bs-toggle="modal" data-bs-target="#upload-statement-modal"><i class="ti ti-file-upload f-20"></i> Upload Bank Statement</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <div class="col-md-4 col-xxl-4">
            <div class="col-md-12">
                <div class="card" id="bank-info-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Bank Details</h5>
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical f-18"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">View</a>
                                    @if ($req_type != 1)
                                        <a class="dropdown-item" href="#">Edit</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete-bank-modal">Delete</a>
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
										<h5 class="text-white">{{ $bank->comp_name }}</h5>
									</div>
								</div>
								@endif
                                <div class="d-flex">
                                    <div class="flex-grow-1 me-3">
                                        <p class="text-white text-sm text-opacity-50 mb-0">Bank Name</p>
                                        <h5 class="text-white">{{ $bank->bank_name }}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-auto">
                                        <p class="text-white text-sm text-opacity-100 mb-0">Account Name</p>
                                        <h4 class="text-white mb-3">{{ $bank->bank_ac_no }}</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-auto">
                                        <p class="text-white text-sm text-opacity-50 mb-0">IFSC Code</p>
                                        <h6 class="text-white mb-0">{{ $bank->ifsc_code }}</h6>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white text-sm text-opacity-50 mb-0">Branch</p>
                                        <h6 class="text-white mb-0">{{ $bank->bank_branch }}</h6>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white text-sm text-opacity-50 mb-0">Swift Code</p>
                                        <h6 class="text-white mb-0">{{ $bank->swift_code }}</h6>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-auto">
                                        <p class="text-white text-sm text-opacity-50 mb-0">Account Name</p>
                                        <h6 class="text-white mb-3">{{ $bank->accholder_name }}</h6>
                                    </div>
                                    <div class="col-auto">
                                        <p class="text-white text-sm text-opacity-50 mb-0">Account Balance</p>
                                        <h6 class="text-white mb-0">₹{{ $bank->curr_bal }}</h6>
                                    </div>
                                    @if(!empty($bank->bank_qr_code))
                                        <div class="col-auto">
                                            <p class="text-white text-sm text-opacity-50 mb-2">Bank QR Code</p>

                                            <button type="button"
                                                    class="btn btn-sm btn-light toggleQrBtn"
                                                    data-target="qrCode{{ $bank->id }}">
                                                <i class="ti ti-eye"></i> View
                                            </button>

                                            <div id="qrCode{{ $bank->id }}" class="mt-2 d-none">
                                                <img src="{{ asset('storage/'.$bank->bank_qr_code) }}"
                                                    alt="Bank QR Code"
                                                    class="img-fluid bg-white p-2 rounded"
                                                    style="width:120px;height:120px;object-fit:contain;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="card" id="credit-debit-summary-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avtar avtar-s bg-light-success flex-shrink-0">
                                            <i class="ph-duotone ph-trend-up f-20"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 text-muted">Total Credit Amount</p>
                                            <h6 class="mb-0">₹ {{ $totalTransAmounts->totalCredit }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avtar avtar-s bg-light-danger flex-shrink-0">
                                            <i class="ph-duotone ph-trend-down f-20"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 text-muted">Total Debit Amount</p>
                                            <h6 class="mb-0">₹ {{ $totalTransAmounts->totalDebit }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-8 col-xxl-8">
            <div class="card table-card" id="transaction-history-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Transaction History</h5>
                    <button class="btn btn-sm btn-link-primary">View All</button>
                </div>
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table tbl-product my-3" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date/Time</th>
									<th>Amount</th>
                                    <th>Type</th>
                                    <th>Description</th>
									<th>Purpose</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($bank_trans as $val)
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>{{ date("d-m-Y", strtotime($val->tran_date)) }}</td>
									<td>₹ {{ $val->tran_amt }}</td>
                                    <td>
                                        <?php
                                        if ($val->tran_type == "Credit") { ?>
                                            <span class="badge text-bg-success">{{ $val->tran_type }}</span>
                                        <?php
                                        } else { ?>
                                            <span class="badge text-bg-danger">{{ $val->tran_type }}</span>
                                        <?php

                                        }
                                        ?>

                                    </td>
                                    <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $val->purpose }}">
										{{ \Illuminate\Support\Str::limit($val->purpose, 20) }}
									</td>
									
									<td>
									{{ !empty($val->reference) ? $val->reference : '' }}
									</td>
                                    <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="#"
                                                    data-bs-toggle="modal" data-bs-target="#view-transaction-modal{{ $val->id }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            @if ($req_type != 1)
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                    <a 
                                                    href="#" 
                                                    data-bs-toggle="modal" data-bs-target="#edit-transaction-modal{{ $val->id }}" 
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                        <i class="ti ti-edit-circle f-18"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a 
                                                    href="#" 
                                                    data-id="{{ $val->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_modal"
                                                    class="delete-btn avtar avtar-xs btn-link-danger btn-pc-default" >
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @foreach ($bank_trans as $val)
            {{-- Edit Modal --}}
            <div class="modal fade" id="edit-transaction-modal{{ $val->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="javascript:void(0);" class="updateTranFrm">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{ $val->id }}">
                                <input type="hidden" name="bankId" value="{{ $bankId }}">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="tran_date" value="{{ $val->tran_date }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Purpose</label>
                                        <input type="text" name="purpose" value="{{ $val->purpose }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Amount</label>
                                        <input type="text" name="tran_amt" value="{{ $val->tran_amt }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transaction Type</label>
                                        <select name="tran_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="Credit" {{ $val->tran_type == 'Credit' ? 'selected' : '' }}>Credit</option>
                                            <option value="Debit" {{ $val->tran_type == 'Debit' ? 'selected' : '' }}>Debit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ref/Cheq No.</label>
                                        <input type="text" name="ref_no" value="{{ $val->ref_no }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- View Modal --}}
            <div class="modal custom-modal fade" id="view-transaction-modal{{ $val->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tran_date" class="form-label">Date<span class="text-danger">*</span></label>
                                    <input type="date" name="tran_date" value="{{ $val->tran_date }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="purpose" class="form-label">Purpose<span class="text-danger">*</span></label>
                                    <input type="text" name="purpose" value="{{ $val->purpose }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tran_amt" class="form-label">Amount<span class="text-danger">*</span></label>
                                    <input type="text" name="tran_amt" value="{{ $val->tran_amt }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tran_type" class="form-label">Account Transaction Type<span class="text-danger">*</span></label>
                                    <select name="tran_type" class="form-control" required>
                                        <option value="">Select Transaction Type</option>
                                        <option value="Credit" {{ $val->tran_type == 'Credit' ? 'selected' : '' }}>Credit</option>
                                        <option value="Debit" {{ $val->tran_type == 'Debit' ? 'selected' : '' }}>Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ref_no" class="form-label">Ref/Cheq No.</label>
                                    <input type="text" name="ref_no" value="{{ $val->ref_no }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
            <form action="javascript:void(0);" method="POST" name="addTranFrm" id="addTranFrm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="addtranId">
                    <input type="hidden" name="bankId" id="addbankId" value="{{ $bankId }}">
                    <input type="hidden" name="prop_id" id="addprop_id" value="{{ $prop_id }}">
                    <input type="hidden" id="addtranDoc" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tran_date" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" name="tran_date" id="addtran_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="purpose" class="form-label">Purpose<span class="text-danger">*</span></label>
                            <input type="text" name="purpose" id="addpurpose" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tran_amt" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="tran_amt" id="addtran_amt" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tran_type" class="form-label">Account Transaction Type<span class="text-danger">*</span></label>
                            <select name="tran_type" id="addtran_type" class="form-control" required>
                                <option value="">Select Transaction Type</option>
                                <option value="Credit">Credit</option>
                                <option value="Debit">Debit</option>
                            </select>
                        </div>
						<div class="col-md-6 mb-3">
                            <label for="tran_amt" class="form-label">Ref/Cheq No.</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control">
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
		<form action="javascript:void(0);" method="post" name="uploadStatementForm" id="uploadStatementForm" enctype="multipart/form-data">
		@csrf
			<div class="message-container"></div>
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add New Transaction Document</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="bank_id" name="bank_id" value="<?php echo $bankId; ?>">
					<input type="hidden" id="pid" name="pid" value="<?php echo $prop_id; ?>">
					<!-- File Upload Form -->
					<!--<form id="uploadStatementForm">-->
						<div class="form-group mb-3">
							<label for="statementFile" class="form-label">Upload Statement (Excel)</label>
							<input type="file" id="statementFile" name="bankstatement" class="form-control" accept=".pdf,.xls,.xlsx" required>
						</div>
						<div id="filePreview" class="mt-3" style="display: none;">
							<p><strong>Uploaded File:</strong> <span id="uploadedFileName"></span></p>
						</div>
						
						<div class="alert border-0 shadow-sm"
							 style="background:#f0fdf4;border-left:4px solid #198754 !important;">
							
							<div class="fw-bold text-success mb-2">
								<i class="fas fa-shield-alt me-1"></i> Before Uploading
							</div>

							<ul class="mb-0 text-dark">
								<li>Upload the statement for the selected bank account only.</li>
								<li>Password-protected or encrypted Excel files are not supported.</li>
								<li>Please upload a valid, unlocked Excel file (.xlsx or .xls).</li>
							</ul>
						</div>
					<!--</form>-->
					<!--<a href="{{asset('uploads/bank_statement/bankstatement.xlsx')}}" download>Download Sample Excel</a>-->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" form="uploadStatementForm">Upload Statement</button>
				</div>
			</div>
		</form>
    </div>
</div>



<!-- Delete Confirmation Modal -->
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Transaction</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" class="w-100 btn btn-danger">
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

<style>
.datatable-info {
    display: none !important;
}
.custom-pagination .pagination {
    font-size: 12px !important;
    margin-bottom: 0;
}

.custom-pagination .page-link {
    padding: 4px 8px !important;
    font-size: 12px !important;
    min-width: 28px;
    text-align: center;
}

.custom-pagination .page-item {
    margin: 0 2px;
}

/* Fix large arrow icons */
.custom-pagination .page-link svg {
    width: 12px !important;
    height: 12px !important;
}

/* Reduce arrow button padding */
.custom-pagination .page-item:first-child .page-link,
.custom-pagination .page-item:last-child .page-link {
    padding: 4px 6px !important;
}
</style>
<!-- Bootstrap 5 -->
@endsection

@section('page-script')
<script>

	document.addEventListener("DOMContentLoaded", function() {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
	});

    $("form#addTranFrm").submit(function(e) {
        e.preventDefault();

        var tranId = $("#addtranId").val();
        var bankId = $("#addbankId").val();
        var prop_id = $("#addprop_id").val();

        let tran_date = $("#addtran_date").val();
        let purpose = $("#addpurpose").val();
        let tran_type = $("#addtran_type").val();
        let tran_amt = $("#addtran_amt").val();
        let ref_no = $("#ref_no").val();

        var tranData = new FormData();
        tranData.append("_token", $('meta[name="csrf-token"]').attr("content")); // CSRF Token
        tranData.append("tran_date", tran_date);
        tranData.append("tran_type", tran_type);
        tranData.append("purpose", purpose);
        tranData.append("tran_amt", tran_amt);
        tranData.append("ref_no", ref_no);
        tranData.append("id", tranId);
        tranData.append("bankId", bankId);
        tranData.append("prop_id", prop_id);

        var projurl = "/save_transaction";

        $.ajax({
            url: projurl,
            type: "POST",
            data: tranData,
            contentType: false,
            processData: false,
            success: function(response) {
                // console.log(response);
                if (response.class === "succ") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    showToast(response, "error");
                }
            },
            error: function(xhr) {
                showToast(xhr.responseText, "error");
            }
        });
    });

    //--------- Update Modal ---------
	//--------- Update Modal ---------
	$(document).on('submit', 'form.updateTranFrm', function(e) {
		e.preventDefault();

		var form = $(this); // jQuery object for the current form

		// Safely grab values relative to this form
		var tranId    = form.find('input[name="id"]').val();
		var bankId    = form.find('input[name="bankId"]').val();
		var tran_date = form.find('input[name="tran_date"]').val();
		var purpose   = form.find('input[name="purpose"]').val();
		var tran_type = form.find('select[name="tran_type"]').val();
		var tran_amt  = form.find('input[name="tran_amt"]').val();
		var ref_no    = form.find('input[name="ref_no"]').val();

		console.log({ tranId, bankId,tran_date, purpose, tran_type, tran_amt, ref_no });

		var tranData = new FormData();
		tranData = form.serialize();
		/*tranData.append("_token", $('meta[name="csrf-token"]').attr("content"));
		tranData.append("id", tranId);
		tranData.append("bankId", bankId);
		tranData.append("tran_date", tran_date);
		tranData.append("purpose", purpose);
		tranData.append("tran_type", tran_type);
		tranData.append("tran_amt", tran_amt);
		tranData.append("ref_no", ref_no);*/

		$.ajax({
			url: "/update_transaction",
			type: "POST",
			data: tranData,
			success: function(response) {
				if (response.class === "succ") {
					showToast(response.message, "success");
					setTimeout(() => window.location.href = response.redirect, 1500);
				} else {
					showToast(response.message || "Error", "error");
				}
			},
			error: function(xhr) {
				showToast(xhr.responseText || "Error", "error");
			}
		});
	});

    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $(document).on('click', '.delete-btn', function() {
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
                url: '/bank_statement_delete/' + deleteId,
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
                    showToast("Error deleting customer!", "success");
                }
            });
        }
    });
	
	//Start Bank statement upload

	$('form#uploadStatementForm').bind('submit',function(){

			$('#loader').show();
			let bank_id = $("#uploadStatementForm #bank_id").val();
			let prop_id = $("#uploadStatementForm #pid").val();
			let bankstatement = $('#uploadStatementForm #statementFile').prop('files')[0];
			

			let statementData = new FormData();

			statementData.append('bank_id', bank_id);
			statementData.append('prop_id', prop_id);
			statementData.append('bankstatement', bankstatement);
			$.ajax({
				url: '/uploadBank_statement',
				type:'POST',
				data:statementData,
				contentType: false,
				processData: false,
				success: function(response) {
					$('#loader').hide();
					
					if (response.class=="succ") {
						$("#uploadStatementForm")[0].reset();
						$("#uploadStatementForm .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
						window.location.href = response.redirect;
					} else {
						$.each(response, function(idx, obj) {
							$("#uploadStatementForm .message-container").html('<div class="err">'+obj+'</div>');
						});
					}
				}
			});

	});
	//End Bank statement upload

    function startBankDetailsTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Bank Details Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-building-bank" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review your bank account particulars, monitor credits/debits, log manual transactions, or upload statements.</p></div>'
                },
                {
                    element: '#bank-info-card',
                    title: 'Bank Profile Card',
                    intro: 'Shows the registered bank name, account number, branch, IFSC code, and current book balance.'
                },
                {
                    element: '#credit-debit-summary-card',
                    title: 'Flow Overview',
                    intro: 'A quick tally of the total credit and total debit amounts logged in this account.'
                },
                {
                    element: '#add-transaction-btn',
                    title: 'Manual Entry',
                    intro: 'Click here to manually record a transaction, specifying transaction date, purpose, amount, type, and reference.'
                },
                {
                    element: '#upload-statement-btn',
                    title: 'Import Bank Statement',
                    intro: 'Click here to upload an Excel bank statement. The system will parse and import all transactions automatically.'
                },
                {
                    element: '#transaction-history-card',
                    title: 'Transaction Logs',
                    intro: 'Lists all transaction history. Check entry dates, amounts, credit/debit badges, and actions to edit/delete/view details.'
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
        $('#start-bank-details-tour').on('click', function(e) {
            e.preventDefault();
            startBankDetailsTour();
        });
    });

    //----- Toggle QR Code -----
    $(document).on('click', '.toggleQrBtn', function () {

        let target = $('#' + $(this).data('target'));
        let icon = $(this).find('i');

        if (target.hasClass('d-none')) {

            target.removeClass('d-none');

            $(this).contents().last()[0].textContent = ' Hide';

            icon.removeClass('ti-eye').addClass('ti-eye-off');

        } else {

            target.addClass('d-none');

            $(this).contents().last()[0].textContent = ' View';

            icon.removeClass('ti-eye-off').addClass('ti-eye');
        }
    });
</script>
@endsection