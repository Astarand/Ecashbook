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
                        <li class="breadcrumb-item active" aria-current="page">Subscription & Billing</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Subscription & Billing</h2>
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
            <div class="row align-items-center justify-content-center mb-4">
                <div class="col-12 mb-4">
                    @if($activeSubscription)
                    <div style="background: linear-gradient(90deg, #8e54e9 0%, #422f90 100%); border-radius:22px; box-shadow:0 2px 12px #6e4afc33; padding:32px 36px; display:flex; align-items:center; justify-content:space-between; font-size:22px; color:#fff; position:relative;">
                        <div style="display:flex; align-items:center; gap:16px;">
                            <span style="font-size:28px; font-weight:700; margin-left:18px;">{{ $activeSubscription->title ?? 'No Active Plan' }}</span>
                        </div>
                        <div style="font-size:16px; font-weight:400; max-width:480px;">
                            <span style="color:#fff; opacity:0.85;">Expiry:</span> <span style="font-weight:600;">{{ \Carbon\Carbon::parse($activeSubscription->end_at)->format('M d, Y') }}</span>
                            &nbsp;|&nbsp;
                            <span style="color:#fff; opacity:0.85;">Next Billing:</span> <span style="font-weight:600;">{{ \Carbon\Carbon::parse($activeSubscription->next_billing_at ?? $activeSubscription->end_at)->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-auto">
                    <!-- Replace toggle with tab design -->
                    <div class="pricing-tab-toggle position-relative mt-4">
                        <!-- 30% OFF badge positioned above the tabs -->
                        <div id="discount-badge"
                            style="position: absolute; top: -19px; right: -38px; display: none; z-index: 4;transform: rotate(22deg);">
                            <div
                                style="background-color: #6e4afc; color: white; border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: bold; display: inline-block;">
                                30% OFF</div>
                        </div>
                        <div class="pricing-tab-container"
                            style="display: inline-flex; background-color: white; border-radius: 8px; position: relative; border: 1px solid #e0e0e0; width: 220px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow: hidden; margin-top: 15px;">
                            <button type="button" onclick="setMonthly()" id="monthly-tab" class="pricing-tab active"
                                style="border: none; border-radius: 8px; padding: 10px 18px; background: transparent; font-weight: 500; z-index: 2; flex: 1; text-align: center; color: white; position: relative;">Monthly</button>
                            <button type="button" onclick="setYearly()" id="yearly-tab" class="pricing-tab"
                                style="border: none; border-radius: 8px; padding: 10px 18px; background: transparent; font-weight: 500; z-index: 2; flex: 1; text-align: center; color: #333;">Yearly</button>
                            <div id="tab-bg"
                                style="position: absolute; top: 0; left: 0; height: 100%; width: 50%; border-radius: 8px; background-color: #6e4afc; transition: all 0.3s ease;">
                            </div>
                        </div>

                        <!-- Arrow pointing from discount badge to Yearly tab -->
                        <div id="discount-arrow"
                            style="position: absolute; top: 9px; right: -31px; display: none; z-index: 3; transform: rotate(65deg);">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="30"
                                height="30">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.5303 13.9697C17.8232 14.2626 17.8232 14.7374 17.5303 15.0303L12.5303 20.0303C12.2374 20.3232 11.7626 20.3232 11.4697 20.0303L6.46967 15.0303C6.17678 14.7374 6.17678 14.2626 6.46967 13.9697C6.76256 13.6768 7.23744 13.6768 7.53033 13.9697L12 18.4393L16.4697 13.9697C16.7626 13.6768 17.2374 13.6768 17.5303 13.9697Z"
                                        fill="#6e4afc"></path>
                                    <g opacity="0.5">
                                        <path
                                            d="M12.75 9.5C12.75 8.54665 12.4702 7.13332 11.6087 5.93677C10.7196 4.70198 9.24444 3.75 7 3.75C6.58579 3.75 6.25 4.08579 6.25 4.5C6.25 4.91421 6.58579 5.25 7 5.25C8.75556 5.25 9.7804 5.96468 10.3913 6.81323C11.0298 7.70002 11.25 8.78668 11.25 9.5L11.25 17.6893L12 18.4393L12.75 17.6893V9.5Z"
                                            fill="#6e4afc"></path>
                                        <path
                                            d="M12.1977 20.2236C12.0432 20.2656 11.878 20.2575 11.7278 20.199C11.8122 20.2319 11.904 20.25 12 20.25C12.0684 20.25 12.1347 20.2408 12.1977 20.2236Z"
                                            fill="#6e4afc"></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <style>
                        /* Remove the filter since we're using the direct fill color */
                        #discount-arrow svg {
                            filter: none;
                        }
                    </style>
                </div>
            </div>
            <div class="d-block" id="pricemonth">
                <div class="row">
                        @foreach($plans as $plan)
                        <div class="col-md-6 col-xxl-3">
                            <div class="card price-card" @if($activeSubscription && $activeSubscription->pid == $plan->id && $activeSubscription->plan_type == 'monthly') style="border:2px solid #6e4afc; box-shadow:0 0 10px #6e4afc33;" @endif>
                                <div class="card-body price-head" style="background-color: #422f90;">
                                    <h5 class="text-white">{{ $plan->title }}</h5>
                                    <h2 class="price-price text-white">₹{{ $plan->monthly_price }} <span>/Month</span></h2>
                                    <div class="price-icon bg-light-primary">
                                        <i class="{{ $plan->icon ?? 'ph-duotone ph-rocket' }}" style="color:#422f90;"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled product-list">
                                        @foreach($plan->features as $feature)
                                        <li class="{{ $feature->is_enabled ? 'enable' : '' }}">
                                            @if($feature->is_enabled)
                                            <i class="ph-duotone ph-check-circle"></i>
                                            @endif
                                            {{ $feature->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    @if($activeSubscription && $activeSubscription->pid == $plan->id && $activeSubscription->plan_type == 'monthly')
                                    <div class="subscription-info" style="font-weight:bold; color:#422f90; background:#f3f0ff; border-radius:6px; padding:4px 10px; text-align:center; margin-bottom:8px;">
                                        <i class="ph-duotone ph-calendar-check me-2"></i>
                                        Active until: {{ \Carbon\Carbon::parse($activeSubscription->end_at)->format('M d, Y') }}
                                    </div>
                                    @endif
                                    <div class="d-grid">
                                        @if($activeSubscription && $activeSubscription->pid == $plan->id && $activeSubscription->plan_type == 'monthly')
                                        <button class="btn" style="background-color:#176a3a;color:#fff;" disabled>
                                            <i class="ph-duotone ph-check-circle me-2"></i>Activated
                                        </button>
                                        @else
                                        <button class="btn btn-primary activate-plan-btn" data-plan-id="{{ $plan->id }}"
                                            data-plan-title="{{ $plan->title }}" data-amount="{{ $plan->monthly_price }}"
                                            data-billing-type="monthly">
                                            Activate This Plan
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>

            <div class="d-none" id="priceyear">
                <div class="row">
                        @foreach($plans as $plan)
                        <div class="col-md-6 col-xxl-3">
                            <div class="card price-card" @if($activeSubscription && $activeSubscription->pid == $plan->id && $activeSubscription->plan_type == 'yearly') style="border:2px solid #6e4afc; box-shadow:0 0 10px #6e4afc33;" @endif>
                                <div class="card-body price-head" style="background-color: #422f90;">
                                    <h5 class="text-white">{{ $plan->title }}</h5>
                                    <h2 class="price-price text-white">₹{{ $plan->yearly_price }} <span>/Year</span></h2>
                                    <div class="price-icon bg-light-primary">
                                        <i class="{{ $plan->icon ?? 'ph-duotone ph-rocket' }}" style="color:#422f90;"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled product-list">
                                        @foreach($plan->features as $feature)
                                        <li class="{{ $feature->is_enabled ? 'enable' : '' }}">
                                            @if($feature->is_enabled)
                                            <i class="ph-duotone ph-check-circle"></i>
                                            @endif
                                            {{ $feature->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    @if($activeSubscription && $activeSubscription->pid == $plan->id && $activeSubscription->plan_type == 'yearly')
                                    <div class="subscription-info" style="font-weight:bold; color:#422f90; background:#f3f0ff; border-radius:6px; padding:4px 10px; text-align:center; margin-bottom:8px;">
                                        <i class="ph-duotone ph-calendar-check me-2"></i>
                                        Active until: {{ \Carbon\Carbon::parse($activeSubscription->end_at)->format('M d, Y') }}
                                    </div>
                                    @endif
                                    <div class="d-grid">
                                        @if($activeSubscription && $activeSubscription->pid == $plan->id && $activeSubscription->plan_type == 'yearly')
                                        <button class="btn" style="background-color:#176a3a;color:#fff;" disabled>
                                            <i class="ph-duotone ph-check-circle me-2"></i>Activated
                                        </button>
                                        @else
                                        <button class="btn btn-primary activate-plan-btn" data-plan-id="{{ $plan->id }}"
                                            data-plan-title="{{ $plan->title }}" data-amount="{{ $plan->yearly_price }}"
                                            data-billing-type="yearly">
                                            Activate This Plan
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <p><strong><span class="text-danger">*</span> PROFESSIONAL & ENTERPRISES PLAN</strong> includes
                        On-Site Professional Accountant Support, but fees may increase based on business growth, and is
                        restricted to companies with annual turnovers of up to ₹50 lakhs or 10 monthly invoices.We will
                        consider the fee increase as per nature of business.
						<strong>The PROFESSIONAL & ENTERPRISES PLAN</strong> includes on-site support from a qualified professional accountant. 
						Fees may increase as your business grows and this plan is available only to companies with an annual turnover of up to ₹50 lakhs or up to 10 invoices per month. 
						Any fee revision will be determined based on the nature and requirements of your business. 
						</p>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->

</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">

            <!-- Header -->
            <div class="modal-header border-0 text-white position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2.5rem 2rem 2rem;">
                <div class="w-100 text-center">
                    <h4 class="modal-title fw-bold mb-2" id="paymentConfirmationModalLabel">Confirm Your Payment</h4>
                    <p class="mb-0 opacity-75">Review your subscription details before proceeding</p>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 1rem; right: 1rem;"></button>
            </div>

            <div class="modal-body p-0">

                <!-- Plan Name Card -->
                <div class="p-4 text-center" style="background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);">
                    <small class="text-muted text-uppercase d-block mb-2" style="font-size: 0.75rem; letter-spacing: 1.5px; font-weight: 600;">Selected Plan</small>
                    <h3 class="mb-0 fw-bold" id="modal-plan-name" style="color: #667eea;">Premium Plan</h3>
                </div>

                <!-- Payment Details -->
                <div class="p-4">
                    <!-- Plan Details Card -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <div class="card border-0" style="background: #f8f9fa;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ph-duotone ph-calendar-check me-2" style="color: #667eea; font-size: 1.3rem;"></i>
                                        <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Billing Cycle</small>
                                    </div>
                                    <h5 class="mb-0 fw-bold" id="modal-plan-type" style="color: #2c3e50;">Monthly</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0" style="background: #f8f9fa;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ph-duotone ph-clock me-2" style="color: #667eea; font-size: 1.3rem;"></i>
                                        <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Start Date</small>
                                    </div>
                                    <h5 class="mb-0 fw-bold" style="color: #2c3e50;">{{ date('d M Y') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Breakdown -->
                    <div class="border rounded-3 p-4 mb-4" style="background: #fff;">
                        <h6 class="mb-3 fw-bold text-uppercase" style="color: #2c3e50; font-size: 0.85rem; letter-spacing: 1px;">
                            <i class="ph-duotone ph-receipt me-2" style="color: #667eea;"></i>Amount Breakdown
                        </h6>

                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-muted">Base Amount</span>
                            <span class="fw-bold" id="modal-base-amount" style="color: #2c3e50; font-size: 1.1rem;">₹0.00</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-muted">GST (18%)</span>
                            <span class="fw-semibold" id="modal-gst-amount" style="color: #2c3e50;">₹0.00</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="fw-semibold" style="color: #2c3e50;">Subtotal</span>
                            <span class="fw-bold" id="modal-total-amount" style="color: #2c3e50; font-size: 1.1rem;">₹0.00</span>
                        </div>

                        <!-- Adjustment (hidden by default) -->
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom" id="adjustment-row" style="display: none;">
                            <span class="text-success fw-medium">
                                <i class="ph-duotone ph-coins me-2"></i>Discount Applied
                            </span>
                            <span class="text-success fw-bold" id="modal-adjustment-amount">-₹0.00</span>
                        </div>
                    </div>

                    <!-- Total Amount Card -->
                    <div class="rounded-3 p-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
                        <div class="position-absolute" style="top: -20px; right: -20px; opacity: 0.1;">
                            <i class="ph-duotone ph-seal-check" style="font-size: 8rem; color: #fff;"></i>
                        </div>
                        <div class="row align-items-center position-relative" style="z-index: 1;">
                            <div class="col-6">
                                <small class="text-white opacity-75 text-uppercase d-block mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Amount</small>
                                <span class="text-white fw-medium">You will be charged</span>
                            </div>
                            <div class="col-6 text-end">
                                <h2 class="mb-0 text-white fw-bold" id="modal-subtotal-amount" style="font-size: 2.5rem; line-height: 1;">₹0.00</h2>
                                <small class="text-white opacity-75">Incl. all taxes</small>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-4 p-3 rounded-3" style="background: #f0fdf4; border: 1px solid #86efac;">
                        <i class="ph-duotone ph-lock-simple" style="color: #16a34a; font-size: 1.5rem;"></i>
                        <div>
                            <p class="mb-0 fw-semibold text-success">Secure Payment</p>
                            <small class="text-muted" style="font-size: 0.75rem;">Your payment information is encrypted and secure</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <div class="d-flex gap-3 w-100">
                    <button type="button" class="btn btn-light flex-fill py-3 fw-medium border" data-bs-dismiss="modal" style="border-radius: 12px;">
                        <i class="ph-duotone ph-arrow-left me-2"></i>Go Back
                    </button>
                    <button type="button" class="btn text-white flex-fill py-3 fw-bold position-relative" id="proceedToPayment" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);">
                        <i class="ph-duotone ph-check-circle me-2"></i>Confirm & Pay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Payment Confirmation Modal */
#paymentConfirmationModal .modal-content {
    border: none;
}

/* Smooth card hover */
#paymentConfirmationModal .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

#paymentConfirmationModal .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Button effects */
#proceedToPayment {
    transition: all 0.3s ease;
}

#proceedToPayment:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
}

.btn-light:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #paymentConfirmationModal .modal-header {
        padding: 1.5rem 1rem 1rem;
    }

    #modal-subtotal-amount {
        font-size: 2rem !important;
    }
}
</style>




