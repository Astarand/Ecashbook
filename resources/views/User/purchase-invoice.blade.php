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
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Purchase Invoice List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-purchase-invoice-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Purchase Invoice List</h2>
                    </div>
                </div>
				@if ($invoice_create_status == "true" && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5))
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.CreatePurchaseInvoices') }}" id="add-purchase-invoice-btn" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Purchase Invoice</a>
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
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if($hasProprietorship)
                                <th>Proprietorship Company</th>
								@endif
                                <th>Vendor Name</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Transation Type</th>
                                <th>Grand Total</th>
                                <th>Due</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($sales as $sale)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
								@if($hasProprietorship)
								<td><span class="text-muted text-hover-primary">{{ !empty($sale->prop_name) ? $sale->prop_name : $sale->comp_name }}</span></td>
                                @endif
								<td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $sale->cust_name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary" href="tel:{{ $sale->cust_phone }}">{{ $sale->cust_phone }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-muted text-hover-primary">{{ $sale->inv_num }}</span></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ date("d-m-Y", strtotime($sale->inv_date)) }}</a></td>
                                <td><span class="text-muted text-hover-primary">{{ $sale->mode_of_pay }}
                                        @if ($sale->mode_of_pay == 'OTHER')
                                        [{{ $sale->other_payment }}]
                                        @endif
                                    </span>
                                </td>
                                <td><span class="text-muted text-hover-primary">₹{{ $sale->grandTotal }}</span></td>
                                <td><span class="text-muted text-hover-primary">₹{{ $sale->due_amount ?? 0 }}</span></td>
                                <td>
                                    @if ($sale->pay_status == 'Full')
                                    <span class="badge bg-success">Full</span>
                                    @elseif ($sale->pay_status == 'Partial')
                                    <span class="badge bg-warning text-dark">Advance</span>                                    
                                    @else
                                    <span class="badge bg-secondary">Due</span>
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
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View Invoice">
                                                <a target="_blank" href="{{ url('/purchase-invoice-pdf/'.base64_encode($sale->id).'/invoice') }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-file f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-purchase-invoice/'.base64_encode($sale->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if ($sale->status != '2' && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5))
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-purchase-invoice/'.base64_encode($sale->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
											@endif
											@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="javascript:void(0);" data-id="{{$sale->id}}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
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
                    <h3>Delete Invoice List Items</h3>
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
@endsection

@section('page-script')
<script>
    function startPurchaseInvoiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Purchase Invoices Directory',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review and manage incoming purchase invoices received from your suppliers and vendors.</p></div>'
                },
                {
                    element: '#add-purchase-invoice-btn',
                    title: 'New Purchase Invoice',
                    intro: 'Click here to log a new purchase invoice details, billing lines, and set payment states.'
                },
                {
                    element: '#pc-dt-simple',
                    title: 'Invoices Listing',
                    intro: 'Browse history logs of vendor bills, displaying dates, reference codes, totals, and payment status badges.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'Generate PDF summaries, view detailed invoice layouts, edit fields (if allowed), or delete records.'
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

    $(document).ready(function () {
        $('#start-purchase-invoice-tour').on('click', function(e) {
            e.preventDefault();
            startPurchaseInvoiceTour();
        });
        let deleteId = null; // Store the ID of the customer to be deleted

        // Capture the customer ID when the delete button is clicked
        $(document).on('click', '.delete-btn', function() {
            deleteId = $(this).data('id');
            console.log("Selected ID: " + deleteId);
            // If you're showing a modal for confirmation, trigger it here
            $('#deleteModal').modal('show'); 
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
                    url: '/delInvoicePurchase/' + deleteId, // Update with your delete route
                    type: 'DELETE',
                    success: function(response) {
                        alert(response.message); // Show success message
                        location.reload(); // Reload the page
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error deleting invoice!");
                    }
                });
            }
        });
    });
</script>


@endsection