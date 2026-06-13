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
                        <li class="breadcrumb-item"><a href="">Sales & Revenue</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Credit & Debit Note</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-sales-credit-debit-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Sales Credit & Debit List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddSalesCreditDebit') }}" id="add-credit-debit-btn" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Credit / Debit Note</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <table class="table tbl-product" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th class="text-end">#</th>
                            <th>Customer Name</th>
                            <th>Invoice Number</th>
                            <th>Voucher Number</th>
                            <th>Date</th>
                            <th>Note Type</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($sales as $sale)
                        <tr>
                            <td class="text-end"><?php echo $i++; ?></td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <h6 class="mb-1">{{ $sale->cust_name }}</h6>
                                        <a class="text-muted f-12 text-hover-primary" href="tel:91{{ $sale->cust_phone }}">+91-{{ $sale->cust_phone }}</a>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-muted text-hover-primary">{{ $sale->invoice_number }}</span></td>
                            <td><span class="text-muted text-hover-primary">{{ $sale->v_num }}</span></td>
                            <td>{{ date("d-m-Y", strtotime($sale->inv_date)) }}</td>
                            <td><span class="text-muted text-hover-primary">{{ $sale->note_type }}</span></td>
                            <td><span class="text-muted text-hover-primary">₹{{ $sale->total_amt }}</span></td>
                            <td>
                                @if ($sale->is_paid == 1)
                                <span class="badge bg-success">Success</span>
                                @else
                                <span class="badge bg-success">Fail</span>
                                @endif
                            </td>
                            <td>
                                <span><i class="ti ti-dots-vertical f-20"></i></span>
                                <div class="prod-action-links">
                                    <ul class="list-inline me-auto mb-0">
                                        
                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                            <a href="{{ url('/view-sales-credit-debit/'.base64_encode($sale->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                <i class="ti ti-eye f-18"></i>
                                            </a>
                                        </li>
                                        
                                        @if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/edit-sales-credit-debit/'.base64_encode($sale->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>

                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" data-id="{{$sale->id}}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
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
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Sales Credit & Debit List</h3>
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
    function startSalesCreditDebitTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Credit & Debit Notes List',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review and manage credit and debit adjustments issued against your sales invoices.</p></div>'
                },
                {
                    element: '#add-credit-debit-btn',
                    title: 'Add Adjustment Note',
                    intro: 'Click here to issue a new Credit/Debit Note adjusting balances for an existing client invoice.'
                },
                {
                    element: '#pc-dt-simple',
                    title: 'Adjustment Registry',
                    intro: 'Browse all registered credit/debit notes, showing customer names, dates, amounts, note types, and statuses.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'View details of the note, edit notes (if permitted), or delete notes.'
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
        $('#start-sales-credit-debit-tour').on('click', function(e) {
            e.preventDefault();
            startSalesCreditDebitTour();
        });
    });

    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $('.delete-btn').on('click', function() {
        deleteId = $(this).data('id');
        //alert(deleteId);
    });
    // alert(deleteId);
    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        //alert('hello');
        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/delInvoiceCreditDebit/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    alert(response.message); // Show success message
                    location.reload(); // Reload the page
                },
                error: function(xhr) {
                    alert("Error deleting invoice!");
                }
            });
        }
    });
</script>

@endsection