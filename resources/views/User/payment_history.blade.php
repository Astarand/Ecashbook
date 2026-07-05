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
                        <li class="breadcrumb-item"><a href="{{ url('/sales-invoice') }}">Sales</a></li>
                        <li class="breadcrumb-item" aria-current="page">Payment History</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payment History List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPaymentModal">
                        <i class="ti ti-square-plus"></i> Add New Payment
                    </a>
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
                                <th>Payment Date</th>
                                <th>Payment Amount</th>
                                <th>Payment Mode</th>
                                <th>Received By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($payments as $pay)
                            <tr>
                                <td class="text-end">
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-1"> {{ date("d-m-Y",
                                        strtotime($pay->payment_date)) }}</h6>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted text-hover-primary">₹&nbsp; {{ $pay->pay_amount }}</span>
                                </td>
                                <td>
                                    <span class="text-muted text-hover-primary">{{ $pay->payment_mode }}</span>
                                </td>
                                <td>
                                    <span class="text-muted text-hover-primary"> {{ $pay->user_name }}</span>
                                </td>
                    
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Download Received">
                                                <a href="{{ url('/payment_received/'.base64_encode($pay->id)) }}"
                                                class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                style="background-color: #3498db; color: #fff; padding: 8px 16px; border-radius: 5px; text-decoration: none;">
                                                    <i class="ti ti-download f-18"></i>
                                                </a>
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



<div class="modal custom-modal fade" id="newPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newPaymentModalLabel">Add New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newPaymentForm">
                    @csrf
                    <input type="hidden" name="inv_no" id="inv_no" value="{{ $sales_data->first()->inv_num }}">
                    <input type="hidden" name="sales_no" id="sales_no" value="{{ $saleId }}">

                    <!-- Payment Amount -->
                    <div class="mb-3">
                        <label for="pay_amount" class="form-label">Payment Amount</label>
                        <input type="number" class="form-control" id="pay_amount" name="pay_amount" required>
                    </div>

                    <!-- Payment Mode Dropdown -->
                    <div class="mb-3">
                        <label for="payment_mode" class="form-label">Payment Mode</label>
                        <select class="form-select" id="payment_mode" name="payment_mode" required>
                            <option value="">-- Select Mode --</option>
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Card">Card</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('submit', '#newPaymentForm', function (e) {
        e.preventDefault();  // Prevent default form submission

        var payAmount = $('#pay_amount').val();
        var paymentMode = $('#payment_mode').val();
        var inv_no = $('#inv_no').val();
        var sales_no = $('#sales_no').val();

        // Ensure all fields are filled
        if (!payAmount || !paymentMode || !sales_no || !inv_no) {
            showToast('Please fill in all the fields!', 'error');
            return;
        }

        // Prepare the payment data
        var paymentData = {
            pay_amount: payAmount,
            payment_mode: paymentMode,
            inv_no: inv_no,
            sales_no: sales_no,
            _token: $('meta[name="csrf-token"]').attr('content')  // CSRF token for security
        };

        // Send the data via AJAX to your backend
        $.ajax({
            url: '/submit-new-payment',  // Define the route to handle the payment submission
            method: 'POST',
            data: paymentData,
            success: function (response) {
                if (response.success) {
                    showToast('Payment submitted successfully!', 'success');
                    
                    setTimeout(function() {
                        $('#newPaymentModal').modal('hide');
                        location.reload();  
                    }, 3000); 
                } else {
                    showToast('Failed to submit payment!', 'error');
                }
            },
            error: function () {
                showToast('Error submitting payment!', 'error');
            }
        });
    });

</script>




@endsection