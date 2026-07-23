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
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
						<li class="breadcrumb-item"><a href="#">Cash & Banking</a></li>
						<li class="breadcrumb-item"><a href="{{ route('user.PaymentVoucherList') }}">Payment Vouchers</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Payment Voucher</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-payment-voucher-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
				</div>
				<div class="col-md-12">
					<div class="page-header-title">
						<h2 class="mb-0">Add Payment Voucher</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- [ breadcrumb ] end -->

	<form method="post" action="#">
		@csrf

		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-3 mb-3">
							<label class="form-label">Date <span class="text-danger">*</span></label>
							<input type="date" name="date" id="date" class="form-control" required>
						</div>

						<div class="col-sm-3 mb-3">
							<label class="form-label">Voucher No <span class="text-danger">*</span></label>
							<input type="text" name="voucher_no" id="voucher_no" class="form-control" value="CP-00001" readonly required>
						</div>

						<div class="col-sm-3 mb-3">
							<label class="form-label">Party Name <span class="text-danger">*</span></label>
							<input type="text" name="party_name" id="party_name" class="form-control" placeholder="Enter party name" required>
						</div>

						<div class="col-sm-3 mb-3">
							<label class="form-label">Particular <span class="text-danger">*</span></label>
							<input type="text" name="particular" id="particular" class="form-control" placeholder="Enter particular" required>
						</div>


						<div class="col-sm-4 mb-3">
							<label class="form-label">Amount <span class="text-danger">*</span></label>
							<input type="number" min="0" step="0.01" name="amount" id="amount" class="form-control" placeholder="Enter amount">
						</div>

						<div class="col-sm-4 mb-3">
							<label class="form-label">Payment Mode <span class="text-danger">*</span></label>
							<select class="form-select" name="payment_mode" id="payment_mode">
								<option value="" selected>Please Select</option>
								<option value="Bank">Bank</option>
								<option value="Netbanking">Netbanking</option>
								<option value="Cheque">Cheque</option>
								<option value="UPI">UPI</option>
								<option value="Cash">Cash</option>
								<option value="Card">Card</option>
							</select>
						</div>

						<div class="col-sm-4 mb-3" id="bankAccountNameWrap" style="display: none;">
							<label class="form-label">Bank Account Name <span class="text-danger">*</span></label>
							<input type="text" name="bank_account_name" id="bank_account_name" class="form-control" placeholder="Enter bank account name">
						</div>

						<div class="row">
							<div class="col-sm-6 mb-3">
								<label class="form-label">Payment Details</label>
								<textarea name="payment_details" id="payment_details" class="form-control" rows="3" placeholder="Enter payment details"></textarea>
							</div>

							<div class="col-sm-6 mb-3">
								<label class="form-label">Notes</label>
								<textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Enter notes"></textarea>
							</div>
						</div>
					</div>
                    <div class="col-12 text-end my-3">
						<a href="{{ route('user.PaymentVoucherList') }}" class="btn btn-primary cancel me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
				</div>
			</div>
		</div>
	</form>
</div>

@endsection

@section('page-script')

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const voucherNoInput = document.getElementById('voucher_no');
		if (voucherNoInput && (!voucherNoInput.value || voucherNoInput.value === 'AUTO')) {
			const now = new Date();
			const y = now.getFullYear().toString().slice(-2);
			const m = String(now.getMonth() + 1).padStart(2, '0');
			const d = String(now.getDate()).padStart(2, '0');
			const h = String(now.getHours()).padStart(2, '0');
			const min = String(now.getMinutes()).padStart(2, '0');
			voucherNoInput.value = `CP-${y}${m}${d}-${h}${min}`;
		}

		const paymentModeInput = document.getElementById('payment_mode');
		const bankAccountNameWrap = document.getElementById('bankAccountNameWrap');
		const bankAccountNameInput = document.getElementById('bank_account_name');

		const toggleBankAccountField = () => {
			if (!paymentModeInput || !bankAccountNameWrap || !bankAccountNameInput) {
				return;
			}

			const isBankSelected = paymentModeInput.value === 'Bank';
			bankAccountNameWrap.style.display = isBankSelected ? 'block' : 'none';
			bankAccountNameInput.required = isBankSelected;

			if (!isBankSelected) {
				bankAccountNameInput.value = '';
			}
		};

		if (paymentModeInput) {
			paymentModeInput.addEventListener('change', toggleBankAccountField);
			toggleBankAccountField();
		}
	});

    function startAddPaymentVoucherTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add Payment Voucher Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Record a manual cash or bank payment.</p></div>'
                },
                {
                    title: 'Add Payment Voucher',
                    intro: 'Record a manual cash or bank payment.'
                },
                {
                    element: '#date', title: 'Transaction Date',
                    intro: 'Specify the date of the transaction.'
                },
                {
                    element: '#party_name', title: 'Party Name',
                    intro: 'Enter the recipient party name.'
                },
                {
                    element: '#amount', title: 'Voucher Amount',
                    intro: 'Enter the transaction amount.'
                },
                {
                    element: 'button[type="submit"]', title: 'Save Voucher',
                    intro: 'Click here to save the payment voucher.'
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
        $('#start-add-payment-voucher-tour').on('click', function(e) {
            e.preventDefault();
            startAddPaymentVoucherTour();
        });
    });
</script>

@endsection