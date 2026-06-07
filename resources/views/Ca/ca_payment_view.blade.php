@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Payment</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="paymentForm">
                        <div class="row">
                            <!-- Date -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="paymentDate" value="{{ $payment->payment_date }}" required>
                                <input type="hidden" value="{{ $payment->id }}" id="pay_id" >
                            </div>

                            <!-- Type Dropdown -->
                            <div class="col-lg-4 mb-3">
                                <label for="entityType" class="form-label">Type</label>
                                <select class="form-select" id="entityType" required>
                                    <option value="">Select Type</option>
                                    <option value="company" {{ $payment->entity_type == 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="agent" {{ $payment->entity_type == 'agent' ? 'selected' : '' }}>Agent</option>
                                    <option value="other" {{ $payment->entity_type == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Name (dynamic) -->
                            <div class="col-lg-4 mb-3" id="nameFieldContainer" style="display: none">
                                <label for="paymentName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="paymentName" value="{{ $payment->name }}" placeholder="Enter Name" />
                            </div>

                            <!-- Customer Dropdown (Initially hidden) -->
                            <div class="col-lg-4 mb-3" id="customerFieldContainer" style="display: none">
                                <label for="customerSelect" class="form-label">Select Customer</label>
                                <select class="form-select" id="customerSelect">
                                    <option value="">Select Customer</option>
                                </select>
                            </div>

                            <!-- Agent Dropdown (Initially hidden) -->
                            <div class="col-lg-4 mb-3" id="agentFieldContainer" style="display: none">
                                <label for="agentSelect" class="form-label">Select Agent</label>
                                <select class="form-select" id="agentSelect">
                                    <option value="">Select Agent</option>
                                </select>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentPhone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" value="{{ $payment->payment_phone }}" id="paymentPhone" placeholder="Enter Phone Number" required>
                            </div>

                            <!-- Payment Type -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentType" class="form-label">Payment Type</label>
                                <select class="form-select" id="paymentType" required>
                                    <option value="">Select Payment Type</option>
                                    <option value="credit" {{ $payment->payment_type == 'credit' ? 'selected' : '' }}>Credit</option>
                                    <option value="debit" {{ $payment->payment_type == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>

                            <!-- Government Fees -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentGovtFees" class="form-label">Government Fees</label>
                                <input type="text" class="form-control" id="paymentGovtFees" value="{{ $payment->gov_fees }}" placeholder="Enter Government Fees" required>
                            </div>

                            <!-- Service Fees -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentServiceFees" class="form-label">Service Fees</label>
                                <input type="text" class="form-control" id="paymentServiceFees" value="{{ $payment->service_fees }}" placeholder="Enter Service Fees" required>
                            </div>

                            <!-- Total Amount -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentAmount" class="form-label">Total Amount</label>
                                <input type="number" class="form-control" id="paymentAmount" value="{{ $payment->total_amount }}" placeholder="Enter Amount" required>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentMethod" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="upi" {{ $payment->payment_method == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank_transfer" {{ $payment->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cheque" {{ $payment->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="card" {{ $payment->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                                </select>
                            </div>

                            <!-- Purpose -->
                            <div class="col-lg-4 mb-3">
                                <label for="paymentPurpose" class="form-label">Purpose</label>
                                <input type="text" class="form-control" id="paymentPurpose" value="{{ $payment->payment_purpose }}" placeholder="Enter Purpose" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('ca.PaymentHistory') }}" class="btn btn-danger">
                                Cancel <i class="ti ti-x-circle ms-2"></i>
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // When dropdown value changes
        $('#entityType').on('change', function() {
            var type = $(this).val();
            
            // Hide all containers first
            $('#nameFieldContainer').hide();
            $('#customerFieldContainer').hide();
            $('#agentFieldContainer').hide();
            
            if (type == 'company') {
                $('#customerFieldContainer').show(); // Show the customer dropdown
                // Fetch customers if 'company' is selected
                $.ajax({
                    url: '/fetch-customers',  // This is your API endpoint to fetch customers
                    method: 'GET',
                    success: function(response) {
                        $('#customerSelect').empty();
                        $('#customerSelect').append('<option value="">Select Customer</option>');
                        // Append customers to the dropdown
                        response.forEach(function(customer) {
                            $('#customerSelect').append('<option value="'+ customer.id +'" ' + 
                              (customer.id == "{{ $payment->customer_id }}" ? 'selected' : '') + '>' +
                              customer.name + '</option>');
                        });
                    }
                });
            } else if (type == 'agent') {
                $('#agentFieldContainer').show();  // Show the agent dropdown
                // Fetch agents if 'agent' is selected
                $.ajax({
                    url: '/fetch-agents',  // This is your API endpoint to fetch agents
                    method: 'GET',
                    success: function(response) {
                        $('#agentSelect').empty();
                        $('#agentSelect').append('<option value="">Select Agent</option>');
                        // Append agents to the dropdown
                        response.forEach(function(agent) {
                            $('#agentSelect').append('<option value="'+ agent.id +'" ' + 
                              (agent.id == "{{ $payment->agent_id }}" ? 'selected' : '') + '>' +
                              agent.agent_name + '</option>');
                        });
                    }
                });
            } else if (type == 'other') {
                $('#nameFieldContainer').show();  // Show the name input field
            }
        });

        // Trigger change event on page load to display the correct fields based on the initial selected entity type
        $('#entityType').trigger('change');

        $('#updatePaymentBtn').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var formData = {
                paymentDate: $('#paymentDate').val(),
                entityType: $('#entityType').val(),
                name: $('#paymentName').val(),
                customerSelect: $('#customerSelect').val(),
                agentSelect: $('#agentSelect').val(),
                paymentPhone: $('#paymentPhone').val(),
                paymentType: $('#paymentType').val(),
                paymentGovtFees: $('#paymentGovtFees').val(),
                paymentServiceFees: $('#paymentServiceFees').val(),
                paymentAmount: $('#paymentAmount').val(),
                paymentMethod: $('#paymentMethod').val(),
                paymentPurpose: $('#paymentPurpose').val(),
                pay_id: $('#pay_id').val(), // Add the payment ID from the hidden field
            };
            // console.log(formData);
            
            // Send AJAX request
            $.ajax({
                url: '/update-payment',  // Your backend route to handle the update
                method: 'POST',
                data: formData,
                success: function(response) {
                    showToast(response.success, 'success');  // Display success message
                    setTimeout(function() {
                         window.location.href = response.redirect_url;  // Reload the page after 3 seconds
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    showToast('Something went wrong. Please try again.', 'error');
                }
            });
        });
    });
</script>

@endsection
