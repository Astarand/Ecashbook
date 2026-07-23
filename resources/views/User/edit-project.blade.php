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
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Project / Job</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-project-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Project / Job</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h4 class="mb-0">Edit Project</h4>
        </div>
    </div>
    

    <form method="post" >
        @csrf
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Project Name<span class="text-danger">*</span></label>
                            <input type="hidden" value="{{ $project->id }}" name="proj_id" id="proj_id">
                            <input type="text" name="proj_name" id="proj_name" value="{{ $project->proj_name }}" class="form-control" placeholder="Project Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Category<span class="text-danger">*</span></label>
                            <select class="form-select" id="topicSelect" name="proj_cat">
                                <option value="" {{ empty($project->proj_cat) ? 'selected' : '' }}>Please Select</option>
                                <option value="Technology" {{ ($project->proj_cat ?? '') == "Technology" ? 'selected' : '' }}>Technology</option>
                                <option value="Finance" {{ ($project->proj_cat ?? '') == "Finance" ? 'selected' : '' }}>Finance</option>
                                <option value="Healthcare" {{ ($project->proj_cat ?? '') == "Healthcare" ? 'selected' : '' }}>Healthcare</option>
                                <option value="Energy" {{ ($project->proj_cat ?? '') == "Energy" ? 'selected' : '' }}>Energy</option>
                                <option value="Manufacturing" {{ ($project->proj_cat ?? '') == "Manufacturing" ? 'selected' : '' }}>Manufacturing</option>
                                <option value="Retail" {{ ($project->proj_cat ?? '') == "Retail" ? 'selected' : '' }}>Retail</option>
                                <option value="Consumer Goods" {{ ($project->proj_cat ?? '') == "Consumer Goods" ? 'selected' : '' }}>Consumer Goods</option>
                                <option value="Transportation and Logistics" {{ ($project->proj_cat ?? '') == "Transportation and Logistics" ? 'selected' : '' }}>Transportation and Logistics</option>
                                <option value="Real Estate" {{ ($project->proj_cat ?? '') == "Real Estate" ? 'selected' : '' }}>Real Estate</option>
                                <option value="Construction" {{ ($project->proj_cat ?? '') == "Construction" ? 'selected' : '' }}>Construction</option>
                                <option value="Hospitality and Tourism" {{ ($project->proj_cat ?? '') == "Hospitality and Tourism" ? 'selected' : '' }}>Hospitality and Tourism</option>
                                <option value="Media and Entertainment" {{ ($project->proj_cat ?? '') == "Media and Entertainment" ? 'selected' : '' }}>Media and Entertainment</option>
                                <option value="Education" {{ ($project->proj_cat ?? '') == "Education" ? 'selected' : '' }}>Education</option>
                                <option value="Agriculture" {{ ($project->proj_cat ?? '') == "Agriculture" ? 'selected' : '' }}>Agriculture</option>
                                <option value="Government and Public Services" {{ ($project->proj_cat ?? '') == "Government and Public Services" ? 'selected' : '' }}>Government and Public Services</option>
                                <option value="Professional Services" {{ ($project->proj_cat ?? '') == "Professional Services" ? 'selected' : '' }}>Professional Services</option>
                                <option value="Nonprofit and Social Services" {{ ($project->proj_cat ?? '') == "Nonprofit and Social Services" ? 'selected' : '' }}>Nonprofit and Social Services</option>
                                <option value="Other" {{ ($project->proj_cat ?? '') == "Other" ? 'selected' : '' }}>Other</option>
                            </select>
                            
                        </div>

                        <div class="mb-3 col-sm-4" id="categoryContainer" style="display: none;">
                            <label for="categorySelect" class="form-label">Select Category:</label>
                            <select class="form-select" name="project_sub_cat" id="categorySelect">
                                <option value="" selected>Please Select</option>
                            </select>
                        </div>

                        <div class="mb-3 col-sm-4" id="otherInputContainer" style="display: none;">
                            <label for="otherInput" class="form-label">Other Category</label>
                            <input type="text" class="form-control" value="{{ $project->other_sub_cat }}" name="other_sub_cat" id="otherInput" placeholder="Enter other category">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <h6 class="mb-0">Project Status <span class="text-danger">*</span></h6>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="proj_status" id="Pending" value="Pending" 
                            {{ ($project->proj_status ?? '') == 'Pending' ? 'checked' : '' }}>
                        <label class="form-check-label" for="Pending">Pending</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="proj_status" id="Ongoing" value="Ongoing" 
                            {{ ($project->proj_status ?? '') == 'Ongoing' ? 'checked' : '' }}>
                        <label class="form-check-label" for="Ongoing">Ongoing</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 m-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="proj_status" id="Done" value="Done" 
                            {{ ($project->proj_status ?? '') == 'Done' ? 'checked' : '' }}>
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
                            <input type="text" class="form-control" name="client_name" value="{{ $project->client_name }}" id="client_name" placeholder="Client Name">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Client Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="client_email" value="{{ $project->client_email }}" id="client_email" placeholder="Client Email">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="client_contact" value="{{ $project->client_contact }}" id="client_contact" placeholder="Client Phone Number">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Project Start Date<span class="text-danger">*</span></label>
                            <input type="Date" class="form-control"  name="proj_start_date" value="{{ $project->proj_start_date }}" id="proj_start_date" placeholder="Client Phone Number">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Project End Date<span class="text-danger">*</span></label>
                            <input type="Date" class="form-control"  name="proj_end_date" value="{{ $project->proj_end_date }}" id="proj_end_date" placeholder="Client Phone Number">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputEmail4">Project Valuation</label>
                            <input type="text" class="form-control"  name="proj_cost" id="proj_cost" value="{{ $project->proj_cost }}" placeholder="Project Valuation" >
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="inputEmail4">Project Description</label>
                            <textarea class="form-control" name="proj_details" id="proj_details"  placeholder="Enter project description here..." rows="4">{{ $project->proj_details }}</textarea>
                        </div>
                        <div class="col-md-12 text-end">
                            {{-- <button type="button" id="#" class="btn btn-secondary me-2">Cancel</button> --}}
                            <a href="{{ url('/project-list') }}" class="btn btn-primary cancel me-2">Cancel</a>
                            <button type="submit" id="#" class="btn btn-primary">Update Project</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </form>
