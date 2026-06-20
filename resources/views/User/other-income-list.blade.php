@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Income List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-other-income-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Income Details</h2>
                    </div>
                </div>
                @if(in_array(Auth::user()->u_type, [2, 5]))
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddOtherIncome') }}" id="add-income-btn" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Income</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header  align-items-center justify-content-between py-3">
                            <h4 class="text-center">
                                Fill the Details
                            </h4>
                        </div>
                        <div class="card-body">

                            <form id="GetIncomeForm">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card shadow-sm border-0 p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="gross_income" value="gross_income">
                                                <label class="form-check-label" for="GrossSalesIncome">Gross Sales Income</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card shadow-sm border-0 p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="net_income" value="net_income">
                                                <label class="form-check-label" for="NetIncome">Net Income</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="inputEmail4">From Date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="from_date" id="from_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="inputEmail4">To Date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="to_date" name="to_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"></label>
                                        <button type="button" class="btn btn-primary w-100 mt-2" id="GetIncomeFormSubmit">Submit</button>

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header  align-items-center justify-content-between">
                            <h4 class="text-center">
                                Total Income
                            </h4>
                        </div>
                        <div class="card-body">
                            <h1 class="text-success text-center my-5" id="totalAmount">₹{{ number_format($totalIncome,2) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if($hasProprietorship)
                                <th>PROPRIETORSHIP COMPANY</th>
								@endif
                                <th>Date</th>
                                <th>Category of Income</th>                                
                                <th>Amount</th>
                                <th>TDS Amount</th>
								<th>Pay Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php $i = 1; ?>
                            @foreach ($incomes as $income)
                            <tr>
                                <td class="text-end">{{$i++}}</td>
								@if($hasProprietorship)
                                <td><span class="text-muted text-hover-primary">{{$income->comp_name}}</span></td>
								@endif
                                <td><span class="text-muted text-hover-primary">{{$income->dateInput}}</span></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{$income->categoryIncome}}</a></td>                                
                                <td><span class="text-muted text-hover-primary">₹{{number_format($income->amount,2)}}</span></td>
                                <td><span class="text-muted text-hover-primary">₹{{number_format($income->tds_amount,2)}}</span></td>
								<td>
                                    @if ($income->pay_status == 'Full')
                                    <span class="badge bg-success text-dark">{{ $income->pay_status }}</span>
                                    @elseif ($income->pay_status == 'Advance')
                                    <span class="badge bg-warning text-dark">{{ $income->pay_status }}</span>   
                                    @elseif ($income->pay_status == 'Due')
                                    <span class="badge bg-danger text-dark">{{ $income->pay_status }}</span> 
                                    @endif
                                </td>
								<td>
                                    @if ($income->status == '0')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @elseif ($income->status == '1')
                                    <span class="badge bg-success">Active</span>   
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a
                                                    href="{{ route('income.view', base64_encode($income->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if(in_array(Auth::user()->u_type, [2, 5]))

                                                @if ($income->status != '0')
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                        <a href="{{ route('income.edit', base64_encode($income->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#" data-id="{{ base64_encode($income->id) }}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
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
                    <h3>Delete Income</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal" class="w-100 btn btn-danger">
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
@endsection

@section('page-script')
<script>
    function startOtherIncomeListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Other Income Directory',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review and manage auxiliary income categories, gross/net details, and TDS tax offsets.</p></div>'
                },
                {
                    element: '#add-income-btn',
                    title: 'Add Other Income',
                    intro: 'Click here to register a new incoming business receipt or revenue transaction.'
                },
                {
                    element: '#GetIncomeForm',
                    title: 'Revenue Filter Parameters',
                    intro: 'Select income structure metrics (Gross Sales vs Net Income) and filter by dates.'
                },
                {
                    element: '#totalAmount',
                    title: 'Calculated Totals',
                    intro: 'This dashboard box dynamically updates to display summarized income values.'
                },
                {
                    element: '#pc-dt-simple',
                    title: 'Revenue Records',
                    intro: 'Browse items table lists of incomes, categorized with amounts, tax details, and status badges.'
                },
                {
                    element: '.prod-action-links',
                    title: 'Action Controls',
                    intro: 'View income details, edit active records, or delete items.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            skipIfNoElement: true,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-other-income-list-tour').on('click', function(e) {
            e.preventDefault();
            startOtherIncomeListTour();
        });
    });
    let deleteId = null; // Store the ID of the customer to be deleted

    // Capture the customer ID when the delete button is clicked
    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
    });

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {

        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/income-delete/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    // alert(response.message); // Show success message
                    // location.reload(); // Reload the page

                    showToast(response.message, "success");
                    setTimeout(() => location.reload(), 2000);
                },
                error: function(xhr) {
                    alert("Error deleting income!");
                    showToast("Error deleting income!", "error");
                }
            });
        }
    });

    //----------- Get Income Data ------------
    $('#GetIncomeFormSubmit').click(function() {
        // Get selected radio button value
        var incomeType = $('input[name="inlineRadioOptions"]:checked').val();
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        var base_url = $("#base_url").val();

        var requestData = {
            incomeType: incomeType,
            fromDate: fromDate,
            toDate: toDate
        };

        $.ajax({
            url: "/GetIncomeData",
            method: 'POST',
            data: requestData,
            success: function(response) {
                // Handle the successful response
                // alert('Income: ' + response.income); 
                console.log(response);

                $('#totalAmount').html('₹' + response.income);
                $('#invoiceType').html(response.incomeType);

            },
            error: function(xhr, status, error) {
                // Handle any errors that occurred
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
</script>

@endsection