@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Cash & Banking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment Vouchers</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payment Vouchers</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">                    
                    <button class="btn btn-primary btn-add"><i class="ti ti-square-plus"></i> Add New Payment Voucher</button>
                </div>

            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
			
				<form method="GET" action="{{ route('user.PaymentVoucherList') }}">

					<div class="row">
					
						<div class="col-md-3">
							<label class="form-label">Proprietorship Company</label>
							<select name="prop_Id" class="form-control">
								<option value="">{{ parentCompanyName() }}</option>
								@foreach($proprietorships as $company)
									<option value="{{ $company->id }}"
										{{ request('prop_Id') == $company->id ? 'selected' : '' }}>									
										{{ $company->comp_name }}
									</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-3">
							<label class="form-label">From Date</label>

							<input type="date"
								   name="from_date"
								   class="form-control"
								   value="{{ request('from_date') }}">
						</div>

						<div class="col-md-3">
							<label class="form-label">To Date</label>

							<input type="date"
								   name="to_date"
								   class="form-control"
								   value="{{ request('to_date') }}">
						</div>
						
						<div class="col-md-3">
							<label class="form-label">Voucher No</label>
							<input type="text"
								   name="voucher_no"
								   class="form-control"
								   placeholder="Search voucher no"
								   value="{{ request('voucher_no') }}">
						</div>
						
						<div class="col-md-3">
							<label class="form-label">Bank Name</label>
							<select name="bank_id" class="form-select">
								<option value="">All</option>
								@foreach($banks as $bank)
									<option value="{{ $bank->id }}"
										{{ request('bank_id') == $bank->id ? 'selected' : '' }}>
										{{ $bank->bank_name }}
									</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-3">
							<label class="form-label">Party Name</label>

							<input type="text"
								   name="party_name"
								   class="form-control"
								   placeholder="Search party"
								   value="{{ request('party_name') }}">
						</div>

						<div class="col-md-3">
							<label class="form-label">Payment Mode</label>

							<select name="payment_mode" class="form-select">
								<option value="">All</option>
								<option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
								<option value="Bank" {{ request('payment_mode') == 'Bank' ? 'selected' : '' }}>Bank</option>
								<option value="UPI" {{ request('payment_mode') == 'UPI' ? 'selected' : '' }}>UPI</option>
							</select>
						</div>

						<div class="col-md-3">
							<label class="form-label">Payment Status</label>

							<select name="is_paid" class="form-select">
								<option value="">All</option>
								<option value="1" {{ request('is_paid') == '1' ? 'selected' : '' }}>
									Paid
								</option>

								<option value="0" {{ request('is_paid') === '0' ? 'selected' : '' }}>
									Outstanding
								</option>
							</select>
						</div>

						<div class="col-md-3">
							<label class="form-label">Voucher Type</label>

							<select name="voucher_type" class="form-select">
								<option value="">All</option>

								<option value="Payment Voucher"
									{{ request('voucher_type') == 'Payment Voucher' ? 'selected' : '' }}>
									Payment
								</option>

								<option value="Receipt Voucher"
									{{ request('voucher_type') == 'Receipt Voucher' ? 'selected' : '' }}>
									Receipt
								</option>
							</select>
						</div>

						<div class="col-md-3">
							<label class="form-label">Party Type</label>

							<select name="party_type" class="form-select">
								<option value="">All</option>

								<option value="Customer"
									{{ request('party_type') == 'Customer' ? 'selected' : '' }}>
									Customer
								</option>

								<option value="Vendor"
									{{ request('party_type') == 'Vendor' ? 'selected' : '' }}>
									Vendor
								</option>

								<option value="Employee"
									{{ request('party_type') == 'Employee' ? 'selected' : '' }}>
									Employee
								</option>

								<option value="Other"
									{{ request('party_type') == 'Other' ? 'selected' : '' }}>
									Other
								</option>
							</select>
						</div>

						<div class="col-md-3 mt-3 d-flex align-items-end">

							<button type="submit"
									class="btn btn-primary me-2">
								<i class="ti ti-search"></i> Search
							</button>

							<a href="{{ route('user.PaymentVoucherList') }}"
							   class="btn btn-secondary">
								Reset
							</a>

						</div>

					</div>

				</form>
                <div class="table-responsive">
                    <table class="table tbl-product my-3">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
								@if($hasProprietorship)
								<th>Proprietorship Company</th>
								@endif
                                <th width="120">Date</th>
                                <th width="">Paid</th>
                                <th>Voucher No</th>
                                <th>Party Type</th>
                                <th>Party Name</th>								
                                <th>Amount(₹)</th>
                                <th>CR/DR</th>
                                <th>Payment Mode</th>
                                <th>Record Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $v)
                            <tr>
								<td>{{ $loop->iteration }}</td>
								@if($hasProprietorship)
                                <td>{{ !empty($v->prop_comp_name) ? $v->prop_comp_name : $v->comp_name }}</td>
								@endif
								<td>
									<input type="date" class="form-control updateDate" data-id="{{ $v->id }}" value="{{ $v->date }}">
								</td>   
								<td class="text-center">
									<div class="form-check d-flex justify-content-center">
										<input class="form-check-input updatePaid"
											   type="checkbox" data-id="{{ $v->id }}"
											    title="{{ $v->is_paid == 1 ? 'Payment is Paid' : 'Outstanding Payment' }}"
											   {{ $v->is_paid == 1 ? 'checked' : '' }}>
									</div>
								</td>
                                <td>{{ $v->voucher_no }}</td>
                                <td>{{ $v->party_type }}</td>
                                <td>{{ $v->party_name }}</td>
								<td>{{ number_format($v->amount,2) }}</td>
                                <td>{{ $v->credit_debit }}</td>
                                <td>{{ $v->payment_mode }}</td>
								<td>
									<span class="badge {{ $v->record_type == 'Manual' ? 'bg-secondary' : 'bg-warning text-dark' }}">
										{{ $v->record_type }}
									</span>
								</td>
                                <td>
                                    <button class="btn btn-sm btn-info viewBtn"
											data-id="{{ $v->id }}"
											title="View">
										<i class="ti ti-eye"></i>
									</button>

									<button class="btn btn-sm btn-primary reqTypeBtn editBtn"
											data-id="{{ $v->id }}"
											title="Edit">
										<i class="ti ti-edit"></i>
									</button>

									{{-- @if($req_type != 1)
										<button class="btn btn-sm btn-primary editBtn"
												data-id="{{ $v->id }}"
												title="Edit">
											<i class="ti ti-edit"></i>
										</button>
									@endif --}}
                                </td>
                            </tr>
                            @empty
							<tr>
								<td colspan="9" class="text-center">
									No data found
								</td>
							</tr>
							@endforelse

                        </tbody>
                    </table>
					<div class="mt-3 d-flex justify-content-end">
						{{ $data->links('pagination::bootstrap-4') }}
					</div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- Modal -->
