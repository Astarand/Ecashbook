@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card customer-details-group shadow-sm">
                <div class="card-body">
                    <div class="row gy-3 align-items-center">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-img">
                                        <img src="/uploads/profile/{{$users->comp_logo}}" alt="">
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>{{$users->comp_name}}</h6>
                                        <p>Company Representative</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-envelope-open"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Email Address</h6>
                                        <p>{{$users->comp_email}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-phone-call"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Phone Number</h6>
                                        <p>{{$users->comp_phone}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-bank"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Company Name</h6>
                                        <p>{{$users->comp_name}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-globe"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Website</h6>
                                        <p><a href="{{$users->comp_website}}" target="_blank">{{$users->comp_website}}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-navigation-arrow"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Company Address</h6>
                                        <p>
											{{$users->city ?? ''}}
											{{$users->state ?? ''}}
											{{$users->comp_bill_pin ?? ''}}
										</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mb-5">
            <div class="row">
                <div class="col-md-2">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-list-numbers text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total No of Task</h6>
                                <h4 class="fw-bold mb-0">{{$totalTasks}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-list-magnifying-glass text-warning f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">No of Ongoing Task</h6>
                                <h4 class="fw-bold mb-0">{{$ongoingTasks}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-list-plus text-danger f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">No of Pending Task</h6>
                                <h4 class="fw-bold mb-0">{{$pendingTasks}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-currency-inr text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total Payments</h6>
                                <h4 class="fw-bold mb-0">₹ {{number_format($totalPayments,2)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-hourglass text-warning f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total Due payment</h6>
                                <h4 class="fw-bold mb-0">₹ {{number_format($totalDuePayments,2)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-infinity text-success f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Recurring</h6>
                                <h4 class="fw-bold mb-0">₹ {{number_format($recurringPayments,2)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 justify-content-end d-flex">
            <div class="me-3">
                <select class="form-select">
					<option value="">No Assigned CA</option>
					@foreach($caUsers as $ca)
						<option value="{{$ca->id}}"
							{{$users->assignCa == $ca->name ? 'selected' : ''}}>
							{{$ca->name}}
						</option>
					@endforeach
				</select>
            </div>
            <button type="button" class="btn btn-primary btn-xl mb-3" data-bs-toggle="modal" data-bs-target="#BankModal">
                <i class="ph-duotone ph-piggy-bank"></i> Show Customer Account Details
            </button>
        </div>
        <div class="col-sm-12">
            <div class="card table-card p-4">
                <div class="card-body table-card">
                    <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
                                <th>Task Category</th>
                                <th>Task Start Date</th>
                                <th>Task End Date</th>
                                <th>Govt. Fees</th>
                                <th>Service Charges</th>
                                <th>Total Amount</th>
                                <th>Payment Mode</th>
                                <th>Payment Status</th>
                                
                            </tr>
                        </thead>
						<tbody>
							@forelse($tasks as $key => $task)
								<tr>
									<td class="text-end">{{ $key + 1 }}</td>

									<td>
										<a class="text-muted text-hover-primary">
											{{ $task->category_name ?? 'N/A' }}
										</a>
									</td>

									<td>
										<a class="text-muted text-hover-primary">
											{{ date('d-m-Y', strtotime($task->task_date)) }}
										</a>
									</td>

									<td>
										<a class="text-muted text-hover-primary">
											{{ !empty($task->due_date) ? date('d-m-Y', strtotime($task->due_date)) : 'N/A' }}
										</a>
									</td>

									<td>
										<a class="text-muted text-hover-primary">
											₹ {{ number_format($task->gov_fees, 2) }}
										</a>
									</td>

									<td>
										<a class="text-muted text-hover-primary">
											₹ {{ number_format($task->services_charges, 2) }}
										</a>
									</td>

									<td>
										<a class="text-muted text-hover-primary">
											₹ {{ number_format($task->total_amount, 2) }}
										</a>
									</td>

									<td>
										<a class="text-muted text-hover-primary">
											{{ $task->payment_mode ?? 'N/A' }}
										</a>
									</td>

									<td>
										@if($task->project_status == 1)
											<span class="badge bg-warning">Pending</span>
										@elseif($task->project_status == 2)
											<span class="badge bg-primary">Working</span>
										@elseif($task->project_status == 3)
											<span class="badge bg-success">Done</span>
										@else
											<span class="badge bg-secondary">Unknown</span>
										@endif
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="10" class="text-center text-muted">
										No Task Found
									</td>
								</tr>
							@endforelse
						</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="BankModal" tabindex="-1" aria-labelledby="customerAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="customerAccountModalLabel">
                    <i class="ph-duotone ph-bank" style="margin-right: 8px;"></i>Customer Bank Account Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

				@if($bankDetails)
					<div class="row">
						<div class="col-md-6 mb-3">
							<h6 class="text-muted">Bank Name</h6>
							<p class="fw-bold">
								{{$bankDetails->bank_name ?? 'N/A'}}
							</p>
						</div>

						<div class="col-md-6 mb-3">
							<h6 class="text-muted">Account Holder</h6>
							<p class="fw-bold">
								{{$bankDetails->accholder_name ?? 'N/A'}}
							</p>
						</div>

						<div class="col-md-6 mb-3">
							<h6 class="text-muted">Account Number</h6>
							<p class="fw-bold">
								{{$bankDetails->bank_ac_no ?? 'N/A'}}
							</p>
						</div>

						<div class="col-md-6 mb-3">
							<h6 class="text-muted">IFSC Code</h6>
							<p class="fw-bold">
								{{$bankDetails->ifsc_code ?? 'N/A'}}
							</p>
						</div>

						<div class="col-md-6 mb-3">
							<h6 class="text-muted">Branch</h6>
							<p class="fw-bold">
								{{$bankDetails->bank_branch ?? 'N/A'}}
							</p>
						</div>
					</div>
				@else
					<div class="text-center py-4">
						<h6 class="text-muted mb-0">
							No Bank Details Found
						</h6>
					</div>
				@endif

			</div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection