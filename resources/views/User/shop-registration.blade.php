@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-4">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 mb-2">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Labour Compliance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shops & Establishment Registration</li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <div class="page-header-title">
                        <h2 class="mb-0 text-dark fw-bold">Shops & Establishment</h2>
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-outline-primary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#aboutShopModal">
                        <i class="ti ti-info-circle f-16"></i> About Shop & Establishment
                    </button>
                    <a href="https://services.wblabour.gov.in" target="_blank" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
                        <i class="ti ti-credit-card f-16"></i> Apply Registration
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Premium Static Alert -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card premium-warning-alert border-0 shadow-sm rounded-4 overflow-hidden mb-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start flex-column flex-lg-row gap-3">
                        <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="avtar avtar-s btn-light-warning rounded-circle"><i class="ti ti-help-circle f-18"></i></span>
                                <h5 class="mb-0 fw-bold text-dark">Steps for Shops & Establishment Registration:</h5>
                            </div>
                            <ol class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">1</span>
                                    <span>Visit the official State Labour Portal or click the Apply Registration button above.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">2</span>
                                    <span>Create an employer account or sign in with your credentials.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">3</span>
                                    <span>Fill out the online application form with branch address, employer details, and category of shop.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">4</span>
                                    <span>Upload required documents including Trade License, PAN, rent agreements, and employee details.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">5</span>
                                    <span>Pay the registration fees based on the number of workers employed.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark font-14">
                                    <span class="step-num-badge-new">6</span>
                                    <span>Once approved by the labor inspector, download and print the Registration Certificate.</span>
                                </li>
                            </ol>
                        </div>
                        <div class="flex-shrink-0 align-self-lg-center d-none d-lg-block">
                            <img src="../assets/images/application/img-accout-alert.png" alt="img" class="img-fluid wid-80 opacity-85">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table tbl-product m-0 custom-list-table align-middle" id="pc-dt-simple">
                            <thead>
                                <tr class="bg-light-header">
                                    <th class="text-end py-3 ps-4" style="width: 60px;">#</th>
                                    <th class="py-3">Registration No</th>
                                    <th class="py-3">Establishment Name</th>
                                    <th class="py-3">Category</th>
                                    <th class="py-3">Owner / Partner</th>
                                    <th class="py-3">No. of Employees</th>
                                    <th class="py-3">Date of Issue</th>
                                    <th class="py-3">Expiry Date</th>
                                    <th class="py-3">Status</th>
                                    <th class="text-center py-3 pe-4" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">1</td>
                                    <td class="fw-bold text-dark">SHP/WB/2026/00482</td>
                                    <td>Ecashbook HQ Office</td>
                                    <td>Commercial Establishment</td>
                                    <td>Rittik Sadhukhan</td>
                                    <td class="fw-semibold">12 Employees</td>
                                    <td class="text-muted">12-04-2024</td>
                                    <td class="text-muted">11-04-2029</td>
                                    <td>
                                        <span class="badge-pill-custom badge-pill-resolved">Active</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-shop-details-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewDetailsModal"
                                                data-reg-no="SHP/WB/2026/00482"
                                                data-est-name="Ecashbook HQ Office"
                                                data-category="Commercial Establishment"
                                                data-owner="Rittik Sadhukhan"
                                                data-employees="12"
                                                data-issue-date="12-04-2024"
                                                data-expiry-date="11-04-2029"
                                                data-status="Active"
                                                data-address="Salt Lake Sector V, Kolkata, WB"
                                                data-remarks="Compliance Complete">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">2</td>
                                    <td class="fw-bold text-dark">SHP/WB/2026/00914</td>
                                    <td>Salt Lake Branch Outlet</td>
                                    <td>Retail Shop</td>
                                    <td>Priya Sharma</td>
                                    <td class="fw-semibold">6 Employees</td>
                                    <td class="text-muted">15-08-2024</td>
                                    <td class="text-muted">14-08-2029</td>
                                    <td>
                                        <span class="badge-pill-custom badge-pill-resolved">Active</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-shop-details-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewDetailsModal"
                                                data-reg-no="SHP/WB/2026/00914"
                                                data-est-name="Salt Lake Branch Outlet"
                                                data-category="Retail Shop"
                                                data-owner="Priya Sharma"
                                                data-employees="6"
                                                data-issue-date="15-08-2024"
                                                data-expiry-date="14-08-2029"
                                                data-status="Active"
                                                data-address="Sector III, Salt Lake, Kolkata, WB"
                                                data-remarks="Renewal Done">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark font-20">Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="table-responsive rounded-3 overflow-hidden border">
                    <table class="table table-striped table-hover m-0 align-middle font-14">
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Registration No</th>
                            <td id="detailRegNo" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Establishment Name</th>
                            <td id="detailEstName" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Category</th>
                            <td id="detailCategory" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Owner / Partner</th>
                            <td id="detailOwner" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employees</th>
                            <td id="detailEmployees" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Address</th>
                            <td id="detailAddress" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Issue Date</th>
                            <td id="detailIssueDate" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Expiry Date</th>
                            <td id="detailExpiryDate" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Remarks</th>
                            <td id="detailRemarks" class="ps-3 text-muted"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- About Shops Registration Modal -->
<div class="modal fade" id="aboutShopModal" tabindex="-1" aria-labelledby="aboutShopModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="avtar avtar-s btn-light-primary rounded-circle"><i class="ti ti-info-circle f-18"></i></span>
                    <h5 class="modal-title fw-bold text-dark font-20" id="aboutShopModalLabel">About Shops & Establishment Registration</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-dark font-14" style="max-height: 70vh; overflow-y: auto;">

                {{-- SECTION 1: WHY REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-help f-18"></i> Why Required</h6>
                    <p class="text-secondary mb-2">Required under State Shops & Establishment Act.</p>
                    <p class="text-secondary mb-1">Applicable for:</p>
                    <div class="row g-2 ps-2">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-point text-primary f-14"></i> Offices</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-point text-primary f-14"></i> Shops</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-point text-primary f-14"></i> Commercial establishments</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-point text-primary f-14"></i> IT companies</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-point text-primary f-14"></i> Consultancy firms</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-point text-primary f-14"></i> Retail businesses</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 2: PURPOSE --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-activity f-18"></i> Purpose</h6>
                    <ul class="list-unstyled ps-2 mb-0">
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Legal identity of business establishment</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Labour regulation compliance</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Employee working conditions</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Working hours & leave compliance</li>
                    </ul>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 3: COMMON USES --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-bookmark f-18"></i> Common Uses</h6>
                    <p class="text-secondary mb-1">Required for:</p>
                    <ul class="list-unstyled ps-2 mb-0">
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-primary f-16"></i> Bank account opening</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-primary f-16"></i> PF/ESI registration</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-primary f-16"></i> Trade license support</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-primary f-16"></i> Government tenders</li>
                    </ul>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 4: DOCUMENTS REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-file-text f-18"></i> Documents Required</h6>
                    <div class="row g-2 ps-2">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-id f-16 text-muted"></i> PAN Card</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-fingerprint f-16 text-muted"></i> Aadhaar of Owner/Director</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-home f-16 text-muted"></i> Rent Agreement / Property Tax Receipt</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-bulb f-16 text-muted"></i> Electricity Bill</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-award f-16 text-muted"></i> Incorporation Certificate</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-users f-16 text-muted"></i> Employee Count Details</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-photo f-16 text-muted"></i> Photograph of Premises</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-x-filled f-16 text-muted"></i> NOC from Owner (if rented)</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-device-mobile f-16 text-muted"></i> Mobile & Email ID</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 5: TYPICAL INFORMATION REQUIRED --}}
                <div>
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-list f-18"></i> Typical Information Required</h6>
                    <ul class="list-unstyled ps-2 mb-0">
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-secondary f-16"></i> Working hours</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-secondary f-16"></i> Weekly off</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-secondary f-16"></i> Nature of business</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-secondary f-16"></i> Employee strength</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-secondary f-16"></i> Male/Female employee count</li>
                    </ul>
                </div>

            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Premium Alert Styles */
.premium-warning-alert {
    background: #fffdf5;
    border-left: 4px solid #f59e0b !important;
    border: 1px solid #ffeeba;
}
.step-num-badge-new {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}

/* Custom Table Styles */
.bg-light-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.custom-list-table th {
    font-weight: 600;
    color: #475569;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.custom-list-table td {
    padding: 14px 12px !important;
}
.custom-list-table tbody tr {
    transition: background-color 0.2s ease;
}
.custom-list-table tbody tr:hover {
    background-color: #f8fafc;
}
.btn-action-detail {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    background: rgba(79, 70, 229, 0.08);
    border: none;
    transition: all 0.2s;
}
.btn-action-detail:hover {
    color: #ffffff;
    background: #4f46e5;
}

/* Custom Badge Styling */
.badge-pill-custom {
    padding: 4px 12px;
    border-radius: 50rem;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-pill-resolved {
    background: rgba(16, 185, 129, 0.08);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.15);
}
.font-12 {
    font-size: 12px;
}
.font-14 {
    font-size: 14px;
}
.w-30 {
    width: 30%;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof simpleDatatables !== 'undefined') {
            const table = document.getElementById('pc-dt-simple');
            if (table && !table.dataset.initialized) {
                table.dataset.initialized = "true";
                new simpleDatatables.DataTable(table, {
                    sortable: true,
                    perPage: 10,
                    searchable: true,
                    fixedHeight: false
                });
            }
        }
    });

    $(document).on('click', '.view-shop-details-btn', function () {
        $('#detailRegNo').text($(this).data('reg-no'));
        $('#detailEstName').text($(this).data('est-name'));
        $('#detailCategory').text($(this).data('category'));
        $('#detailOwner').text($(this).data('owner'));
        $('#detailEmployees').text($(this).data('employees') + ' Employees');
        $('#detailAddress').text($(this).data('address'));
        $('#detailIssueDate').text($(this).data('issue-date'));
        $('#detailExpiryDate').text($(this).data('expiry-date'));
        $('#detailRemarks').text($(this).data('remarks'));
    });
</script>
@endsection
