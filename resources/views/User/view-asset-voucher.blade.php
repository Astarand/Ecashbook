@extends('App.Layout')

@section('container')

<div class="pc-content">

<ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Assets Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/assets-voucher-list') }}">Assets Voucher</a></li>
                        <li class="breadcrumb-item" aria-current="page">View Asset Voucher</li>
                    </ul>
    <div class="row mb-4">
        <h3>View Asset Voutcher</h3>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>Basic Details</h5>
        </div>
        <div class="card-body">
            <form action="javascript:void(0);" method="post" name="addAssetVoucherFrm" id="addAssetVoucherFrm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="vId" value="{{ $assetvoucher->id }}">
                @csrf
            <div class="row">

                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 p-3 m-2">
                            <div class="form-check">
                                <input class="form-check-input" name="v_type" value="1" type="radio" name="assetVoucherDetils" id="inward" <?php echo ($assetvoucher->v_type=='1')? "checked":"" ?>>
                                <label class="form-check-label" for="inward">Inward</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 p-3 m-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="v_type" value="2" name="assetVoucherDetils" id="outward" <?php echo ($assetvoucher->v_type=='2')? "checked":"" ?>>
                                <label class="form-check-label" for="outward">Outward</label>
                            </div>
                        </div>
                    </div>
					<div class="col-sm-4 mb-3">
						<label class="form-label">Proprietorship Company</label>
						<select name="propId" class="form-control">
							<option value="">Select Company</option>
							@foreach($proprietorships as $company)
								<option value="{{ $company->id }}" <?=($assetvoucher->propId == $company->id) ? 'selected' : '' ?>>
									{{ $company->comp_name }}
								</option>
							@endforeach
						</select>
					</div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Voucher Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="voucher_no" id="voucher_no" value="{{ $assetvoucher->voucher_no }}" placeholder="Enter Voucher Number">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Voucher Name <span class="text-danger">*</span></label>
                        <input type="text" name="voucher_name" id="voucher_name" class="form-control" value="{{ $assetvoucher->voucher_name }}" placeholder="Enter Voucher Name">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Brunch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" id="branch_name" class="form-control" value="{{ $assetvoucher->branch_name }}" placeholder="Enter Brunch Name">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Invoice Voucher Number <span class="text-danger">*</span></label>
                        <input type="text" name="inv_voucher_no" id="inv_voucher_no" class="form-control" value="{{ $assetvoucher->inv_voucher_no }}" placeholder="Enter Invoice Voucher Number">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ $assetvoucher->invoice_date }}" placeholder="Enter Date">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Total Cost (INR) <span class="text-danger">*</span></label>
                        <input type="text" name="total_cost" id="total_cost" value="{{ $assetvoucher->total_cost }}" class="form-control" placeholder="Enter Total Cost">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="designation_id">Series<span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <div class="form-group me-2" style="flex-grow: 1;">
                                <select class="select form-select" name="series_id" id="series_id">
                                    <option value="">Select Series</option>
                                    @foreach($assetSeries as $k=>$series)
                                    <option value="{{ $series->id }}" <?php echo ($assetvoucher->series_id==$series->id)? "selected":"" ?>>{{ $series->series_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="#" data-bs-toggle="modal" data-bs-target="#add_series">
                                <i class="ti ti-plus py-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="designation_id">Vendor<span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <div class="form-group me-2" style="flex-grow: 1;">
                                <select class="select form-select" name="vendor_id" id="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $k=>$vendor)
                                    <option value="{{ $vendor->id }}" <?php echo ($assetvoucher->vendor_id==$vendor->id)? "selected":"" ?>>{{ $vendor->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="{{ route('user.AddVendor') }}" target="_blank">
                                <i class="ti ti-plus py-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="text-end btn-page mt-4">
                        {{-- <div class="btn btn-outline-secondary">Cancel</div> --}}
                        <a href="{{ url('/assets-voutcher-list') }}" class="btn btn-outline-warning">Cancel</a>
                        {{-- <div class="btn btn-primary">Save Changes</div> --}}
                        {{-- <button type="submit" class="btn btn-primary">Update Voucher</button> --}}
                    </div>
                
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_series" tabindex="-1" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add New Series</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0);" method="post" name="addSeriesFrm" id="addSeriesFrm">
                @csrf
                {{-- <input type="hidden" name="_token" value="W0toh99gQub89hiQ1JDskqVuPKG9XUALbwOvPRIk"> --}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Add New Series <span class="text-danger">*</span></label>
                                <input type="text" required name="series_name" id="series_name" class="form-control" placeholder="Series Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="message-container"></div>
                    <div id="addEmployeeLoader" class="loader"></div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        document.querySelectorAll('input, textarea, select').forEach(el => {
			el.disabled = true;
		});

        //------------- Add Assets Series -----------
        $("form#addSeriesFrm").bind("submit", function (e) {
            e.preventDefault();
            var seriesName = $("#series_name").val().trim();

            // Manual required validation
            if (seriesName === "") {
                showToast("Required Series Name", "error");
                $("#series_name").focus();
                return false;
            }
            
            var addurl = "/save_add_series_name";
            var seriesData = $("form#addSeriesFrm").serialize();

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: addurl,
                type: "POST",
                data: seriesData,
                success: function (response) {
                    if (response.class == "succ") {
                        window.location.reload();
                    } else {
                        $.each(response, function (idx, obj) {
                            $("#addSeriesFrm .message-container").html(
                                '<div class="err">' + obj + "</div>"
                            );
                        });
                    }
                },
            });
            
        });

    </script>
@endsection