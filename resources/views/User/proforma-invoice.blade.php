@extends('App.Layout')

@section('container')

<style>
    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Sales</a></li>
                        <li class="breadcrumb-item" aria-current="page">Proforma Invoice List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-proforma-invoice-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Proforma Invoice List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.CreateProformaInvoice') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Proforma Invoice</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Customer Name</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Quantity</th>
                                <th>Grand Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">1</td>
                                <td>
                                    <h6 class="mb-1">John Doe</h6>
                                    <a class="text-muted f-12 text-hover-primary" href="tel:+911234567890">1234567890</a>
                                </td>
                                <td><span class="text-muted text-hover-primary">Click/PI/25/0001</span></td>
                                <td><span class="text-muted">10-02-2025</span></td>
                                <td><span class="text-muted"> 500 Pc</span></td>
                                <td><span class="text-muted">₹ 12,500</span></td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="View Invoice">
                                                <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-file f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="View">
                                                <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="Edit">
                                                <a href="#" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end">2</td>
                                <td>
                                    <h6 class="mb-1">Amit Sharma</h6>
                                    <a class="text-muted f-12 text-hover-primary" href="tel:+919876543210">9876543210</a>
                                </td>
                                <td><span class="text-muted text-hover-primary">Click/PI/25/0001</span></td>
                                <td><span class="text-muted">14-02-2025</span></td>
                                <td><span class="text-muted">300 Carton</span></td>
                                <td><span class="text-muted">₹ 7,900</span></td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="View Invoice">
                                                <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-file f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="View">
                                                <a href="#" class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="Edit">
                                                <a href="#" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="Delete">
                                                <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@section('page-script')
<script>
    function startProformaInvoiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Proforma Invoices List Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Track generated proforma estimates.</p></div>'
                },
                {
                    title: 'Proforma Invoices List',
                    intro: 'Track generated proforma estimates.'
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
        $('#start-proforma-invoice-list-tour').on('click', function(e) {
            e.preventDefault();
            startProformaInvoiceTour();
        });
    });
</script>
@endsection

