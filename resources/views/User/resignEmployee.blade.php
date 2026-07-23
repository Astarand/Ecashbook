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
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                            <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Resigned Employees</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-resign-employee-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Resigned Employees</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-body table-card">
                    <table class="table tbl-product" id="dom-table">
                        <!--<table class="table tbl-product" id="pc-dt-simple">-->
                        <thead>
                            <tr>
                                <th class="text-end">#</th>
								@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
                                <th>PROPRIETORSHIP COMPANY</th>
								@endif
                                <th>Name</th>
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
                                        {{ $employee->status == 1 ? 'Active' : 'Resign' }}
                                    </span>
                                </td>
                                <td>
                                    <span><i class="ti ti-dots-vertical f-20"></i></span>
                                    <div class="prod-action-links">
                                        <ul class="list-inline me-auto mb-0">
                                            
                                            <!-- View Employee -->
                                            <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                title="View">
                                                <a href="{{ url('view_resign_user_employee/' . $encodedId) }}"
                                                    class="avtar avtar-xs btn-link-success btn-pc-default tour-view-resign">
                                                    <i class="ti ti-eye f-18"></i>
                                                </a>
                                            </li>
											
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

                            
                            @endforeach
                        </tbody>

                    </table>
					<div class="d-flex justify-content-end mt-3">
						{{ $employees->links('pagination::bootstrap-4') }}
					</div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>


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

    // --- Interactive Tour ---
    function startResignEmployeeTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Resigned Employees Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-users" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Manage and view profiles of employees who have resigned from the organization.</p></div>'
                },
                {
                    element: '#dom-table',
                    title: 'Resigned Employees List',
                    intro: 'This table displays all resigned employees with their departments, designations, and status.'
                },
                {
                    element: '.tour-view-resign',
                    title: 'View Employee Details',
                    intro: 'Click here to view detailed resignation history, documents, and final settlement details for this employee.',
                    position: 'left'
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
        $('#start-resign-employee-tour').on('click', function(e) {
            e.preventDefault();
            startResignEmployeeTour();
        });
    });
</script>
@endsection
@endsection