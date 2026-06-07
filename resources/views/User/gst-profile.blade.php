@extends('App.Layout')

@section('container')
<?php //echo "<pre>"; print_r($jsonData);exit;	 ?>
<?php //echo "<pre>"; print_r($compData);exit;	 ?>
<?php //echo ($status_desc);exit;	 ?>
<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item"><a href="#">GST Management & Returns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">GST Profile & Registration</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">GST Profile & Registration</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
				@if ($status_cd == '1')
					<div style="color: green; font-weight: bold;text-align:center">
						{{ $status_desc }}
					</div>
				@endif
				@if ($status_cd == '0')
					<div style="color: red; font-weight: bold;text-align:center">
						{{ $status_desc }}
					</div>
				@endif
                <div class="card-body">
                    <div class="row gy-3 align-items-center">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-buildings"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Legal Name of Business </h6>
                                        <p> @if(array_key_exists('lgnm', $jsonData)) {{ $jsonData['lgnm'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-flow-arrow"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Trade Name</h6>
                                        <p>@if(array_key_exists('tradeNam', $jsonData)) {{ $jsonData['tradeNam'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-address-book"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>GSTIN Number</h6>
                                        <p>@if(array_key_exists('gstin', $jsonData)) {{ $jsonData['gstin'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-identification-card"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>PAN Number</h6>
                                        <p>{{ $compData->comp_pan_no }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-bank"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Constitution of Business</h6>
                                        <p>@if(array_key_exists('stj', $jsonData)) {{ $jsonData['stj'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-calendar"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Date of GST Registration</h6>
                                        <p>@if(array_key_exists('rgdt', $jsonData)) {{ $jsonData['rgdt'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-arrows-counter-clockwise"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Type of GST Registration</h6>
                                        <p>@if(array_key_exists('dty', $jsonData)) {{ $jsonData['dty'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="customer-details">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="customer-widget-icon">
                                        <i class="ph-duotone ph-hand-coins"></i>
                                    </span>
                                    <div class="customer-details-cont">
                                        <h6>Primary Business Activity</h6>
                                        <p>@if(array_key_exists('ctb', $jsonData)) {{ $jsonData['ctb'] }} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item" data-target-form="#gstDetails">
                            <a href="#gstProfile" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Address & Contact Information</span>
                            </a>
                        </li>
                        <li class="nav-item" data-target-form="#gstReturn">
                            <a href="#gstReturnStatus" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-piggy-bank"></i>
                                <span class="d-none d-sm-inline">Banking Details</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="gstProfile">
                            <div class="col-md-12">
                                <div class="row gy-3 align-items-center">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                        <div class="customer-details">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-envelope-simple-open"></i>
                                                </span>
                                                <div class="customer-details-cont">
                                                    <h5>Business Email ID</h5>
                                                    <p>{{ $compData->comp_email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                        <div class="customer-details">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-phone"></i>
                                                </span>
                                                <div class="customer-details-cont">
                                                    <h5>Contact Number</h5>
                                                    <p>{{ $compData->comp_phone }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-8 col-sm-10">
                                        <div class="customer-details">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-user-list"></i>
                                                </span>
                                                <div class="customer-details-cont w-100">
                                                    <h6>Authorized Signatory Details</h6>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <p class="mb-0">{{ $compData->comp_name }}</p>
                                                        <p class="mb-0">{{ $compData->comp_phone }}</p>
                                                        <p class="mb-0">{{ $compData->comp_website }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="customer-widget-icon">
                                                <i class="ph-duotone ph-map-pin"></i>
                                            </span>
                                            <div>
                                                <h5>Registered Office Address</h5>
                                                <p class="mb-0">
												@if(array_key_exists('pradr', $jsonData)) 
													{{ $jsonData['pradr']['addr']['bnm'].','.$jsonData['pradr']['addr']['st']
														.','.$jsonData['pradr']['addr']['loc'].','.$jsonData['pradr']['addr']['pncd'] }} 
												@endif</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="customer-widget-icon">
                                                <i class="ph-duotone ph-buildings"></i>
                                            </span>
                                            <div>
                                                <h5>Additional Places of Business</h5>
                                                <p class="mb-0">
												@if(array_key_exists('pradr', $jsonData)) 
													{{ $jsonData['pradr']['addr']['bnm'].','.$jsonData['pradr']['addr']['st']
														.','.$jsonData['pradr']['addr']['loc'].','.$jsonData['pradr']['addr']['pncd'] }} 
												@endif</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class="tab-pane" id="gstReturnStatus">
							@foreach($bankData as $val)
                            <div class="card-body">
                                <div class="col-md-12">
									
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-bank"></i>
                                                </span>
                                                <div>
                                                    <h5>Bank Name</h5>
                                                    <p class="mb-0">{{ $val->bank_name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-credit-card"></i>
                                                </span>
                                                <div>
                                                    <h5>Account Number</h5>
                                                    <p class="mb-0">{{ $val->ac_no }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-user-circle"></i>
                                                </span>
                                                <div>
                                                    <h5>Account Holder Name</h5>
                                                    <p class="mb-0">{{ $val->bank_holder_name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-barcode"></i>
                                                </span>
                                                <div>
                                                    <h5>IFSC Code</h5>
                                                    <p class="mb-0">{{ $val->ifsc_code }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-storefront"></i>
                                                </span>
                                                <div>
                                                    <h5>Branch</h5>
                                                    <p class="mb-0">{{ $val->bank_branch }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="customer-widget-icon">
                                                    <i class="ph-duotone ph-qr-code"></i>
                                                </span>
                                                <div>
                                                    <h5>UPI ID</h5>
                                                    <p class="mb-0">{{ $val->ac_upid }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									
                                </div>
                            </div>
							@endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>
@endsection