</div>
<script>
    // Categories for each topic
    document.addEventListener("DOMContentLoaded", function () {
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

        // Get the selected category and subcategory from PHP
        const selectedCategory = "{{ $project->proj_cat ?? '' }}";
        const selectedSubCategory = "{{ $project->project_sub_cat ?? '' }}";

        // Function to populate subcategories
        function populateSubcategories(topic) {
            categorySelect.innerHTML = '<option value="" selected>Please Select</option>';
            if (categories[topic]) {
                categories[topic].forEach((category) => {
                    const option = document.createElement("option");
                    option.value = category;
                    option.textContent = category;
                    if (category === selectedSubCategory) {
                        option.selected = true; // Preselect subcategory
                    }
                    categorySelect.appendChild(option);
                });
            }
        }

        // Initialize on page load
        if (selectedCategory) {
            topicSelect.value = selectedCategory;
            if (selectedCategory === "Other") {
                categoryContainer.style.display = "none";
                otherInputContainer.style.display = "block";
            } else if (categories[selectedCategory]) {
                categoryContainer.style.display = "block";
                otherInputContainer.style.display = "none";
                populateSubcategories(selectedCategory);
            }
        }

        // Handle topic selection change
        topicSelect.addEventListener("change", function () {
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
                populateSubcategories(selectedTopic);
            } else {
                categoryContainer.style.display = "none";
                otherInputContainer.style.display = "none";
            }
        });
    });

    $(document).ready(function () {

    $("form").submit(function (event) {
        event.preventDefault();

        $(".validation-error").remove();

        let proj_id = $("#proj_id").val();
        let url = proj_id ? "/update_project" : "/save_add_project";

        let formData = new FormData(this);

        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success: function (response) {
                $("#loader").hide();
                if (response.status === "success") {
                    showToast(response.message, "success");
                    setTimeout(() => window.location.href = response.redirect, 2000);
                } else {
                    showToast(response.message, "error");
                }
            },

            error: function (xhr) {
                $("#loader").hide();
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
                showToast("Something went wrong. Please try again.", "error");
            }
        });
    });

});


    // $(document).ready(function () {
    //     $("form").submit(function (event) {
    //         event.preventDefault(); 
    //         var proj_id = document.getElementById("proj_id").value;
    //         if(proj_id != ""){
    //             var url = "/update_project";
    //         }else{
    //             var url = "/save_add_project";
    //         }
    //         let formData = new FormData(this); 

    //         $.ajax({
    //             headers: {
    //                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //             },
    //             url: url, 
    //             type: "POST",
    //             data: formData,
    //             processData: false, // Required for FormData
    //             contentType: false, // Required for FormData
    //             dataType: "json",
    //             success: function (response) {
    //                 if (response.status === "success") {
    //                     // alert(response.message); // Show success message
    //                     // window.location.href = response.redirect; // Redirect to /projects
    //                     showToast(response.message, "success");
    //                     setTimeout(() => window.location.href = response.redirect, 2000);
    //                 } else {
    //                     // alert(response.message); // Show error message
    //                     showToast(response.message, "error");
    //                 }
    //             },
    //             error: function () {
    //                 alert("Something went wrong. Please try again.");
    //                 showToast("Something went wrong. Please try again.", "error");
    //             }
    //         });
    //     });
    // });


    function startEditProjectTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Project Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Modify project name, deadlines, assigned managers, or status.</p></div>'
                },
                {
                    title: 'Edit Project',
                    intro: 'Modify project name, deadlines, assigned managers, or status.'
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
        $('#start-edit-project-tour').on('click', function(e) {
            e.preventDefault();
            startEditProjectTour();
        });
    });
</script>
@endsection
