@extends('App.Layout')

@section('container')

<style>
    .spinner {
        border: 4px solid #f3f3f3; /* Light grey */
        border-top: 4px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Sales & Revenue</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Invoices</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-sales-invoice-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Sales Invoice List</h2>
                    </div>
                </div>
				@if ($invoice_create_status == "true" && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5))
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.CreateSalesInvoices') }}" id="add-sales-invoice-btn" class="btn btn-primary"><i
                            class="ti ti-square-plus"></i> Add New Sales Invoice</a>
                </div>
				@endif

            </div>
            <br>
            @if ($invoice_create_status == "false")
            <div class="alert alert-danger" role="alert">
                Maximum 2 invoices can remain pending. Please clear previous invoices to create a new one.
            </div>
            @endif
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
				
				<div class="alert alert-info mb-3" style="font-size:13px;">
					<h6 class="mb-2">
						<i class="ti ti-info-circle me-1"></i>
						Signature Instructions
					</h6>

					<p class="mb-1">
						🔐 <strong>Digital Signature (DSC):</strong>
						Download the invoice PDF, sign it using your DSC/USB Token, and upload the signed PDF for <strong>GST compliance, audits, and official records</strong>.
					</p>

					<p class="mb-0">
						✍️ <strong>Normal Signature:</strong>
						Upload a signature image in <strong>PNG</strong> format during Edit Invoice.
					</p>
				</div>
 				
 				<div class="table-responsive">
					<table class="table tbl-product"  id="pc-dt-simple">
						<thead>
							<tr>
								<th class="text-end">#</th>
								@if($hasProprietorship)
								<th>Proprietorship Company</th>
								@endif
								<th>Customer Name</th>
								<th>Invoice Number</th>
								<th>Invoice Date</th>
								<th>Transation Type</th>
								<th>Grand Total</th>
								<th>Due</th>
								<th>Digital Signature</th>
								<th>Payment Status</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; ?>
							@foreach ($sales as $sale)
							<tr>
								<td class="text-end">
									<?php echo $i++; ?>
								</td>
								@if($hasProprietorship)
								<td><span class="text-muted text-hover-primary">{{ !empty($sale->prop_name) ? $sale->prop_name : $sale->comp_name }}</span></td>
								@endif
								<td>
									<div class="row">
										<div class="col">
											<h6 class="mb-1">{{ $sale->cust_name }}</h6>
											<a class="text-muted f-12 text-hover-primary"
												href="tel:+91{{ $sale->cust_phone }}">{{ $sale->cust_phone }}</a>
										</div>
									</div>
								</td>
								<td><span class="text-muted text-hover-primary">{{ $sale->inv_num }}</span></td>
								<td><a class="text-muted text-hover-primary" href="#">{{ date("d-m-Y",
										strtotime($sale->inv_date)) }}</a></td>
								<td><span class="text-muted text-hover-primary">{{ $sale->mode_of_pay }}
										@if ($sale->mode_of_pay == 'OTHER')
										[{{ $sale->other_payment }}]
										@endif
									</span>
								</td>
								<td><span class="text-muted text-hover-primary">₹&nbsp; {{ $sale->total_amount }}</span></td>
								<td><span class="text-muted text-hover-primary">₹&nbsp; {{ $sale->due_amount ?? 0 }}</span></td>
								<td>
									@if ($sale->signed_pdf_status == 1)
										<span class="badge bg-success">
											<i class="ti ti-circle-check f-14"></i>
											Signed
										</span>
									@else
										<span class="badge bg-warning text-dark">
											<i class="ti ti-circle-x f-14"></i>
											Not Signed
										</span>
									@endif
								</td>
								<td>
									@if ($sale->pay_status == 'Full')
									<span class="badge bg-success">Full Payment</span>
									@elseif ($sale->pay_status == 'Partial')
									<span class="badge bg-warning text-dark">Advance</span>                                    
									@else
									<span class="badge bg-danger">Due</span>
									@endif
								</td>
								<td>
									@if ($sale->status == '0')
									<span class="badge bg-warning">Pending</span>
									@elseif ($sale->status == '1')
									<span class="badge bg-success">Active</span>    
									@elseif ($sale->status == '2')
									<span class="badge bg-danger">Cancelled</span>    
									@endif
								</td>
								<td>
									<span><i class="ti ti-dots-vertical f-20"></i></span>
									<div class="prod-action-links">
										<ul class="list-inline me-auto mb-0">
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="View Invoice">
												<a target="_blank" href="{{ url('/sales-invoice-pdf/'.base64_encode($sale->id).'/invoice') }}"
													class="avtar avtar-xs btn-link-warning btn-pc-default">
													<i class="ti ti-file f-18"></i>
												</a>
											</li>
											@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
											<li class="list-inline-item align-bottom"
												data-bs-toggle="tooltip"
												data-bs-placement="top"
												title="Upload Digitally Signed PDF">
												<a href="#"
												   class="avtar avtar-xs btn-link-primary btn-pc-default upload-pdf-btn"
												   data-id="{{$sale->id}}"
												   data-type="sales"
												   data-inv="{{$sale->inv_num}}"
												   data-bs-toggle="modal"
												   data-bs-target="#uploadPdfModal">
													<i class="ti ti-cloud-upload f-18"></i>
												</a>
											</li>
											@endif
											@if($sale->signed_pdf_status==1)
											<li class="list-inline-item align-bottom"
												data-bs-toggle="tooltip"
												title="View Signed PDF">
												<a href="javascript:void(0)"
												   class="avtar avtar-xs btn-link-primary view-pdf-btn"
												   data-pdf="{{ url($sale->signed_pdf) }}">
													<i class="ti ti-file-invoice f-18"></i>
												</a>
											</li>
											@endif
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="View">
												<a href="{{ url('/view-sales-invoice/'.base64_encode($sale->id)) }}"
													class="avtar avtar-xs btn-link-warning btn-pc-default">
													<i class="ti ti-eye f-18"></i>
												</a>
											</li>
											@if ($sale->status != '2' && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5))
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="Edit">
												<a href="{{ url('/edit-sales-invoice/'.base64_encode($sale->id)) }}"
													class="avtar avtar-xs btn-link-success btn-pc-default">
													<i class="ti ti-edit-circle f-18"></i>
												</a>
											</li>
											@endif
											@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
												@if ($sale->pay_status == 'Due')
												<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Third Party Settlement">
													<a href="javascript:void(0);" title="Third Party Settlement"
															class="btn btn-sm btn-warning settlement-btn"
															data-module="Sales"
															data-id="{{ $sale->id }}">
														<i class="ti ti-replace"></i>
													</a>
												</li>
												@endif
											<li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
												title="Delete">
												<a href="#" data-id="{{$sale->id}}"
													class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
													data-bs-toggle="modal" data-bs-target="#delete_modal">
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
					<div class="mt-3 d-flex justify-content-end">
						
					</div>
				</div>
			</div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Invoice</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal"
                                class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal"
                                class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadPdfModal">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header flex-column align-items-center position-relative">
			<button
				type="button"
				class="btn-close position-absolute end-0 top-0 m-3"
				data-bs-dismiss="modal">
			</button>

			<h5 class="modal-title mb-2">
				<i class="ti ti-file-certificate me-1"></i>
				Upload Digitally Signed PDF
			</h5>

			<span class="badge bg-light-primary text-dark px-3 py-2">
				Invoice No: <span id="invoice_number" class="fw-bold"></span>
			</span>
		</div>
		
         <div class="modal-body">
            <form id="uploadPdfForm" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="id" id="pdf_id">
               <input type="hidden" name="type" id="pdf_type">
               <div class="alert alert-info">
                  <i class="ti ti-info-circle"></i>
                  Only PDF allowed.
                  Maximum size 2 MB.
               </div>
               <div class="mb-3">
                  <label>
                  Select Signed PDF
                  </label>
                  <input
                     type="file"
                     name="pdf"
                     id="pdf_file"
                     class="form-control"
                     accept="application/pdf">
               </div>
               <div
                  id="uploadError"
                  class="text-danger"></div>
               <button
                  class="btn btn-primary w-100">
               Upload PDF
               </button>
            </form>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="pdfPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Signed PDF Preview</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body p-0">
                <embed
					id="pdfFrame"
					src=""
					type="application/pdf"
					width="100%"
					height="700">
            </div>
        </div>
    </div>
