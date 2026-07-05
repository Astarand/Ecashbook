@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="row">

        <form action="javascript:void(0);" method="post"
              name="setReminderFrmCA"
              id="setReminderFrmCA"
              enctype="multipart/form-data">
            @csrf

            {{-- ================= FILTER CARD ================= --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-3 mb-3">
                            <label>Reminder Type</label>
                            <select name="reminder_type" id="reminder_type" class="form-select">
                                <option value="">Select</option>
                                <option value="bulk">Bulk</option>
                                <option value="specific">Specific</option>
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Access User</label>
                            <select name="user_type" id="user_type"
                                    onchange="getAllUserCA(this)"
                                    class="form-select">
                                <option value="">Select</option>
                                <option value="All">All</option>
                                <option value="all_ca">All CA</option>
                                <option value="all_company">All User/Company</option>
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Customer Type</label>
                            <select name="customer_type"
                                    id="customer_type"
                                    onchange="getUserCaAccessByStatus(this)"
                                    class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                <option value="All">All</option>
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Reminder Through</label>
                            <select name="reminder_through"
                                    id="reminder_through"
                                    class="form-select">
                                <option value="">Select</option>
                                <option value="mail">Mail</option>
                                <option value="whatsapp">Whatsapp</option>
                                <option value="notification">Notification</option>
                                <option value="sms">SMS</option>
                                <option value="all">All</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ================= SELECTED USERS CARD ================= --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div id="toastContainer" class="d-flex flex-wrap gap-2">
                        <!-- Toasts will appear here -->
                    </div>
                </div>
            </div>

            {{-- ================= MESSAGE CARD ================= --}}
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Subject</label>
                            <input type="text" name="sub_text"
                                   id="sub_text"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-9 mb-3">
                            <textarea name="msg_text"
                                      id="msg_text"
                                      rows="10"
                                      class="form-control"
                                      required></textarea>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file"
                                   name="fileAttached"
                                   class="form-control"
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">
                                Send Reminder
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

{{-- ================= SCRIPTS ================= --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function () {

    // ================= REMOVE USER =================
    $(document).on('click', '.remove-user', function () {
        $(this).closest('.user-toast').fadeOut(200, function () {
            $(this).remove();
        });
    });

});


// ================= GET ALL USER =================
function getAllUserCA(el) {

    let base_url = $("#base_url").val();
    let select_type = el.value;
    let customer_type = $("#customer_type").val();

    if (!select_type) {
        $("#toastContainer").empty();
        return;
    }

    $.ajax({
        url: base_url + "/allUserCA",
        type: "POST",
        dataType: "json",
        data: {
            sendData: 'getData',
            customer_type: customer_type,
            select_type: select_type
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (data) {
            renderUsers(data);
        }
    });
}


// ================= FILTER BY STATUS =================
function getUserCaAccessByStatus(el) {

    let base_url = $("#base_url").val();
    let customer_type = el.value;
    let select_type = $("#user_type").val();

    $.ajax({
        url: base_url + "/userCaListsAccess",
        type: "POST",
        dataType: "json",
        data: {
            sendData: 'getData',
            customer_type: customer_type,
            select_type: select_type
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (data) {
            renderUsers(data);
        }
    });
}


// ================= RENDER USERS =================
function renderUsers(data) {

    $("#toastContainer").empty();

    if (!data.matched_names || data.matched_names.length === 0) {
        return;
    }

    let str = "";

    $.each(data.matched_names, function (idx, item) {

        if (!item.name) return;

        let typeBadgeClass = item.type_label === 'CA'
            ? 'bg-primary'
            : 'bg-success';

        let statusBadgeClass = item.status_label === 'Active'
            ? 'bg-success'
            : 'bg-danger';

        str += `
            <div class="toast show user-toast align-items-center border-0 p-2">
                <div class="d-flex align-items-center">

                    <div class="me-2">
                        ${item.name}
                        <span class="badge ${typeBadgeClass} ms-2">
                            ${item.type_label}
                        </span>
                        <span class="badge ${statusBadgeClass} ms-2">
                            ${item.status_label}
                        </span>

                        <input type="hidden" name="userId[]" value="${item.userId}">
                    </div>

                    <button type="button"
                            class="btn-close remove-user ms-auto">
                    </button>

                </div>
            </div>
        `;
    });

    $("#toastContainer").html(str);
}

</script>

@endsection