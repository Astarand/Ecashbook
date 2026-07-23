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
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/expenses-list') }}">Expenses Management</a></li>
                        <li class="breadcrumb-item" aria-current="page">Expenses Management List</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-expenses-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Expenses Management List</h2>
                    </div>
                </div>
                @if(in_array(Auth::user()->u_type, [2,5]))
                <div class="col-md-8 text-end">
                    <a href="{{ route('user.AddExpenses') }}" class="btn btn-primary" id="add-expense-btn"><i class="ti ti-square-plus"></i> Add New Expenses</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card" id="expenses-table-card">
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
                                <th>Threshold Type</th>
                                <th>Deduction</th>
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
                                <td><span class="text-muted text-hover-primary">{{ $expen->threshold_type }}</span></td>
                                <td>
									<div>
										<span class="badge 
											{{ $expen->tax_treatment == 'Fully Allowed' ? 'bg-success' : ($expen->tax_treatment == 'Partial Allowed' ? 'bg-warning text-dark' : 'bg-danger') }}">
											{{ $expen->tax_treatment }}
										</span>
										<br>
										<small class="text-muted">
											Ratio:
											<strong>{{ $expen->allowed_ratio }}%</strong>
										</small>
										<br>
										<small class="fw-bold">
											Deduction: ₹ {{ number_format($expen->rebate_amt, 2) }}
										</small>
									</div>
								</td>
                                <td><span class="text-muted text-hover-primary">{{ $expen->approved_by }}</span></td>
								<td>
                                    @if ($expen->payment_status == 'full')
                                    <span class="badge bg-success text-dark">{{ $expen->payment_status }}</span>
                                    @elseif ($expen->payment_status == 'advance')
                                    <span class="badge bg-warning text-dark">{{ $expen->payment_status }}</span>   
                                    @elseif ($expen->payment_status == 'due')
                                    <span class="badge bg-danger text-dark">{{ $expen->payment_status }}</span> 
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

												<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Third Party Settlement">
													<a href="javascript:void(0);" title="Third Party Settlement"
															class="btn btn-sm btn-warning settlement-btn"
															data-module="Expense"
															data-id="{{ $expen->id }}">
														<i class="ti ti-replace"></i>
													</a>
												</li>
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

	

@endsection

@section('page-script')
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

    function startExpensesListTour() {
        function launch() {
            let introTour = introJs().setOptions({
                steps: [
                    {
                        title: 'Expenses List Guide',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage company operating expenses, track TDS/GST payments, and monitor approval status.</p></div>'
                    },
                    {
                        element: '#add-expense-btn',
                        title: 'Add New Expenses',
                        intro: 'Click here to record a new business expense, assign category, TDS/GST, vendor, and upload proof.'
                    },
                    {
                        element: '#expenses-table-card',
                        title: 'Expenses Records',
                        intro: 'View details of logged expenses including date, ref number, categories, total amount, TDS, payment status, and approval details.'
                    },
                    {
                        element: '.prod-action-links',
                        title: 'Action Items',
                        intro: 'View full details of an expense record, edit it, or delete it from the system.'
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
            });

            introTour.onbeforechange(function(targetElement) {
                if (!targetElement) return;
                let isAction = targetElement.matches('.prod-action-links') || targetElement.closest('.prod-action-links') || targetElement.closest('td:last-child');
                let scrollable = targetElement.closest('.datatable-container') || targetElement.closest('.table-responsive') || document.querySelector('.datatable-container') || document.querySelector('.table-responsive');
                if (scrollable) {
                    if (isAction) {
                        scrollable.scrollLeft = scrollable.scrollWidth;
                    } else {
                        scrollable.scrollLeft = 0;
                    }
                }
            });

            introTour.start();
        }

        if (typeof introJs === 'function') {
            launch();
        } else {
            // CSS
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }

            // JS
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    function bindExpensesTour() {
        const btn = document.getElementById('start-expenses-list-tour');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                startExpensesListTour();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindExpensesTour);
    } else {
        bindExpensesTour();
    }
</script>
@endsection