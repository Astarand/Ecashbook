@extends('App.Layout')
@section('container')

<div class="pc-content">

    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-4">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="page-header-title">
                        <h4 class="mb-0 fw-bold">Gratuity Compliance</h4>
                    </div>
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Labour Compliance</a></li>
                        <li class="breadcrumb-item" aria-current="page">Gratuity Payment</li>
                    </ul>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-outline-primary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#aboutGratuityModal">
                        <i class="ti ti-info-circle f-16"></i> About Gratuity Act
                    </button>
                    <a href="https://samadhan.wblabour.gov.in" target="_blank" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
                        <i class="ti ti-credit-card f-16"></i> Labour commissionerate
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Gratuity Compliance Alert Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3.5 mb-0 premium-warning-alert">
                <div class="d-flex align-items-start gap-3">
                    <div class="alert-icon-box bg-warning text-white rounded-3 p-2 d-flex align-items-center justify-content-center">
                        <i class="ti ti-shield-alert f-22"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading fw-bold text-dark font-15 mb-1">Gratuity separation benefits</h6>
                        <p class="text-secondary small mb-2">Gratuity is a statutory separating benefit paid by employers to employees who have completed at least 5 years of continuous service.</p>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="bg-white rounded-3 px-3 py-2 border shadow-none d-flex align-items-center gap-2">
                                    <span class="step-num-badge-new">1</span>
                                    <span class="small font-weight-semibold text-muted">Service threshold: 5 continuous years</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white rounded-3 px-3 py-2 border shadow-none d-flex align-items-center gap-2">
                                    <span class="step-num-badge-new">2</span>
                                    <span class="small font-weight-semibold text-muted">Formula: 15 days salary per year</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white rounded-3 px-3 py-2 border shadow-none d-flex align-items-center gap-2">
                                    <span class="step-num-badge-new">3</span>
                                    <span class="small font-weight-semibold text-muted">Form F: Nomination declarations</span>
                                </div>
                            </div>
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
                                    <th class="py-3">Employee Name</th>
                                    <th class="py-3">Date of Joining</th>
                                    <th class="py-3">Years of Service</th>
                                    <th class="py-3">Last Drawn Basic</th>
                                    <th class="py-3">Accrued Gratuity</th>
                                    <th class="py-3">Nomination Status</th>
                                    <th class="py-3">Eligibility</th>
                                    <th class="text-center py-3 pe-4" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">1</td>
                                    <td class="fw-bold text-dark">Arindam Kundu</td>
                                    <td>12-04-2019</td>
                                    <td>7 Years</td>
                                    <td class="fw-semibold">₹ 60,000.00</td>
                                    <td class="fw-bold text-success">₹ 2,42,307.00</td>
                                    <td><span class="badge bg-light-success text-success">Form F Submitted</span></td>
                                    <td><span class="badge-pill-custom badge-pill-resolved">Eligible</span></td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-gratuity-details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDetailsModal"
                                                data-name="Arindam Kundu"
                                                data-doj="12-04-2019"
                                                data-years="7 Years"
                                                data-basic="₹ 60,000.00"
                                                data-accrued="₹ 2,42,307.00"
                                                data-nominee="Submitted (Form F - Spouse)"
                                                data-status="Eligible"
                                                data-remarks="Continuous service requirement met. Accrued provision generated.">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">2</td>
                                    <td class="fw-bold text-dark">Sunita Rao</td>
                                    <td>15-08-2020</td>
                                    <td>5 Years</td>
                                    <td class="fw-semibold">₹ 48,000.00</td>
                                    <td class="fw-bold text-success">₹ 1,38,461.00</td>
                                    <td><span class="badge bg-light-success text-success">Form F Submitted</span></td>
                                    <td><span class="badge-pill-custom badge-pill-resolved">Eligible</span></td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-gratuity-details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDetailsModal"
                                                data-name="Sunita Rao"
                                                data-doj="15-08-2020"
                                                data-years="5 Years"
                                                data-basic="₹ 48,000.00"
                                                data-accrued="₹ 1,38,461.00"
                                                data-nominee="Submitted (Form F - Parents)"
                                                data-status="Eligible"
                                                data-remarks="Gratuity entitlement unlocked from August 2025.">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">3</td>
                                    <td class="fw-bold text-dark">Rajesh Sen</td>
                                    <td>10-10-2023</td>
                                    <td>2 Years</td>
                                    <td class="fw-semibold">₹ 42,000.00</td>
                                    <td class="fw-bold text-muted">₹ 0.00</td>
                                    <td><span class="badge bg-light-warning text-warning">Pending Form F</span></td>
                                    <td><span class="badge-pill-custom badge-pill-pending">Not Eligible</span></td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-gratuity-details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDetailsModal"
                                                data-name="Rajesh Sen"
                                                data-doj="10-10-2023"
                                                data-years="2 Years"
                                                data-basic="₹ 42,000.00"
                                                data-accrued="₹ 0.00"
                                                data-nominee="Pending"
                                                data-status="Not Eligible (< 5 Years)"
                                                data-remarks="Will become eligible in October 2028. No provision built.">
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
                <h5 class="modal-title fw-bold text-dark font-20">Gratuity Compliance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="table-responsive rounded-3 overflow-hidden border">
                    <table class="table table-striped table-hover m-0 align-middle font-14">
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee Name</th>
                            <td id="detailName" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Date of Joining</th>
                            <td id="detailDoj" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Completed Service</th>
                            <td id="detailYears" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Last Drawn Basic</th>
                            <td id="detailBasic" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Accrued Gratuity</th>
                            <td id="detailAccrued" class="ps-3 fw-semibold text-success"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Nomination (Form F)</th>
                            <td id="detailNominee" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Eligibility Status</th>
                            <td id="detailStatus" class="ps-3 fw-bold text-dark"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Remarks / Notes</th>
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