</div>

	

@endsection

@section('page-script')
<script>
    function startSalesInvoiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Sales Invoices Directory',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review and manage your company sales invoices, tracking totals, receipts, and payment status badges.</p></div>'
                },
                {
                    element: '#add-sales-invoice-btn',
                    title: 'New Sales Invoice',
                    intro: 'Click here to create a new billing invoice, set items, compute GST, and register payments.'
                },
                {
                    element: '.table-responsive',
                    title: 'Invoices Listing',
                    intro: 'Browse history files, showing invoice codes, dates, transaction types, grand totals, and payment status.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'Analyse reports, upload or download signed PDFs, view invoice details, edit properties, or delete items.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            skipIfNoElement: true,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-sales-invoice-tour').on('click', function(e) {
            e.preventDefault();
            startSalesInvoiceTour();
        });

        // Set inputs in the PDF Upload Modal when click trigger
        $(document).on('click', '.upload-pdf-btn', function () {
            let id = $(this).data('id');
            let type = $(this).data('type');
            let inv = $(this).data('inv');
            $('#pdf_id').val(id);
            $('#pdf_type').val(type);
            $('#invoice_number').text(inv);
            $('#uploadError').text('');
            $('#pdf_file').val('');
        });

        // Handle the PDF Upload via AJAX
        $('#uploadPdfForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#uploadError').text('');
            
            $.ajax({
                url: "{{ route('upload.signed.pdf') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 'success' || response.success) {
                        alert(response.message || 'Signed PDF uploaded successfully!');
                        location.reload();
                    } else {
                        $('#uploadError').text(response.message || 'Error uploading PDF.');
                    }
                },
                error: function (xhr) {
                    let res = xhr.responseJSON;
                    $('#uploadError').text(res && res.message ? res.message : 'An error occurred during upload.');
                }
            });
        });
    });

	$(document).ready(function () {

		function filterInvoice() {

			let search = $('#searchInvoice').val();
			let searchDate = $('#searchDate').val();

			let url = "{{ url('/sale-invoices') }}?search=" + encodeURIComponent(search)
						+ "&search_date=" + encodeURIComponent(searchDate);

			window.location.href = url;
		}

		// Search button click
		$('#searchBtn').on('click', function () {
			filterInvoice();
		});

		// Enter key press
		$('#searchInvoice').on('keypress', function (e) {

			if (e.which == 13) {
				filterInvoice();
			}

		});

	});
	
    let deleteId = null; 
    $(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id');
	});

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/delInvoice/' + deleteId,
                type: 'DELETE',
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error deleting invoice!");
                }
            });
        }
    });

</script>
@endsection
