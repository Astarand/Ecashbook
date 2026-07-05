@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/ca-dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ca.PaymentHistory') }}">Payment History</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add Payment</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCAAddPaymentTour();" id="start-ca-add-payment-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add Payment</h2>
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
                    <form>
                    <div class="row">
                        <!-- Date -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="paymentDate" required>
                        </div>
                        <!-- Type Dropdown -->
                        <div class="col-lg-4 mb-3">
                            <label for="entityType" class="form-label">Type</label>
                            <select class="form-select" id="entityType" required>
                                <option value="">Select Type</option>
                                <option value="company">Company</option>
                                <option value="agent">Agent</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Name (dynamic) -->
                        <div class="col-lg-4 mb-3" id="nameFieldContainer" style="display: none">
                            <label for="paymentName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="paymentName" placeholder="Enter Name" />
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
                            <input type="text" class="form-control" id="paymentPhone" placeholder="Enter Phone Number" required>
                        </div>
                        <!-- Payment Type -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentType" class="form-label">Payment Type</label>
                            <select class="form-select" id="paymentType" required>
                                <option value="">Select Payment Type</option>
                                <option value="credit">Credit</option>
                                <option value="debit">Debit</option>
                            </select>
                        </div>
                        <!-- Government Fees -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentGovtFees" class="form-label">Government Fees</label>
                            <input type="text" class="form-control" id="paymentGovtFees" placeholder="Enter Government Fees" required>
                        </div>

                        <!-- Service Fees -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentServiceFees" class="form-label">Service Fees</label>
                            <input type="text" class="form-control" id="paymentServiceFees" placeholder="Enter Service Fees" required>
                        </div>
                        
                        <!-- Total Amount -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentAmount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="paymentAmount" placeholder="Enter Amount" required>
                        </div>
                        <!-- Payment Method -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" required>
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="card">Card</option>
                            </select>
                        </div>
                        
                        

                        <!-- Purpose -->
                        <div class="col-lg-4 mb-3">
                            <label for="paymentPurpose" class="form-label">Purpose</label>
                            <input type="text" class="form-control" id="paymentPurpose" placeholder="Enter Purpose" required>
                        </div>
                    </div>

                    {{-- <button type="button" class="btn btn-primary" id="addPaymentBtn">Add Payment</button> --}}
                    
                    <div class="">
                        <button type="button" id="addPaymentBtn" class="btn btn-primary d-flex align-items-center justify-content-center">
                            Add Payment <i class="ti ti-arrow-up-right-circle ms-2"></i>
                        </button>
                    </div>
                    
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function startCAAddPaymentTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Add Payment Record',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-square-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Onboard and log a new billing payment transaction details for clients or agents.</p></div>'
                    },
                    {
                        element: '#paymentDate',
                        title: 'Payment Date',
                        intro: 'Select the date when this transaction occurred.'
                    },
                    {
                        element: '#entityType',
                        title: 'Entity Classification',
                        intro: 'Specify the recipient or source category: choose Company for client firms, Agent for commissions, or Other.'
                    },
                    {
                        element: '#paymentPhone',
                        title: 'Contact Mobile',
                        intro: 'Enter a valid mobile number for correspondence logs.'
                    },
                    {
                        element: '#paymentType',
                        title: 'Credit / Debit Designation',
                        intro: 'Designate the transaction type: choose Credit for incoming revenue and Debit for outgoing expenses.'
                    },
                    {
                        element: '#paymentGovtFees',
                        title: 'Government Fees Portion',
                        intro: 'Portion of the payment allocated to official government taxes or registry dues.'
                    },
                    {
                        element: '#paymentServiceFees',
                        title: 'Service Fees Portion',
                        intro: 'Portion allocated to your firm\'s professional charges.'
                    },
                    {
                        element: '#paymentAmount',
                        title: 'Total Settlement Amount',
                        intro: 'The net total amount of the transaction (calculated government fees + service charges).'
                    },
                    {
                        element: '#paymentMethod',
                        title: 'Payment Method',
                        intro: 'Mode of settlement chosen (Cash, UPI, Bank Transfer, Cheque, or Card).'
                    },
                    {
                        element: '#paymentPurpose',
                        title: 'Payment Purpose',
                        intro: 'Summarize the context or description of this transaction.'
                    },
                    {
                        element: '#addPaymentBtn',
                        title: 'Save Payment Record',
                        intro: 'Click here to save and publish the transaction ledger entry.'
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

        if (typeof introJs === 'function') {
            launch();
        } else {
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    $(document).ready(function() {
        // When dropdown value changes
        $('#entityType').on('change', function() {
            var type = $(this).val();
            
            // Hide all containers first
            $('#nameFieldContainer').hide();
            $('#customerFieldContainer').hide();
            $('#agentFieldContainer').hide();
            
            if (type == 'company') {
                // Fetch customers if 'company' is selected
                $('#customerFieldContainer').show(); // Show the customer dropdown
                
                $.ajax({
                    url: '/fetch-customers',  // This is your API endpoint to fetch customers
                    method: 'GET',
                    success: function(response) {
                        $('#customerSelect').empty();
                        $('#customerSelect').append('<option value="">Select Customer</option>');
                        
                        // Append customers to the dropdown
                        response.forEach(function(customer) {
                            $('#customerSelect').append('<option value="'+ customer.id +'">'+ customer.name +'</option>');
                        });
                    }
                });
            } else if (type == 'agent') {
                // Fetch agents if 'agent' is selected
                $('#agentFieldContainer').show(); // Show the agent dropdown
                
                $.ajax({
                    url: '/fetch-agents',  // This is your API endpoint to fetch agents
                    method: 'GET',
                    success: function(response) {
                        $('#agentSelect').empty();
                        $('#agentSelect').append('<option value="">Select Agent</option>');
                        
                        // Append agents to the dropdown
                        response.forEach(function(agent) {
                            $('#agentSelect').append('<option value="'+ agent.id +'">'+ agent.agent_name +'</option>');
                        });
                    }
                });
            } else if (type == 'other') {
                // Show a text box for 'other'
                $('#nameFieldContainer').show();
            }
        });

        $('#addPaymentBtn').click(function(e) {
            e.preventDefault();

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
                paymentPurpose: $('#paymentPurpose').val()
            };

            // Send the data to the backend
            $.ajax({
                url: '/add-payment',
                method: 'POST',
                data: formData,
                success: function(response) {
                    showToast(response.success, 'success');
                    setTimeout(function() {
                        window.location.href = response.redirect_url;  // Redirect to the returned URL
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    showToast('Something went wrong. Please try again.', 'error');
                }
            });
        });

        $('#start-ca-add-payment-tour').on('click', function(e) {
            e.preventDefault();
            startCAAddPaymentTour();
        });
    });
</script>
@endsection