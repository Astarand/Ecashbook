@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Assets Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.AssetList') }}">Assets List</a></li>
                        <li class="breadcrumb-item" aria-current="page">Assets List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-assets-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Assets List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    @if($req_tag != 1)
                        <a href="{{ route('user.AddAsset') }}" class="btn btn-primary">
                            <i class="ti ti-square-plus f-20"></i> Add New Asset
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
		
			<div class="card mb-3">
				<div class="card-body">
					<div class="row">

						<div class="col-md-3">
							<label>From Date</label>
							<input type="date" id="from_date" class="form-control">
						</div>

						<div class="col-md-3">
							<label>To Date</label>
							<input type="date" id="to_date" class="form-control">
						</div>
						
						<div class="col-md-3">
							<label>Asset Type</label>
							<select id="asset_type_filter" class="form-control">
								<option value="">All</option>
								<option value="current">Current</option>
								<option value="non-current">Non-Current</option>
							</select>
						</div>

						<div class="col-md-3 d-flex align-items-end">
							<button class="btn btn-success w-100" id="exportBtn">
								Export Excel
							</button>
						</div>

					</div>
				</div>
			</div>
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if($hasProprietorship)
								<th>PROPRIETORSHIP COMPANY</th>
								@endif
                                <th>Asset ID</th>
								<th>Asset Type</th>
								<th>Asset Cate.</th>
                                <th>Date</th>
                                <th>Asset Name</th>
                                <th>Amount</th>     
                                <th>TDS</th>     
                                <th>Purchase By</th>
                                <th>Pay Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($assets as $asset)
							
							@php
								$isCwip = $asset->assetType == 'non-current' 
										  && $asset->nonCurrentAssetType == 'Capital Work in Progress';

								$status = $isCwip 
										  ? $asset->cwip_pay_status 
										  : $asset->pay_status;
							@endphp
                            <tr>
                                <td class="text-end">{{$loop->index + 1}}</td>
								@if($hasProprietorship)
								<td>{{$asset->comp_name}}</td>
								@endif
                                <td>{{$asset->asset_id}}</td>
                                <td>{{$asset->assetType}}</td>
								<td>
									{{ $asset->nonCurrentAssetType ?? $asset->currentAssetType ?? '-' }}
								</td>
                                <td>{{$asset->date}}</td>
                                <td>{{$asset->asset_name ?: $asset->project_name}}</td>
                                <td>
                                    {{$asset->amount}}

                                    @if(strtolower($asset->gst_applicable ?? '') == 'yes')
                                        @php
                                            $gstAmt = (float) ($asset->gst_amt ?? 0);
                                            $baseAmt = (float) ($asset->amount ?? 0);
                                            $totalWithGst = $baseAmt + $gstAmt;
                                        @endphp

                                        <br>
                                        <span class="badge bg-light text-dark mt-1 d-inline-block">
                                            {{ ucfirst($asset->gst_trans) }} |
                                            GST {{ $asset->gst_rate }}% |
                                            GST Amt: ₹{{ number_format($gstAmt, 2) }} |
                                            Total: ₹{{ number_format($totalWithGst, 2) }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{$asset->tds_amt}}</td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $asset->purchaseByAudit }}</a></td>
								<td>
									@if (!empty($status))

										@if ($status == 'Full')
											<span class="badge bg-success text-dark">{{ $status }}</span>

										@elseif ($status == 'Advance')
											<span class="badge bg-warning text-dark">{{ $status }}</span>

										@else
											<span class="badge bg-danger text-dark">{{ $status }}</span>
										@endif

									@else
										-
									@endif
								</td>
                               <td>
                                    @if ($asset->isActive == '0')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @elseif ($asset->isActive == '1')
                                    <span class="badge bg-success">Active</span>   
                                    @endif
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <!-- <a
                                                    href="#"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#productOffcanvas">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a> -->

                                                <!-- ritam -->
                                                <a
                                                    href="{{ route('user.ViewAsset', base64_encode($asset->id)) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if($req_tag != 1)

                                                @if ($asset->isActive != '0')
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                        <a href="{{ route('user.EditAsset', base64_encode($asset->id)) }}"
                                                        class="avtar avtar-xs btn-link-success btn-pc-default">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                <li class="list-inline-item align-bottom">
                                                    <a href="javascript:void(0)" 
                                                    data-id="{{ base64_encode($asset->id) }}"
                                                    class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_modal"
                                                    data-bs-title="Delete"
                                                    data-bs-toggle="tooltip">
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
					<div class="d-flex justify-content-end mt-3">
						{{ $assets_pagination->links('pagination::bootstrap-4') }}
					</div>
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
                    <h3>Delete Asset</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-primary" id="confirmDelete">
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
    let deleteId = null; // Store the ID of the asset to be deleted

    // Capture the asset ID when the delete button is clicked
    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
    });

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
         //alert(deleteId);

        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/delete-asset/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    // alert(response.message); // Show success message
                    // location.reload(); // Reload the page

                    showToast(response.message, "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    alert("Error deleting asset!");
                    showToast("Error deleting asset!", "error");
                }
            });
        }
    });
	
	$('#exportBtn').on('click', function () {
		let from = $('#from_date').val();
		let to   = $('#to_date').val();
		let type  = $('#asset_type_filter').val();
		if (!from || !to) {
			alert("Please select both dates");
			return;
		}
		let url = `/export-assets?from_date=${from}&to_date=${to}&asset_type=${type}`;
		window.location.href = url; // trigger download
	});

    function startAssetsTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Assets List Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-briefcase" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review company assets database, monitor depreciation details, and track current vs. non-current holdings.</p></div>'
                },
                {
                    element: 'a[href="{{ route('user.AddAsset') }}"]',
                    title: 'Add New Asset',
                    intro: 'Click this button to record new capital investments or asset details.'
                },
                {
                    element: '.card.mb-3',
                    title: 'Asset Filters',
                    intro: 'Filter assets database by specific duration ranges or asset types, and export results directly to Excel.'
                },
                {
                    element: '.table-responsive',
                    title: 'Asset Ledger Table',
                    intro: 'Track details including purchase dates, GST transactions, payment status, and asset values.'
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
        $('#start-assets-tour').on('click', function(e) {
            e.preventDefault();
            startAssetsTour();
        });
    });
</script>

@endsection