<div class="modal fade" id="voucherModal">
    <div class="modal-dialog modal-lg">
        <form method="post" id="voucherForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="edit_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment / Receipt Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Voucher Type Selection -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label d-block fw-bold">Select Voucher Type <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input voucher_type" type="radio" name="voucher_type" id="type_payment" value="Payment Voucher" required checked>
                                <label class="form-check-label" for="type_payment">Payment Voucher</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input voucher_type" type="radio" name="voucher_type" id="type_receipt" value="Receipt Voucher" required>
                                <label class="form-check-label" for="type_receipt">Receipt Voucher</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="voucherFieldsSection">
					
						<div class="col-md-6 mb-2">
							<label class="form-label">Proprietorship Company</label>
							<select name="propId" id="propId" class="form-select">
								<option value="">{{ parentCompanyName() }}</option>
								@foreach($proprietorships as $company)
									<option value="{{ $company->id }}">
										{{ $company->comp_name }}
									</option>
								@endforeach
							</select>
						</div>
						
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                            <input type="text" name="voucher_no" id="voucher_no" readonly class="form-control" placeholder="Enter voucher no.">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Party Type <span class="text-danger">*</span></label>
                            <select name="party_type" id="party_type" class="form-select">
                                <option value="">Please Select</option>
                                <option value="Customer">Customer</option>
                                <option value="Vendor">Vendor</option>
                                <option value="Employee">Employee</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2" id="otherPartyTypeWrap" style="display: none;">
                            <label class="form-label">Other Party Type <span class="text-danger">*</span></label>
                            <input type="text" name="other_party_type" id="other_party_type" class="form-control" placeholder="Enter other party type">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Party Name <span class="text-danger">*</span></label>                            
							<select name="party_name" id="party_name" class="form-select">
                                <option value="">Please Select</option>
                            </select>						
						</div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Transaction Details <span class="text-danger">*</span></label>
                            <select name="transaction_details" id="transaction_details" class="form-select">
                                <option value="" selected>Please Select</option>
                                <option value="Against Invoice">Against Invoice</option>
                                <option value="Advance">Advance</option>
                                <option value="Adjustment">Adjustment</option>
                                <option value="Direct Expense">Direct Expense</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2" id="otherTransactionWrap" style="display: none;">
                            <label class="form-label">Other Transaction Details <span class="text-danger">*</span></label>
                            <input type="text" name="other_transaction_details" id="other_transaction_details" class="form-control" placeholder="Enter other transaction details">
                        </div>

                        <div class="col-md-6 mb-2" id="invoiceNoWrap" style="display: none;">
                            <label class="form-label">Invoice / Reference No</label>                            
							<select name="invoice_no" id="invoice_no" class="form-select">
                                <option value="">Please Select</option>
                            </select>
						</div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Voucher Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" step="0.01" min="0" class="form-control" placeholder="Enter amount">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                            <select name="payment_mode" id="payment_mode" class="form-select">
                                <option value="" selected>Please Select</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>
						
						<div class="col-md-6 mb-2" id="bankWrap" style="display:none;">
							<label class="form-label">Bank Name <span class="text-danger">*</span></label>
							<select name="bank_id" id="bank_id" class="form-select">
								<option value="">Please Select</option>
							</select>
						</div>
						
						<div class="col-md-6 mb-2">
							<label class="form-label">Payment Status <span class="text-danger">*</span></label>
							<select name="is_paid" id="is_paid" class="form-select">
								<option value="0">Outstanding</option>
								<option value="1">Paid</option>
							</select>
						</div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Reference ID (UTR/Cheque No)</label>
                            <input type="text" name="reference_id" id="reference_id" class="form-control" placeholder="Enter reference ID">
                        </div>

                        

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                            <textarea name="narration" id="narration" class="form-control" rows="2" placeholder="Enter purpose"></textarea>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" id="attachment" class="form-control">
							<div id="attachmentPreview" class="mt-2"></div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Approved By</label>
                            <input type="text" name="approved_by" id="approved_by" class="form-control" placeholder="Enter approver name">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('page-script')

