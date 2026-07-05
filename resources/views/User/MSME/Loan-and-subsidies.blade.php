@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center text-white">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-box bg-white bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="ph-duotone ph-bank fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">MSME Loans & Subsidies</h3>
                                <p class="mb-0 opacity-75 small">Analyze credit opportunities, calculate monthly payments, and discover subsidy options</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="row">
        {{-- EMI Calculator Section --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark"><i class="ph-duotone ph-calculator me-2 text-success"></i>Business Loan EMI Calculator</h5>
                    <p class="text-muted small">Determine monthly debt servicing outlays based on principal, interest rate, and tenure.</p>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="mb-4">
                        <label for="emiAmount" class="form-label fw-semibold text-dark d-flex justify-content-between">
                            <span>Loan Amount</span>
                            <span class="text-success fw-bold" id="emiAmountVal">₹10 Lakh</span>
                        </label>
                        <input type="range" class="form-range" id="emiAmount" min="1" max="200" step="1" value="10">
                        <div class="d-flex justify-content-between text-muted small mt-1">
                            <span>₹1 Lakh</span>
                            <span>₹50 Lakh</span>
                            <span>₹1 Crore</span>
                            <span>₹2 Crore</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="emiRate" class="form-label fw-semibold text-dark d-flex justify-content-between">
                            <span>Interest Rate (p.a.)</span>
                            <span class="text-success fw-bold" id="emiRateVal">8.5%</span>
                        </label>
                        <input type="range" class="form-range" id="emiRate" min="5" max="20" step="0.1" value="8.5">
                        <div class="d-flex justify-content-between text-muted small mt-1">
                            <span>5%</span>
                            <span>10%</span>
                            <span>15%</span>
                            <span>20%</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="emiTenure" class="form-label fw-semibold text-dark d-flex justify-content-between">
                            <span>Loan Tenure</span>
                            <span class="text-success fw-bold" id="emiTenureVal">5 Years</span>
                        </label>
                        <input type="range" class="form-range" id="emiTenure" min="1" max="15" step="1" value="5">
                        <div class="d-flex justify-content-between text-muted small mt-1">
                            <span>1 Year</span>
                            <span>5 Years</span>
                            <span>10 Years</span>
                            <span>15 Years</span>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded-3 d-flex flex-column gap-3 mt-4 border border-dashed border-success border-opacity-25">
                        <div class="row text-center">
                            <div class="col-4">
                                <span class="text-muted small d-block mb-1">Monthly EMI</span>
                                <h5 class="fw-bold text-dark mb-0" id="calculatedEmi">₹20,517</h5>
                            </div>
                            <div class="col-4 border-start">
                                <span class="text-muted small d-block mb-1">Total Interest</span>
                                <h5 class="fw-bold text-warning mb-0" id="calculatedInterest">₹2.31 Lakh</h5>
                            </div>
                            <div class="col-4 border-start">
                                <span class="text-muted small d-block mb-1">Total Payable</span>
                                <h5 class="fw-bold text-success mb-0" id="calculatedTotal">₹12.31 Lakh</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Subsidy Schemes Section --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark"><i class="ph-duotone ph-currency-circle-dollar me-2 text-success"></i>Featured Subsidies</h5>
                    <p class="text-muted small">Specialized credit-linked interest subsidies and capital grants.</p>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <div class="list-group list-group-flush">
                        {{-- Subsidy 1 --}}
                        <div class="list-group-item bg-transparent border-0 px-0 py-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="bg-light-success p-2 rounded me-3 text-success">
                                    <i class="ph-duotone ph-percent fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold text-dark mb-0">CLCSS (Technology Subsidy)</h6>
                                        <span class="badge bg-light-success text-success rounded-pill fw-semibold small">15% Grant</span>
                                    </div>
                                    <p class="text-muted small mb-0">15% upfront capital subsidy for institutional finance taken to modernize machinery and tooling.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Subsidy 2 --}}
                        <div class="list-group-item bg-transparent border-0 px-0 py-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="bg-light-success p-2 rounded me-3 text-success">
                                    <i class="ph-duotone ph-gender-female fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold text-dark mb-0">Stand-Up India</h6>
                                        <span class="badge bg-light-info text-info rounded-pill fw-semibold small">SC/ST & Women</span>
                                    </div>
                                    <p class="text-muted small mb-0">Loans from ₹10 Lakh up to ₹1 Crore for setting up greenfield enterprises with concessional rates.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Subsidy 3 --}}
                        <div class="list-group-item bg-transparent border-0 px-0 py-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="bg-light-success p-2 rounded me-3 text-success">
                                    <i class="ph-duotone ph-factory-members fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold text-dark mb-0">SIDBI SMILE</h6>
                                        <span class="badge bg-light-primary text-primary rounded-pill fw-semibold small">Soft Loan</span>
                                    </div>
                                    <p class="text-muted small mb-0">Attractive soft loan assistance for meeting debt-equity ratios of new or growing enterprises.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center text-md-start">
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-success w-100 py-2 fw-semibold text-white shadow-sm">
                            <i class="ph-duotone ph-chats me-1"></i> Speak to CA to Apply Subsidy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.header-icon-box {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
</style>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // EMI Calculation triggers
    $('#emiAmount, #emiRate, #emiTenure').on('input', function() {
        calculateEmiValues();
    });

    function calculateEmiValues() {
        let amount = parseFloat($('#emiAmount').val()) * 100000; // in Lakhs
        let rate = parseFloat($('#emiRate').val()) / 12 / 100; // monthly rate
        let tenure = parseFloat($('#emiTenure').val()) * 12; // in months

        // UI Label Updates
        $('#emiAmountVal').text('₹' + $('#emiAmount').val() + ' Lakh');
        $('#emiRateVal').text($('#emiRate').val() + '%');
        $('#emiTenureVal').text($('#emiTenure').val() + ' Years');

        // EMI Formula
        // EMI = [P x R x (1+R)^N]/[((1+R)^N)-1]
        let emi = (amount * rate * Math.pow(1 + rate, tenure)) / (Math.pow(1 + rate, tenure) - 1);
        let totalPayable = emi * tenure;
        let totalInterest = totalPayable - amount;

        // Formatting
        $('#calculatedEmi').text('₹' + Math.round(emi).toLocaleString('en-IN'));
        
        let formatInterest = totalInterest / 100000;
        let formatTotal = totalPayable / 100000;

        $('#calculatedInterest').text('₹' + formatInterest.toFixed(2) + ' Lakh');
        $('#calculatedTotal').text('₹' + formatTotal.toFixed(2) + ' Lakh');
    }

    calculateEmiValues(); // Initial trigger
});
</script>
@endsection
