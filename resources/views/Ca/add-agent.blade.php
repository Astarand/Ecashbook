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
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/agent-list') }}">Agent & Channel Partner</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Agent</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-agent-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add Agent / Channel Partner</h2>
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
                        <!-- START: Define your tab pans here -->
                        <div class="tab-pane show active" id="personalDetail">
                        <form action="javascript:void(0);" method="post" name="addAgentFrm" id="addAgentFrm"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id" id="agentId" value="">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col-sm-auto text-center">
                                        <div class="position-relative me-3 d-inline-flex">
                                            <!-- Hidden file input -->
                                            <input type="file" id="fileInput" name="agent_image" class="d-none" accept="image/*">
                                            
                                            <!-- Pencil Button -->
                                            <div class="position-absolute top-50 start-100 translate-middle">
                                                <button type="button" class="btn btn-sm btn-primary btn-icon"
                                                    onclick="document.getElementById('fileInput').click();">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Image Preview --> 
                                            <img id="agentImagePreview" src="{{ asset('storage/profile/e-cashbook.png') }}" alt="user-image"
                                                class="wid-150 rounded img-fluid ms-2">
                                        </div>
                                    </div>
                                    
                
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Agent Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required name="agent_name" id="agent_name" class="form-control"
                                                    placeholder="Agent Name">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Contact Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required name="agent_phone" id="agent_phone" class="form-control"
                                                    placeholder="Enter Contact Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" required name="agent_email" id="agent_email" class="form-control"
                                                    placeholder="Enter Email Address">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Agent Whatsapp Number<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" required name="agent_whats_no" id="agent_whats_no" class="form-control"
                                                    placeholder="Enter Agent Whatsapp Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name</label>
                                                <input type="text" name="company_name" id="company_name" class="form-control"
                                                    placeholder="Enter Company Name">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Website</label>
                                                <input type="text" name="company_website" id="company_website" class="form-control"
                                                    placeholder="Enter website">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                                </div>
                            <!-- end contact detail tab pane -->
                            <div class=" tab-pane" id="address">
                            <!-- <form action="javascript:void(0);" method="post" name="addAgentaddressFrm" id="addAgentaddressFrm" enctype="multipart/form-data">-->
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                                        <h5>Permanent Address</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                <input type="text" required name="address_lineone" id="address_lineone" class="form-control"
                                                    placeholder="Enter Address Line 1">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2</label>
                                                <input type="text" name="address_linetwo" id="address_linetwo" class="form-control"
                                                    placeholder="Enter Address Line 2">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                <select class="form-control" required name="agent_state" id="agent_state"
                                                    onChange="changeState(this);">
                                                    <option value="">Select State</option>
                                                    @foreach($states as $k => $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach
                
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label">City <span class="text-danger">*</span></label>
                                                <select class="form-control" required name="agent_city" id="city">
                                                    <option value="">Select City</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                <input type="text" required name="agent_pincode" id="agent_pincode" class="form-control"
                                                    placeholder="Enter Zip Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-lg-6 col-sm-12">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5>Current Address</h5>
                                         <div class="btn btn-primary" id="copy-permanent-address-btn" onclick="copyParamanentAddress()">Same as Permanent Address</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1</label>
                                                <input type="text" name="curraddress_lineone" id="curraddress_lineone"
                                                    class="form-control" placeholder="Enter Address Line 1">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2</label>
                                                <input type="text" name="curraddress_linetwo" id="curraddress_linetwo"
                                                    class="form-control" placeholder="Enter Address Line 2">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label">State</label>
                                                <select class="form-control" name="curragent_state" id="curr_state"
                                                    onChange="changeState_curr(this);">
                                                    <option value="">Select State</option>
                                                    @foreach($states as $k => $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                    @endforeach
                
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label">City</label>
                                                <select class="form-control" name="curragent_city" id="curr_city">
                                                    <option value="">Select City</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-0">
                                                <label class="form-label">Zip Code</label>
                                                <input type="text" name="curragent_pincode" id="curragent_pincode" class="form-control"
                                                    placeholder="Enter Zip Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);"
                                            class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <button type='submit' id="nxtBtnVThree"
                                            class="btn btn-primary d-flex align-items-center justify-content-center">
                                            Add Agent <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                
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
    function startAddAgentTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add New Agent / Partner',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-user-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Input personal profile details and addresses to register a new partner.</p></div>'
                },
                {
                    element: '#personalDetail',
                    title: 'Agent Details Form',
                    intro: 'Provide the agent name, contact number, email, and company details here.'
                },
                {
                    element: '#address',
                    title: 'Address Information',
                    intro: 'Define the permanent and current addresses. Use the copy shortcut if they are identical.'
                },
                {
                    element: '#copy-permanent-address-btn',
                    title: 'Address Shortcut',
                    intro: 'Click here to copy the permanent address to the current address fields automatically.'
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
        }).onbeforechange(function(targetElement) {
            if (targetElement.id === 'personalDetail') {
                // Switch to agent details tab
                document.querySelector('.nav-pills .nav-link[href="#personalDetail"]').click();
            } else if (targetElement.id === 'address' || targetElement.id === 'copy-permanent-address-btn') {
                // Switch to address tab
                document.querySelector('.nav-pills .nav-link[href="#address"]').click();
            }
        }).start();
    }

    $(document).ready(function() {
        $('#start-add-agent-tour').on('click', function(e) {
            e.preventDefault();
            startAddAgentTour();
        });
    });
    document.getElementById('fileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const img = document.querySelector('.wid-150');
            img.src = URL.createObjectURL(file);
        }
    });

        //Add Employee Copy from Parmanent Address
        function copyParamanentAddress() {
            // Copy address lines and zip code
            document.getElementById("curraddress_lineone").value = document.getElementById("address_lineone").value;
            document.getElementById("curraddress_linetwo").value = document.getElementById("address_linetwo").value;
            document.getElementById("curragent_pincode").value = document.getElementById("agent_pincode").value;

            // Copy state and trigger AJAX to load cities
            const permanentState = document.getElementById("agent_state").value;
            document.getElementById("curr_state").value = permanentState;

            // Now trigger AJAX to load current cities
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });

            $.ajax({
                url: $("#base_url").val() + "/getCity?" + permanentState,
                dataType: "json",
                data: { id: permanentState },
                success: function (data) {
                    let str = '<option value="">Select City</option>';
                    $.each(data, function (idx, item) {
                        str += '<option value="' + item.id + '">' + item.name + '</option>';
                    });
                    $("#curr_city").html(str);

                    // After cities are populated, copy city value
                    const permanentCity = document.getElementById("city").value;
                    document.getElementById("curr_city").value = permanentCity;
                }
            });
        }


    document.addEventListener('DOMContentLoaded', function () {
        // Handle Next button click - FIXED
        document.querySelector('.next-btn').addEventListener('click', function () {
            // Remove active class from current tab
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            
            // Add active class to address tab
            document.querySelector('.nav-pills .nav-link[href="#address"]').classList.add('active');
            document.querySelector('#address').classList.add('show', 'active');
        });

        // Handle Previous button click
        document.querySelector('.previous-btn').addEventListener('click', function () {
            // Remove active class from current tab
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            
            // Add active class to personal details tab
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