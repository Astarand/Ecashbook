@extends('App.Layout')

@section('container')




<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Startup Filing Reports</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-startup-filing-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h2 class="mb-0">Startup Filing Reports</h2>
                    </div>
                </div>
								@if(Auth::user()->u_type == 2)
								<div class="col-md-4 text-end">
                    <a href="{{ route('user.StartupFiling') }}" class="btn btn-primary tour-apply-btn"><i class="ti ti-square-plus"></i> Apply</a>
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
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product table-hover mb-0" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Business Name</th>
                                <th>Founder Name</th>
                                <th>Date Added</th>
                                <th>Application Status</th>
                                <th>Process Date</th>
                                <th>Payment Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $row)
                            <tr id="row{{ $row->id }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="row align-items-center">
                                        <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                <i class="ti ti-rocket f-20"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-0">{{ $row->business_name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $row->founder_name }}</td>
                                <td>
                                    <span class="text-muted"><i class="ti ti-calendar me-1"></i>{{ $row->created_at->format('d-m-Y') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light-{{ $row->app_status == 'Processed' ? 'success text-success' : 'warning text-warning' }}">
                                        {{ $row->app_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="ti ti-calendar-event me-1"></i>{{ $row->process_date ? \Carbon\Carbon::parse($row->process_date)->format('d-m-Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($row->payment_status == 'Full')
                                        <span class="badge bg-light-success text-success">Full</span>
                                    @elseif($row->payment_status == 'Advance')
                                        <span class="badge bg-light-warning text-warning">Advance</span>
                                    @else
                                        <span class="badge bg-light-secondary text-secondary">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ url('/startup-filing/view/'.$row->id) }}"
                                           class="avtar avtar-xs btn-link-success btn-pc-default"
                                           data-bs-toggle="tooltip"
                                           title="View Details">
                                            <i class="ti ti-eye f-18"></i>
                                        </a>
                                        @if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                                        <a href="javascript:void(0)"
                                           class="avtar avtar-xs btn-link-warning btn-pc-default"
                                           onclick="openStatusModal({{ $row->id }}, 'startup_incubator_applications')"
                                           data-bs-toggle="tooltip"
                                           title="Update Status">
                                            <i class="ti ti-edit-circle f-18"></i>
                                        </a>
                                        @endif
                                        @if(Auth::user()->u_type == 2)
                                        <button class="avtar avtar-xs btn-link-danger btn-pc-default deleteBtn"
                                                data-id="{{ $row->id }}"
                                                data-bs-toggle="tooltip"
                                                title="Delete Application">
                                            <i class="ti ti-trash f-18"></i>
                                        </button>
                                        @endif
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


<div class="modal fade" id="statusAppModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="statusForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Application Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="record_id">
                    <input type="hidden" id="table_flag">

                    <div class="mb-3">
                        <label>Application Status</label>
                        <select class="form-control" id="app_status">
                            <option value="Pending">Pending</option>
                            <option value="Processed">Processed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Process Date</label>
                        <input type="date" class="form-control" id="process_date">
                    </div>

                    <div class="mb-3">
                        <label>Payment Status</label>
						<select class="form-control" name="payment_status" id="payment_status">
                            <option value="">Select</option>
                            <option value="Full">Full</option>
                            <option value="Advance">Advance</option>
                        </select>
                    </div>

                    <div id="statusError" class="text-danger d-none"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>

	//Update status
	function openStatusModal(id, table) {
		$('#record_id').val(id);
		$('#table_flag').val(table);

		$('#statusAppModal').modal('show');

		// Optional: fetch existing data
		$.get(`/common/get-status/${table}/${id}`, function(res){
			if(res.status){
				$('#app_status').val(res.data.app_status);
				$('#process_date').val(res.data.process_date);
				$('#payment_status').val(res.data.payment_status);
			}
		});
	}
	
	$('#statusForm').submit(function(e){
		e.preventDefault();

		let data = {
			_token: '{{ csrf_token() }}',
			id: $('#record_id').val(),
			table: $('#table_flag').val(),
			app_status: $('#app_status').val(),
			process_date: $('#process_date').val(),
			payment_status: $('#payment_status').val()
		};

		$.post('/common/update-status', data, function(res){
			if(res.status){
				$('#statusAppModal').modal('hide');
				showToast('Updated successfully', 'success');
				location.reload();
			} else {
				$('#statusError').removeClass('d-none').text(res.message);
			}
		});
	});

    
	$('.deleteBtn').click(function(){
		if(!confirm('Delete this record?')) return;

		let id = $(this).data('id');

		$.post('/startup-filing/delete',{
			_token:'{{ csrf_token() }}',
			id:id
		},function(res){
			if(res.success){
				$('#row'+id).remove();
				showToast(res.message,'success');
			}
		});
	});

function startStartupFilingListTour() {
    if (typeof introJs !== 'function') return;

    let steps = [
        {
            title: 'Incubator Applications Guide',
            intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-rocket" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Review incubator application logs, filing states, payment details, and processed status indicators.</p></div>'
        }
    ];

    if (document.querySelector('.tour-apply-btn')) {
        steps.push({
            element: '.tour-apply-btn',
            title: 'Apply New Service',
            intro: 'Click here to start a new Startup Incubator Service engagement.'
        });
    }

    steps.push({
        element: '.table-responsive',
        title: 'Applications Table',
        intro: 'Review business name, founder details, application statuses, and process dates.'
    });

    introJs().setOptions({
        steps: steps,
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
    $('#start-startup-filing-list-tour').on('click', function(e) {
        e.preventDefault();
        startStartupFilingListTour();
    });
});
</script>
@endsection