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
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Project / Job</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Project / Job</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item" data-target-form="#contactDetailForm">
                            <a href="#personalDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Agent Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#address" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-map-pin"></i>
                                <span class="d-none d-sm-inline">Address</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- START: Agent Details tab -->
                        <div class="tab-pane show active" id="personalDetail">
                            <form action="javascript:void(0);" method="post" id="addAgentFrm" enctype="multipart/form-data">
                                <input type="hidden" id="agentId" value="">
                                
                                <div class="row mt-4">
                                    <div class="col-sm-auto text-center">
                                        <div class="position-relative me-3 d-inline-flex">
                                            <div class="position-absolute top-50 start-100 translate-middle">
                                                <button type="button" class="btn btn-sm btn-primary btn-icon">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                            </div>
                                            <img src="../assets/images/application/img-prod-3.jpg" alt="user-image"
                                                class="wid-150 rounded img-fluid ms-2">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="agent_name">Agent Name <span class="text-danger">*</span></label>
                                                <input type="text" id="agent_name" class="form-control" placeholder="Agent Name">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="agent_phone">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" id="agent_phone" class="form-control" placeholder="Enter Contact Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="agent_email">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" id="agent_email" class="form-control" placeholder="Enter Email Address">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="agent_whats_no">Agent Whatsapp Number<span class="text-danger">*</span></label>
                                                <input type="number" id="agent_whats_no" class="form-control" placeholder="Enter Agent Whatsapp Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="company_name">Company Name</label>
                                                <input type="text" id="company_name" class="form-control" placeholder="Enter Company Name">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="company_website">Company Website</label>
                                                <input type="text" id="company_website" class="form-control" placeholder="Enter website">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
                                        <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                        </div>
                        <!-- END: Agent Details tab -->

                        <!-- START: Address tab -->
                        <div class="tab-pane" id="address">
                            <div class="row">
                                <!-- Permanent Address -->
                                <div class="col-lg-6 col-sm-12">
                                    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                                        <h5>Permanent Address</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="address_lineone">Address Line 1</label>
                                                <input type="text" id="address_lineone" class="form-control" placeholder="Enter Address Line 1">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="address_linetwo">Address Line 2</label>
                                                <input type="text" id="address_linetwo" class="form-control" placeholder="Enter Address Line 2">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label" for="agent_state">State</label>
                                                <select class="form-control" id="agent_state">
                                                    <option value="">Select State</option>
                                                    <option value="">State 1</option>
                                                    <option value="">State 2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label" for="city">City</label>
                                                <select class="form-control select-style" id="city">
                                                    <option value="">Select City</option>
                                                    <option value="">City 1</option>
                                                    <option value="">City 2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label" for="agent_pincode">Zip Code</label>
                                                <input type="text" id="agent_pincode" class="form-control" placeholder="Enter Zip Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Address -->
                                <div class="col-lg-6 col-sm-12">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5>Current Address</h5>
                                        <div class="btn btn-primary" onclick="copyParamanentAddress()">Same as Permanent Address</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="curraddress_lineone">Address Line 1</label>
                                                <input type="text" id="curraddress_lineone" class="form-control" placeholder="Enter Address Line 1">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="curraddress_linetwo">Address Line 2</label>
                                                <input type="text" id="curraddress_linetwo" class="form-control" placeholder="Enter Address Line 2">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label" for="curr_state">State</label>
                                                <select class="form-control" id="curr_state">
                                                    <option value="">Select State</option>
                                                    <option value="">State 1</option>
                                                    <option value="">State 2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label" for="curr_city">City</label>
                                                <select class="form-control" id="curr_city">
                                                    <option value="">Select City</option>
                                                    <option value="">City 1</option>
                                                    <option value="">City 2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label" for="curragent_pincode">Zip Code</label>
                                                <input type="text" id="curragent_pincode" class="form-control" placeholder="Enter Zip Code">
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
                                        <button type="button" id="nxtBtnVThree" class="btn btn-primary d-flex align-items-center justify-content-center">
                                            Update Agent <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                        <!-- END: Address tab -->
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>

<script>
    // Copy Permanent Address to Current Address (static, using IDs only)
    function copyParamanentAddress() {
        document.getElementById("curraddress_lineone").value =
            document.getElementById("address_lineone").value;
        document.getElementById("curraddress_linetwo").value =
            document.getElementById("address_linetwo").value;
        document.getElementById("curr_state").value =
            document.getElementById("agent_state").value;
        document.getElementById("curr_city").value =
            document.getElementById("city").value;
        document.getElementById("curragent_pincode").value =
            document.getElementById("agent_pincode").value;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Handle Next button click (go to Address tab)
        document.querySelector('.next-btn').addEventListener('click', function () {
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('.nav-pills .nav-link[href="#address"]').classList.add('active');
            document.querySelector('#address').classList.add('show', 'active');
        });

        // Handle Previous button click (back to Personal Details)
        document.querySelector('.previous-btn').addEventListener('click', function () {
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('.nav-pills .nav-link[href="#personalDetail"]').classList.add('active');
            document.querySelector('#personalDetail').classList.add('show', 'active');
        });

        // Handle "Other" checkbox click safely (if exists)
        const otherCheckbox = document.querySelector('#customCheckinlh15');
        if (otherCheckbox) {
            otherCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    document.querySelector('#otherInput').style.display = 'block';
                } else {
                    document.querySelector('#otherInput').style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
