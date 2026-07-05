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
                        <li class="breadcrumb-item" aria-current="page">Payment History</li>
                    </ul>
                    <a href="javascript:void(0);" onclick="startCAPaymentTour();" id="start-ca-payment-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payment History</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end mt-2">
                    <a href="{{ route('ca_add.payment') }}" id="add-payment-btn" class="btn btn-primary"><i class="ti ti-plus"></i> Add a payment</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row">
        <div class="col-12">
            <div class="card table-card" id="payments-table-card">
                <div class="card-header d-flex align-items-center justify-content-between pt-4 pb-3">
                    <h3 class="mb-0">Payment Management</h3>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>User Type</th>
                                    <th>Phone Number</th>
                                    <th>Payment Type</th>
                                    <th>Purpose</th>
                                    <th>Total Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $key => $payment)
                                <?php $encodedId = base64_encode($payment->id); ?>
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}</td>
                                    <td>{{ $payment->entity_name }}</td>
                                    <td>{{ ucfirst($payment->entity_type) }}</td> <!-- Display 'Company' or 'Agent' -->
                                    <td>{{ $payment->payment_phone }}</td>
                                    <td class="text-success">
                                        <i class="fas fa-circle f-10 m-r-10"></i> {{ ucfirst($payment->payment_type) }}
                                    </td>
                                    <td>{{ $payment->payment_purpose }}</td>
                                    <td class="text-primary">
                                        <i class="fas fa-circle f-10 m-r-10"></i> ₹ {{ number_format($payment->total_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <a href="{{ route('CA.CompanyDetails', ['custId' => base64_encode($payment->customer_id)]) }}" class="avtar avtar-xs btn-link-secondary">
                                            <i class="ti ti-eye f-20"></i>
                                        </a> --}}
                                        <a href="{{ url('ca_view_payment/' . $encodedId) }}" class="avtar avtar-xs btn-link-secondary">
                                            <i class="ti ti-eye f-20"></i>
                                        </a>
                                        <a href="{{ url('ca_edit_payment/' . $encodedId) }}" class="avtar avtar-xs btn-link-primary mx-1" title="Edit">
                                            <i class="ti ti-edit f-20"></i>
                                        </a>
                                        <a href="javascript:void(0);"
										   class="avtar avtar-xs btn-link-danger mx-1 delete-payment-btn"
										   data-id="{{ base64_encode($payment->id) }}"
										   title="Delete">
											<i class="ti ti-trash f-20"></i>
										</a>


                                        <!-- Delete Confirmation Modal -->
                                        <!--<div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deletePaymentModalLabel">Delete Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this payment?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                        <button type="button" class="btn btn-danger">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
						
						<!-- Delete Confirmation Modal -->
						<div class="modal fade" id="deletePaymentModal" tabindex="-1">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Delete Payment</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
										Are you sure you want to delete this payment?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
										<button type="button" class="btn btn-danger" id="confirmDeletePayment">
											Yes, Delete
										</button>
									</div>
								</div>
							</div>
						</div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Add a Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addPaymentBtn">Add Payment</button>
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
                // Fetch customers if 'company' is selected
                $('#customerFieldContainer').show(); // Show the customer dropdown
                
                $.ajax({
                    url: '/fetch-customers',  // This is your API endpoint to fetch customers
                    method: 'GET',
                    success: function(response) {
                        // console.log(response);
                        
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
                    // console.log(response);
                    
                    // alert(response.success);
                    showToast(response.success, 'success');
                    setTimeout(function() {
                        location.reload();  // This will reload the page
                    }, 3000);
                    
                },
                error: function(xhr, status, error) {
                    // console.log(xhr.responseText);
                    
                    showToast('Something went wrong. Please try again.', 'error');
                }
            });
        });

    });

    let deletePaymentId = null;

	// Open modal & store ID
	$(document).on('click', '.delete-payment-btn', function () {
		deletePaymentId = $(this).data('id');
		$('#deletePaymentModal').modal('show');
	});

	// Confirm delete
	$('#confirmDeletePayment').on('click', function () {

		if (!deletePaymentId) return;

		$.ajax({
			url: "{{ route('ca.payment.delete') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				pay_id: deletePaymentId
			},
			success: function (res) {
				$('#deletePaymentModal').modal('hide');

				if (res.status === 'success') {
					showToast(res.message, 'success');
					setTimeout(() => location.reload(), 1500);
				} else {
					showToast(res.message, 'error');
				}
			},
			error: function () {
				showToast('Delete failed. Try again.', 'error');
			}
		});
	});

    function startCAPaymentTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Payment Management',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-wallet" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Keep track of incoming client payments, agent commissions, government fees, and service charges.</p></div>'
                    },
                    {
                        element: '#add-payment-btn',
                        title: 'Add New Payment Record',
                        intro: 'Click here to log a new credit or debit transaction, specifying payment purpose, method, and client metadata.'
                    },
                    {
                        element: '#payments-table-card',
                        title: 'Payment Logs Registry',
                        intro: 'Overview of all billing and transactional logs including transaction date, client name, user type, phone, payment type, purpose, and amount.'
                    },
                    {
                        element: '.delete-payment-btn',
                        title: 'Payment Entry Actions',
                        intro: 'Standard controls to inspect payment invoice data, edit details, or delete logs.',
                        skipIfNoElement: true
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
        $('#start-ca-payment-tour').on('click', function(e) {
            e.preventDefault();
            startCAPaymentTour();
        });
    });

</script>


@endsection