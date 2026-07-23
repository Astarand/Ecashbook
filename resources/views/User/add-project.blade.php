@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Project / Job</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-project-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add New Project / Job</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h4 class="mb-0">Add New Project</h4>
        </div>
    </div>

    <form method="post">
        @csrf
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Project Name<span class="text-danger">*</span></label>
                            <input type="text" name="proj_name" id="proj_name" required class="form-control" placeholder="Project Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Category<span class="text-danger">*</span></label>
                            <select class="form-select" id="topicSelect" name="proj_cat" required>
                                <option value="" selected>Please Select</option>
                                <option value="Technology">Technology</option>
                                <option value="Finance">Finance</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Energy">Energy</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Retail">Retail</option>
                                <option value="Consumer Goods">Consumer Goods</option>
                                <option value="Transportation and Logistics">Transportation and Logistics</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Construction">Construction</option>
                                <option value="Hospitality and Tourism">Hospitality and Tourism</option>
                                <option value="Media and Entertainment">Media and Entertainment</option>
                                <option value="Education">Education</option>
                                <option value="Agriculture">Agriculture</option>
                                <option value="Government and Public Services">Government and Public Services</option>
                                <option value="Professional Services">Professional Services</option>
                                <option value="Nonprofit and Social Services">Nonprofit and Social Services</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3 col-sm-4" id="categoryContainer" style="display: none;">
                            <label for="categorySelect" class="form-label">Select Category: <span class="text-danger">*</span></label>
                            <select class="form-select" name="project_sub_cat" id="categorySelect">
                                <option value="" selected>Please Select</option>
                            </select>
                        </div>

                        <div class="mb-3 col-sm-4" id="otherInputContainer" style="display: none;">
                            <label for="otherInput" class="form-label">Other Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="other_sub_cat" id="otherInput" placeholder="Enter other category">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4" id="project-status-row">
            <h6 class="mb-0">Project Status <span class="text-danger">*</span></h6>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="proj_status" id="Pending" value="Pending" checked>
                        <label class="form-check-label" for="Pending">Pending</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="proj_status" id="Ongoing" value="Ongoing">
                        <label class="form-check-label" for="Ongoing">Ongoing</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="proj_status" id="Done" value="Done">
                        <label class="form-check-label" for="Done">Done</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Client Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required name="client_name" id="client_name" placeholder="Client Name">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Client Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" required name="client_email" id="client_email" placeholder="Client Email">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required name="client_contact" id="client_contact" placeholder="Client Phone Number">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Project Start Date<span class="text-danger">*</span></label>
                            <input type="Date" class="form-control" required name="proj_start_date" id="proj_start_date" placeholder="Client Phone Number">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Project End Date<span class="text-danger">*</span></label>
                            <input type="Date" class="form-control" required name="proj_end_date" id="proj_end_date" placeholder="Client Phone Number">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Project Valuation</label>
                            <input type="text" class="form-control"  name="proj_cost" id="proj_cost" placeholder="Project Valuation">
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="inputEmail4">Project Description</label>
                            <textarea class="form-control" name="proj_details" id="proj_details" placeholder="Enter project description here..." rows="4"></textarea>
                        </div>
                        <div class="col-md-12 text-end">
                            <a href="{{ url('/project-list') }}" class="btn btn-primary cancel me-2">Cancel</a>
                            <button type="submit" id="#" class="btn btn-primary">Add Project</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </form>
</div>
@endsection

