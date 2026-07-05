@extends('App.Layout')

@section('container')

<style>
    /* Premium GST Verification Styling */
    .gst-verify-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    .gst-verify-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06) !important;
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
    #taxProfileTable tr td:first-child {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        width: 30%;
    }
    #taxProfileTable tr td i {
        color: #4f46e5;
        margin-right: 6px;
        font-size: 1.1rem;
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
                            <li class="breadcrumb-item active" aria-current="page">GSTIN Verification</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-og-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">GSTIN Verification</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="col-12 mb-4">
        <div class="card gst-verify-card gst-input-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="card-body p-4">
                <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">Verify Taxpayer GSTIN Number <span class="text-danger">*</span></label>
                <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden; border: 1px solid #cbd5e1; background-color: #fff;">
                    <span class="input-group-text bg-white border-0" style="height: 52px; padding-left: 18px; padding-right: 10px;">
                        <i class="ti ti-search text-muted f-20"></i>
                    </span>
                    <input type="text" id="inputGst" class="form-control border-0 px-2" placeholder="Enter 15-digit GSTIN (e.g. 27AAAAA1111A1Z1)" style="height: 52px; font-weight: 500; font-size: 1rem; letter-spacing: 0.5px; outline: none; box-shadow: none;">
                    <button class="btn btn-primary px-4 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnGetGst" type="button" style="height: 52px; background-color: #4f46e5; border: 0; font-size: 1rem; border-radius: 0 6px 6px 0;">
                        <i class="ti ti-cloud-download f-20"></i> Get Details
                    </button>
                </div>
                <div class="form-text text-muted mt-2"><i class="ti ti-info-circle"></i> Enter the 15-character Goods and Services Tax Identification Number for real-time verification.</div>
            </div>
        </div>
    </div>
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card gst-verify-card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-pills-gst nav-justified">
                        <li class="nav-item" data-target-form="#gstDetails">
                            <a href="#gstProfile" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">GSTIN Profile</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#gstReturn">
                            <a href="#gstReturnStatus" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-file-arrow-down"></i>
                                <span class="d-none d-sm-inline">GST Return Status</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card gst-verify-card gst-content-card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="gstProfile">
                            <div class="col-md-12 mx-auto">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <colgroup>
                                            <col style="width: 30%;">
                                            <col style="width: 70%;">
                                        </colgroup>
                                        <tbody id="taxProfileTable">
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-identification-card"></i> GSTIN of the Tax Payer</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-buildings"></i> Legal Name of the Business</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-map-pin"></i> Principal Place of Business</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-map-trifold"></i> Additional Place of Business</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-map-pin"></i> State Jurisdiction</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-map-pin-line"></i> Center Jurisdiction</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-calendar"></i> Date of Registration</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-briefcase"></i> Constitution of Business</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-user-square"></i> Taxpayer Type</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-check-square-offset"></i> GSTIN Status</strong></td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td><strong><i class="ph-duotone ph-calendar"></i> Date of Cancellation</strong></td>
                                                <td>-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class="tab-pane" id="gstReturnStatus">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-4">
                                    <div class="page-header-title">
                                        <h5 class="mb-0">Choose Financial Year</h5>
                                    </div>
                                </div>
                                <div class="col-md-8 ">
                                    <div class="row text-end">
                                        <div class="col-md-9"></div>
                                        <div class="col-md-3">
                                            <select class="form-control error" name="gst" id="selectGstReturn" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <!--<option label="Select Year"></option>
                                                <option value="2024-25">2024-25</option>
                                                <option value="2023-24">2023-24</option>-->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table tbl-product taxReturnTable" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th class="text-end">#</th>
                                        <th>Valid</th>
                                        <th>Mode Of Filing</th>
                                        <th>Date of Filing</th>
                                        <th>Return Period</th>
                                        <th>Return Type</th>
                                        <th>ARN</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

$(document).ready(function() {
    // Starting year and number of years to show
    let startYear = 2023;
    let numberOfYears = 20; // how many options you want
    let $dropdown = $('#selectGstReturn');
    for (let i = 0; i < numberOfYears; i++) {
        let year1 = startYear + i;
        let year2 = (startYear + i + 1) % 100; // last 2 digits
        let financialYear = `${year1}-${year2.toString().padStart(2, '0')}`;
        $dropdown.append(`<option value="${financialYear}">${financialYear}</option>`);
    }
});

function isRequired(inputId) {
    var value = $.trim($('#' + inputId).val()); // remove extra spaces

    if (value === "") {
        $('#' + inputId).css('border', '1px solid red');
        return false;
    } else {
        $('#' + inputId).css('border', '');
        return true;
    }
}

