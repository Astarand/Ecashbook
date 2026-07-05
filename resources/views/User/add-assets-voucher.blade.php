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
                        <li class="breadcrumb-item"><a href="#">Assets Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/assets-voucher-list') }}">Assets Voucher</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add Asset Voucher</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-assets-voucher-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add Asset Voucher</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-4">
        <h3>Add Asset Voucher</h3>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>Basic Details</h5>
        </div>
        <div class="card-body">
            <form action="javascript:void(0);" method="post" name="addAssetVoucherFrm" id="addAssetVoucherFrm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="vId" value="">
                @csrf
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 p-3 m-2">
                            <div class="form-check">
                                <input class="form-check-input" name="v_type" value="1" type="radio" name="assetVoucherDetils" id="inward">
                                <label class="form-check-label" for="inward">Inward</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 p-3 m-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="v_type" value="2" name="assetVoucherDetils" id="outward">
                                <label class="form-check-label" for="outward">Outward</label>
                            </div>
                        </div>
                    </div>
					<div class="col-sm-4 mb-3">
						<label class="form-label">Proprietorship Company</label>
						<select name="propId" class="form-control">
							<option value="">Select Company</option>
							@foreach($proprietorships as $company)
								<option value="{{ $company->id }}">
									{{ $company->comp_name }}
								</option>
							@endforeach
						</select>
					</div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Voucher Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="voucher_no" id="voucher_no" placeholder="Enter Voucher Number">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Voucher Name <span class="text-danger">*</span></label>
                        <input type="text" name="voucher_name" required id="voucher_name" class="form-control" placeholder="Enter Voucher Name">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Brunch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" required id="branch_name" class="form-control" placeholder="Enter Brunch Name">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Invoice Voucher Number <span class="text-danger">*</span></label>
                        <input type="text" name="inv_voucher_no" required id="inv_voucher_no" class="form-control" placeholder="Enter Invoice Voucher Number">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" name="invoice_date" required id="invoice_date" class="form-control" placeholder="Enter Date">
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Total Cost (INR) <span class="text-danger">*</span></label>
                        <input type="text" name="total_cost" required id="total_cost" class="form-control" placeholder="Enter Total Cost">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="designation_id">Series<span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <div class="form-group me-2" style="flex-grow: 1;">
                                <select class="select form-select" name="series_id" required id="series_id">
                                    <option value="">Select Series</option>
                                    @foreach($assetSeries as $k=>$series)
                                    <option value="{{ $series->id }}">{{ $series->series_name }}</option>
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
                                <select class="select form-select" required name="vendor_id" id="vendor_id" >
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $k=>$vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="{{ url('/add-vendor') }}" target="_blank">
                                <i class="ti ti-plus py-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="text-end btn-page mt-4">
                        {{-- <div class="btn btn-outline-secondary">Cancel</div> --}}
                        <a href="{{ url('/assets-voucher-list') }}" class="btn btn-outline-secondary">Cancel</a>
                        {{-- <div class="btn btn-primary">Save Changes</div> --}}
                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
    // $("form#addAssetVoucherFrm").bind("submit", function() {
    //     //e.preventDefault();

    //     // $("#addVocherLoader").show();
    //     var vId = $("#vId").val();
    //     if (vId == "") {
    //         var projurl = "/save_add_voucher";
    //     } else {
    //         var projurl = "/update_voucher";
    //     }
    //     var projectData = $("form#addAssetVoucherFrm").serialize();

    //     $.ajax({
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         url: projurl,
    //         type: "POST",
    //         data: projectData,
    //         success: function(response) {
    //             // $("#addVocherLoader").hide();
    //             if (response.class == "succ") {
    //                 // $("#addAssetVoucherFrm .message-container").html(
    //                 //     '<div class="' +
    //                 //         response.class +
    //                 //         '">' +
    //                 //         response.message +
    //                 //         "</div>"
    //                 // );
    //                 // window.location.href = response.redirect;

    //                 showToast(response.message, "success");
    //                 setTimeout(() => {
    //                     window.location.href = response.redirect;
    //                 }, 2000);
    //             } else {
    //                 $.each(response, function(idx, obj) {
    //                     // $("#addAssetVoucherFrm .message-container").html(
    //                     //     '<div class="err">' + obj + "</div>"
    //                     // );
    //                     showToast(obj, "error");
    //                 });
    //             }
    //         },
    //     });
    // });

    $("form#addAssetVoucherFrm").on("submit", function(e) {
        e.preventDefault();

        var vId = $("#vId").val();
        var projurl = (vId == "") ? "/save_add_voucher" : "/update_voucher";

        // 🔎 Get values
        var v_type = $("input[name='v_type']:checked").val();
        var voucher_no = $("#voucher_no").val().trim();
        var voucher_name = $("#voucher_name").val().trim();
        var branch_name = $("#branch_name").val().trim();
        var inv_voucher_no = $("#inv_voucher_no").val().trim();
        var invoice_date = $("#invoice_date").val().trim();
        var total_cost = $("#total_cost").val().trim();
        var series_id = $("#series_id").val();
        var vendor_id = $("#vendor_id").val();

        // 🔴 Required Validation
        if (!v_type) {
            showToast("Please select Voucher Type", "error");
            return false;
        }

        if (voucher_no === "") {
            showToast("Voucher Number is required", "error");
            $("#voucher_no").focus();
            return false;
        }

        if (voucher_name === "") {
            showToast("Voucher Name is required", "error");
            $("#voucher_name").focus();
            return false;
        }

        if (branch_name === "") {
            showToast("Branch Name is required", "error");
            $("#branch_name").focus();
            return false;
        }

        if (inv_voucher_no === "") {
            showToast("Invoice Voucher Number is required", "error");
            $("#inv_voucher_no").focus();
            return false;
        }

        if (invoice_date === "") {
            showToast("Invoice Date is required", "error");
            $("#invoice_date").focus();
            return false;
        }

        if (total_cost === "") {
            showToast("Total Cost is required", "error");
            $("#total_cost").focus();
            return false;
        }

        if (series_id === "") {
            showToast("Please select Series", "error");
            $("#series_id").focus();
            return false;
        }

        if (vendor_id === "") {
            showToast("Please select Vendor", "error");
            $("#vendor_id").focus();
            return false;
        }

        // ✅ If all valid, submit AJAX
        var projectData = $(this).serialize();
        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: projurl,
            type: "POST",
            data: projectData,
            success: function(response) {
                $("#loader").hide();
                if (response.class == "succ") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    showToast(response.message, "error");
                }
            },
            error: function() {
                    $("#loader").hide();
                showToast("Something went wrong", "error");
            },
        });

    });

    //------------- Add Assets Series -----------
    $("form#addSeriesFrm").on("submit", function(e) {
        e.preventDefault(); // stop normal form submit

        var seriesName = $("#series_name").val().trim();

        // Manual required validation
        if (seriesName === "") {
            showToast("Required Series Name", "error");
            $("#series_name").focus();
            return false;
        }
        //e.preventDefault();

        var addurl = "/save_add_series_name";
        var seriesData = $("form#addSeriesFrm").serialize();

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: addurl,
            type: "POST",
            data: seriesData,
            success: function(response) {
                if (response.class == "succ") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showToast(response.message, "error");
                }
            },
            // success: function(response) {
            //     if (response.class == "succ") {
            //         // window.location.reload();
            //         showToast("Series Add Successfully", "success");
            //         setTimeout(() => {
            //             window.location.reload();
            //         }, 2000);
            //     } else {
            //         $.each(response, function(idx, obj) {
            //             // $("#addSeriesFrm .message-container").html(
            //             //     '<div class="err">' + obj + "</div>"
            //             // );
            //             showToast("Error: While serise add", "error");
            //         });
            //     }
            // },
        });

    });

    function startAddAssetsVoucherTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add Asset Voucher Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Record purchase or depreciation entries for assets.</p></div>'
                },
                {
                    title: 'Add Asset Voucher',
                    intro: 'Record purchase or depreciation entries for assets.'
                },
                {
                    element: 'form', title: 'Voucher Details',
                    intro: 'Enter date, select asset, and input transaction details.'
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
        $('#start-add-assets-voucher-tour').on('click', function(e) {
            e.preventDefault();
            startAddAssetsVoucherTour();
        });
    });
</script>
@endsection