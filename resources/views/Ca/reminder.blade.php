@extends('App.Layout')

@section('container')
    <div class="pc-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Set & Send Reminder</h5>
            <a href="javascript:void(0);" onclick="startCAReminderTour();" id="start-ca-reminder-tour" class="text-primary d-inline-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                <u>How does this Page works?</u>
            </a>
        </div>
        <div class="row">
            <div class="card mb-3" id="reminder-settings-card">
                <div class="card-body">
                <form action="javascript:void(0);" method="post" name="setReminderFrmCA" id="setReminderFrmCA"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <div class="form-group">
                                <label>Reminder Type</label>
                                <select name="reminder_type" id="reminder_type" class="form-select" aria-label="Default select example">
                                    <option value=""> Select </option>
                                    <option value="bulk">Bulk</option>
                                    <option value="specific">Specific</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <div class="form-group">
                                <label>Access User</label>
                                <select name="user_type" id="user_type" onchange="getUserAccess(this);" class="form-select " aria-label="Default select example">
                                    <option value=""> Select </option>
                                    <option value="All"> All </option>
                                    <option value="Company Incorporation">Company Incorporation</option>
                                    <option value="Company Compliances">Company Compliances</option>
                                    <option value="ROC Return">ROC Return</option>
                                    <option value="Accounts Preparation">Accounts Preparation</option>
                                    <option value="GST &amp; Taxation">GST &amp; Taxation</option>
                                    <option value="Auditing">Auditing</option>
                                    <option value="Auditor Recruitment">Auditor Recruitment</option>
                                    <option value="Licensing &amp; Registration">Licensing &amp; Registration</option>
                                    <option value="Income Tax Return">Income Tax Return</option>
                                    <option value="TDS">TDS</option>
                                    <option value="PF &amp; ESIC">PF &amp; ESIC</option>
                                    <option value="P-tax">P-tax</option>
                                    <option value="Project Report / DPR with CMA Data">Project Report / DPR with CMA Data
                                    </option>
                                    <option value="Outsourcing of work">Outsourcing of work</option>
                                    <option value="Outsourcing of employee">Outsourcing of employee</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <div class="form-group">
                                <label>Customer Type</label>
                                <select name="customer_type" id="customer_type" onchange="getUserAccessByStatus(this);" class="form-select has-success" aria-label="Default select example" aria-invalid="false">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                    <option value="All">All</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <div class="form-group">
                                <label>Reminder Through</label>
                                <select name="reminder_through" id="reminder_through" class="form-select " aria-label="Default select example">
                                    <option value=""> Select </option>
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
            </div>
            <div class="card">
                <div class="card-body d-flex flex-wrap gap-3">
                    <div class="toast align-items-center border-0 show toastContainer" id="toastContainer" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                        
                    </div>
                    
                </div>
            </div>
            <div class="card" id="reminder-composer-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="exampleInputEmail4">Subject</label>
                                <input type="text" name="sub_text" id="sub_text" class="form-control" required  placeholder="Subject">
                            </div>
                        </div>
                        <div class="col-md-9 mb-3">
                            <textarea name="msg_text" id="msg_text" required class="form-control" placeholder="Enter Message" rows="14"></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="upload-area">
                                <span class="">Click to Upload or Drag &amp; Drop</span>
                                <input type="file" id="fileAttached" name="fileAttached" class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden="">
                                <div class="file-preview-container">
                                    <div class="file-preview">
                                        <div class="file-info">
                                            <div class="file-name"></div>
                                            <div class="file-size">Uploaded File</div>
                                        </div>
                                        
                                        <a href="" download="" class="btn btn-success btn-sm">Download</a>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <button class="btn btn-primary" type="submit">Send Reminder</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("reminder_type").addEventListener("change", function () {
                var selectField = document.querySelector(".select.tagging");
                var options = selectField.options;
 
                if (this.value === "1") { // Bulk
                    for (var i = 0; i < options.length; i++) {
                        options[i].selected = true;
                    }
                } else if (this.value === "2") { // Specific
                    // You will manually select options
                }
            });
        });

        function startCAReminderTour() {
            if (typeof introJs !== 'function') return;

            introJs().setOptions({
                steps: [
                    {
                        title: 'Broadcast Reminders',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-bell-ringing" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Broadcasting tool to notify client companies about tax deadlines, document collections, and outstanding updates.</p></div>'
                    },
                    {
                        element: '#reminder-settings-card',
                        title: 'Reminder Settings',
                        intro: 'Define the reminder type (Bulk or Specific), target users by module (GST, ROC, Audit), status (Active, Inactive), and preferred communication channel (WhatsApp, Email, Notification).'
                    },
                    {
                        element: '#reminder-composer-card',
                        title: 'Message Composer',
                        intro: 'Enter subject, write your message, upload templates/documents via drag & drop, and dispatch to selected recipients.'
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
            $('#start-ca-reminder-tour').on('click', function(e) {
                e.preventDefault();
                startCAReminderTour();
            });
        });
    </script>
@endsection