<script>
    function setMonthly() {
        document.getElementById('monthly-tab').classList.add('active');
        document.getElementById('yearly-tab').classList.remove('active');
        document.getElementById('tab-bg').style.left = '0';
        document.getElementById('monthly-tab').style.color = 'white';
        document.getElementById('yearly-tab').style.color = '#333';
        document.getElementById('pricemonth').classList.remove('d-none');
        document.getElementById('pricemonth').classList.add('d-block');
        document.getElementById('priceyear').classList.remove('d-block');
        document.getElementById('priceyear').classList.add('d-none');

        // Show the discount badge and arrow when in Monthly view and point to Yearly tab
        document.getElementById('discount-badge').style.display = 'block';
        document.getElementById('discount-arrow').style.display = 'block';
    }

    function setYearly() {
        document.getElementById('yearly-tab').classList.add('active');
        document.getElementById('monthly-tab').classList.remove('active');
        document.getElementById('tab-bg').style.left = '50%';
        document.getElementById('yearly-tab').style.color = 'white';
        document.getElementById('monthly-tab').style.color = '#333';
        document.getElementById('priceyear').classList.remove('d-none');
        document.getElementById('priceyear').classList.add('d-block');
        document.getElementById('pricemonth').classList.remove('d-block');
        document.getElementById('pricemonth').classList.add('d-none');

        // Hide the discount badge and arrow when in Yearly view
        document.getElementById('discount-badge').style.display = 'none';
        document.getElementById('discount-arrow').style.display = 'none';
    }

    // Fallback showToast function if not defined globally
    if (typeof showToast !== 'function') {
        function showToast(message, type) {
            // Simple fallback alert if showToast is not available
            if (type === 'success') {
                alert('✅ ' + message);
            } else if (type === 'error') {
                alert('❌ ' + message);
            } else {
                alert(message);
            }
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Set default to monthly to show the discount badge and arrow
        setMonthly();

        // Add event listeners to activate plan buttons
        document.querySelectorAll('.activate-plan-btn').forEach(button => {
            button.addEventListener('click', function() {
                const planId = this.getAttribute('data-plan-id');
                const planTitle = this.getAttribute('data-plan-title');
                const amount = parseFloat(this.getAttribute('data-amount'));
                const billingType = this.getAttribute('data-billing-type');

                showPaymentConfirmationModal(planId, planTitle, amount, billingType);
            });
        });

        // Add event listener for proceed to payment button
        document.getElementById('proceedToPayment').addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const planTitle = this.getAttribute('data-plan-title');
            const totalAmount = parseFloat(this.getAttribute('data-total-amount'));
            const billingType = this.getAttribute('data-billing-type');

            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentConfirmationModal'));
            modal.hide();

            // Proceed to payment
            initiatePayment(planId, planTitle, totalAmount, billingType);
        });
    });

    function showPaymentConfirmationModal(planId, planTitle, baseAmount, billingType) {
        // Calculate money adjustment for plan upgrades
        const adjustmentAmount = calculateMoneyAdjustment(baseAmount, billingType);

        // Calculate GST (18%)
        const gstAmount = baseAmount * 0.18;
        const subtotalAmount = baseAmount + gstAmount;
        const finalAmount = subtotalAmount - adjustmentAmount;

        // Update modal content
        document.getElementById('modal-plan-name').textContent = planTitle;
        document.getElementById('modal-plan-type').textContent = billingType.charAt(0).toUpperCase() + billingType.slice(1);
        document.getElementById('modal-base-amount').textContent = '₹' + baseAmount.toFixed(2);
        document.getElementById('modal-gst-amount').textContent = '₹' + gstAmount.toFixed(2);
        document.getElementById('modal-total-amount').textContent = '₹' + subtotalAmount.toFixed(2);
        document.getElementById('modal-adjustment-amount').textContent = adjustmentAmount > 0 ? '-₹' + adjustmentAmount.toFixed(2) : '₹0';
        document.getElementById('modal-subtotal-amount').textContent = '₹' + finalAmount.toFixed(2);

        // Show/hide adjustment row based on whether there's an adjustment
        const adjustmentRow = document.getElementById('adjustment-row');
        if (adjustmentAmount > 0) {
            adjustmentRow.style.display = 'flex';
        } else {
            adjustmentRow.style.display = 'none';
        }

        // Store data in proceed button for later use
        const proceedBtn = document.getElementById('proceedToPayment');
        proceedBtn.setAttribute('data-plan-id', planId);
        proceedBtn.setAttribute('data-plan-title', planTitle);
        proceedBtn.setAttribute('data-total-amount', finalAmount.toFixed(2));
        proceedBtn.setAttribute('data-billing-type', billingType);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('paymentConfirmationModal'));
        modal.show();
    }

    function calculateMoneyAdjustment(newPlanAmount, newBillingType) {
        // Get active subscription data from PHP
        const activeSubscription = @json($activeSubscription);

        if (!activeSubscription) {
            return 0; // No active subscription, no adjustment
        }

        // Get current plan amount based on billing type
        const currentPlanAmount = activeSubscription.plan_type === 'monthly'
            ? parseFloat(activeSubscription.monthly_price || 0)
            : parseFloat(activeSubscription.yearly_price || 0);

        // Only apply adjustment for upgrades (new plan costs more)
        if (newPlanAmount <= currentPlanAmount) {
            return 0; // Not an upgrade, no adjustment
        }

        // Calculate remaining days
        const currentDate = new Date();
        const endDate = new Date(activeSubscription.end_at);
        const remainingDays = Math.max(0, Math.ceil((endDate - currentDate) / (1000 * 60 * 60 * 24)));

        if (remainingDays <= 0) {
            return 0; // Subscription expired, no adjustment
        }

        // Calculate daily rate of current plan
        const totalDaysInCurrentPlan = activeSubscription.plan_type === 'monthly' ? 28 : 365;
        const dailyRate = currentPlanAmount / totalDaysInCurrentPlan;

        // Calculate adjustment amount (money back for unused days)
        const adjustmentAmount = dailyRate * remainingDays;

        // console.log('Money Adjustment Calculation:', {
        //     currentPlanAmount: currentPlanAmount,
        //     newPlanAmount: newPlanAmount,
        //     remainingDays: remainingDays,
        //     dailyRate: dailyRate,
        //     adjustmentAmount: adjustmentAmount,
        //     currentPlanType: activeSubscription.plan_type,
        //     totalDaysInCurrentPlan: totalDaysInCurrentPlan
        // });

        return adjustmentAmount;
    }

    function initiatePayment(planId, planTitle, amount, billingType) {
        // Create order on server
        fetch('{{ route("subscription.create-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                plan_id: planId,
                amount: amount,
                billing_type: billingType
            })
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {
                openRazorpayCheckout(data.order, planId, planTitle, amount, billingType);
            } else {
                showToast('Error creating order: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while creating the order', 'error');
        });
    }

    function openRazorpayCheckout(order, planId, planTitle, amount, billingType) {
        const options = {
            key: '{{ config("services.razorpay.key") }}',
            amount: order.amount,
            currency: order.currency,
            name: 'Subscription Plan',
            description: planTitle + ' - ' + billingType.charAt(0).toUpperCase() + billingType.slice(1),
            order_id: order.id,
            handler: function(response) {
                // Payment successful
                verifyPayment(response, planId, amount, billingType);
            },
            prefill: {
                name: '{{ auth()->user()->name ?? "" }}',
                email: '{{ auth()->user()->email ?? "" }}',
                contact: '{{ auth()->user()->phone ?? "" }}'
            },
            theme: {
                color: '#422f90'
            },
            modal: {
                ondismiss: function() {
                    console.log('Payment modal closed');
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    }

    function verifyPayment(response, planId, amount, billingType) {
        // First try the test endpoint to see if the issue is with signature verification
        console.log('Payment response:', response);

        const paymentData = {
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_signature: response.razorpay_signature,
            plan_id: planId,
            amount: amount,
            billing_type: billingType
        };

        console.log('Sending payment data:', paymentData);

        fetch('{{ route("subscription.test-verify-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(paymentData)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            if (data.success) {
                showToast('Payment successful! Your subscription has been activated.', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showToast('Payment verification failed: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while verifying the payment', 'error');
        });
    }
</script>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@endsection