<script>

$(document).ready(function () {

	
    var req_type = "{{ $req_type }}";

    if(req_type == 1){
        $('.reqTypeBtn').hide();
    }

	
	//Update DATE
	$('body').on('change', '.updateDate', function (e) {

		e.stopPropagation();
		let id   = $(this).data('id');
		let date = $(this).val();
		$("#loader").show();
		$.ajax({
			url: '/payment-voucher/quick-update',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				id: id,
				field: 'date',
				value: date
			},

			success: function(res)
			{
				$("#loader").hide();
				showToast(res.message, "success");
			},

			error: function()
			{
				$("#loader").hide();
				showToast("Something went wrong!", "error");
			}
		});
	});
	
	//Update paid status
	$(document).on('change', '.updatePaid', function () {

		let id = $(this).data('id');
		let value = $(this).is(':checked') ? 1 : 0;
		$("#loader").show();
		$.ajax({
			url: '/payment-voucher/quick-update',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				id: id,
				field: 'is_paid',
				value: value
			},

			success: function(res)
			{
				$("#loader").hide();
				showToast(res.message, "success");
			},

			error: function()
			{
				$("#loader").hide();
				showToast("Something went wrong!", "error");
			}
		});
	});
	
	//Payment mode change
	function loadBankList(selectedBank = '')
	{
		$("#loader").show();
		$.get('/get-bank-list', function(res){
			$("#loader").hide();
			let html = `<option value="">Please Select</option>`;
			$.each(res, function(i, v){
				let selected = '';
				if(v.id == selectedBank)
				{
					selected = 'selected';
				}

				html += `
					<option value="${v.id}" ${selected}>
						${v.bank_name}
					</option>
				`;
			});

			$('#bank_id').html(html);
		});
	}
	
	function toggleBankField(selectedBank = '')
	{
		let paymentMode = $('#payment_mode').val();
		if(paymentMode === 'Bank')
		{
			$('#bankWrap').show();
			$('#bank_id').prop('required', true);
			loadBankList(selectedBank);
		}
		else
		{
			$('#bankWrap').hide();
			$('#bank_id').val('').prop('required', false).html('<option value="">Please Select</option>');
		}
	}
	
	$('#payment_mode').on('change', function () {
		toggleBankField();
	});

	let isEditMode = false;
	// =========================================
	// RESET FUNCTIONS
	// =========================================

	function resetPartyFields()
	{
		$('#otherPartyTypeWrap').hide();

		$('#other_party_type')
			.val('')
			.prop('required', false);

		// recreate select
		if ($('#party_name').prop("tagName") !== 'SELECT')
		{
			$('#party_name').replaceWith(`
				<select name="party_name"
						id="party_name"
						class="form-select">
					<option value="">Please Select</option>
				</select>
			`);
		}
		else
		{
			$('#party_name').html(`
				<option value="">Please Select</option>
			`);
		}
	}

	function resetTransactionFields()
	{
		$('#transaction_details').val('');

		$('#otherTransactionWrap').hide();

		$('#other_transaction_details')
			.val('')
			.prop('required', false);

		$('#invoiceNoWrap').hide();

		$('#invoice_no')
			.html('<option value="">Please Select</option>')
			.prop('required', false);

		$('#amount').val('');
	}

	function resetPaymentFields()
	{
		$('#payment_mode').val('');
		$('#bank_id').val('');
		$('#bank_id').html(`<option value="">Please Select</option>`);
		$('#bankWrap').hide();
		$('#reference_id').val('');
		$('#narration').val('');
		$('#approved_by').val('');
		$('#attachment').val('');
		$('#attachmentPreview').html('');
	}

	function resetFullFormKeepVoucher()
	{
		resetPartyFields();
		resetTransactionFields();
		resetPaymentFields();
		$('#propId').val('');
	}

	// =========================================
	// GENERATE VOUCHER NUMBER
	// =========================================

	function generateVoucherNumber()
	{
		let voucherType = $('input[name="voucher_type"]:checked').val();

		$("#loader").show();

		$.ajax({
			url: '/generate-payment-voucher-no',
			type: 'GET',
			data: {
				voucher_type: voucherType
			},
			success: function(res)
			{
				$("#loader").hide();

				if(res.status)
				{
					$('#voucher_no').val(res.voucher_no);
				}
			},
			error:function(){
				$("#loader").hide();
			}
		});
	}

	// =========================================
	// LOAD PARTY LIST
	// =========================================

	function loadPartyList(selectedId = '')
	{
		let partyType = $('#party_type').val();

		if(!partyType || partyType === 'Other')
		{
			return;
		}

		$("#loader").show();

		$.get('/get-party-list', {
			party_type: partyType
		}, function (res) {

			$("#loader").hide();

			let html = `
				<option value="">
					Please Select
				</option>
			`;

			$.each(res, function (i, v) {

				let selected = '';

				if(v.id == selectedId)
				{
					selected = 'selected';
				}

				html += `
					<option value="${v.id}" ${selected}>
						${v.name}
					</option>
				`;
			});

			$('#party_name').html(html);
		});
	}

	// =========================================
	// LOAD INVOICE LIST
	// =========================================

	function loadInvoices(selectedInvoice = '')
	{
		let inv_date       = $('#date').val();
		let transaction    = $('#transaction_details').val();
		let party_type     = $('#party_type').val();
		let party_name     = $('#party_name').val();
		let voucherType    = $('input[name="voucher_type"]:checked').val();

		if(transaction !== 'Against Invoice')
		{
			$('#invoiceNoWrap').hide();

			$('#invoice_no')
				.html('<option value="">Please Select</option>');

			return;
		}
		
		// validation
		if(!party_type || !party_name)
		{
			$('#invoiceNoWrap').hide();

			$('#invoice_no')
				.html('<option value="">Please Select</option>');

			return;
		}

		$('#invoiceNoWrap').show();

		$("#loader").show();

		$.get('/get-invoice-list', {
			party_type	 : party_type,
			party_name   : party_name,
			voucher_type : voucherType,
			inv_date	 : inv_date
		}, function(res){

			$("#loader").hide();
			let html = '<option value="">Please Select</option>';
			$.each(res, function(i,v){

				let selected = '';
				if(v.id == selectedInvoice)
				{
					selected = 'selected';
				}
				html += `
					<option value="${v.id}" ${selected}>
						${v.inv_num}
					</option>
				`;
			});

			$('#invoice_no').html(html);
		});
	}
	
	$('#party_name').on('change', function(){

		$('#invoice_no').html(`
			<option value="">Please Select</option>
		`);

		$('#amount').val('');

		if($('#transaction_details').val() === 'Against Invoice')
		{
			loadInvoices();
		}
	});

	// =========================================
	// AUTO AMOUNT
	// =========================================

	function getInvoiceAmount()
	{
		let invoiceId = $('#invoice_no').val();

		let voucherType = $('input[name="voucher_type"]:checked').val();

		if(!invoiceId)
		{
			$('#amount').val('');
			return;
		}

		$("#loader").show();

		$.get('/get-invoice-amount', {
			invoice_id: invoiceId,
			voucher_type: voucherType
		}, function(res){

			$("#loader").hide();

			$('#amount').val(res.amount);
		});
	}

	// =========================================
	// PARTY TYPE CHANGE
	// =========================================

	$('#party_type').on('change', function () {

		// reset dependent fields
		resetPartyFields();

		resetTransactionFields();

		let val = $(this).val();

		if (val === 'Other')
		{
			$('#otherPartyTypeWrap').show();

			$('#other_party_type')
				.prop('required', true);

			$('#party_name').replaceWith(`
				<input type="text"
					   name="party_name"
					   id="party_name"
					   class="form-control"
					   placeholder="Enter party name">
			`);

			return;
		}

		loadPartyList();
	});

	// =========================================
	// TRANSACTION DETAILS CHANGE
	// =========================================

	$('#transaction_details').on('change', function () {

		let val = $(this).val();

		// reset
		$('#otherTransactionWrap').hide();

		$('#other_transaction_details')
			.val('')
			.prop('required', false);

		$('#invoiceNoWrap').hide();

		$('#invoice_no')
			.prop('required', false)
			.html('<option value="">Please Select</option>');

		$('#amount').val('');

		// other
		if (val === 'Other')
		{
			$('#otherTransactionWrap').show();

			$('#other_transaction_details')
				.prop('required', true);
		}

		// invoice
		if (val === 'Against Invoice')
		{
			$('#invoice_no').prop('required', true);

			loadInvoices();
		}
	});

	// =========================================
	// VOUCHER TYPE CHANGE
	// =========================================

	$('.voucher_type').on('change', function () {

		// do not regenerate during edit/view
		if(isEditMode)
		{
			return;
		}
		// reset all dependent fields
		resetFullFormKeepVoucher();

		// regenerate voucher no
		generateVoucherNumber();
	});

	// =========================================
	// DATE CHANGE
	// =========================================

	$('#date').on('change', function(){

		resetTransactionFields();

		if($('#transaction_details').val() === 'Against Invoice')
		{
			loadInvoices();
		}
	});

	// =========================================
	// INVOICE CHANGE
	// =========================================

	$('#invoice_no').on('change', function(){
		getInvoiceAmount();
	});

	// =========================================
	// ADD MODAL
	// =========================================

	$('.btn-add').click(function() {
		
		isEditMode = false;
		$('#voucherForm')[0].reset();
		$('#attachmentPreview').html('');
		resetFullFormKeepVoucher();
		$('#voucherForm input, #voucherForm textarea, #voucherForm select')
			.prop('readonly', false)
			.prop('disabled', false);

		$('.voucher_type').prop('disabled', false);
		$('#saveBtn').show();
		$('#voucherForm').attr(
			'action',
			"{{ route('payment.store') }}"
		);

		generateVoucherNumber();
		$('#voucherModal').modal('show');
	});

	// =========================================
	// EDIT
	// =========================================

	$('.editBtn').click(function () {
		
		isEditMode = true;
		let id = $(this).data('id');
		$('#voucherForm')[0].reset();
		resetFullFormKeepVoucher();

		$('#voucherForm input, #voucherForm textarea, #voucherForm select')
			.prop('readonly', false)
			.prop('disabled', false);

		$('.voucher_type').prop('disabled', false);
		$('#saveBtn').show();
		$("#loader").show();
		
		$.get('/payment-voucher/edit/' + id, function (res) {

			$("#loader").hide();
			$('#propId').val(res.propId);
			$('#date').val(res.date);
			$('#voucher_no').val(res.voucher_no);
			$('input[name="voucher_type"][value="' + res.voucher_type + '"]').prop('checked', true);
			$('#party_type').val(res.party_type);
			$('#transaction_details').val(res.transaction_details);
			$('#amount').val(res.amount);
			$('#payment_mode').val(res.payment_mode);
			toggleBankField(res.bank_id);
			$('#is_paid').val(res.is_paid);
			$('#reference_id').val(res.reference_id);
			$('#narration').val(res.narration);
			$('#approved_by').val(res.approved_by);

			// party
			if(res.party_type === 'Other')
			{
				$('#otherPartyTypeWrap').show();

				$('#other_party_type').val(res.other_party_type);

				$('#party_name').replaceWith(`
					<input type="text"
						   name="party_name"
						   id="party_name"
						   class="form-control"
						   value="${res.party_name}">
				`);
			}
			else
			{
				loadPartyList(res.party_id);
			}

			// transaction
			if(res.transaction_details === 'Other')
			{
				$('#otherTransactionWrap').show();

				$('#other_transaction_details')
					.val(res.other_transaction_details);
			}

			// invoice
			/*if(res.transaction_details === 'Against Invoice')
			{
				loadInvoices(res.invoice_no);
			}*/
			
			if(res.transaction_details === 'Against Invoice')
			{
				$('#invoiceNoWrap').show();

				$('#invoice_no').prop('required', true);

				$("#loader").show();

				$.get('/get-invoice-list', {
					party_type: res.party_type,
					party_name   : res.party_id,
					voucher_type: res.voucher_type,
					inv_date: res.date
				}, function(invoiceRes){

					$("#loader").hide();

					let html = `
						<option value="">Please Select</option>
					`;

					$.each(invoiceRes, function(i, v){

						let selected = '';

						if(v.id == res.invoice_no)
						{
							selected = 'selected';
						}

						html += `
							<option value="${v.id}" ${selected}>
								${v.inv_num}
							</option>
						`;
					});

					$('#invoice_no').html(html);
				});
			}
			else
			{
				$('#invoiceNoWrap').hide();

				$('#invoice_no')
					.html('<option value="">Please Select</option>');
			}

			// attachment
			$('#attachmentPreview').html('');

			if(res.attachment)
			{
				let fileUrl = '/' + res.attachment;

				let ext = res.attachment.split('.').pop().toLowerCase();

				if(['jpg','jpeg','png','gif','webp'].includes(ext))
				{
					$('#attachmentPreview').html(`
						<a href="${fileUrl}" target="_blank">
							<img src="${fileUrl}"
								 style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;padding:2px;">
						</a>
					`);
				}
				else
				{
					$('#attachmentPreview').html(`
						<a href="${fileUrl}"
						   target="_blank"
						   class="btn btn-sm btn-info">
							View Attachment
						</a>
					`);
				}
			}

			// form action
			$('#voucherForm').attr(
				'action',
				'/payment-voucher/update/' + id
			);

			$('#voucherModal').modal('show');
		});
	});

	// =========================================
	// VIEW
	// =========================================

	$('.viewBtn').click(function () {

		let id = $(this).data('id');

		$('.editBtn[data-id="'+id+'"]').trigger('click');

		setTimeout(function () {

			$('#voucherForm input, #voucherForm textarea').prop('readonly', true);
			$('#voucherForm select').prop('disabled', true);
			$('.voucher_type').prop('disabled', true);
			$('#attachment').prop('disabled', true);
			$('#saveBtn').hide();

		}, 700);
	});

	// =========================================
	// FORM SUBMIT
	// =========================================

	$('#voucherForm').submit(function(e) {

		e.preventDefault();
		let form = $(this);
		let url = form.attr('action');
		let formData = new FormData(this);
		$("#loader").show();

		$.ajax({
			url: url,
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,

			success: function(res) {

				$("#loader").hide();
				showToast(res.message, "success");
				setTimeout(function() {
					location.reload();
				}, 1000);
			},

			error: function(xhr) {
				$("#loader").hide();
				if(xhr.status === 422)
				{
					let errors = xhr.responseJSON.errors;
					let errorMsg = '';
					$.each(errors, function(key, value){
						errorMsg += value[0] + '\n';
					});
					showToast(errorMsg, "error");
				}
				else if(xhr.responseJSON && xhr.responseJSON.message)
				{
					showToast(xhr.responseJSON.message, "error");
				}
				else
				{
					showToast("Something went wrong!", "error");
				}
			}
		});
	});

	

});

</script>


@endsection
