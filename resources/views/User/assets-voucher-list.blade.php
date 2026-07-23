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
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Assets Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/assets-voucher-list') }}">Assets Voucher</a></li>
                        <li class="breadcrumb-item" aria-current="page">Assets Voucher List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-assets-voucher-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Assets Voucher List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddAssetVoucher') }}" class="btn btn-primary"><i class="ti ti-square-plus f-20"></i> Add New Asset Voucher </a>
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
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>PROPRIETORSHIP COMPANY</th>
                                <th>Series</th>
                                <th>Voucher Name</th>
                                <th>Voucher Number</th>
                                <th>Invoice Date</th>
                                <th>Vendor</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($asset_vouchers as $asset_voucher)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
                                <td><a class="text-muted text-hover-primary">{{ $asset_voucher->comp_name }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $asset_voucher->series_name }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $asset_voucher->voucher_name }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $asset_voucher->voucher_no }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $asset_voucher->invoice_date }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $asset_voucher->vendor_name }}</a></td>
                                <td><a class="text-muted text-hover-primary">₹{{ $asset_voucher->total_cost }}</a></td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-asset-voucher/'.base64_encode($asset_voucher->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            @if($req_type != 1)

                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                    <a href="{{ url('/edit-asset-voucher/'.base64_encode($asset_voucher->id)) }}"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                        <i class="ti ti-edit-circle f-18"></i>
                                                    </a>
                                                </li>

                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
                                                    data-id="{{ $asset_voucher->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_modal">
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
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Assets</h3>
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
    let deleteId = null;

    // Capture the customer ID when the delete button is clicked
    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');

    });

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        // alert(deleteId);

        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/delAssetVoucher/' + deleteId,
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

    function startAssetsVoucherListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Assets Voucher List Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track depreciation and purchase entries for company assets.</p></div>'
                },
                {
                    title: 'Assets Voucher List',
                    intro: 'Track depreciation and purchase entries for company assets.'
                },
                {
                    element: 'table', title: 'Asset Vouchers Table',
                    intro: 'Review recorded asset vouchers, their asset names, types, and values.'
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
        $('#start-assets-voucher-list-tour').on('click', function(e) {
            e.preventDefault();
            startAssetsVoucherListTour();
        });
    });
</script>

@endsection