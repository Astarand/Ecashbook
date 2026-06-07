@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Task Wise Quote Set</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('ca.AddQuote') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add New Quote</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-12">
        <div class="card table-card">
          <div class="card-header d-flex align-items-center justify-content-between pt-4 pb-3">
            <h3 class="mb-0">Quote Set</h3>
          </div>
          <div class="card-body pt-2 pb-4">
            <div class="table-responsive">
              <table class="table table-hover" id="pc-dt-simple">
                <thead>
                  <tr class="text-center">
                    <th>#</th>
                    <th>Task Category</th>
                    <th>Goverment Fees</th>
                    <th>Services Charges</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                @foreach ($quotes as $val)
                    <tr class="text-center">
                        <td><?php echo $i++; ?></td>
                        <td>{{$val->category_name}}</td>
                        <td>₹{{$val->govfee}}</td>
                        <td>₹{{$val->service_charge}}</td>
                        <td>
                            {{-- <a href="{{ url('/view-quote/'.base64_encode($val->id)) }}" class="avtar avtar-xs btn-link-secondary">
                            <i class="ti ti-eye f-20"></i>
                            </a> --}}
                            <a href="{{ url('/edit-quote/'.base64_encode($val->id)) }}" class="avtar avtar-xs btn-link-secondary">
                            <i class="ti ti-edit f-20"></i>
                            </a>
                            <a href="#" data-id="{{$val->id}}" class="avtar avtar-xs btn-link-secondary delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
                            <i class="ti ti-trash f-20"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Quote</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" id="del_quot" data-id="" class="w-100 btn btn-primary">
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
    let deleteId = null; // Store the ID of the Carriage to be deleted

    // Capture the Carriage ID when the delete button is clicked
    $('.delete-btn').on('click', function () {
        deleteId = $(this).data('id'); 
        // alert(deleteId);
    });
   // alert(deleteId);
    // Handle the delete confirmation
    $('#del_quot').on('click', function () {
			if (deleteId) {
				$.ajaxSetup({
					headers: {
						"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
					},
				});
		
				$.ajax({
					url: '/delQuote',
					type: 'POST',
					data: { id: deleteId },
					success: function (response) {
						showToast(response.message, response.class); // Show toast
						setTimeout(function () {
							window.location.href = response.redirect;
						}, 2000);
					},
					error: function (xhr) {
						showToast("Error deleting Quote!", 'error');
					}
				});
			}
		});

});
</script>

@endsection