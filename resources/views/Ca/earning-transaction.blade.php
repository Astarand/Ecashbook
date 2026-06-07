@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Transaction History</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="col-sm-12 mt-5">
        <div class="card table-card">
            <div class="card-body table-card">
                <table class="table tbl-product" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th class="text-end">#</th>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Payment Amount</th>
                            <th>Mode of Payment</th>
                            <th>Account Holder Name</th>
                            <th>Account Number</th>
                            <th>Bank Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-end">1</td>
                            <td><a class="text-muted text-hover-primary" href="#">19 Dec 2024</a></td>
                            <td><a class="text-muted text-hover-primary" href="#">@123456789</a></td>
                            <td><a class="text-success text-hover-primary" href="#">₹ 500</a></td>
                            <td><a class="text-muted text-hover-primary" href="#">UPI</a></td>
                            <td><a class="text-muted text-hover-primary" href="#">Name Surname</a></td>
                            <td><a class="text-muted text-hover-primary" href="#">123456789</a></td>
                            <td><a class="text-muted text-hover-primary" href="#">IDFC Bank</a></td>
                            <td>
                                <span class="badge bg-success">Complete</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection