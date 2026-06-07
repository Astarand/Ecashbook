@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item" data-target-form="#contactDetailForm">
                            <a href="#personalDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Company Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#access" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-check-circle"></i>
                                <span class="d-none d-sm-inline">Purpose of Attachment</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                    </ul>
                </div>
            </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="card">
       <div class="card-body">
         <div class="tab-content">
                        <!-- START: Define your tab pans here -->
        <div class="tab-pane show active" id="personalDetail">
        <form action="javascript:void(0);" method="post" name="addcustFrm" id="addcustFrm" enctype="multipart/form-data">
			<input type="hidden" name="id" id="custId" value="{{$client->userId}}">
			@csrf
            <div class="row mt-4">
                <div class="col">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Company Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="comp_name" id="comp_name" value="{{$client->comp_name}}" placeholder="Enter Company Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Company Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control"  name="comp_email" id="comp_email" value="{{$client->comp_email}}" placeholder="Enter Company Email">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="comp_phone" id="comp_phone" value="{{$client->comp_phone}}" placeholder="Enter Phone Number">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Company GST Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_no" id="gst_no" value="{{$client->gst_no}}" placeholder="Enter Company GST Number">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Company Website<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="comp_website" id="comp_website" value="{{$client->comp_website}}" placeholder="Enter Company Website">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Pan Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="comp_pan_no" id="comp_pan_no" value="{{$client->comp_pan_no}}" placeholder="Enter Pan Number">
                        </div>
                       <div class="mb-3 col-md-3">
                            <label class="form-label" for="inputEmail4">Business Agent Name<span class="text-danger">*</span></label>
                            <div class="form-group me-2">
                                <select class="select form-select" name="agent_name" id="agent_name">
									<option value="">Select Name</option>
									<option value=" ">None</option>
									@foreach($agents as $k=>$val)
											<option value="{{ $val->id }}" <?php echo ($client->id==$val->id)? "selected":"" ?>>{{ $val->agent_name }}</option>
										@endforeach
								</select>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>

                                <?php 
								$perposesDetails = isset($client->compincorp)?$client->compincorp:"";
								$perposesDetails = explode(',', $perposesDetails);
							?>
                            <div class="d-flex wizard justify-content-end mt-3">
                                <div class="last">
                                    <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                        Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                            <!-- end education detail tab pane -->
                        <div class="tab-pane" id="access">
                            <div class="d-flex flex-wrap justify-content-start">
                                <!-- Checkbox Groups -->
                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Company Incorporation', $perposesDetails)) { echo 'checked="checked"'; }?> value="Company Incorporation" id="customCheckinlh0">
                                        <label class="form-check-label" for="customCheckinlh0">Company Incorporation</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Company Compliances', $perposesDetails)) { echo 'checked="checked"'; }?>  value="Company Compliances" id="customCheckinlh1">
                                        <label class="form-check-label" for="customCheckinlh1">Company Compliances</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('ROC Return', $perposesDetails)) { echo 'checked="checked"'; }?>  value="ROC Return" id="customCheckinlh2">
                                        <label class="form-check-label" for="customCheckinlh2">ROC Return</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Accounts Preparation', $perposesDetails)) { echo 'checked="checked"'; }?> value="Accounts Preparation" id="customCheckinlh3">
                                        <label class="form-check-label" for="customCheckinlh3">Accounts Preparation</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('GST & Taxation', $perposesDetails)) { echo 'checked="checked"'; }?> value="GST & Taxation" id="customCheckinlh4">
                                        <label class="form-check-label" for="customCheckinlh4">GST & Taxation</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Auditing', $perposesDetails)) { echo 'checked="checked"'; }?> value="Auditing" id="customCheckinlh5">
                                        <label class="form-check-label" for="customCheckinlh5">Auditing</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Auditor Recruitment', $perposesDetails)) { echo 'checked="checked"'; }?> value="Auditor Recruitment" id="customCheckinlh6">
                                        <label class="form-check-label" for="customCheckinlh6">Auditor Recruitment</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Licensing & Registration', $perposesDetails)) { echo 'checked="checked"'; }?> value="Licensing & Registration" id="customCheckinlh7">
                                        <label class="form-check-label" for="customCheckinlh7">Licensing & Registration</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Income Tax Return', $perposesDetails)) { echo 'checked="checked"'; }?> value="Income Tax Return" id="customCheckinlh8">
                                        <label class="form-check-label" for="customCheckinlh8">Income Tax Return</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('TDS', $perposesDetails)) { echo 'checked="checked"'; }?> value="TDS" id="customCheckinlh9">
                                        <label class="form-check-label" for="customCheckinlh9">TDS</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('PF & ESIC', $perposesDetails)) { echo 'checked="checked"'; }?>  value="PF & ESIC"" id="customCheckinlh10">
                                        <label class="form-check-label" for="customCheckinlh10">PF & ESIC</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('P-tax', $perposesDetails)) { echo 'checked="checked"'; }?> value="P-tax" id="customCheckinlh11">
                                        <label class="form-check-label" for="customCheckinlh11">P-tax</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Project Report / DPR with CMA Data', $perposesDetails)) { echo 'checked="checked"'; }?> value="Project Report / DPR with CMA Data" id="customCheckinlh12">
                                        <label class="form-check-label" for="customCheckinlh12">Project Report / DPR with CMA Data</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Outsourcing of work', $perposesDetails)) { echo 'checked="checked"'; }?>  value="Outsourcing of work" id="customCheckinlh13">
                                        <label class="form-check-label" for="customCheckinlh13">Outsourcing of work</label>
                                    </div>
                                </div>

                                <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="compincorp[]" <?php if (in_array('Outsourcing of employee', $perposesDetails)) { echo 'checked="checked"'; }?> value="Outsourcing of employee" id="customCheckinlh14">
                                        <label class="form-check-label" for="customCheckinlh14">Outsourcing of employee</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex wizard justify-content-between mt-3">
                                <div class="first">
                                    <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                    </a>
                                </div>
                                <div class="last">
                                    <button type='submit' id="nxtBtnVThree" class="btn btn-primary d-flex align-items-center justify-content-center">
                                        Save changes <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
            </form>
       <!-- END: Define your tab pans here -->
       </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle Next button click
        document.querySelector('.next-btn').addEventListener('click', function () {
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('.nav-pills .nav-link[href="#access"]').classList.add('active');
            document.querySelector('#access').classList.add('show', 'active');
        });

        // Handle Previous button click
        document.querySelector('.previous-btn').addEventListener('click', function () {
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('.nav-pills .nav-link[href="#personalDetail"]').classList.add('active');
            document.querySelector('#personalDetail').classList.add('show', 'active');
        });

        // Handle "Other" checkbox click
        document.querySelector('#customCheckinlh15').addEventListener('change', function () {
            if (this.checked) {
                document.querySelector('#otherInput').style.display = 'block';
            } else {
                document.querySelector('#otherInput').style.display = 'none';
            }
        });
    });
</script>

@endsection