@section('page-script')
<script>
    function startAddProjectTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add New Project',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Register a new project or job profile, assign it to a client, define valuations and timelines.</p></div>'
                },
                {
                    element: '#proj_name',
                    title: 'Project Title',
                    intro: 'Enter the unique project title or reference name here.'
                },
                {
                    element: '#topicSelect',
                    title: 'Category & Classification',
                    intro: 'Choose the project topic and sub-category classification.'
                },
                {
                    element: '#project-status-row',
                    title: 'Current Project Status',
                    intro: 'Define the current project state (Pending, Ongoing, or Done).'
                },
                {
                    element: '#client_name',
                    title: 'Client Information',
                    intro: 'Input client contact parameters: Name, Email, and Phone details.'
                },
                {
                    element: '#proj_start_date',
                    title: 'Project Timeline & Budget',
                    intro: 'Establish the project start date, scheduled end date, and overall budget valuation.'
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
        $('#start-add-project-tour').on('click', function(e) {
            e.preventDefault();
            startAddProjectTour();
        });
    });
    // Categories for each topic
    const categories = {
        Technology: [
            "Software Development",
            "Hardware Manufacturing",
            "Information Technology Services",
            "Internet and e-commerce",
            "Telecommunications",
        ],
        Finance: [
            "Banking",
            "Investment Banking",
            "Asset Management",
            "Insurance",
            "Financial Technology (Fintech)",
        ],
        Healthcare: [
            "Pharmaceuticals",
            "Biotechnology",
            "Medical Devices",
            "Healthcare Services",
            "HealthTech",
        ],
        Energy: [
            "Oil and gas",
            "Renewable energy (solar, wind, hydroelectric, etc.)",
            "Utilities (Electricity, Water, Gas)",
            "Energy Services",
        ],
        Manufacturing: [
            "Automotive",
            "Aerospace and defence",
            "Consumer Goods",
            "Industrial Machinery",
            "Chemicals",
        ],
        Retail: [
            "General Retail",
            "E-commerce and online retail",
            "Specialty Retail",
            "Wholesale",
        ],
        "Consumer Goods": [
            "Food and Beverage",
            "Personal Care and Cosmetics",
            "Apparel and Footwear",
            "Household Products",
        ],
        "Transportation and Logistics": [
            "Air Transportation",
            "Rail Transportation",
            "Maritime and Shipping",
            "Logistics and Supply Chain",
            "Warehousing and Distribution",
        ],
        "Real Estate": [
            "Residential real estate",
            "Commercial real estate",
            "Real Estate Development",
            "Property Management",
            "Real Estate Investment Trusts (REITs)",
        ],
        Construction: [
            "Building Construction",
            "Infrastructure Development",
            "Civil Engineering",
            "Architectural Services",
        ],
        "Hospitality and Tourism": [
            "Hotels and Accommodations",
            "Travel agencies and tour operators",
            "Restaurants and food services",
            "Attractions and Entertainment",
        ],
        "Media and Entertainment": [
            "Broadcasting (television, radio)",
            "Film Production and Distribution",
            "Streaming Services",
            "Publishing",
            "Gaming and interactive entertainment",
        ],
        Education: [
            "Schools and universities",
            "E-learning and online education",
            "Educational Services",
            "Educational Technology (EdTech)",
        ],
        Agriculture: [
            "Crop Production",
            "Livestock Farming",
            "Agribusiness",
            "Agricultural Technology (AgriTech)",
        ],
        "Government and Public Services": [
            "Public Administration",
            "Defense and security",
            "Healthcare Services",
            "Education Services",
            "Infrastructure Development",
        ],
        "Professional Services": [
            "Legal Services",
            "Accounting and auditing",
            "Consulting",
            "Human Resources",
            "Marketing and advertising",
        ],
        "Nonprofit and Social Services": [
            "Charitable Organisations",
            "Social Advocacy Groups",
            "Foundations",
            "NGOs (non-governmental organisations)",
        ],
    };

    // Elements
    const topicSelect = document.getElementById("topicSelect");
    const categoryContainer = document.getElementById("categoryContainer");
    const categorySelect = document.getElementById("categorySelect");
    const otherInputContainer = document.getElementById("otherInputContainer");
    const otherInput = document.getElementById("otherInput");

    // Handle topic selection
    topicSelect.addEventListener("change", function() {
        const selectedTopic = topicSelect.value;

        // Reset category and input field
        categorySelect.innerHTML = '<option value="" selected>Please Select</option>';
        otherInput.value = "";

        if (selectedTopic === "Other") {
            categoryContainer.style.display = "none";
            otherInputContainer.style.display = "block";
        } else if (categories[selectedTopic]) {
            otherInputContainer.style.display = "none";
            categoryContainer.style.display = "block";

            // Populate categories
            categories[selectedTopic].forEach((category) => {
                const option = document.createElement("option");
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
        } else {
            categoryContainer.style.display = "none";
            otherInputContainer.style.display = "none";
        }
    });

    $(document).ready(function () {

        $("form").submit(function (event) {
            event.preventDefault();

            // Remove old validation errors
            $(".validation-error").remove();

            let formData = new FormData(this);
            $("#loader").show();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/save_add_project",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",

                success: function (response) {
                    $("#loader").hide();
                    if (response.status === "success") {
                        showToast(response.message, "success");

                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);

                    } else {
                        showToast(response.message || "Something went wrong.", "error");
                    }
                },

                error: function (xhr) {
                    $("#loader").hide();
                    // ✅ Laravel validation error (422)
                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function (field, messages) {

                            let input = $("[name='" + field + "']");

                            if (input.length) {
                                input.after(
                                    '<span class="text-danger validation-error">' +
                                    messages[0] +
                                    "</span>"
                                );
                            }
                        });

                        showToast("Please fix the validation errors.", "error");
                        return;
                    }
                    $("#loader").hide();
                    // ✅ Other server errors
                    showToast("Something went wrong. Please try again.", "error");
                }
            });
        });

    });


    // $(document).ready(function() {
    //     $("form").submit(function(event) {
    //         event.preventDefault();

    //         let formData = new FormData(this);
    //         // alert("gii");
    //         $.ajax({
    //             headers: {
    //                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //             },
    //             url: '/save_add_project',
    //             type: "POST",
    //             data: formData,
    //             processData: false, // Required for FormData
    //             contentType: false, // Required for FormData
    //             dataType: "json",
    //             success: function(response) {
    //                 if (response.status === "success") {
    //                     // alert(response.message); // Show success message
    //                     // window.location.href = response.redirect; // Redirect to /projects

    //                     showToast(response.message, "success");
    //                     setTimeout(() => window.location.href = response.redirect, 2000); // Reload after 2s
    //                 } else {
    //                     // alert(response.message); // Show error message
    //                     showToast(response.message, "error");
    //                 }
    //             },
    //             error: function() {
    //                 // alert("Something went wrong. Please try again.");
    //                 showToast("Something went wrong. Please try again.", "error");
    //             }
    //         });
    //     });
    // });
</script>
@endsection