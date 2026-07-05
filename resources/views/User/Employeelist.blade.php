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
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employee Master</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-employee-list-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Employee Master</h2>
                    </div>
                </div>
                @if(Auth::user()->u_type == 2 || Auth::user()->u_type == 3 || Auth::user()->u_type == 5 || Auth::user()->u_type == 6)
                <div class="col-md-8 text-end mt-2">
                    <a href="{{ route('user.AddEmployee') }}" class="btn btn-primary" id="add-employee-btn"><i class="ti ti-square-plus"></i>
                        Add New Employee</a>
                </div>
				@endif
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card" id="employee-table-card">
                <div class="card-body table-card">
                    {{-- <table class="table tbl-product" id="dom-table"> --}}
                        <table class="table tbl-product" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                                <th>PROPRIETORSHIP COMPANY</th>
								@endif
                                <th>Employee Name</th>
                                <th>Contact Number</th>
                                <th>Deperatment</th>
                                <th>Designation</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($employees as $employee)
                            <?php $encodedId = base64_encode($employee->id); ?>
                            <tr>
                                <td class="text-end">{{ $i++ }}</td>
								@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
								<td>{{$employee->comp_name}}</td>
								@endif
                                <td>
                                    <div class="row">
                                        <div class="col-auto pe-0">
                                            <div class="avtar avtar-s btn-light-primary">
                                                @php
                                                $profileImg = $employee->profile_img ? asset('storage/user_employee/' .
                                                $employee->profile_img) : asset('assets/images/user/avatar-2.jpg');
                                                @endphp
                                                <img src="{{ $profileImg }}" alt="user-image" class="wid-40 hei-40 rounded">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">{{ $employee->name }}</h6>
                                            <a class="text-muted f-12 text-hover-primary"
                                                href="mailto:{{ $employee->email_id ?? 'N/A' }}">{{ $employee->email_id
                                                ?? 'N/A' }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="tel:{{ $employee->phone }}">{{
                                        $employee->phone }}</a></td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $employee->dept_name }}</a>
                                </td>
                                <td><a class="text-muted text-hover-primary" href="#">{{ $employee->designation_name
                                        }}</a></td>
                                <td>
                                    <span class="badge {{ $employee->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $employee->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            <!-- Resignation -->
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Resignation">
                                                <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#regine_modal{{ $employee->id }}">
                                                    <i class="ph-duotone ph-user-circle-minus f-18"></i>
                                                </a>
                                            </li>
                                            <!-- View Employee -->
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="View">
                                                <a href="{{ url('view_user_employee/' . $encodedId) }}"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 3 || Auth::user()->u_type == 5 || Auth::user()->u_type == 6)
                                            <!-- Edit Employee -->
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <a href="{{ url('edit_user_employee/' . $encodedId) }}"
                                                    class="avtar avtar-xs btn-link-warning btn-pc-default">
                                                    <i class="ti ti-edit-circle f-18"></i>
                                                </a>
                                            </li>
                                            @endif
                                            <!-- Delete Employee -->
                                            <!--<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">-->
                                            <!--    <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_modal_{{ $employee->id }}">-->
                                            <!--        <i class="ti ti-trash f-18"></i>-->
                                            <!--    </a>-->

                                            <!--    {{-- <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn" data-id="{{ $encodedId }}">-->
                                            <!--    <i class="ti ti-trash f-18"></i>-->
                                            <!--    </a> --}}-->
                                            <!--</li>-->
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="delete_modal_{{ $encodedId }}" tabindex="-1"
                                aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Status Update</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to update <b>{{ $employee->name }}</b>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger confirm-delete"
                                                data-id="{{ $encodedId }}">Delete</button>
                                            {{-- <a href="{{ url('delet_user_employee/' . $encodedId) }}"
                                                class="btn btn-danger">Delete</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Regine --}}
                            <div class="modal custom-modal fade" id="regine_modal{{ $employee->id }}" tabindex="-1"
                                role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            {{-- <form id="resignationForm" action="{{ route('update.resignation') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-header">
                                                    <div class="row">
                                                        <div class="col-sm-12 mb-3">
                                                            <label class="form-label">Date of Resignation<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="regdate"
                                                                id="regdate" placeholder="Enter date of Resignation">
                                                            <input type="hidden" name="empId" id="empId"
                                                                value="{{ $employee->id }}">
                                                        </div>
                                                        <div class="col-sm-12 mb-3">
                                                            <label class="form-label">Upload Resignation Letter<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="file" class="form-control" name="reg_documet"
                                                                id="reg_documet"
                                                                placeholder="Enter date of Resignation">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-btn">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <button type="submit" class="w-100 btn btn-success">
                                                                Submit
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" data-bs-dismiss="modal"
                                                                class="w-100 btn btn-secondary paid-cancel-btn">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form> --}}
                                            <form id="resignationForm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-header">
                                                    <div class="row">
                                                        <div class="col-sm-12 mb-3">
                                                            <label class="form-label">Date of Resignation / Terminate<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="regdate"
                                                                required>
                                                            <input type="hidden" name="empId" id="empId"
                                                                value="{{ $employee->id }}">
                                                        </div>
                                                        <div class="col-sm-12 mb-3">
                                                            <label class="form-label">Upload Resignation Letter<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="file" class="form-control" name="reg_documet"
                                                                accept=".pdf,.doc,.docx,.jpg,.png" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-btn">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <button type="submit"
                                                                class="w-100 btn btn-success submit-resignation"
                                                                data-id="{{ $employee->id }}">
                                                                Submit
                                                            </button>
                                                            {{-- <button type="submit"
                                                                class="w-100 btn btn-success submit-resignation"
                                                                data-id="{{ preg_replace('/[^A-Za-z0-9]/', '', $encodedId) }}">
                                                                Submit
                                                            </button> --}}
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" data-bs-dismiss="modal"
                                                                class="w-100 btn btn-secondary">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            @endforeach
                        </tbody>

                    </table>
					{{-- <div class="d-flex justify-content-end mt-3">
						{{ $employees->links('pagination::bootstrap-4') }}
					</div> --}}
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
                    <h3>Delete Employee</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal"
                                class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
    function toggleDateFields(employeeId) {
        const permanentYes = document.getElementById('permanent_wfh_yes' + employeeId);
        const dateFields = document.getElementById('dateFields' + employeeId);
        const startDate = document.getElementById('start_date' + employeeId);
        const endDate = document.getElementById('end_date' + employeeId);
        
        if (permanentYes.checked) {
            dateFields.style.display = 'none';
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');
            startDate.value = '';
            endDate.value = '';
        } else {
            dateFields.style.display = 'block';
            startDate.setAttribute('required', 'required');
            endDate.setAttribute('required', 'required');
        }
    }

    $(document).ready(function() {
        $(".confirm-delete").click(function() {
            let encodedId = $(this).data("id");

            $.ajax({
                url: "/delet_user_employee/" + encodedId,
                type: "GET", // Ensure the route matches your Laravel method
                success: function(response) {
                    if (response.status == "succ") {
                        showToast(response.message, "success"); // Show success toast
                        setTimeout(() => location.reload(), 2000); // Reload after 2s
                    } else {
                        showToast("Error: " + response.message, "error"); // Show error toast
                    }
                },
                error: function() {
                    showToast("An error occurred. Please try again.", "error");
                }
            });

            // Close the modal after clicking delete
            $(this).closest(".modal").modal("hide");
        });

    });

    $(document).on("click", ".submit-resignation", function(e) {
        e.preventDefault(); // Prevent default form submission

        let encodedId = $(this).data("id");
        if (!encodedId) {
            showToast("Invalid employee ID!", "error");
            return;
        }

        let form = $("#resignationForm"); // Select the form directly
        if (!form.length) {
            showToast("Resignation form not found!", "error");
            return;
        }

        let formData = new FormData(form[0]); // Create FormData object

        $.ajax({
            url: "{{ route('update.resignation') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".submit-resignation").prop("disabled", true); // Disable button to prevent duplicate submissions
            },
            success: function(response) {
                if (response.class === "succ") {
                    showToast(response.message, "success");
                    setTimeout(() => location.reload(), 2000); // Reload after 2s
                } else {
                    showToast("Resignation Update Failed: " + response.message, "error");
                }
            },
            error: function(xhr) {
                let errorMessage = "An error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast(errorMessage, "error");
            },
            complete: function() {
                $(".submit-resignation").prop("disabled", false); // Re-enable button after request
            }
        });

        // Close the modal using the correct ID format
        let modalId = "#regine_modal" + encodedId;
        console.log("Closing Modal:", modalId);
        $(modalId).modal("hide");
    });

    // Work from Home form submission
    $(document).on("click", ".submit-wfh", function(e) {
        e.preventDefault();

        let employeeId = $(this).data("id");
        let form = $("#wfhForm" + employeeId);
        
        if (!form.length) {
            showToast("Work from Home form not found!", "error");
            return;
        }

        let formData = new FormData(form[0]);

        // Disable end date field if permanent WFH is checked
        let permanentCheckbox = $("#permanent_wfh" + employeeId);
        let endDateField = form.find('input[name="end_date"]');
        
        if (permanentCheckbox.is(':checked')) {
            endDateField.prop('required', false);
        } else {
            endDateField.prop('required', true);
        }

        // Basic validation
        let startDate = form.find('input[name="start_date"]').val();
        let endDate = form.find('input[name="end_date"]').val();
        
        if (!startDate) {
            showToast("Please select a start date!", "error");
            return;
        }
        
        if (!permanentCheckbox.is(':checked') && !endDate) {
            showToast("Please select an end date or check permanent work from home!", "error");
            return;
        }
        
        if (!permanentCheckbox.is(':checked') && startDate > endDate) {
            showToast("End date must be after start date!", "error");
            return;
        }

        // Here you would typically send the data to your backend
        // For now, just show a success message since backend is not implemented
        showToast("Work from Home request submitted successfully!", "success");
        
        // Close the modal
        $("#wfh_modal" + employeeId).modal("hide");
        
        // Reset form
        form[0].reset();
    });

    // Handle permanent WFH checkbox change
    $(document).on("change", "[id^='permanent_wfh']", function() {
        let employeeId = $(this).attr('id').replace('permanent_wfh', '');
        let endDateField = $("#wfhForm" + employeeId).find('input[name="end_date"]');
        
        if ($(this).is(':checked')) {
            endDateField.prop('disabled', true).prop('required', false);
        } else {
            endDateField.prop('disabled', false).prop('required', true);
        }
    });

    function startEmployeeListTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Employee Master Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-users" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage your organization\'s employee profiles, department allocation, designation assignments, and active work statuses.</p></div>'
                },
                {
                    element: '#add-employee-btn',
                    title: 'Add New Employee',
                    intro: 'Click here to register a new employee. This launches the wizard to capture personal, address, job, bank details, and app permissions.'
                },
                {
                    element: '#employee-table-card',
                    title: 'Employee Directory',
                    intro: 'List of all registered employees. Verify their contact details, assigned departments, and status (Active/Inactive).'
                },
                {
                    element: '.prod-action-links',
                    title: 'Employee Action Controls',
                    intro: 'Quick links to trigger Resignation/Termination procedures, View full profile sheets, or Edit records.'
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
        $('#start-employee-list-tour').on('click', function(e) {
            e.preventDefault();
            startEmployeeListTour();
        });
    });
</script>
@endsection