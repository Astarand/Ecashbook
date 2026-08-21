@extends('App.Layout')

@section('page-style')
<style>
    .hr-letter-preview-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        padding: 2.5rem;
        min-height: 400px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        line-height: 1.8;
    }
    .hr-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        transition: all 0.2s ease-in-out;
        border: 1px solid transparent;
        text-decoration: none;
    }
    .hr-icon-btn.btn-view {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }
    .hr-icon-btn.btn-view:hover {
        background-color: var(--bs-primary);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.25);
    }
    .hr-icon-btn.btn-pdf {
        background-color: rgba(220, 38, 38, 0.1);
        color: #dc2626;
    }
    .hr-icon-btn.btn-pdf:hover {
        background-color: #dc2626;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);
    }
</style>
@endsection

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.EmployeeList') }}">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">HR Letter</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title d-flex align-items-center justify-content-between">
                        <h2 class="mb-0">HR Letters</h2>
                        <a href="{{ route('user.EmployeeList') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                            <i class="ti ti-arrow-left"></i> Back to Employees
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end" style="width: 60px;">#</th>
                                <th>Subject</th>
                                <th style="width: 180px;">Send Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($letters as $index => $letter)
                            <tr>
                                <td class="text-end">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $letter->subject }}</span>
                                </td>
                                <td>
                                    @if ($letter->sent_at)
                                    <span class="badge bg-light-secondary text-dark px-2 py-1">
                                        <i class="ti ti-calendar me-1"></i>{{ \Carbon\Carbon::parse($letter->sent_at)->format('d M Y') }}
                                    </span>
                                    @else
                                    <span class="badge bg-light-warning text-dark px-2 py-1">Not Sent</span>
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="javascript:void(0)"
                                                   class="avtar avtar-xs btn-link-warning btn-pc-default viewLetterBtn"
                                                   data-subject="{{ $letter->subject }}"
                                                   data-content="{{ $letter->content }}">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Download PDF">
                                                <a href="{{ route('user.employee_hr_letter_pdf', [
                                                        'empId' => base64_encode($empId),
                                                        'letterId' => base64_encode($letter->id)
                                                    ]) }}"
                                                   class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                   target="_blank">
                                                    <i class="ph-duotone ph-file-pdf f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No HR letters available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- ===== View Letter Modal ===== -->
<div class="modal fade" id="viewLetterModal" tabindex="-1" aria-labelledby="viewLetterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title text-white d-flex align-items-center gap-2" id="viewLetterLabel">
                    <i class="ti ti-file-text f-20"></i>
                    <span>HR Letter Preview</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #f8fafc;">
                <div class="hr-letter-preview-box">
                    <h4 id="modalLetterSubject" class="fw-bold text-center text-dark mb-4 pb-3 border-bottom"></h4>
                    <div id="modalLetterContent"></div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-end">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // View Letter Modal handler
        document.querySelectorAll(".viewLetterBtn").forEach(button => { 
            button.addEventListener("click", function () { 
                const subject = this.getAttribute("data-subject"); 
                const content = this.getAttribute("data-content"); 
                document.getElementById("modalLetterSubject").innerText = subject; 
                document.getElementById("modalLetterContent").innerHTML = content; 
                const modal = new bootstrap.Modal(document.getElementById("viewLetterModal")); 
                modal.show(); 
            }); 
        }); 
    }); 
</script>
@endsection