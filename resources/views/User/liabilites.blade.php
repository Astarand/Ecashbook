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
                        <li class="breadcrumb-item"><a href="#">Liabilities & Borrowings</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/liabilites-list')}}">Liabilities & Borrowing List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Liability & Borrowing</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-liabilities-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Liabilites & Borrowings List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddLiabilites') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Liabilites</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <!-- [ Row 1 ] start -->
        <div class="col-md-12 col-xxl-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="../assets/images/widget/img-status-2.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-stretch justify-content-between h-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="avtar bg-brand-color-1 text-white me-3">
                                <i class="ph-duotone ph-flag-banner f-26"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Share Holder's Fund</p>
                                <div class="d-flex align-items-end">
                                    <h2 class="mb-0 f-w-500">₹ {{ number_format($shareHolderFund,2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="javascript:void(0);" data-type="share-holder" data-title="Share Holder's Fund" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center justify-content-center flex-row text-center position-relative overflow-hidden viewLiability" data-bs-toggle="tooltip" title="View">
                                <i class="ti ti-eye f-18 me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="../assets/images/widget/img-status-1.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-stretch justify-content-between h-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="avtar bg-brand-color-2 text-white me-3">
                                <i class="ph-duotone ph-scales f-26"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0"> Share Application Money Pending Allotment</p>
                                <div class="d-flex align-items-end">
                                    <h2 class="mb-0 f-w-500">₹ {{ number_format($shareAppMoney,2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="javascript:void(0);" data-type="share-app" data-title="Share Application Money" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center justify-content-center flex-row h-100 w-100 text-center position-relative overflow-hidden viewLiability" data-bs-toggle="tooltip" title="View">
                                <i class="ti ti-eye f-18 me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-xxl-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="../assets/images/widget/img-status-1.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-stretch justify-content-between h-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="avtar bg-brand-color-3 text-white me-3">
                                <i class="ph-duotone ph-user-list f-26"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Non-Current Liabilities</p>
                                <div class="d-flex align-items-end">
                                    <h2 class="mb-0 f-w-500">₹ {{ number_format($nonCurrent,2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="javascript:void(0);" data-type="non-current" data-title="Non-Current Liabilities" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center justify-content-center flex-row h-100 w-100 text-center position-relative overflow-hidden viewLiability" data-bs-toggle="tooltip" title="View">
                                <i class="ti ti-eye f-18 me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="../assets/images/widget/img-status-3.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-stretch justify-content-between h-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="avtar bg-brand-color-2 text-white me-3">
                                <i class="ph-duotone ph-scroll f-26"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Current Liabilities</p>
                                <div class="d-flex align-items-end">
                                    <h2 class="mb-0 f-w-500">₹ {{ number_format($current,2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="javascript:void(0);" data-type="current" data-title="Current Liabilities" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center justify-content-center flex-row h-100 w-100 text-center position-relative overflow-hidden viewLiability" data-bs-toggle="tooltip" title="View">
                                <i class="ti ti-eye f-18 me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xxl-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="../assets/images/widget/img-status-2.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-stretch justify-content-between h-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="avtar bg-brand-color-1 text-white me-3">
                                <i class="ph-duotone ph-read-cv-logo f-26"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Total Liabilities</p>
                                <div class="d-flex align-items-end">
                                    <h2 class="mb-0 f-w-500">₹ {{ number_format($totalLiabilities,2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="javascript:void(0);" data-type="total" data-title="Total Liabilities" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center justify-content-center flex-row h-100 w-100 text-center position-relative overflow-hidden viewLiability" data-bs-toggle="tooltip" title="View">
                                <i class="ti ti-eye f-18 me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Row 1 ] end -->
    </div>

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <table class="table tbl-product" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th class="text-end">#</th>
							@if($hasProprietorship)
							<th>PROPRIETORSHIP COMPANY</th>
							@endif
                            <th>Date</th>
                            <th>Liabilities Type</th>
                            <th>Total Payable Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                        $typeNames = [
                            'share_holder_fund' => "Share Holder's Funds",
                            'share_application_money' => "Share Application Money Pending Allotment",
                            'non_current_liabilities' => "Non-Current Liabilities",
                            'current_liabilities' => "Current Liabilities",
                        ];
                        @endphp

                        @foreach ($liabilities as $i => $row)
                            <tr>
                                <td class="text-end">{{ $i + 1 }}</td>
								@if($hasProprietorship)
								<td>{{$row->comp_name}}</td>
								@endif
                                <td>
                                    <span class="text-muted text-hover-primary">
                                        {{ date('d M Y', strtotime($row->added_date)) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-muted text-hover-primary">
                                        {{ $typeNames[$row->liabilities_type] ?? $row->liabilities_type }}
                                    </span>
                                </td>
								<td>₹ {{ number_format($row->amount, 2) }}</td>
								<td>
                                    @if ($row->status == '0')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @elseif ($row->status == '1')
                                    <span class="badge bg-success">Active</span>   
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">

                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="View">
                                                <a href="{{ url('view-liabilities/'.base64_encode($row->id)) }}"
                                                class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if($req_type != 1)

                                                @if ($row->status != '0')
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" title="Edit">
                                                        <a href="{{ url('edit-liabilities/'.base64_encode($row->id)) }}"
                                                        class="avtar avtar-xs btn-link-success btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                <li class="list-inline-item" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="javascript:void(0)" 
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default liabdelete"
                                                    data-id="{{ $row->id }}"
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
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal fade" id="liabilityModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="liabilityModalTitle">Liability Details</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="liabilityModalBody">
        Loading...
      </div>

    </div>
  </div>
</div>


<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Liabilites</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" id="del_liab" class="w-100 btn btn-primary">
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

	
	$(document).on('click', '.viewLiability', function () {
		let type = $(this).data('type');
		let title = $(this).data('title');
		$('#liabilityModalTitle').text(title);
        
		$('#liabilityModalBody').html('Loading...');
		$.get("{{ url('/liabilities') }}/" + type, function (html) {
			$('#liabilityModalBody').html(html);
			$('#liabilityModal').modal('show');
		});
	});

	// pagination inside modal
	$(document).on('click', '#liabilityModal .pagination a', function(e){
		e.preventDefault();
		let url = $(this).attr('href');

		$.get(url, function(html){
			$('#liabilityModalBody').html(html);
		});
	});
	
    $(document).on("click", ".liabdelete", function() {
        var itemId = $(this).data("id");

        $("#del_liab").off("click").on("click", function() {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "DELETE",
                url: "/delLiabilities/" + itemId,
                dataType: "json",
                success: function(data) {
                    if (data.status === "success") {
                        showToast("Deleted Successfully", "success");
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    } else {
                        showToast(data.message, "error");
                    }
                },
                error: function() {
                    showToast("Something went wrong. Please try again.", "error");
                }
            });
        });
    });

    function startLiabilitiesTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Liabilities & Borrowings Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage company borrowings, stockholder funds, and long/short term debt details in one ledger.</p></div>'
                },
                {
                    element: 'a[href="{{ route('user.AddLiabilites') }}"]',
                    title: 'Add New Liability',
                    intro: 'Click here to log a new borrowing or update equity/liability status.'
                },
                {
                    element: '.col-md-12.col-xxl-4',
                    title: 'Equity & Shareholder Funds',
                    intro: 'View total capital injected by directors or shareholders.'
                },
                {
                    element: '.table-card',
                    title: 'Liabilities Table',
                    intro: 'Review list of liabilities, their payable balances, and record status (Active/Cancelled).'
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
        $('#start-liabilities-tour').on('click', function(e) {
            e.preventDefault();
            startLiabilitiesTour();
        });
    });
</script>


@endsection
