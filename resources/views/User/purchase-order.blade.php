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
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Purchase Order List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Purchase Order List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.CreatePurchaseOrder') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Purchase Order</a>
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
                                <th>Purchase Invoice Number</th>
                                <th>PO Date</th>
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
                                <td><span class="text-muted text-hover-primary">Click/PO/25/0001</span></td>
                                <td><span class="text-muted">10-02-2025</span></td>
                                <td><span class="text-muted">12,500 Pc</span></td>
                                <td><span class="text-muted">₹ 1,25,000</span></td>
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
                                <td><span class="text-muted text-hover-primary">Click/PO/25/0001</span></td>
                                <td><span class="text-muted">14-02-2025</span></td>
                                <td><span class="text-muted">7,900 Box</span></td>
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

@endsection
