@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/add-compliance-reminder') }}">Edit Compliance Reminder</a></li>
                        <li class="breadcrumb-item" aria-current="page">Edit Compliance Reminder</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-4">
        <h3>Edit Compliance Reminder</h3>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="javascript:void(0);" method="POST" name="addcomreminderFrm" id="addcomreminderFrm">
                <input type="hidden" name="id" id="rId" value="{{ $complianceReminder->id }}">
                @csrf
					<div class="row">

						<div class="col-sm-4 mb-3">
							<label class="form-label">Compliance Name<span class="text-danger">*</span></label>
							<select name="compliance_id" required class="form-control">
								<option value="">-- Select Compliance --</option>
								@foreach($compliances as $compliance)
									<option value="{{ $compliance->id }}"  {{ $compliance->id == $complianceReminder->compliance_id ? 'selected' : '' }}>
										{{ $compliance->name }}
									</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Form/Return</label>
							<input type="text" required id="form_name" name="form_name" value="{{ $complianceReminder->form_name }}" class="form-control" placeholder="Enter Form/Return">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Frequency<span class="text-danger">*</span></label>
							<select id="frequency" name="frequency" required class="form-control">
								<option value="">Select frequency</option>
								<option value="monthly" {{ $complianceReminder->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
								<option value="quarterly" {{ $complianceReminder->frequency == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
								<option value="annual" {{ $complianceReminder->frequency == 'annual' ? 'selected' : '' }}>Annual</option>
							</select>
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Due Day</label>
							<input type="text"  id="due_day" name="due_day" value="{{ $complianceReminder->due_day }}" class="form-control" placeholder="Enter Due Day">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Due Month</label>
							<input type="text" id="due_month" name="due_month" value="{{ $complianceReminder->due_month }}" class="form-control" placeholder="Enter Due Month">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Due Year<span class="text-danger">*</span></label>
							<select id="due_year_type" name="due_year_type" required class="form-control">
								<option value="">Select due year</option>
								<option value="current" {{ $complianceReminder->due_year_type == 'current' ? 'selected' : '' }}>current</option>
								<option value="next" {{ $complianceReminder->due_year_type == 'next' ? 'selected' : '' }}>next</option>
							</select>
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Reminder Day</label>
							<input type="text" id="reminder_day" name="reminder_day" value="{{ $complianceReminder->reminder_day }}" class="form-control" placeholder="Enter Reminder Day">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Reminder Month</label>
							<input type="text" id="reminder_month" name="reminder_month" value="{{ $complianceReminder->reminder_month }}" class="form-control" placeholder="Enter Reminder Month">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Reminder Year<span class="text-danger">*</span></label>
							<select id="reminder_year_type" name="reminder_year_type" required class="form-control">
								<option value="">Select due year</option>
								<option value="current" {{ $complianceReminder->reminder_year_type == 'current' ? 'selected' : '' }}>current</option>
								<option value="next" {{ $complianceReminder->reminder_year_type == 'next' ? 'selected' : '' }}>next</option>
							</select>
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Status<span class="text-danger">*</span></label>
							<select id="reminderStatus" name="reminderStatus" required class="form-control">
								<option value="">Select Status</option>
								<option value="1" {{ $complianceReminder->reminderStatus == 1 ? 'selected' : '' }}>Active</option>
								<option value="0" {{ $complianceReminder->reminderStatus == 0 ? 'selected' : '' }}>Inactive</option>
							</select>
						</div>
						<!--<div class="col-sm-4 mb-3">
							<label class="form-label">Compliance Name<span class="text-danger">*</span></label>
							<input type="text" id="compliance" name="compliance" value="{{ $complianceReminder->compliance }}" required class="form-control" placeholder="Enter Compliance Name">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Form/Return</label>
							<input type="text" id="form_return" name="form_return" value="{{ $complianceReminder->form_return }}" class="form-control" placeholder="Enter Form/Return">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Frequency<span class="text-danger">*</span></label>
							<input type="text" id="frequency" name="frequency" value="{{ $complianceReminder->frequency }}" required class="form-control" placeholder="Enter Frequency">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Standard Due Date</label>
							<input type="text" id="standard_due_date" name="standard_due_date" value="{{ $complianceReminder->standard_due_date }}" class="form-control" placeholder="Enter Standard Due Date">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Reminder Date</label>
							<input type="text" id="reminder_date" name="reminder_date" value="{{ $complianceReminder->reminder_date }}" class="form-control" placeholder="Enter Reminder Date">
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Status<span class="text-danger">*</span></label>
							<select id="status" name="status" required class="form-control">
								<option value="">Select Status</option>
								<option value="1" {{ $complianceReminder->status == 1 ? 'selected' : '' }}>Active</option>
								<option value="0" {{ $complianceReminder->status == 0 ? 'selected' : '' }}>Inactive</option>
							</select>
						</div>
						<div class="col-sm-4 mb-3">
							<label class="form-label">Compliance Reminder Created By</label>
							<input type="text" id="compliance_reminder_created_by" name="compliance_reminder_created_by" value="{{ $complianceReminder->compliance_reminder_created_by }}" class="form-control" placeholder="Enter Compliance Reminder Created By">
						</div>-->
					</div>
                    <div class="col-12 text-end">
                        <a href="{{ url('/compliance-reminder-list') }}" class="btn customer-btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
   document.addEventListener("DOMContentLoaded", function () {
    $("form#addcomreminderFrm").on("submit", function (e) {
        e.preventDefault();

        var rId = $("#rId").val();

        var surl = rId == "" 
            ? "/save_compliance_reminder"
            : "/update_compliance_reminder/" + rId;

        var expensesData = new FormData(this);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: surl,
            type: "POST",
            data: expensesData,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#expenseLoader").hide();
                if (response.class == "succ") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    showToast("Error while updating", "error");
                }
            },
            error: function (xhr) {
                showToast("Server error occurred", "error");
                console.log(xhr.responseText);
            }
        });
    });
});


   
</script>
@endsection