document.addEventListener("DOMContentLoaded", function() {
	
	$("#selectGstReturn").on('change', function(e) {
		var period = $(this).val(); 
		let base_url = $('#base_url').val(); 
		let gstin = $('#inputGst').val(); 
		var return_type = "";
		if (isRequired('inputGst')) {
			getGstReturnData(gstin,period,return_type);
		}else{
			alert("Please enter GST No.");
		}
	});
	
	$('#btnGetGst').on('click', function(e) {
        
		let base_url = $('#base_url').val(); 
		let gstin = $('#inputGst').val(); 
		var period = $('#selectGstReturn option:selected').val();
		var return_type = "";
		
		if (isRequired('inputGst')) {
			$('#loader').show();
			$.ajax({
				url: base_url + '/gst/profile',
				type: 'POST',
				data: {'gstin': gstin},
				success: function(data) {
					$('#loader').hide();
					if (data && data.data) {
						let d = data.data;
						let address = "";

						if (d.pradr && d.pradr.addr) {
							const a = d.pradr.addr;
							address = `
								${a.bno || ''} ${a.bnm || ''}, ${a.flno || ''}, ${a.st || ''}, ${a.loc || ''}, ${a.dst || ''}, ${a.stcd || ''} - ${a.pncd || ''}
							`;
						}
						let html =`
						<tr>
							<td><strong><i class="ph-duotone ph-identification-card"></i> GSTIN of the Tax Payer</strong></td>
							<td>${d.gstin}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-buildings"></i> Legal Name of the Business</strong></td>
							<td>${d.lgnm}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-pin"></i> Principal Place of Business</strong></td>
							<td>${address}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-trifold"></i> Additional Place of Business</strong></td>
							<td>${address}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-pin"></i> State Jurisdiction</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-pin-line"></i> Center Jurisdiction</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-calendar"></i> Date of Registration</strong></td>
							<td>${d.rgdt}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-briefcase"></i> Constitution of Business</strong></td>
							<td>${d.ctb}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-user-square"></i> Taxpayer Type</strong></td>
							<td>${d.dty}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-check-square-offset"></i> GSTIN Status</strong></td>
							<td>${d.sts}</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-calendar"></i> Date of Cancellation</strong></td>
							<td>-</td>
						</tr>`;
						$('#taxProfileTable').html(html);
					}else{
						alert("GST details is not found, Please check you GST no.");
						let html =`
						<tr>
							<td><strong><i class="ph-duotone ph-identification-card"></i> GSTIN of the Tax Payer</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-buildings"></i> Legal Name of the Business</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-pin"></i> Principal Place of Business</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-trifold"></i> Additional Place of Business</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-pin"></i> State Jurisdiction</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-map-pin-line"></i> Center Jurisdiction</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-calendar"></i> Date of Registration</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-briefcase"></i> Constitution of Business</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-user-square"></i> Taxpayer Type</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-check-square-offset"></i> GSTIN Status</strong></td>
							<td>-</td>
						</tr>
						<tr>
							<td><strong><i class="ph-duotone ph-calendar"></i> Date of Cancellation</strong></td>
							<td>-</td>
						</tr>`;
						$('#taxProfileTable').html(html);
					}
					
				},
				error: function(xhr) {
					
				}
			});
			
			
			getGstReturnData(gstin,period,return_type);
		}else{
			alert("Please enter GST No.");
		}
	});

});



function getGstReturnData(gstin,period,return_type){
	let base_url = $('#base_url').val(); 
	$('#loader').show();
	$.ajax({
			url: base_url + '/gst/return-status',
			type: 'POST',
			data: {'gstin': gstin,'period': period,'return_type': return_type},
			dataType: 'json',
			success: function(response) {
				$('#loader').hide();
				let table = $('.taxReturnTable');
				table.find('tbody').empty();
				if (response && response.data) {
					
					$.each(response.data.EFiledlist, function (index, item) {
						let valid = item.valid ?? '';
						let colorClass = (item.status === 'Filed') ? 'bg-success' : 'bg-danger';
						let row = `
							<tr>
								<td class="text-end">${index + 1}</td>
								<td><span class="text-muted text-hover-primary">${valid}</span></td>
								<td><a class="text-muted text-hover-primary">${item.mof}</a></td>
								<td><a class="text-muted text-hover-primary">${item.dof}</a></td>
								<td><span class="text-muted text-hover-primary">${item.ret_prd}</span></td>
								<td><span class="text-muted text-hover-primary">${item.rtntype}</span></td>
								<td><span class="text-muted text-hover-primary">${item.arn}</span></td>
								<td><span class="badge ${colorClass}">${item.status}</span></td>
							</tr>`;
						 table.find('tbody').append(row);
					});
				}else{
					table.find('tbody').append(`<tr><td colspan="6">No data found</td></tr>`);
				}
				
			},
			error: function(xhr) {
				
			}
		});
}

function startOgTour() {
    if (typeof introJs !== 'function') return;

    introJs().setOptions({
        steps: [
            {
                title: 'GSTIN Verification Guide',
                intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-zoom-in" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Search and verify details for any third-party GSTIN taxpayer via live GST portal APIs.</p></div>'
            },
            {
                element: '.gst-input-card',
                title: 'Search Panel',
                intro: 'Enter a valid GSTIN registration number and click "Get Details" to retrieve real-time data.'
            },
            {
                element: '.nav-pills-gst',
                title: 'Navigation Tabs',
                intro: 'Toggle between the taxpayer Profile details and filing History return list.'
            },
            {
                element: '.gst-content-card',
                title: 'Data Worksheet',
                intro: 'Review verified taxpayer business address, registration dates, constitution, and historical return filings (GSTR-1, GSTR-3B) with ARNs and filing dates.'
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
    $('#start-og-tour').on('click', function(e) {
        e.preventDefault();
        startOgTour();
    });
});
</script>
