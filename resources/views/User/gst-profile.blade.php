@extends('App.Layout')

@section('container')

<style>
    /* Premium GST Profile Styling */
    .gst-profile-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    .gst-profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
    }
    .customer-widget-icon {
        width: 48px !important;
        height: 48px !important;
        background-color: #f1f5f9 !important;
        color: #4f46e5 !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.5rem !important;
        transition: all 0.2s ease !important;
    }
    .customer-details:hover .customer-widget-icon {
        background-color: #e0e7ff !important;
        color: #4338ca !important;
        transform: scale(1.05);
    }
    .customer-details-cont h6 {
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #64748b !important;
        margin-bottom: 4px !important;
        font-weight: 700 !important;
    }
    .customer-details-cont h5 {
        font-size: 0.85rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #475569 !important;
        margin-bottom: 4px !important;
        font-weight: 700 !important;
    }
    .customer-details-cont p {
        font-size: 0.95rem !important;
        color: #1e293b !important;
        font-weight: 600 !important;
        margin-bottom: 0 !important;
    }
    .nav-pills-gst .nav-link {
        color: #475569 !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        padding: 0.6rem 1.2rem !important;
        transition: all 0.2s ease !important;
        border: 1px solid transparent !important;
    }
    .nav-pills-gst .nav-link.active {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }
</style>

<div class="pc-content">

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                            <li class="breadcrumb-item"><a href="#">GST Management & Returns</a></li>
                            <li class="breadcrumb-item active" aria-current="page">GST Profile & Registration</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-gst-profile-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
                        </a>
                    </div>
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
            <div class="card gst-profile-card gst-summary-card">
				@if ($status_cd == '1')
                    <div class="alert alert-success d-flex align-items-center gap-3 m-4 border-0" style="background-color: #ecfdf5; border-left: 5px solid #10b981 !important; border-radius: 8px; box-shadow: 0 2px 8px rgba(16,185,129,0.08);">
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="ti ti-circle-check f-22"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-success fw-bold">Success</h6>
                            <p class="mb-0 text-success-800 small">{{ $status_desc }}</p>
                        </div>
                    </div>
				@endif
				@if ($status_cd == '0')
                    <div class="alert alert-danger d-flex align-items-center gap-3 m-4 border-0" style="background-color: #fef2f2; border-left: 5px solid #ef4444 !important; border-radius: 8px; box-shadow: 0 2px 8px rgba(239,68,68,0.08);">
                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="ti ti-circle-x f-22"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-danger fw-bold">Verification Notice</h6>
                            <p class="mb-0 text-danger-800 small">{{ $status_desc }}</p>
                        </div>
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
    <div id="employeeEnroll" class="form-wizard row justify-content-center mt-3">
        <div class="col-12">
            <div class="card gst-profile-card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-pills-gst nav-justified">
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
            <div class="card gst-profile-card">
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

@section('page-script')
<script>
    function startGstProfileTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'GST Profile & Registration Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-receipt" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the GST Profile & Registration statement. Here you can view verified taxpayer credentials retrieved directly from the GST Portal API.</p></div>'
                },
                {
                    element: '.gst-summary-card',
                    title: 'GSTIN Summary Credentials',
                    intro: 'This panel showcases core registration details: Legal Name, Trade Name, GSTIN number, PAN number, Date of Registration, and Primary Business Activity type.'
                },
                {
                    element: '.nav-pills-gst',
                    title: 'Contact & Banking Tabs',
                    intro: 'Toggle between "Address & Contact Information" and "Banking Details" to view corresponding sub-sections.'
                },
                {
                    element: '#gstProfile',
                    title: 'Registered Office & Contact Details',
                    intro: 'View office addresses, secondary additional place records, business email contact, and authorized signatory details.'
                },
                {
                    element: 'a[href="#gstReturnStatus"]',
                    title: 'Banking Profile Details',
                    intro: 'Switch to the Banking Details tab to view configured bank names, accounts, holder names, branch names, IFSC codes, and UPI configurations.'
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
        $('#start-gst-profile-tour').on('click', function(e) {
            e.preventDefault();
            startGstProfileTour();
        });
    });
</script>
@endsection
