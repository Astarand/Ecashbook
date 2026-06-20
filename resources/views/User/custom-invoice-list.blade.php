@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>                        
                        <li class="breadcrumb-item">Accounting & Finance</li>
						<li class="breadcrumb-item active" aria-current="page">Custom Invoice</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-custom-invoice-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Custom Invoice List</h2>
                    </div>
                </div>
				@if (Auth::user()->u_type != 3 || Auth::user()->u_type != 6)
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.GenerateCustomInvoice') }}" id="generate-custom-invoice-btn" class="btn btn-primary"><i class="ti ti-square-plus"></i> Generate New Invoice</a>
                </div>
				@endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-body table-card">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Customer Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($custom_invoices as $key => $invoice)
                                <tr>
                                    <td class="text-end">{{ $key + 1 }}</td>
                                    <td><span class="text-muted text-hover-primary">{{ $invoice->invoice_number }}</span></td>
                                    <td><span class="text-muted text-hover-primary">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span></td>
                                    <td><a class="text-muted text-hover-primary" href="#">{{ $invoice->issued_to_company_name }}</a></td>
                                    <td>
                                        @if(empty($invoice->cust_id))
                                            <span class="badge bg-ash-gray text-gray">
                                                Not-Attached
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                Attached
                                            </span>
                                    @endif</td>
                                    <td>
                                        
                                        <span class="badge {{ $invoice->status == '1' ? 'bg-success' : 'bg-danger' }}">
                                                        
                                            {{ $invoice->status == '1' ? 'Approved' : 'Cancelled' }}
                                        </span>
                                        
                                    </td>
                                    <td>
                                        <span><i class="ti ti-dots-vertical f-20"></i></span>
                                        <div class="prod-action-links">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View Invoice">
                                                    <a
                                                        href="{{ route('ViewCustomInvoice', base64_encode($invoice->id)) }}"
                                                        class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                </li>
                                                    {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                        <a href="#!" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li> --}}
                                                {{-- <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                </li> --}}

                                                <li class="list-inline-item align-bottom">
                                                    @if($invoice->status == 1)
                                                        <a class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="tooltip" title="Cancelled" href="javascript:void(0);" onclick="cancelInvoice({{ $invoice->id }})">
                                                            <i class="fa fa-ban me-2"></i>
                                                        </a>
                                                    @else
                                                        <a class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="tooltip" title="Approved" href="javascript:void(0);" onclick="approveInvoice({{ $invoice->id }})">
                                                            <i class="fa fa-check me-2"></i>
                                                        </a>
                                                    @endif
                                                </li>

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
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-primary">
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
    function startCustomInvoiceListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Custom Invoices Directory',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-file" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review and manage generated custom invoices. Custom invoices allow you to billing directly without pre-entered products.</p></div>'
                },
                {
                    element: '#generate-custom-invoice-btn',
                    title: 'Generate Custom Invoice',
                    intro: 'Click here to design and issue a new custom bill invoice.'
                },
                {
                    element: '#pc-dt-simple',
                    title: 'Invoice Registry',
                    intro: 'Browse history lists of custom invoices, displaying invoice numbers, dates, customer companies, attached customer details status, and approval states.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'View detailed layout of the invoice, or toggle approval/cancellation status.'
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
        $('#start-custom-invoice-list-tour').on('click', function(e) {
            e.preventDefault();
            startCustomInvoiceListTour();
        });
    });

    function approveInvoice(id) {
        // Add logic to approve the invoice
        
        

            var action = '1';

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: '/custom_invoice_status_update',
                method: 'POST',
                data: {
                        id: id,
                        action: action
                    },
                success: function(response) {
                    //console.log(response);
                    // location.reload();
                    showToast("Invoice Update Successfully", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    
                },
                error: function(xhr) {
                    // console.log('An error occurred:', xhr);
                    showToast("Error: While Update Invoice", "error");
                }
            });
    }

    function cancelInvoice(id) {
        // Add logic to cancel the invoice
        // console.log('Cancel Invoice ID:', id);
        

            var action = '0';
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: '/custom_invoice_status_update',
                method: 'POST',
                data: {
                        id: id,
                        action: action
                    },
                success: function(response) {
                    //console.log(response);
                    

                    showToast("Invoice Update Successfully", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    
                },
                error: function(xhr) {
                    // console.log('An error occurred:', xhr);
                    showToast("Error: While Update Invoice", "error");
                }
            });
        
    }
</script>

@endsection