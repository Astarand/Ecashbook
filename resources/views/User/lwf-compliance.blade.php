@extends('App.Layout')
@section('container')

<div class="pc-content">

    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-4">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="page-header-title">
                        <h4 class="mb-0 fw-bold">Labour Welfare Fund (LWF)</h4>
                    </div>
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Labour Compliance</a></li>
                        <li class="breadcrumb-item" aria-current="page">LWF Compliance</li>
                    </ul>
                </div>
                <div class="col-md-7 text-end">
                    <button class="btn btn-outline-primary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#aboutLwfModal">
                        <i class="ti ti-info-circle f-16"></i> About LWF Compliance
                    </button>
                    <a href="https://shramikbhaban.wblabour.gov.in" target="_blank" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
                        <i class="ti ti-credit-card f-16"></i> LWF Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- LWF Compliance Alert Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3.5 mb-0 premium-warning-alert">
                <div class="d-flex align-items-start gap-3">
                    <div class="alert-icon-box bg-warning text-white rounded-3 p-2 d-flex align-items-center justify-content-center">
                        <i class="ti ti-shield-alert f-22"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading fw-bold text-dark font-15 mb-1">Labour Welfare Fund (LWF) statutory guidelines</h6>
                        <p class="text-secondary small mb-2">LWF is a statutory employee welfare contribution collected by the State Labour Welfare Board to fund worker welfare programs.</p>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="bg-white rounded-3 px-3 py-2 border shadow-none d-flex align-items-center gap-2">
                                    <span class="step-num-badge-new">1</span>
                                    <span class="small font-weight-semibold text-muted">Deduction: Employee + Employer</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white rounded-3 px-3 py-2 border shadow-none d-flex align-items-center gap-2">
                                    <span class="step-num-badge-new">2</span>
                                    <span class="small font-weight-semibold text-muted">Frequency: Semi-Annual / Varies by State</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white rounded-3 px-3 py-2 border shadow-none d-flex align-items-center gap-2">
                                    <span class="step-num-badge-new">3</span>
                                    <span class="small font-weight-semibold text-muted">Portal: Online Registration & Filing</span>
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
                                    <th class="py-3">Filing Period</th>
                                    <th class="py-3">Employee Count</th>
                                    <th class="py-3">Employee Contribution</th>
                                    <th class="py-3">Employer Contribution</th>
                                    <th class="py-3">Challan No</th>
                                    <th class="py-3">Due Date</th>
                                    <th class="py-3">Payment Status</th>
                                    <th class="text-center py-3 pe-4" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">1</td>
                                    <td class="fw-bold text-dark">June 2026</td>
                                    <td>24 Employees</td>
                                    <td class="fw-semibold text-dark">₹ 240.00</td>
                                    <td class="fw-semibold text-dark">₹ 720.00</td>
                                    <td class="text-muted">CH-LWF-982351</td>
                                    <td class="text-muted">15-07-2026</td>
                                    <td>
                                        <span class="badge-pill-custom badge-pill-resolved">Filed</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-lwf-details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDetailsModal"
                                                data-period="June 2026"
                                                data-count="24 Employees"
                                                data-emp-contribution="₹ 240.00"
                                                data-employer-contribution="₹ 720.00"
                                                data-total="₹ 960.00"
                                                data-challan="CH-LWF-982351"
                                                data-due-date="15-07-2026"
                                                data-status="Filed"
                                                data-remarks="Filing successfully submitted through State Welfare Board portal.">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">2</td>
                                    <td class="fw-bold text-dark">December 2025</td>
                                    <td>20 Employees</td>
                                    <td class="fw-semibold text-dark">₹ 200.00</td>
                                    <td class="fw-semibold text-dark">₹ 600.00</td>
                                    <td class="text-muted">CH-LWF-874211</td>
                                    <td class="text-muted">15-01-2026</td>
                                    <td>
                                        <span class="badge-pill-custom badge-pill-resolved">Filed</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-lwf-details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDetailsModal"
                                                data-period="December 2025"
                                                data-count="20 Employees"
                                                data-emp-contribution="₹ 200.00"
                                                data-employer-contribution="₹ 600.00"
                                                data-total="₹ 800.00"
                                                data-challan="CH-LWF-874211"
                                                data-due-date="15-01-2026"
                                                data-status="Filed"
                                                data-remarks="LWF half yearly payment successfully filed and processed.">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end ps-4 fw-medium text-muted">3</td>
                                    <td class="fw-bold text-dark">June 2025</td>
                                    <td>18 Employees</td>
                                    <td class="fw-semibold text-dark">₹ 180.00</td>
                                    <td class="fw-semibold text-dark">₹ 540.00</td>
                                    <td class="text-muted">CH-LWF-763910</td>
                                    <td class="text-muted">15-07-2025</td>
                                    <td>
                                        <span class="badge-pill-custom badge-pill-resolved">Filed</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn-action-detail view-lwf-details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDetailsModal"
                                                data-period="June 2025"
                                                data-count="18 Employees"
                                                data-emp-contribution="₹ 180.00"
                                                data-employer-contribution="₹ 540.00"
                                                data-total="₹ 720.00"
                                                data-challan="CH-LWF-763910"
                                                data-due-date="15-07-2025"
                                                data-status="Filed"
                                                data-remarks="Annual compliance verified. Receipt archived.">
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
                <h5 class="modal-title fw-bold text-dark font-20">LWF Filing Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="table-responsive rounded-3 overflow-hidden border">
                    <table class="table table-striped table-hover m-0 align-middle font-14">
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Filing Period</th>
                            <td id="detailPeriod" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee Count</th>
                            <td id="detailCount" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee Contribution</th>
                            <td id="detailEmpContribution" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employer Contribution</th>
                            <td id="detailEmployerContribution" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Total Contribution</th>
                            <td id="detailTotal" class="ps-3 fw-semibold text-dark"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Challan No</th>
                            <td id="detailChallan" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Due Date</th>
                            <td id="detailDueDate" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Payment Status</th>
                            <td id="detailStatus" class="ps-3 text-muted"></td>
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

