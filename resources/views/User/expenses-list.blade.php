@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/expenses-list') }}">Expenses Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Expenses Management List</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Expenses Management List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddExpenses') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Expenses</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if($hasProprietorship)
								<th>PROPRIETORSHIP COMPANY</th>
								@endif
                                <th>Date</th>
                                <th>Invoice / Ref Number</th>
                                <th>Expense Categories</th>
                                <th>Expense Details</th>
                                <th>Total Amount</th>
                                <th>TDS</th>
                                <th>Approve By</th>
                                <th>Pay Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($expenses as $expen)
                            <tr>
                                <td class="text-end"><?php echo $i++; ?></td>
								@if($hasProprietorship)
								<td><span class="text-muted text-hover-primary">{{$expen->comp_name}}</span></td>
								@endif
                                <td><span class="text-muted text-hover-primary">{{ $expen->expense_date }}</span></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $expen->exp_invno }}</a></td>
                                <td><span class="text-muted text-hover-primary">{{ $expen->expense_cat }}</span></td>
                                <td><span class="text-muted text-hover-primary">{{ ucwords(str_replace(['_', '-'], ' ', $expen->expense_type)) }}</span></td>
                                <td><span class="text-muted text-hover-primary">₹ {{ $expen->expense_amt }}</span></td>
                                <td><span class="text-muted text-hover-primary">₹ {{ $expen->tds_amount }}</span></td>
                                <td><span class="text-muted text-hover-primary">{{ $expen->approved_by }}</span></td>
								<td>
                                    @if ($expen->payment_status == 'full')
                                    <span class="badge bg-success text-dark">{{ $expen->payment_status }}</span>
                                    @elseif ($expen->payment_status == 'advance')
                                    <span class="badge bg-warning text-dark">{{ $expen->payment_status }}</span>   
                                    @endif
                                </td>
								<td>
                                    @if ($expen->status == '0')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @elseif ($expen->status == '1')
                                    <span class="badge bg-success">Active</span>   
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ url('/view-expenses/'.base64_encode($expen->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@php
                                                $userType = Auth::user()->u_type;
                                            @endphp

                                            @if(in_array($userType, [2,5]))

                                                @if ($expen->status != '0')
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                    <a href="{{ url('/edit-expenses/'.base64_encode($expen->id)) }}"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                        <i class="ti ti-edit-circle f-18"></i>
                                                    </a>
                                                </li>
                                                @endif

                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default expenses"
                                                    data-id="{{$expen->id}}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_modal">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                </li>

                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Expenses </h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" id="del_exp" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on("click", ".expenses", function() {
        var itemId = $(this).data("id");

        $("#del_exp").off("click").on("click", function() {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "DELETE",
                url: "/deleteExpenses/" + itemId, // Pass ID in the URL
                dataType: "json",
                success: function(data) {
                    if (data.status === "success") {
                        // window.location.href = data.redirect;
                        showToast("Delete Successfully", "success");
                        setTimeout(() => {
                            setTimeout(() => location.reload(), 2000);
                        }, 2000);
                    } else {
                        // alert(data.message); // Show error message if delete fails
                        showToast(data.message, "error");
                    }
                },
                error: function() {
                    // alert("Something went wrong. Please try again.");
                    showToast("Something went wrong. Please try again.", "error");
                }
            });
        });
    });
</script>

@endsection