<!-- About Gratuity Modal -->
<div class="modal fade" id="aboutGratuityModal" tabindex="-1" aria-labelledby="aboutGratuityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="avtar avtar-s btn-light-primary rounded-circle"><i class="ti ti-info-circle f-18"></i></span>
                    <h5 class="modal-title fw-bold text-dark font-20" id="aboutGratuityModalLabel">About Gratuity Payments</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-dark font-14" style="max-height: 70vh; overflow-y: auto;">
                
                {{-- SECTION 1: WHY REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-help f-18"></i> Why Required</h6>
                    <p class="text-secondary mb-2">Gratuity is a retirement/separation benefit paid by employer to employee.</p>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 2: APPLICABILITY --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-calendar-event f-18"></i> Applicability</h6>
                    <p class="text-secondary mb-2">Gratuity becomes applicable after <strong>5 years continuous service</strong> (Except death/disability cases).</p>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 3: DEPENDENCY LAYERS --}}
                <div>
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-list f-18"></i> Gratuity Depends On</h6>
                    <ul class="list-unstyled ps-2 mb-0">
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Employee service tracking</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Gratuity eligibility</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Gratuity provision</li>
                        <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-circle-check text-success f-16"></i> Exit settlement</li>
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
}
.alert-icon-box {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
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
.badge-pill-pending {
    background: rgba(239, 68, 68, 0.08);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.15);
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

    $(document).on('click', '.view-gratuity-details-btn', function () {
        $('#detailName').text($(this).data('name'));
        $('#detailDoj').text($(this).data('doj'));
        $('#detailYears').text($(this).data('years'));
        $('#detailBasic').text($(this).data('basic'));
        $('#detailAccrued').text($(this).data('accrued'));
        $('#detailNominee').text($(this).data('nominee'));
        $('#detailStatus').text($(this).data('status'));
        $('#detailRemarks').text($(this).data('remarks'));
    });
</script>
@endsection
