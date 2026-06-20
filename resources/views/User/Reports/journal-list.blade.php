@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/journal-list') }}">Journal</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Journal List</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-journal-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Journal List</h2>
                    </div>
                </div>
				@if($req_type != 1)
					<div class="col-md-8 text-end">
						<a href="{{ route('user.AddJournal') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Journal</a>
					</div>
				@endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <!-- Filter Options Card -->
            <div class="card mb-4 reconciliation-filter-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div class="card-header py-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1.05rem;">
                        <i class="ti ti-filter f-20"></i> Filter Journal Options
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ url('/journal-list') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-muted">Ledger</label>
                                <select name="party_name" class="form-select">
                                    <option value="">All</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party }}"
                                            {{ request('party_name') == $party ? 'selected' : '' }}>
                                            {{ $party }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" style="height: 41px;">
                                    <i class="ti ti-filter f-18"></i> Filter
                                </button>
                                <a href="{{ url('/journal-list') }}" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center" style="height: 41px;">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card mb-4 journal-table-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1.05rem;">
                        <i class="ti ti-table f-20"></i> Journal Records
                    </h5>
                    <a href="{{ route('journal.export', request()->query()) }}" class="btn btn-success btn-sm d-flex align-items-center gap-2">
                        <i class="ti ti-file-export f-18"></i> Export Excel
                    </a>
                </div>
                <div class="card-body p-4">
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
                                <th>Payment Status</th>
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
								<td>{{ date('d-m-Y',strtotime($journal->journal_date)) }}</td>
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
								<td>{{ $journal->payment_status }}</td>
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

    function startJournalTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Journal List Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-notes" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage and review journal voucher entries from this dashboard.</p></div>'
                },
                {
                    element: 'a[href="{{ route("user.AddJournal") }}"]',
                    title: 'Add New Journal',
                    intro: 'Click here to create a new manual journal entry.'
                },
                {
                    element: '.reconciliation-filter-card',
                    title: 'Filter Options',
                    intro: 'Filter your journal list by selecting specific ledgers and date ranges.'
                },
                {
                    element: '.journal-table-card',
                    title: 'Journal Records Table',
                    intro: 'This section contains the journal entries. You can see details like date, ledger name, Cr/Dr type, and total amount.'
                },
                {
                    element: 'a[href*="export-journal"]',
                    title: 'Export Excel',
                    intro: 'Click here to export the currently filtered journal entries to an Excel sheet.'
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
        $('#start-journal-tour').on('click', function(e) {
            e.preventDefault();
            startJournalTour();
        });
    });
</script>

@endsection
