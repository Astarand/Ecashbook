@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Payslip</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-employee-payslip-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Payslip</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3" id="payslips-table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table tbl-product" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $index => $payslip)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ date('F', mktime(0, 0, 0, $payslip->month, 1)) }}</td>
                                <td>{{ $payslip->financial_year }}</td>
                                <td>
                                    <a href="/download-payslip/{{ $payslip->id }}" class="btn btn-success" target="_blank">
                                        Download Payslip
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No payslips found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<script>
    function startEmployeePayslipTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'View & Download Payslips',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-file-text" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Access and download your monthly generated salary payslips here.</p></div>'
                },
                {
                    element: '#payslips-table-card',
                    title: 'Payslips Table',
                    intro: 'List of all generated payslips showing the month, year, and a direct download button for PDF format.'
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
        $('#start-employee-payslip-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeePayslipTour();
        });
    });
</script>
@endsection