<!-- About LWF Modal -->
<div class="modal fade" id="aboutLwfModal" tabindex="-1" aria-labelledby="aboutLwfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="avtar avtar-s btn-light-primary rounded-circle"><i class="ti ti-info-circle f-18"></i></span>
                    <h5 class="modal-title fw-bold text-dark font-20" id="aboutLwfModalLabel">About Labour Welfare Fund (LWF)</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-dark font-14" style="max-height: 70vh; overflow-y: auto;">
                
                {{-- SECTION 1: WHY REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-help f-18"></i> Why Required</h6>
                    <p class="text-secondary mb-2">Labour Welfare Fund (LWF) is a statutory employee welfare contribution collected by the State Labour Welfare Board.</p>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 2: PURPOSE --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-heart f-18"></i> Purpose: This money is used for</h6>
                    <ul class="list-unstyled ps-2 mb-0">
                        <li class="d-flex align-items-center gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16"></i> Worker welfare schemes</li>
                        <li class="d-flex align-items-center gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16"></i> Education assistance</li>
                        <li class="d-flex align-items-center gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16"></i> Medical support</li>
                        <li class="d-flex align-items-center gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16"></i> Labour welfare activities</li>
                    </ul>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 3: CALCULATION --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-calculator f-18"></i> Calculation</h6>
                    <p class="text-secondary mb-0">LWF is calculated: <strong>employee-wise plus employer contribution</strong>.</p>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 4: PAYMENT SCHEDULE STEPS --}}
                <div>
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-list-check f-18"></i> Payment Schedule Steps</h6>
                    <div class="row g-3 ps-2">
                        <div class="col-md-6">
                            <div class="bg-light rounded-3 p-3 h-100 shadow-none border-0">
                                <h6 class="fw-bold text-dark font-14 mb-2">Steps 1 - 3</h6>
                                <div class="d-flex flex-column gap-2.5">
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1">1</span>
                                        <span class="small text-secondary">Register company on: <strong>State Labour Welfare Board portal</strong></span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1">2</span>
                                        <span class="small text-secondary">Add employee details, employee count, and salary details</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1">3</span>
                                        <span class="small text-secondary">System calculates: employee contribution & employer contribution</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded-3 p-3 h-100 shadow-none border-0">
                                <h6 class="fw-bold text-dark font-14 mb-2">Steps 4 - 6</h6>
                                <div class="d-flex flex-column gap-2.5">
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1">4</span>
                                        <span class="small text-secondary">Generate challan</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1">5</span>
                                        <span class="small text-secondary">Pay online</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1">6</span>
                                        <span class="small text-secondary">Download challan & payment receipt</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    $(document).on('click', '.view-lwf-details-btn', function () {
        $('#detailPeriod').text($(this).data('period'));
        $('#detailCount').text($(this).data('count'));
        $('#detailEmpContribution').text($(this).data('emp-contribution'));
        $('#detailEmployerContribution').text($(this).data('employer-contribution'));
        $('#detailTotal').text($(this).data('total'));
        $('#detailChallan').text($(this).data('challan'));
        $('#detailDueDate').text($(this).data('due-date'));
        $('#detailStatus').text($(this).data('status'));
        $('#detailRemarks').text($(this).data('remarks'));
    });
</script>
@endsection
