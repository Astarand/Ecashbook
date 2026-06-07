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
                        <li class="breadcrumb-item active" aria-current="page">Project & Job Management</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Project & Job List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ url('/add-project') }}" class="btn btn-primary"><i class="ti ti-square-plus f-20"></i> Add New Project</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Project Name</label>
                            <input type="text" id="proj_name" name="proj_name" class="form-control" placeholder="Project Name">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Client Name</label>
                            <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Client Name">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="form-label">Category<span class="text-danger">*</span></label>
                            <select class="form-select" id="topicSelect" name="proj_cat">
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

                        <div class="mb-3 col-sm-3" id="categoryContainer" style="display: none;">
                            <label for="categorySelect" class="form-label">Select Category:</label>
                            <select class="form-select" id="categorySelect" name="project_sub_cat">
                                <option value="" selected>Please Select</option>
                            </select>
                        </div>

                        <div class="mb-3 col-sm-3" id="otherInputContainer" style="display: none;">
                            <label for="otherInput" class="form-label">Other Category</label>
                            <input type="text" class="form-control" name="other_sub_cat" id="otherInput" placeholder="Enter other category">
                        </div>

                        <div class="col-sm-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="searchBtn">Search</button>
                        </div>
                        <div class="col-sm-6 mb-3 d-flex align-items-end">
                            <a href="{{ url('/project-list') }}" class="btn btn-danger w-100" id="resetBtn">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 mt-4">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cdcdcd;">
                                <th class="text-end">#</th>
                                <th>Company Name</th>
                                <th>Project Name</th>
                                <th>Project Category</th>
                                <th>Phone</th>
                                <th>Project Cost</th>
                                <th>Create Date</th>
                                <th>Dead Line</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $key => $project)
                            <tr>
                                <td class="text-end">{{ $key + 1 }}</td>
                                <td><a class="text-muted text-hover-primary">{{ $project->client_name }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $project->proj_name }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $project->proj_cat }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ $project->client_contact }}</a></td>
                                <td><a class="text-muted text-hover-primary">₹{{ number_format($project->proj_cost, 2) }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ date('d/m/Y', strtotime($project->proj_start_date)) }}</a></td>
                                <td><a class="text-muted text-hover-primary">{{ date('d/m/Y', strtotime($project->proj_end_date)) }}</a></td>
                                <td>
                                    @php
                                    $statusColors = [
                                    'Done' => 'bg-success',
                                    'Ongoing' => 'bg-warning',
                                    'Pending' => 'bg-danger',
                                    ];
                                    $badgeClass = $statusColors[$project->proj_status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $project->proj_status }}</span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
                                                <a href="{{ url('/viewProject/' . base64_encode($project->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                <a href="{{ url('/editProject/' . base64_encode($project->id)) }}" class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                <a href="" data-id="{{ base64_encode($project->id) }}" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete Transaction </h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="confirmDelete" data-bs-dismiss="modal" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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

    let deleteId = null;
    $(document).on('click', '.delete-btn', function (e) {
		e.preventDefault();
        deleteId = $(this).data('id');
    });

    // Handle the delete confirmation
    $('#confirmDelete').on('click', function() {
        //alert(deleteId);

        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/delProject/' + deleteId, // Update with your delete route
                type: 'DELETE',
                success: function(response) {
                    // alert(response)
                    // alert(response.message); 
                    // location.reload();
                    showToast(response.message, "success");
                    setTimeout(() => location.reload(), 2000);
                },
                error: function(xhr) {
                    // alert("Error deleting Lone!");
                    showToast("Error deleting Project", "error");
                }
            });
        }
    });


    //---------Filter Data --------

    $(document).ready(function() {
        $("#searchBtn").click(function(e) {
            e.preventDefault();

            var proj_name = $("#proj_name").val().trim();
            var client_name = $("#client_name").val().trim();
            var proj_cat = $("#topicSelect").val();
            var project_sub_cat = $("#categorySelect").val();

            if (!proj_cat) {
                // alert("Category is required!");
                showToast("Category is required!", "error");
                return false;
            }

            // Show loading spinner
            $("#searchBtn").html('<i class="fa fa-spinner fa-spin"></i> Searching...').prop("disabled", true);

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ route('search.projects') }}",
                type: "GET",
                data: {
                    proj_name: proj_name,
                    client_name: client_name,
                    proj_cat: proj_cat,
                    project_sub_cat: project_sub_cat
                },
                success: function(response) {
                    $("tbody").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Something went wrong! Please try again.");
                },
                complete: function() {
                    // Restore button state after request completes
                    $("#searchBtn").html('Search').prop("disabled", false);
                }
            });
        });
    });


    // // Reset Button
    // $("#resetBtn").click(function () {
    //     $("#proj_name").val("");
    //     $("#client_name").val("");
    //     $("#topicSelect").val("");
    //     $("#categorySelect").val("");

    //     $.ajax({
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         url: "{{ route('search.projects') }}",
    //         type: "GET",
    //         data: {
    //             reset: true
    //         },
    //         success: function (response) {
    //             $("tbody").html(response);
    //         }
    //     });
    // });
</script>
@endsection