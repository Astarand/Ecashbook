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
                                <i class="ph-duotone ph-check-circle fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">MSME Eligibility Checker</h3>
                                <p class="mb-0 opacity-75 small">Instantly calculate your enterprise class and check matching schemes & benefits</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="row">
        {{-- Left Form Column --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark"><i class="ph-duotone ph-calculator me-2 text-primary"></i>Enterprise Details</h5>
                    <p class="text-muted small">Enter your financial figures to find your MSME tier classification.</p>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <form id="eligibilityForm" action="javascript:void(0);">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Business Sector</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="sector" id="sectorMfg" value="manufacturing" checked>
                                    <label class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1 rounded-3" for="sectorMfg">
                                        <i class="ph-duotone ph-factory fs-3"></i>
                                        <span class="small fw-bold">Manufacturing</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="sector" id="sectorSvc" value="services">
                                    <label class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1 rounded-3" for="sectorSvc">
                                        <i class="ph-duotone ph-briefcase fs-3"></i>
                                        <span class="small fw-bold">Services</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="investment" class="form-label fw-semibold text-dark d-flex justify-content-between">
                                <span>Investment in Plant & Machinery</span>
                                <span class="text-primary fw-bold" id="investmentVal">₹1.00 Crore</span>
                            </label>
                            <input type="range" class="form-range" id="investment" min="0.1" max="60" step="0.1" value="1">
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>₹10 Lakh</span>
                                <span>₹10 Cr</span>
                                <span>₹30 Cr</span>
                                <span>₹50 Cr+</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="turnover" class="form-label fw-semibold text-dark d-flex justify-content-between">
                                <span>Annual Turnover</span>
                                <span class="text-primary fw-bold" id="turnoverVal">₹5.00 Crore</span>
                            </label>
                            <input type="range" class="form-range" id="turnover" min="0.5" max="300" step="0.5" value="5">
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>₹50 Lakh</span>
                                <span>₹50 Cr</span>
                                <span>₹150 Cr</span>
                                <span>₹250 Cr+</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Additional Details</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="hasUdyam">
                                <label class="form-check-label text-secondary small" for="hasUdyam">Already registered with Udyam Portal?</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="hasStartup">
                                <label class="form-check-label text-secondary small" for="hasStartup">Registered with DPIIT as a Startup?</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" id="btnCalculate">
                            <i class="ph-duotone ph-lightning me-1"></i> Calculate Classification
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Results Column --}}
        <div class="col-lg-7 mb-4">
            {{-- Initial state: Guidelines --}}
            <div id="classificationPlaceholder" class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center py-5 px-4 text-center">
                    <div class="bg-light-primary p-4 rounded-circle mb-3">
                        <i class="ph-duotone ph-info fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Awaiting Classification</h5>
                    <p class="text-muted max-w-400">Adjust the sliders on the left corresponding to your enterprise's investment and turnover parameters, then calculate to view eligibility results.</p>
                    <div class="border-top w-100 pt-4 mt-3 text-start">
                        <h6 class="fw-bold mb-2 text-dark">Revised MSME Threshold Rules:</h6>
                        <ul class="text-muted small ps-3">
                            <li class="mb-1"><strong class="text-secondary">Micro:</strong> Investment ≤ ₹1 Cr & Turnover ≤ ₹5 Cr</li>
                            <li class="mb-1"><strong class="text-secondary">Small:</strong> Investment ≤ ₹10 Cr & Turnover ≤ ₹50 Cr</li>
                            <li class="mb-1"><strong class="text-secondary">Medium:</strong> Investment ≤ ₹50 Cr & Turnover ≤ ₹250 Cr</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Results State --}}
            <div id="classificationResults" class="d-none h-100">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 text-center text-md-start">
                        <div class="row align-items-center">
                            <div class="col-md-3 mb-3 mb-md-0 text-center">
                                <div id="badgeContainer" class="p-4 rounded-circle d-inline-block shadow-sm">
                                    <i id="classIcon" class="fs-1"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <span class="badge bg-light-primary text-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Calculated Status</span>
                                <h3 id="classTitle" class="fw-bold mb-1 text-dark">Micro Enterprise</h3>
                                <p id="classDesc" class="text-muted small mb-0">Your business fits within the micro classification thresholds based on investment & turnover.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Eligible benefits --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-dark"><i class="ph-duotone ph-sparkles me-2 text-warning"></i>Eligible Benefits & Subsidies</h5>

                        <div class="list-group list-group-flush" id="benefitsList">
                            <!-- Populated dynamically -->
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                            <a href="{{ route('user.msme.discover-schemes') }}" class="btn btn-outline-primary btn-sm px-3">Explore Schemes</a>
                            <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm px-3 flex-fill">Request Registration Assistance</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.max-w-400 {
    max-width: 400px;
}
.header-icon-box {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Format slider values
    $('#investment').on('input', function() {
        let val = parseFloat($(this).val());
        if (val < 1) {
            $('#investmentVal').text('₹' + Math.round(val * 100) + ' Lakh');
        } else {
            $('#investmentVal').text('₹' + val.toFixed(1) + ' Crore');
        }
    });

    $('#turnover').on('input', function() {
        let val = parseFloat($(this).val());
        if (val < 1) {
            $('#turnoverVal').text('₹' + Math.round(val * 100) + ' Lakh');
        } else {
            $('#turnoverVal').text('₹' + val.toFixed(1) + ' Crore');
        }
    });

    // Form submission calculation
    $('#eligibilityForm').on('submit', function() {
        let investment = parseFloat($('#investment').val());
        let turnover = parseFloat($('#turnover').val());
        let isStartup = $('#hasStartup').is(':checked');

        let classification = '';
        let classDesc = '';
        let classColor = '';
        let classIcon = '';

        if (investment <= 1 && turnover <= 5) {
            classification = 'Micro Enterprise';
            classDesc = 'Congratulations! Your business falls under the Micro category. You are eligible for maximum interest rate subsidies and priority registration schemes.';
            classColor = '#05b871';
            classIcon = 'ph-duotone ph-shield-check text-white';
        } else if (investment <= 10 && turnover <= 50) {
            classification = 'Small Enterprise';
            classDesc = 'Your business falls under the Small category. You are eligible for standard credit guarantees, technology upgradation schemes, and procurement benefits.';
            classColor = '#ff9f43';
            classIcon = 'ph-duotone ph-trend-up text-white';
        } else if (investment <= 50 && turnover <= 250) {
            classification = 'Medium Enterprise';
            classDesc = 'Your business falls under the Medium category. You are eligible for specialized export market support, industrial infrastructure grants, and technology transfer subsidies.';
            classColor = '#243e8b';
            classIcon = 'ph-duotone ph-buildings text-white';
        } else {
            classification = 'Large Enterprise (Non-MSME)';
            classDesc = 'Your enterprise dimensions exceed the current statutory thresholds for MSMEs. You might explore startup benefits or corporate expansion incentives.';
            classColor = '#ea5455';
            classIcon = 'ph-duotone ph-x-circle text-white';
        }

        // Show result views
        $('#classificationPlaceholder').addClass('d-none');
        $('#classificationResults').removeClass('d-none');

        // Style the badge background
        $('#badgeContainer').css('background-color', classColor);
        $('#classIcon').attr('class', classIcon + ' fs-1');
        $('#classTitle').text(classification);
        $('#classDesc').text(classDesc);

        // Generate benefits list
        let benefits = [];
        if (classification !== 'Large Enterprise (Non-MSME)') {
            benefits.push({
                title: 'Collateral-free CGTMSE Loan Assistance',
                desc: 'Access credit up to ₹5 Crore without offering collateral security.',
                icon: 'ph-duotone ph-percent text-success'
            });
            benefits.push({
                title: 'Delayed Payment Protection',
                desc: 'Statutory mandate requiring buyers to pay MSMEs within 45 days, with compounding interest penalties.',
                icon: 'ph-duotone ph-clock text-info'
            });
            benefits.push({
                title: 'ISO & ZED Certification Subsidy',
                desc: 'Get up to 80% reimbursement on quality and ecological certification audits.',
                icon: 'ph-duotone ph-certificate text-warning'
            });
            benefits.push({
                title: 'Electricity & Patent Rebates',
                desc: 'Up to 50% concession on patent registration fee and discount on power tariffs.',
                icon: 'ph-duotone ph-lightbulb text-primary'
            });
        }
        if (isStartup) {
            benefits.push({
                title: 'DPIIT Startup Tax Holiday',
                desc: '3 years of 100% tax holiday under Section 80-IAC of the Income Tax Act.',
                icon: 'ph-duotone ph-piggy-bank text-danger'
            });
        }
        if (classification === 'Large Enterprise (Non-MSME)') {
            benefits.push({
                title: 'Corporate Assistance & Strategic Consulting',
                desc: 'Connect with a CA expert to review scaling plans, IPO planning, or tax compliance optimization.',
                icon: 'ph-duotone ph-users-three text-danger'
            });
        }

        let benefitsHtml = '';
        benefits.forEach(function(b) {
            benefitsHtml += `
                <div class="list-group-item bg-transparent border-0 px-0 py-3 d-flex align-items-start border-bottom">
                    <div class="bg-light p-2 rounded me-3">
                        <i class="${b.icon} fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">${b.title}</h6>
                        <p class="text-muted small mb-0">${b.desc}</p>
                    </div>
                </div>
            `;
        });
        $('#benefitsList').html(benefitsHtml);
    });
});
</script>
@endsection
