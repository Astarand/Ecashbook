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
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- START: Define your tab pans here -->
                        <div class="tab-pane show active" id="personalDetail">
                            <form action="javascript:void(0);" method="post" name="addcustFrm" id="addcustFrm"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id" id="custId" value="">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="comp_name" id="comp_name" class="form-control"
                                                    placeholder="Company Name" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Contact Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="comp_phone" id="comp_phone"
                                                    class="form-control" placeholder="Enter Contact Number" required
                                                    pattern="\d{10}" maxlength="10"
                                                    oninput="this.value=this.value.replace(/[^0-9]/g, '').slice(0,10);">

                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address<span
                                                        class="text-danger">*</span></label>
                                                <input type="email" name="comp_email" id="comp_email"
                                                    class="form-control" placeholder="Enter Email Address" required>
                                                <small id="email_check_msg" class="text-danger mt-1"
                                                    style="display: none;"></small>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Company GST Number</label>
                                                <input type="text" name="comp_gst_no" id="comp_gst_no"
                                                    class="form-control" placeholder="Enter Company GST Number"
                                                    pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"
                                                    maxlength="15"
                                                    title="Enter a valid 15-character GST number (e.g., 22AAAAA0000A1Z5)">

                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Company Website</label>
                                                <input type="text" name="comp_website" id="comp_website"
                                                    class="form-control" placeholder="Enter Company Website" >
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">PAN Number</label>
                                                <input type="text" name="comp_pan_no" id="comp_pan_no"
                                                    class="form-control" placeholder="Enter PAN Number"
                                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10"
                                                    title="Enter a valid 10-character PAN (e.g., ABCDE1234F)"
                                                    oninput="this.value = this.value.toUpperCase();">

                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Business Agent Name<span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group me-2">
                                                    <select class="select form-select" name="agent_name" id="agent_name"
                                                        required>
                                                        <option value="">Select Name</option>
                                                        @foreach($agents as $k=>$val)
                                                        <option value="{{ $val->id }}">{{ $val->agent_name }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
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
                                <!-- end education detail tab pane -->
                                <div class="tab-pane" id="access">
                                    <div class="d-flex flex-wrap justify-content-start">
                                        <!-- Checkbox Groups -->
                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Company Incorporation">
                                                <label class="form-check-label">Company Incorporation</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Company Compliances">
                                                <label class="form-check-label">Company Compliances</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="MCA / ROC Compliances">
                                                <label class="form-check-label">MCA / ROC Compliances</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Accounts Preparation">
                                                <label class="form-check-label">Accounts Preparation</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="GST & Filings">
                                                <label class="form-check-label">GST & Filings</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Auditing">
                                                <label class="form-check-label">Auditing</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Auditor Recruitment">
                                                <label class="form-check-label">Auditor Recruitment</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="MSME / Trade Licence">
                                                <label class="form-check-label">MSME / Trade Licence</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Licensing & Registration">
                                                <label class="form-check-label">Licensing & Registration</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Income Tax Filings">
                                                <label class="form-check-label">Income Tax Filings</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="TDS & Filing">
                                                <label class="form-check-label">TDS & Filing</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="PF & ESIC">
                                                <label class="form-check-label">PF & ESIC</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Professional Tax">
                                                <label class="form-check-label">Professional Tax</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Project Report / DPR with CMA Data">
                                                <label class="form-check-label">Project Report / DPR with CMA Data</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Outsourcing of Work">
                                                <label class="form-check-label">Outsourcing of Work</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Outsourcing of Employee">
                                                <label class="form-check-label">Outsourcing of Employee</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Payroll & HR Compliances">
                                                <label class="form-check-label">Payroll & HR Compliances</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Company Legal Service">
                                                <label class="form-check-label">Company Legal Service</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Consulting & Advisory Services">
                                                <label class="form-check-label">Consulting & Advisory Services</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="DPDP Act, 2023">
                                                <label class="form-check-label">DPDP Act, 2023</label>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="IP Advisory">
                                                <label class="form-check-label">IP Advisory</label>
                                            </div>
                                        </div>


                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="MSME / Trade Licence">
                                                <label class="form-check-label">MSME / Trade Licence</label>
                                            </div>
                                        </div>
                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Company Legal Service">
                                                <label class="form-check-label">Company Legal Service</label>
                                            </div>
                                        </div>
                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="DPDP Act,2023">
                                                <label class="form-check-label">DPDP Act,2023</label>
                                            </div>
                                        </div>



                                        <div class="card shadow-sm border-0 p-3 m-2 flex-grow-1" style="min-width: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="compincorp[]" value="Other" id="customCheckinlh15">
                                                <label class="form-check-label" for="customCheckinlh15">Other</label>
                                            </div>
                                        </div>

                                        <div id="otherInput" class="mt-2 w-100" style="display: none;">
                                            <div class="card shadow-sm border-0 p-3">
                                                <label class="form-label" for="other_specify">Please specify other service:</label>
                                                <input type="text" name="other_specify" id="other_specify" class="form-control" placeholder="Enter the specific service you need">
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
                                                <button type="submit" id="nxtBtnVThree"
                                                    class="btn btn-primary d-flex align-items-center justify-content-center"
                                                    disabled>
                                                    Add Company <i class="ti ti-arrow-up-right-circle ms-2"></i>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Next button click
        document.querySelector('.next-btn').addEventListener('click', function() {
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('.nav-pills .nav-link[href="#access"]').classList.add('active');
            document.querySelector('#access').classList.add('show', 'active');
        });

        // Handle Previous button click
        document.querySelector('.previous-btn').addEventListener('click', function() {
            document.querySelector('.nav-pills .nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.show.active').classList.remove('show', 'active');
            document.querySelector('.nav-pills .nav-link[href="#personalDetail"]').classList.add('active');
            document.querySelector('#personalDetail').classList.add('show', 'active');
        });

        // Handle "Other" checkbox click
        const otherCheckbox = document.querySelector('#customCheckinlh15');
        const otherInput = document.querySelector('#otherInput');
        
        if (otherCheckbox && otherInput) {
            otherCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    otherInput.style.display = 'block';
                    otherInput.querySelector('input').focus(); // Focus on the text input
                } else {
                    otherInput.style.display = 'none';
                    otherInput.querySelector('input').value = ''; // Clear the input when unchecked
                }
            });
        }
    });



    $(document).ready(function() {
        // Form validation setup
        var addcustFrm = $('#addcustFrm').validate({
            rules: {
                // comp_gst_no: {
                //     required: true
                // },
                comp_name: {
                    required: true,
                    minlength: 3
                },
                comp_phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    number: true
                },
                comp_email: {
                    required: true,
                    email: true
                },
                // comp_pan_no: {
                //     required: true,
                //     minlength: 10
                // },
                // comp_website: {
                //     required: true
                // },
                agent_name: {
                    required: true
                }
            },
            messages: {
                // comp_gst_no: {
                //     required: "GST no. is required"
                // },
                comp_name: {
                    required: "Name is required"
                },
                comp_phone: {
                    required: "Mobile is required",
                    minlength: "10 digits required"
                },
                comp_email: {
                    required: "Email is required"
                },
                // comp_pan_no: {
                //     required: "PAN no. is required",
                //     minlength: "10 characters required"
                // },
                // comp_website: {
                //     required: "Website is required"
                // },
                agent_name: {
                    required: "Agent name is required"
                }
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).addClass("has-success").removeClass("has-error");
            }
        });

        // Form submission handler
        $('form#addcustFrm').bind('submit', function(e) {
            e.preventDefault();
            if (addcustFrm.form()) {
                var custId = $("#custId").val();
                var custurl = custId == "" ? '/save_client' : '/update_client';
                var custData = $('form#addcustFrm').serialize();
                
                $.ajax({
                    url: custurl,
                    type: 'POST',
                    data: custData,
                    success: function(response) {
                        if (response.class == "succ") {
                            showToast(response.message, "success");
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            $.each(response, function(idx, obj) {
                                showToast(obj, "error");
                            });
                        }
                    },
                    error: function() {
                        showToast("Something went wrong. Please try again.", "error");
                    }
                });
            }
        });

        // Email check functionality
        const $submit = $('#nxtBtnVThree');
        const $msg = $('#email_check_msg');

        $('#comp_email').on('input', function() {
            const email = $(this).val();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                $msg.hide();
                $submit.prop('disabled', true);
                return;
            }

            $.ajax({
                url: '/check-email',
                method: 'POST',
                data: {
                    email: email
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.exists === true) {
                        $msg.text('This email is already registered.').show();
                        $submit.prop('disabled', true);
                    } else {
                        $msg.hide();
                        $submit.prop('disabled', false);
                    }
                },
                error: function() {
                    $msg.text('Something went wrong while checking email.').show();
                    $submit.prop('disabled', true);
                }
            });
        });

        // Handle checkbox validation for "Other" field
        $('#customCheckinlh15').on('change', function() {
            const otherInput = $('input[name="other_specify"]');
            if (this.checked) {
                otherInput.prop('required', true);
                // Add validation rule dynamically
                otherInput.rules('add', {
                    required: true,
                    messages: {
                        required: "Please specify the other service"
                    }
                });
            } else {
                otherInput.prop('required', false);
                otherInput.val('');
                // Remove validation rule
                otherInput.rules('remove', 'required');
            }
        });

        // Ensure at least one checkbox is selected
        $('#nxtBtnVThree').on('click', function(e) {
            const checkboxes = $('input[name="compincorp[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                showToast("Please select at least one service", "error");
                return false;
            }
            
            // If "Other" is selected, ensure the text field is filled
            const otherChecked = $('#customCheckinlh15').is(':checked');
            const otherText = $('input[name="other_specify"]').val().trim();
            if (otherChecked && !otherText) {
                e.preventDefault();
                showToast("Please specify the other service", "error");
                return false;
            }
        });
    });
</script>







@endsection