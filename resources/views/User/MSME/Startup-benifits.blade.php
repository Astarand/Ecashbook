@extends('App.Layout')

@section('container')
<div class="pc-content">
    {{-- PAGE HEADER --}}
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center text-white">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-box bg-white bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="ph-duotone ph-rocket-launch fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">Startup India Benefits</h3>
                                <p class="mb-0 opacity-75 small">Unlock DPIIT recognition, tax holidays, patent rebates, and easy compliance avenues</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INTERACTIVE TAB NAVIGATION --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill gap-2" id="startupTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold" id="benefits-tab" data-bs-toggle="pill" data-bs-target="#benefits" type="button" role="tab" aria-selected="true">
                                <i class="ph-duotone ph-gift me-2 fs-5"></i> DPIIT Recognition Benefits
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="roadmap-tab" data-bs-toggle="pill" data-bs-target="#roadmap" type="button" role="tab" aria-selected="false">
                                <i class="ph-duotone ph-git-fork me-2 fs-5"></i> Registration Roadmap
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="checklist-tab" data-bs-toggle="pill" data-bs-target="#checklist" type="button" role="tab" aria-selected="false">
                                <i class="ph-duotone ph-check-square me-2 fs-5"></i> "Am I a Startup?" Check
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB PANELS --}}
    <div class="tab-content" id="startupTabsContent">
        {{-- Tab 1: Benefits --}}
        <div class="tab-pane fade show active" id="benefits" role="tabpanel" aria-labelledby="benefits-tab">
            <div class="row">
                {{-- Benefit 1 --}}
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 border-top border-4 border-info">
                        <div class="card-body p-4">
                            <div class="bg-light-info p-3 rounded-3 d-inline-block mb-3 text-info">
                                <i class="ph-duotone ph-receipt-tax fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">3-Year Income Tax Holiday</h5>
                            <p class="text-secondary small mb-0">Eligible recognized startups can apply for tax exemption under Section 80-IAC of the Income Tax Act for 3 consecutive years out of their first 10 years.</p>
                        </div>
                    </div>
                </div>

                {{-- Benefit 2 --}}
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 border-top border-4 border-primary">
                        <div class="card-body p-4">
                            <div class="bg-light-primary p-3 rounded-3 d-inline-block mb-3 text-primary">
                                <i class="ph-duotone ph-gear fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Self-Compliance Portal</h5>
                            <p class="text-secondary small mb-0">Startups are allowed to self-certify compliance under 6 Labor laws and 3 Environmental laws via a single online portal, with no inspections for 3-5 years.</p>
                        </div>
                    </div>
                </div>

                {{-- Benefit 3 --}}
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 border-top border-4 border-success">
                        <div class="card-body p-4">
                            <div class="bg-light-success p-3 rounded-3 d-inline-block mb-3 text-success">
                                <i class="ph-duotone ph-lightbulb fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">IP & Patent Fast-Track</h5>
                            <p class="text-secondary small mb-0">Avail up to 80% rebate in patent application filings and 50% rebate on trademark filings, along with fast-track examination services.</p>
                        </div>
                    </div>
                </div>

                {{-- Benefit 4 --}}
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 border-top border-4 border-warning">
                        <div class="card-body p-4">
                            <div class="bg-light-warning p-3 rounded-3 d-inline-block mb-3 text-warning">
                                <i class="ph-duotone ph-scales fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Public Procurement Priority</h5>
                            <p class="text-secondary small mb-0">Exemption from Earnest Money Deposit (EMD), security deposit requirements, and prior experience/turnover criteria when bidding for public tenders.</p>
                        </div>
                    </div>
                </div>

                {{-- Benefit 5 --}}
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 border-top border-4 border-danger">
                        <div class="card-body p-4">
                            <div class="bg-light-danger p-3 rounded-3 d-inline-block mb-3 text-danger">
                                <i class="ph-duotone ph-door-open fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Easy 90-Day Winding Up</h5>
                            <p class="text-secondary small mb-0">Under the Insolvency and Bankruptcy Code (IBC) 2016, startups with simple debt structures can wind up business operations within 90 days.</p>
                        </div>
                    </div>
                </div>

                {{-- Benefit 6 --}}
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 rounded-3 border-top border-4 border-dark">
                        <div class="card-body p-4">
                            <div class="bg-light-dark p-3 rounded-3 d-inline-block mb-3 text-dark">
                                <i class="ph-duotone ph-piggy-bank fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">SIDBI Fund of Funds</h5>
                            <p class="text-secondary small mb-0">Access to venture capital funding programs backed by the Indian Government's ₹10,000 Crore Fund of Funds managed by SIDBI.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 2: Roadmap --}}
        <div class="tab-pane fade" id="roadmap" role="tabpanel" aria-labelledby="roadmap-tab">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-5">
                            <h4 class="fw-bold text-dark mb-4 text-center">Step-by-Step Registration Process</h4>
                            
                            <div class="timeline">
                                <div class="timeline-item d-flex mb-4">
                                    <div class="timeline-step me-4 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">1</div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">Incorporate Business</h5>
                                        <p class="text-muted small">Register as a Private Limited Company, Limited Liability Partnership (LLP), or Partnership Firm. Proprietorships are not eligible.</p>
                                    </div>
                                </div>

                                <div class="timeline-item d-flex mb-4">
                                    <div class="timeline-step me-4 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">2</div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">Assemble Supporting Documentation</h5>
                                        <p class="text-muted small">Prepare a detailed pitch deck or brief profile summarizing your innovation, patent details (if any), write-up of target market problem, and scaling potential.</p>
                                    </div>
                                </div>

                                <div class="timeline-item d-flex mb-4">
                                    <div class="timeline-step me-4 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">3</div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">Submit Application on Startup India Portal</h5>
                                        <p class="text-muted small">Fill out the online DPIIT recognition form, upload certificates of incorporation, write-up of innovation, and sign declarations.</p>
                                    </div>
                                </div>

                                <div class="timeline-item d-flex">
                                    <div class="timeline-step me-4 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;"><i class="ph ph-check"></i></div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">Obtain Certificate of Recognition</h5>
                                        <p class="text-muted small">Once verified by the ministry inspectors, a digital certificate of recognition containing a unique startup ID will be issued.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-5 pt-3 border-top">
                                <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                    Get CA Help to Register Startup
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 3: Checklist --}}
        <div class="tab-pane fade" id="checklist" role="tabpanel" aria-labelledby="checklist-tab">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0 text-center">
                            <h5 class="fw-bold mb-1 text-dark">DPIIT Startup Eligibility Self-Test</h5>
                            <p class="text-muted small mb-0">Evaluate your startup parameters to check for official DPIIT recognition eligibility.</p>
                        </div>
                        
                        <div class="card-body p-4">
                            {{-- LIVE ELIGIBILITY PROGRESS METER --}}
                            <div class="mb-4 p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark small"><i class="ph-duotone ph-chart-line me-1 text-primary"></i> Eligibility Score:</span>
                                    <span class="badge bg-primary fw-bold" id="progressVal">0% Eligible</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 5px; background-color: #dee2e6;">
                                    <div class="progress-bar bg-gradient-primary transition-all" id="checklistProgressBar" role="progressbar" style="width: 0%; border-radius: 5px;"></div>
                                </div>
                            </div>

                            {{-- INTERACTIVE CARDS CHECKLIST --}}
                            <div class="d-flex flex-column gap-3 mb-4">
                                {{-- Item 1: Org Type --}}
                                <div class="checklist-panel p-3 rounded-3 d-flex align-items-center justify-content-between border" id="panelOrgType" style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="panel-icon bg-light text-muted p-2.5 rounded-3">
                                            <i class="ph-duotone ph-buildings fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Incorporated Entity Type</h6>
                                            <span class="text-muted small d-block">Private Limited Company, LLP, or Registered Partnership (Proprietorships are ineligible).</span>
                                        </div>
                                    </div>
                                    <div class="check-circle rounded-circle border d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; min-width: 26px; border-color: #dee2e6; color: transparent; background: #fff;">
                                        <i class="ph ph-check fw-bold small"></i>
                                    </div>
                                    <input type="checkbox" class="d-none" id="checkOrgType">
                                </div>

                                {{-- Item 2: Age --}}
                                <div class="checklist-panel p-3 rounded-3 d-flex align-items-center justify-content-between border" id="panelAge" style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="panel-icon bg-light text-muted p-2.5 rounded-3">
                                            <i class="ph-duotone ph-calendar fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Entity Age Limit</h6>
                                            <span class="text-muted small d-block">Incorporated or registered within the last 10 years.</span>
                                        </div>
                                    </div>
                                    <div class="check-circle rounded-circle border d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; min-width: 26px; border-color: #dee2e6; color: transparent; background: #fff;">
                                        <i class="ph ph-check fw-bold small"></i>
                                    </div>
                                    <input type="checkbox" class="d-none" id="checkAge">
                                </div>

                                {{-- Item 3: Turnover --}}
                                <div class="checklist-panel p-3 rounded-3 d-flex align-items-center justify-content-between border" id="panelTurnover" style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="panel-icon bg-light text-muted p-2.5 rounded-3">
                                            <i class="ph-duotone ph-currency-circle-dollar fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Turnover Cap</h6>
                                            <span class="text-muted small d-block">Annual turnover did not exceed ₹100 Crore in any financial year since incorporation.</span>
                                        </div>
                                    </div>
                                    <div class="check-circle rounded-circle border d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; min-width: 26px; border-color: #dee2e6; color: transparent; background: #fff;">
                                        <i class="ph ph-check fw-bold small"></i>
                                    </div>
                                    <input type="checkbox" class="d-none" id="checkTurnover">
                                </div>

                                {{-- Item 4: Innovation --}}
                                <div class="checklist-panel p-3 rounded-3 d-flex align-items-center justify-content-between border" id="panelInnovation" style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="panel-icon bg-light text-muted p-2.5 rounded-3">
                                            <i class="ph-duotone ph-lightbulb fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Innovation & Scalability Focus</h6>
                                            <span class="text-muted small d-block">Working towards innovation, development or improvement of products, processes, or services.</span>
                                        </div>
                                    </div>
                                    <div class="check-circle rounded-circle border d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; min-width: 26px; border-color: #dee2e6; color: transparent; background: #fff;">
                                        <i class="ph ph-check fw-bold small"></i>
                                    </div>
                                    <input type="checkbox" class="d-none" id="checkInnovation">
                                </div>
                            </div>

                            {{-- DYNAMIC ALERTS / CALL TO ACTIONS --}}
                            <div id="checklistResult" class="alert alert-info border-0 text-center mb-0 p-3 rounded-3">
                                <i class="ph-duotone ph-info fs-4 me-2 align-middle"></i>
                                Toggle parameters above to check status.
                            </div>
                        </div>
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
.timeline-item {
    position: relative;
}
.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: -24px;
    width: 2px;
    background-color: #e9ecef;
}
.transition-all {
    transition: all 0.3s ease;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Checklist custom panels */
.checklist-panel {
    background-color: #fafbfc;
    border-color: #e9ecef !important;
    transition: all 0.2s ease;
}
.checklist-panel:hover {
    border-color: #ced4da !important;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    transform: translateY(-1px);
}
.checklist-panel.panel-active {
    background-color: #f0fdf4;
    border-color: #86efac !important;
}
.checklist-panel.panel-active .panel-icon {
    background-color: #dcfce7 !important;
    color: #16a34a !important;
}
.checklist-panel.panel-active .check-circle {
    background-color: #16a34a !important;
    border-color: #16a34a !important;
    color: #ffffff !important;
}
</style>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Handling click on panel to trigger check
    $('.checklist-panel').on('click', function() {
        let checkbox = $(this).find('input[type="checkbox"]');
        let isChecked = checkbox.is(':checked');
        
        checkbox.prop('checked', !isChecked);
        $(this).toggleClass('panel-active', !isChecked);
        
        updateChecklistMeter();
    });

    function updateChecklistMeter() {
        let org = $('#checkOrgType').is(':checked');
        let age = $('#checkAge').is(':checked');
        let turn = $('#checkTurnover').is(':checked');
        let innov = $('#checkInnovation').is(':checked');

        let checkCount = (org ? 1 : 0) + (age ? 1 : 0) + (turn ? 1 : 0) + (innov ? 1 : 0);
        let progressPercent = checkCount * 25;

        // Update progress bar
        $('#checklistProgressBar').css('width', progressPercent + '%');
        $('#progressVal').text(progressPercent + '% Eligible');

        // Update results display
        if (checkCount === 4) {
            $('#checklistResult')
                .removeClass('alert-info alert-warning alert-success')
                .addClass('alert-success')
                .html(`
                    <div class="d-flex flex-column align-items-center gap-2">
                        <span class="fs-5 fw-bold"><i class="ph-duotone ph-check-circle fs-4 me-1 align-middle"></i> 100% Eligible for DPIIT Startup India Recognition!</span>
                        <p class="small text-secondary mb-2">Your enterprise matches all conditions set by the Department for Promotion of Industry and Internal Trade.</p>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-success btn-sm px-4 fw-bold rounded-pill text-white shadow-sm mt-1">Get Professional Registration Help</a>
                    </div>
                `);
        } else if (checkCount > 0) {
            $('#checklistResult')
                .removeClass('alert-info alert-success alert-warning')
                .addClass('alert-warning')
                .html('<i class="ph-duotone ph-warning fs-5 me-2 align-middle"></i>Almost there! You satisfy **' + checkCount + ' out of 4** criteria. Select all boxes to check final recognition eligibility.');
        } else {
            $('#checklistResult')
                .removeClass('alert-success alert-warning alert-info')
                .addClass('alert-info')
                .html('<i class="ph-duotone ph-info fs-5 me-2 align-middle"></i>Select the parameters above corresponding to your startup structure to evaluate eligibility.');
        }
    }
});
</script>
@endsection
