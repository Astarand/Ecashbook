@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Journal Entry</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Journal Entry</h2>
                    </div>
                </div>
                 <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddJournal') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Journal</a>
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
                                <th class="text-end">#</th>
                                <th>Date</th>
                                <th>Journal No</th>
                                <th>Entry Type</th>
                                <th>Ledger</th>
                                <th>Cr / Dr</th>
                                <th>Amount (₹)</th>
                                <th>GST (%)</th>
                                <th>TDS Amt (₹)</th>
                                <th>Party</th>
                                <th>Note</th>
                                <th>Amend/Reverse</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
						@forelse($journals as $key => $journal)
							<tr>
								<!-- Serial Number -->
								<td class="text-end">
									{{ $journals->firstItem() + $key }}
								</td>
								<td>{{ \Carbon\Carbon::parse($journal->journal_date)->format('d-m-Y') }}</td>
								<td>{{ 'JV-' . $journal->journal_no }}</td>
								<td>{{ $journal->entry_type }}</td>
								<td>{{ $journal->ledger }}</td>
								<td>
									<span class="badge {{ $journal->debit_credit == 'Credit' ? 'bg-success' : 'bg-danger' }}">
										{{ $journal->debit_credit }}
									</span>
								</td>
								<td>{{ number_format($journal->total_amount, 2) }}</td>
								<td>{{ $journal->gst_rate }}</td>
								<td>{{ number_format($journal->tds_amt, 2) }}</td>
								<td>{{ $journal->party_name }}</td>
								<td>
									{{ $journal->notes == 'Other' ? $journal->other_note : $journal->notes }}
								</td>
								<td>
									@if(!empty($journal->rev_amend_status))
										<span class="badge 
											{{ $journal->rev_amend_status == 'reverse' ? 'bg-danger' : 'bg-warning text-dark' }}">
											
											<i class="ti 
												{{ $journal->rev_amend_status == 'reverse' ? 'ti-refresh' : 'ti-edit' }}">
											</i>
											
											{{ ucfirst($journal->rev_amend_status) }}
										</span>
									@endif
								</td>
								<td>
									<span class="badge {{ $journal->status == 'Manual' ? 'bg-secondary' : 'bg-warning text-dark' }}">
										{{ $journal->status }}
									</span>
								</td>

								<!-- Actions -->
								<td>
									<span><i class="ti ti-dots-vertical f-20"></i></span>
									<div class="prod-action-links">
										<ul class="list-inline me-auto mb-0">

											@if($journal->status == 'Manual')
											<li class="list-inline-item" title="View">
												<a href="{{ route('user.viewJournal', base64_encode($journal->id)) }}"
												   class="avtar avtar-xs btn-link-success btn-pc-default">
													<i class="ti ti-eye f-18"></i>
												</a>
											</li>

											<!-- Edit -->
											<li class="list-inline-item" title="Edit">
												<a href="{{ route('user.editJournal', base64_encode($journal->id)) }}"
												   class="avtar avtar-xs btn-link-primary btn-pc-default">
													<i class="ti ti-edit f-18"></i>
												</a>
											</li>
											@else
												
											@php
												switch($journal->source) {
													case 'Sales':
														$viewUrl   = url('/view-sales-invoice/'.base64_encode($journal->autoId));
														$editUrl   = url('/edit-sales-invoice/'.base64_encode($journal->autoId));
														$listRoute = route('user.SalesInvoices');
														break;
													case 'Purchase':
														$viewUrl   = url('/view-purchase-invoice/'.base64_encode($journal->autoId));
														$editUrl   = url('/edit-purchase-invoice/'.base64_encode($journal->autoId));
														$listRoute = route('user.PurchaseInvoices');
														break;
													case 'Expense':
														$viewUrl   = url('/view-expenses/'.base64_encode($journal->autoId));
														$editUrl   = url('/edit-expenses/'.base64_encode($journal->autoId));
														$listRoute = route('user.ExpensesList');
														break;
													case 'Income':
														$viewUrl   = url('/viewincome/'.base64_encode($journal->autoId));
														$editUrl   = url('/editincome/'.base64_encode($journal->autoId));
														$listRoute = route('user.OtherIncomeList');
														break;
													case 'Asset':
														$viewUrl   = url('/view-asset/'.base64_encode($journal->autoId));
														$editUrl   = url('/edit-asset/'.base64_encode($journal->autoId));
														$listRoute = route('user.AssetList');
														break;
													case 'Liability':
														$viewUrl   = url('/view-liabilities/'.base64_encode($journal->autoId));
														$editUrl   = url('/edit-liabilities/'.base64_encode($journal->autoId));
														$listRoute = route('user.Liabilites');
														break;

													default:
														$viewUrl = $editUrl = $listRoute = '#';
												}
											@endphp

											@if($req_type != 1)
												<li class="list-inline-item" title="View">
													<a href="{{ $viewUrl }}"
													class="avtar avtar-xs btn-link-success btn-pc-default">
														<i class="ti ti-eye f-18"></i>
													</a>
												</li>
												<!--<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Reserve">
													<a href="{{ $editUrl }}" class="avtar avtar-xs btn-link-warning btn-pc-default">
														<i class="ti ti-bookmark f-18"></i>
													</a>
												</li>-->
											
												@if($journal->rev_amend_status != 'reverse')
													<li class="list-inline-item" title="Reverse">
														<a href="javascript:void(0)" 
														class="avtar avtar-xs btn-link-danger btn-pc-default reverseBtn"
														data-id="{{ $journal->autoId }}"
														data-source="{{ $journal->source }}">
															<i class="ti ti-refresh f-18"></i>
														</a>
													</li>
													<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Amend">
														<a href="{{ $editUrl }}" class="avtar avtar-xs btn-link-primary btn-pc-default">
															<i class="ti ti-edit f-18"></i>
														</a>
													</li>
												@endif
											@endif
											
											<!--<li class="list-inline-item" title="Delete">
												<a href="javascript:void(0)"
												   class="avtar avtar-xs btn-link-danger btn-pc-default delete-journal"
												   data-id="{{ $journal->id }}">
													<i class="ti ti-trash f-18"></i>
												</a>
											</li>-->
											@endif
										</ul>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="11" class="text-center">No records found</td>
							</tr>
						@endforelse
                        </tbody>
                    </table>
					<div class="d-flex justify-content-end mt-3">
						{{ $journals->links('pagination::bootstrap-4') }}
					</div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<script>

$(document).on('click', '.reverseBtn', function(){

    let autoId = $(this).data('id');
    let source = $(this).data('source');

    if(confirm('Are you sure you want to reverse this entry?')){

        $.ajax({
            url: "{{ route('journal.reverse') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                autoId: autoId,
                source: source
            },
            success: function(res){
                if(res.success){
                    alert(res.message);
                    location.reload();
                }
            },
            error: function(){
                alert('Something went wrong');
            }
        });

    }
});

$(document).on('click', '.delete-journal', function () {

    let id = $(this).data('id');

    if (!confirm('Are you sure you want to delete this journal?')) {
        return;
    }

    $.ajax({
        url: "/delete-journal/" + id,
        type: "DELETE",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.status) {
				showToast(res.message, 'success');
                location.reload();
            } else {
				showToast('Something went wrong', 'error');
            }
        },
        error: function () {
			showToast('Error occurred', 'error');
        }
    });
});
</script>

@endsection
