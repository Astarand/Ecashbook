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
                                <i class="ph-duotone ph-compass fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">Discover MSME Schemes</h3>
                                <p class="mb-0 opacity-75 small">Explore government schemes, incentives, and assistance programs tailored for your business growth</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="ph-duotone ph-magnifying-glass text-muted"></i></span>
                                <input type="text" id="schemeSearch" class="form-control bg-light border-0" placeholder="Search schemes by name or keywords..." style="border-radius: 0 6px 6px 0;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="categoryFilters">
                                <button class="btn btn-sm btn-primary filter-btn px-3" data-category="all">All Schemes</button>
                                <button class="btn btn-sm btn-light-secondary filter-btn px-3" data-category="credit">Credit & Finance</button>
                                <button class="btn btn-sm btn-light-secondary filter-btn px-3" data-category="subsidy">Subsidies & Grants</button>
                                <button class="btn btn-sm btn-light-secondary filter-btn px-3" data-category="tech">Technology & Quality</button>
                                <button class="btn btn-sm btn-light-secondary filter-btn px-3" data-category="startup">Startups & Innovation</button>
                                <button class="btn btn-sm btn-light-secondary filter-btn px-3" data-category="trade">Trade & Exports</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCHEMES GRID --}}
    <div class="row" id="schemesGrid">
        {{-- Scheme 1: CGTMSE --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="credit">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-semibold small">Credit & Finance</span>
                        <div class="scheme-icon bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                            <i class="ph-duotone ph-shield-check fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">CGTMSE</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">Credit Guarantee Fund Trust for Micro and Small Enterprises</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-primary me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Eligible Entities:</strong> Both new and existing Micro and Small Enterprises (including manufacturing, service, and retail trade sectors).</li>
                                    <li class="mb-1"><strong>Lending Institutions:</strong> Scheduled commercial banks, RRBs, and approved Member Lending Institutions (MLIs).</li>
                                    <li class="mb-0"><strong>Priority Categories:</strong> First-generation entrepreneurs, women entrepreneurs, and businesses in aspirational districts may receive higher guarantee coverage.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>No Collateral Required:</strong> No need to pledge assets or property.</li>
                                    <li class="mb-1"><strong>High Loan Limit:</strong> Loans up to ₹10 crore.</li>
                                    <li class="mb-1"><strong>Guarantee Coverage:</strong> 75%–90% of the loan amount is covered in case of default.</li>
                                    <li class="mb-1"><strong>Low Fees:</strong> Subsidized Annual Guarantee Fee (AGF) starting from 0.37%.</li>
                                    <li class="mb-0"><strong>Special Concessions:</strong> Up to 90% cover for Women; 85% cover for SC/ST, Micro-Enterprises (loans to ₹5L), and ZED Units.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://www.cgtmse.in/#" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> CGTMSE Portal</a>
                            <a href="https://ramp.msme.gov.in/ramp/cgtmse.php" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> RAMP CGTMSE Page</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 2: PMEGP --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="subsidy">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-success text-success px-3 py-2 rounded-pill fw-semibold small">Subsidies & Grants</span>
                        <div class="scheme-icon bg-success bg-opacity-10 text-success p-2 rounded-circle">
                            <i class="ph-duotone ph-currency-inr fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">PMEGP</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">The Prime Minister’s Employment Generation Programme</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-success me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Individual entrepreneurs, SHGs, Charitable Trusts, Registered Societies, and Production Co-operative Societies.</li>
                                    <li class="mb-1">Only new projects and viable micro-enterprises are eligible.</li>
                                    <li class="mb-0">Applicants must be 18+ and have at least an 8th-standard education.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Subsidy:</strong> 15%–35% of project cost based on category and location.</li>
                                    <li class="mb-1"><strong>Low Contribution:</strong> Beneficiary contributes only 5%–10%; banks finance the rest.</li>
                                    <li class="mb-1"><strong>Collateral-Free Loans:</strong> Up to ₹10 lakh under CGTMSE.</li>
                                    <li class="mb-1"><strong>Project Limits:</strong> Up to ₹50 lakh (manufacturing) and ₹20 lakh (service/business).</li>
                                    <li class="mb-0"><strong>Expansion Support:</strong> Successful units can get a second loan up to ₹1 crore for growth. Covers working capital & setup costs.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://www.jansamarth.in/prime-minister-employment-generation-program-scheme" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> JanSamarth Portal</a>
                            <a href="https://www.myscheme.gov.in/schemes/pmegp" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> myScheme PMEGP</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 3: CLCSS --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="subsidy">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-success text-success px-3 py-2 rounded-pill fw-semibold small">Subsidies & Grants</span>
                        <div class="scheme-icon bg-success bg-opacity-10 text-success p-2 rounded-circle">
                            <i class="ph-duotone ph-cpu fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">CLCSS</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">Credit Linked Capital Subsidy Scheme</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-success me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Must be a Micro or Small Enterprise (MSE) under the MSMED Act.</li>
                                    <li class="mb-1">Eligible business types include sole proprietorships, partnerships, co-operatives, and private/public limited companies.</li>
                                    <li class="mb-0">A valid <strong>Udyam Registration</strong> is mandatory.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Capital Subsidy:</strong> 15% subsidy on eligible machinery purchases, up to ₹15 lakh.</li>
                                    <li class="mb-1"><strong>Higher SC/ST Benefit:</strong> Up to 25% subsidy, capped at ₹25 lakh under SCLCSS.</li>
                                    <li class="mb-1"><strong>Lower Loan Burden:</strong> Subsidy reduces the outstanding loan amount and interest costs.</li>
                                    <li class="mb-0"><strong>Improved Productivity & Compliance:</strong> Modern machinery enhances efficiency, quality, and environmental/energy compliance.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://sclcss.msme.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> SCLCSS Portal</a>
                            <a href="https://my.msme.gov.in/mymsme/reg/COM_ClcssAppForm.aspx" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Apply Online</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 4: ZED Certification --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="tech">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-warning text-warning px-3 py-2 rounded-pill fw-semibold small">Technology & Quality</span>
                        <div class="scheme-icon bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                            <i class="ph-duotone ph-certificate fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">ZED Certification</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">Zero Defect Zero Effect Certification Scheme</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-warning me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Must have a valid Udyam Registration Number.</li>
                                    <li class="mb-1">Open to Micro, Small, and Medium Enterprises (MSMEs) in manufacturing and eligible service sectors.</li>
                                    <li class="mb-0">Multiple units under a single Udyam Registration can apply.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Certification Subsidy:</strong> Up to 80% subsidy on certification costs; additional benefits for women, SC/ST, and special regions.</li>
                                    <li class="mb-1"><strong>Joining Reward:</strong> First-time applicants receive ₹10,000.</li>
                                    <li class="mb-1"><strong>Technology Support:</strong> Up to ₹5 lakh for handholding and technology upgrades.</li>
                                    <li class="mb-0"><strong>Banking & Market Benefits:</strong> Interest concessions, fee waivers, enhanced brand credibility, export potential, and procurement options.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://zed.msme.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> ZED Portal</a>
                            <a href="https://www.myscheme.gov.in/schemes/mszcs" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> myScheme ZED</a>
                            <a href="https://dcmsme.gov.in/CLCS_TUS_Scheme/ZED_Scheme/Scheme_Guidelines.aspx" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Guidelines</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 5: Lean Manufacturing Scheme --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="tech">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-warning text-warning px-3 py-2 rounded-pill fw-semibold small">Technology & Quality</span>
                        <div class="scheme-icon bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                            <i class="ph-duotone ph-chart-bar fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">Lean Manufacturing Scheme</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">MSME Lean Competitive Scheme</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-warning me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Must have a valid Udyam Registration and primarily engaged in manufacturing activities.</li>
                                    <li class="mb-1">Must be an active operational unit and willing to adopt lean tools (5S, Kaizen, Kanban, etc.).</li>
                                    <li class="mb-0">Participate in assessments & contribution of consultancy costs. Also open to CFCs & Groups of Enterprises (GoE).</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Lower Costs:</strong> Reduces waste and operational expenses.</li>
                                    <li class="mb-1"><strong>Higher Productivity:</strong> Improves efficiency and output.</li>
                                    <li class="mb-1"><strong>Better Quality & Delivery:</strong> Minimizes defects, rework, and shortens production lead times.</li>
                                    <li class="mb-0"><strong>Engagement & Competitiveness:</strong> Encourages teamwork and enhances export readiness.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://lean.msme.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Lean Portal</a>
                            <a href="https://dcmsme.gov.in/CLCS_TUS_Scheme/Lean_Manufacturing/Scheme_Guidelines.aspx" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Lean Guidelines</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 6: Digital MSME --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="tech">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-warning text-warning px-3 py-2 rounded-pill fw-semibold small">Technology & Quality</span>
                        <div class="scheme-icon bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                            <i class="ph-duotone ph-laptop fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">Digital MSME</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">ICT promotion and cloud tools in the MSME Sector</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-warning me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Must have a valid Udyam Registration.</li>
                                    <li class="mb-1">PAN and GSTIN are mandatory.</li>
                                    <li class="mb-0">Must not have availed similar government digitalization benefits earlier. For loan-linked support, good credit & valid ITR is required.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Digitalization Support:</strong> Subsidies for cloud tools (ERP, CRM, and accounting software).</li>
                                    <li class="mb-1"><strong>Lower Costs & Access:</strong> Automates processes, reduces errors, and enables access to e-commerce platforms.</li>
                                    <li class="mb-1"><strong>Credit & Insights:</strong> Improves transparency, cashflow monitoring, and loan eligibility.</li>
                                    <li class="mb-0"><strong>Training Support:</strong> Guidance on digital tools, cybersecurity, and online marketing.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://dcmsme.gov.in/CLCS_TUS_Scheme/Digital_MSME_Scheme/Scheme_Guidelines.aspx" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> ICT Guidelines</a>
                            <a href="https://lean.msme.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Lean & Digital Portal</a>
                            <a href="https://www.msme.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Ministry of MSME</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 7: Startup India --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="startup">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-danger text-danger px-3 py-2 rounded-pill fw-semibold small">Startups & Innovation</span>
                        <div class="scheme-icon bg-danger bg-opacity-10 text-danger p-2 rounded-circle">
                            <i class="ph-duotone ph-rocket fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">Startup India</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">DPIIT Startup Recognition & Support Portal</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-danger me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Must be a Private Limited Company, LLP, or Registered Partnership Firm.</li>
                                    <li class="mb-1">Should be within 10 years of incorporation (up to 20 years for DeepTech startups).</li>
                                    <li class="mb-1">Annual turnover must not exceed ₹200 crore. Innovative, scalable, and new entity.</li>
                                    <li class="mb-0">Indian promoters must hold at least 51% ownership.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Tax Benefits:</strong> Income tax holiday (Section 80-IAC) and Angel Tax exemption.</li>
                                    <li class="mb-1"><strong>IPR Benefits:</strong> 80% rebate on patent fees and fast-track IP processing.</li>
                                    <li class="mb-1"><strong>Compliance & Exit:</strong> Self-certification, reduced regulatory burden, and fast 90-day winding exit.</li>
                                    <li class="mb-0"><strong>Opportunities:</strong> Access to Fund of Funds, credit guarantee, and easier govt tender access (no EMD/prior experience).</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://www.startupindia.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Startup India Portal</a>
                            <a href="https://www.dpiit.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> DPIIT Website</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 8: Mudra Loan --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="credit">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-semibold small">Credit & Finance</span>
                        <div class="scheme-icon bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                            <i class="ph-duotone ph-hand-coins fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">Mudra Loan</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">Pradhan Mantri MUDRA Yojana (PMMY)</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-primary me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Individuals, proprietary concerns, partnership firms, and private limited companies are eligible.</li>
                                    <li class="mb-1">Business must be in manufacturing, trading, or service sectors (agriculture generally not eligible).</li>
                                    <li class="mb-0">Applicant should have a good credit history, must not be a bank defaulter, and age between 18 and 65 years.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>No Collateral Required:</strong> No collateral or third-party guarantee required.</li>
                                    <li class="mb-1"><strong>Concessional Costs:</strong> Affordable interest rates with minimal/zero processing fees.</li>
                                    <li class="mb-1"><strong>Flexible Repayment:</strong> Tenure up to 7 years. Concessions for women.</li>
                                    <li class="mb-0"><strong>Loan Categories:</strong>
                                        <ul class="ps-3 mb-0">
                                            <li><strong>Shishu:</strong> Up to ₹50,000 (startups & micro).</li>
                                            <li><strong>Kishore:</strong> ₹50,001 – ₹5 lakh (growing business).</li>
                                            <li><strong>Tarun:</strong> ₹5 lakh – ₹10 lakh (expansion).</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://www.jansamarth.in/business-loan-pradhan-mantri-mudra-yojana-scheme" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> JanSamarth Mudra</a>
                            <a href="https://www.mudra.org.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> Mudra Portal</a>
                            <a href="https://www.myscheme.gov.in/schemes/pmmy" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> myScheme PMMY</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 9: Stand-Up India --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="credit">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-semibold small">Credit & Finance</span>
                        <div class="scheme-icon bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                            <i class="ph-duotone ph-crown fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">Stand-Up India</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">Promoting Greenfield Enterprise financing for women and SC/ST communities</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-primary me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Applicant must be a woman or belong to the SC/ST category.</li>
                                    <li class="mb-1">Must be 18 years or older. Funding is available only for new (greenfield) ventures.</li>
                                    <li class="mb-1">Minimum 51% ownership and control held by SC/ST or woman.</li>
                                    <li class="mb-0">Good credit history and must not be a bank defaulter.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Loan Amount:</strong> ₹10 lakh to ₹1 crore for new businesses.</li>
                                    <li class="mb-1"><strong>Low Margin:</strong> Concessional margin requirement of up to 15% with subsidy support.</li>
                                    <li class="mb-1">Repayment up to 7 years with a moratorium of up to 18 months.</li>
                                    <li class="mb-0">Credit guarantee support reduces collateral burden. Covers manufacturing, service, and trading.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://www.standupmitra.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> StandupMitra</a>
                            <a href="https://www.myscheme.gov.in/schemes/sui" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> myScheme SUI</a>
                            <a href="https://web.umang.gov.in/landing/scheme/detail/standup-india_sui.html" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> UMANG Portal Link</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheme 10: Export Incentives --}}
        <div class="col-12 mb-4 scheme-card-wrapper" data-category="trade">
            <div class="card border-0 shadow-sm h-100 scheme-card rounded-3 hover-lift transition-all">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-light-info text-info px-3 py-2 rounded-pill fw-semibold small">Trade & Exports</span>
                        <div class="scheme-icon bg-info bg-opacity-10 text-info p-2 rounded-circle">
                            <i class="ph-duotone ph-globe-hemisphere-east fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 py-3">
                    <h4 class="fw-bold mb-1 scheme-title text-primary">Export Incentives</h4>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">RoDTEP, SEIS, AA, EPCG incentives</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-user-focus text-info me-2"></i> Eligibility Criteria</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1">Applicant must be a registered exporter with a valid Importer Exporter Code (IEC).</li>
                                    <li class="mb-1">Business should export eligible goods or services from India. Realization of proceeds required.</li>
                                    <li class="mb-0">Must comply with customs, GST, and foreign trade regulations. Must not be under restrictions.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 h-100">
                                <h5 class="fw-bold text-dark mb-3"><i class="ph-duotone ph-gift text-success me-2"></i> Key Benefits</h5>
                                <ul class="text-secondary ps-3 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                                    <li class="mb-1"><strong>Tax Refunds:</strong> RoDTEP and Duty Drawback refund eligible taxes/duties on exports.</li>
                                    <li class="mb-1"><strong>Duty-Free Imports:</strong> Advance Authorisation & EPCG allow duty-free or reduced-duty imports of raw materials/machinery.</li>
                                    <li class="mb-1"><strong>Lower Financing Costs:</strong> Interest Equalisation Scheme reduces credit interest rates.</li>
                                    <li class="mb-0"><strong>Risk Protection & Rewards:</strong> ECGC export insurance and SEIS incentives for service exporters based on foreign exchange earnings.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-1 fw-bold">Official Government Portals:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="https://www.dgft.gov.in/CP/?opt=meis" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> DGFT Portal</a>
                            <a href="https://www.icegate.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> ICEGATE Customs</a>
                            <a href="https://apeda.gov.in/" target="_blank" class="btn btn-xs btn-light text-secondary border py-1 px-2 rounded-pill small"><i class="ph ph-link me-1"></i> APEDA Portal</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 pt-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.msme.eligibility-checker') }}" class="btn btn-outline-primary btn-sm flex-fill">Check Eligibility</a>
                        <a href="{{ route('user.msme.consultant-assistance') }}" class="btn btn-primary btn-sm flex-fill">Apply Assistance</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NO SCHEMES FOUND MESSAGE --}}
    <div class="row d-none" id="noSchemesFound">
        <div class="col-12 text-center py-5">
            <div class="mb-3 text-muted">
                <i class="ph-duotone ph-smiley-sad fs-1"></i>
            </div>
            <h5 class="fw-bold text-dark">No schemes match your criteria</h5>
            <p class="text-muted">Try adjusting your search keywords or choosing a different category filter.</p>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
}
.header-icon-box {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Search input event
    $('#schemeSearch').on('keyup', function() {
        filterSchemes();
    });

    // Category button click event
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('btn-primary').addClass('btn-light-secondary');
        $(this).removeClass('btn-light-secondary').addClass('btn-primary');
        filterSchemes();
    });

    function filterSchemes() {
        const query = $('#schemeSearch').val().toLowerCase();
        const category = $('#categoryFilters .btn-primary').data('category');
        let visibleCount = 0;

        $('.scheme-card-wrapper').each(function() {
            const cardCategory = $(this).data('category');
            const cardTitle = $(this).find('.scheme-title').text().toLowerCase();
            const cardSub = $(this).find('.text-muted').text().toLowerCase();
            const cardDesc = $(this).text().toLowerCase(); // full text search for rich match

            const matchesSearch = cardTitle.includes(query) || cardSub.includes(query) || cardDesc.includes(query);
            const matchesCategory = (category === 'all') || (cardCategory === category);

            if (matchesSearch && matchesCategory) {
                $(this).removeClass('d-none');
                visibleCount++;
            } else {
                $(this).addClass('d-none');
            }
        });

        if (visibleCount === 0) {
            $('#noSchemesFound').removeClass('d-none');
        } else {
            $('#noSchemesFound').addClass('d-none');
        }
    }
});
</script>
@endsection
