@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Subscription Customers</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Subscription Customers</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Company Name</th>
                                <th>Plan Type</th>
                                <th>Paid Amount</th>
                                <th>Subscribe From</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($customers as $key => $customer)

                            <tr>
                                <td class="text-end">{{ $key + 1 }}</td>

                                <td>
                                    <span class="text-muted text-hover-primary">
                                        CUST{{ str_pad($customer->userId, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1">{{ $customer->name }}</h6>

                                            <a class="text-muted f-12 text-hover-primary"
                                                href="mailto:{{ $customer->email }}">
                                                {{ $customer->email }}
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <a class="text-muted text-hover-primary" href="#">
                                        {{ $customer->phone ?? 'N/A' }}
                                    </a>
                                </td>

                                <td>
                                    {{ $customer->comp_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $customer->plan_type ?? 'N/A' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($customer->paid_amount ?? 0, 2) }}
                                </td>

                                <td>
                                    <span class="text-muted text-hover-primary">
                                        {{ !empty($customer->start_at) ? date('d-m-Y', strtotime($customer->start_at)) : 'N/A' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-muted text-hover-primary">
                                        {{ !empty($customer->end_at) ? date('d-m-Y', strtotime($customer->end_at)) : 'N/A' }}
                                    </span>
                                </td>

                                <td>
                                    @if($customer->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    @if($customer->payment_status == 'success')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>

                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>

                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">

                                            <li class="list-inline-item align-bottom"
                                                data-bs-toggle="tooltip"
                                                title="View">

                                                <a href="{{ url('/customer-details/'.base64_encode($customer->userId)) }}"
                                                class="avtar avtar-xs btn-link-warning btn-pc-default">

                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>

                                            <li class="list-inline-item align-bottom"
                                                data-bs-toggle="tooltip"
                                                title="Transaction">

                                                <a href="javascript:void(0)"
                                                class="avtar avtar-xs btn-link-info btn-pc-default trans_view"

                                                data-name="{{ $customer->name ?? '' }}"
                                                data-transaction-id="{{ $customer->transaction_id ?? '' }}"
                                                data-merchant-order-id="{{ $customer->merchantOrderId ?? '' }}"
                                                data-provider-ref="{{ $customer->providerReferenceId ?? '' }}"
                                                data-payment-instrument="{{ $customer->paymentInstrument ?? '' }}"
                                                data-paid-amount="{{ $customer->paid_amount ?? '' }}"
                                                data-base-amount="{{ $customer->base_amount ?? '' }}"
                                                data-gst-amount="{{ $customer->gst_amount ?? '' }}"
                                                data-gst-percentage="{{ $customer->gst_percentage ?? '' }}"
                                                data-adjustment-amount="{{ $customer->adjustment_amount ?? '' }}"
                                                data-payment-status="{{ $customer->payment_status ?? '' }}"
                                                data-response-msg="{{ $customer->response_msg ?? '' }}"
                                                data-start-at="{{ !empty($customer->start_at) ? date('d-m-Y', strtotime($customer->start_at)) : '' }}"
                                                data-end-at="{{ !empty($customer->end_at) ? date('d-m-Y', strtotime($customer->end_at)) : '' }}"

                                                data-bs-toggle="modal"
                                                data-bs-target="#customer_transaction_modal">

                                                    <i class="ti ti-wallet f-18"></i>
                                                </a>
                                            </li>

                                            {{-- <li class="list-inline-item align-bottom"
                                                data-bs-toggle="tooltip"
                                                title="Edit">

                                                <a href="#"
                                                class="avtar avtar-xs btn-link-success btn-pc-default">

                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li> --}}

                                            @if($customer->status == 1)

                                                <li class="list-inline-item align-bottom"
                                                    data-bs-toggle="tooltip"
                                                    title="Deactivate">

                                                    <a href="#"
                                                    data-id="{{ $customer->subscriber_id }}"
                                                    data-status="0"
                                                    class="status-change avtar avtar-xs btn-link-danger btn-pc-default status_update"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#customer_status_update">

                                                        <i class="ti ti-bell-off f-18"></i>
                                                    </a>
                                                </li>

                                            @else

                                                <li class="list-inline-item align-bottom"
                                                    data-bs-toggle="tooltip"
                                                    title="Activate">

                                                    <a href="#"
                                                    data-id="{{ $customer->subscriber_id }}"
                                                    data-status="1"
                                                    class="status-change avtar avtar-xs btn-link-success btn-pc-default status_update"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#customer_status_update">

                                                        <i class="ti ti-check f-18"></i>
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

<!-- Transaction Details Modal -->
<div class="modal custom-modal fade" id="customer_transaction_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction / Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th>User</th>
                                <td id="trxUserName">-</td>
                            </tr>
                            <tr>
                                <th>Transaction ID</th>
                                <td id="trxId">-</td>
                            </tr>
                            <tr>
                                <th>Merchant Order ID</th>
                                <td id="trxMerchantOrderId">-</td>
                            </tr>
                            <tr>
                                <th>Provider Reference</th>
                                <td id="trxProviderRef">-</td>
                            </tr>
                            <tr>
                                <th>Payment Instrument</th>
                                <td id="trxInstrument">-</td>
                            </tr>
                            <tr>
                                <th>Amount Paid</th>
                                <td id="trxAmount">-</td>
                            </tr>
                            <tr>
                                <th>Base Amount</th>
                                <td id="trxBaseAmount">-</td>
                            </tr>
                            <tr>
                                <th>GST Amount</th>
                                <td id="trxGstAmount">-</td>
                            </tr>
                            <tr>
                                <th>GST %</th>
                                <td id="trxGstPerc">-</td>
                            </tr>
                            <tr>
                                <th>Adjustment Amount</th>
                                <td id="trxAdjustment">-</td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td id="trxStatus">-</td>
                            </tr>
                            <tr>
                                <th>Response Message</th>
                                <td id="trxResp">-</td>
                            </tr>
                            <tr>
                                <th>Subscribe From</th>
                                <td id="trxStartAt">-</td>
                            </tr>
                            <tr>
                                <th>Expiry Date</th>
                                <td id="trxEndAt">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal custom-modal fade" id="customer_status_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3 id="modalTitle">Status Update Customer</h3>
                    <p id="modalText">Are you sure you want to update status?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="status_update_cust" class="w-100 btn btn-danger">
                                Yes
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                No
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
    //------------- Status Update ---------
    let statusId = null;
    let statusData = null;

    $('.status_update').on('click', function() {
        statusId = $(this).data('id');
        statusData = $(this).data('status');
    });

    $('#status_update_cust').on('click', function() {
        if (statusId !== null && statusData !== null) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/subscription-customer-status-update',
                type: 'POST',
                data: {
                    customer_id: statusId,
                    status: statusData
                },
                success: function(response) {
                    if (response.status == true) {
                        showToast(response.message, "success");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showToast("Error: " + response.message, "error");
                    }
                },
                error: function(xhr) {
                    showToast("Error updating status!", "error");
                }
            });
        }
    });

    //------------- Transaction Details Modal Populate ---------
    
    $(document).on('click', '.trans_view', function () {

        const $btn = $(this);

        const name = $btn.attr('data-name') || '-';
        const transactionId = $btn.attr('data-transaction-id') || '-';
        const merchantOrderId = $btn.attr('data-merchant-order-id') || '-';
        const providerRef = $btn.attr('data-provider-ref') || '-';
        const instrument = $btn.attr('data-payment-instrument') || '-';
        const paidAmount = $btn.attr('data-paid-amount') || '';
        const baseAmount = $btn.attr('data-base-amount') || '';
        const gstAmount = $btn.attr('data-gst-amount') || '';
        const gstPerc = $btn.attr('data-gst-percentage') || '';
        const adjustment = $btn.attr('data-adjustment-amount') || '';
        const status = $btn.attr('data-payment-status') || '-';
        const resp = $btn.attr('data-response-msg') || '-';
        const startAt = $btn.attr('data-start-at') || '-';
        const endAt = $btn.attr('data-end-at') || '-';

        $('#trxUserName').text(name);
        $('#trxId').text(transactionId);
        $('#trxMerchantOrderId').text(merchantOrderId);
        $('#trxProviderRef').text(providerRef);
        $('#trxInstrument').text(instrument);

        $('#trxAmount').text(
            paidAmount !== '' ? '₹ ' + parseFloat(paidAmount).toFixed(2) : '-'
        );

        $('#trxBaseAmount').text(
            baseAmount !== '' ? '₹ ' + parseFloat(baseAmount).toFixed(2) : '-'
        );

        $('#trxGstAmount').text(
            gstAmount !== '' ? '₹ ' + parseFloat(gstAmount).toFixed(2) : '-'
        );

        $('#trxGstPerc').text(
            gstPerc !== '' ? gstPerc + '%' : '-'
        );

        $('#trxAdjustment').html(
            adjustment !== ''
                ? '<span class="badge bg-info">₹ ' + parseFloat(adjustment).toFixed(2) + '</span>'
                : '<span class="badge bg-secondary">-</span>'
        );

        if (status.toUpperCase() === 'SUCCESS') {
            $('#trxStatus').html('<span class="badge bg-success">SUCCESS</span>');
        } else if (status.toUpperCase() === 'PENDING') {
            $('#trxStatus').html('<span class="badge bg-warning">PENDING</span>');
        } else {
            $('#trxStatus').html('<span class="badge bg-danger">' + status + '</span>');
        }

        $('#trxResp').text(resp);
        $('#trxStartAt').text(startAt);
        $('#trxEndAt').text(endAt);

        $('#customer_transaction_modal').modal('show');
    });

</script>


@endsection