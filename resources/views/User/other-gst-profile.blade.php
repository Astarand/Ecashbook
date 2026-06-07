@extends('App.Layout')

@section('container')

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
                        <li class="breadcrumb-item active" aria-current="page">GSTIN Verification</li>
                    </ul>
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
    <div class="col--12">
        <div class="card">
            <div class="card-body">
                <div class="col-sm-12">
                    <label class="form-label">GST Number <span class="text-danger">*</span></label>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="inputGst" class="form-control" placeholder="Enter GST Number">
                            <button class="btn btn-primary" id="btnGetGst" type="button"><i class="ti ti-cloud-download align-middle"></i> Get Details</button>
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
            <div class="card">
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
</script>
