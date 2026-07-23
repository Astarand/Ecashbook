@extends('App.Layout')

@section('container')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/ca-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/ca-compliances-list') }}">Compliances</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add Compliance</li>
                        </ul>
                        <a href="javascript:void(0);" onclick="startAddStatutoryTour();" id="start-add-statutory-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="page-header-title">
                            <h2 class="mb-0" style="font-size: 1.5rem; font-weight: 600;">Add Statutory & Compliances</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="javascript:void(0);" method="POST" name="addStatutoryFrm" id="addStatutoryFrm">
                    <input type="hidden" name="id" id="eId" value="">
                    @csrf
                    <div class="form-group-item">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="inputEmail4">Company</label>
                                    <div class="form-group">
                                        <select class="form-select" name="compId" id="compId">
                                            <option value="">Select</option>
                                            @foreach($companys as $k=>$company)
                                            <option value="{{ $company->userId }}">{{ $company->comp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="inputEmail4">Statutory & Compliance</label>
                                    <select class="form-select" name="statutory_doc" id="statutory_doc">
                                        <option value="">Select</option>
                                        <option value="Annual GST Return">Annual GST Return</option>
                                        <option value="Annual P-tax Return">Annual P-tax Return</option>
                                        <option value="Annual TDS Return">Annual TDS Return </option>
                                        <option value="Excise Duty Return">Excise Duty Return</option>
                                        <option value="FSSAI Renew">FSSAI Renew</option>
                                        <option value="Income Tax Return (ITR)">Income Tax Return (ITR)</option>
                                        <option value="Monthly GST Return">Monthly GST Return</option>
                                        <option value="Monthly TDS Return">Monthly TDS Return</option>
                                        <option value="Monthly PF Payment">Monthly PF Payment</option>
                                        <option value="Monthly ESIC Return">Monthly ESIC Return</option>
                                        <option value="Monthly P-tax Payment">Monthly P-tax Payment</option>
                                        <option value="Payroll Tax Return">Payroll Tax Return</option>
                                        <option value="P-tax Enrollment Payment">P-tax Enrollment Payment</option>
                                        <option value="Quaterly TDS Return">Quaterly TDS Return</option>
                                        <option value="Quater GST Return">Quater GST Return</option>
                                        <option value="ROC Return">ROC Return</option>
                                        <option value="Shop & Establishment Renew">Shop & Establishment Renew</option>
                                        <option value="TCS Return">TCS Return</option>
                                        <option value="Tax Audit">Tax Audit</option>
                                        <option value="Trade License Renew">Trade License Renew</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3" id="other_statutory_div" style="display:none;">
                                <label class="form-label">Enter Other Compliance <span class="text-danger">*</span></label>
                                <input type="text" name="other_statutory_doc" id="other_statutory_doc" class="form-control" placeholder="Enter Compliance Name">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label class="form-label" for="inputEmail4">Due Date</label>
                                <input type="Date" name="statutory_due_date" id="statutory_due_date" class="form-control" placeholder="Enter Date">
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="inputEmail4">Message By CA</label>
                                <input type="text" name="statutory_msg" id="statutory_msg" class="form-control" placeholder="Enter Message">
                            </div>
                        </div>
                    </div>
                    <div class="message-container"></div>
                    <div id="statutoryLoader" class="loader"></div>
                    <div class="add-customer-btns text-end">
                        <a href="{{ url('/ca-compliances-list') }}" class="btn btn-danger me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('page-script')
<script>
    function startAddStatutoryTour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Add Statutory & Compliance',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-file-text" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Onboard new statutory compliance records and due dates for your client companies.</p></div>'
                    },
                    {
                        element: '#compId',
                        title: 'Client Company Selection',
                        intro: 'Choose the client company for which this compliance record is being configured.'
                    },
                    {
                        element: '#statutory_doc',
                        title: 'Compliance Category',
                        intro: 'Select the type of tax or registry filing compliance (e.g. GST return, ITR, PF Payment).'
                    },
                    {
                        element: '#statutory_due_date',
                        title: 'Due Date',
                        intro: 'Set the official deadline date for the compliance task.'
                    },
                    {
                        element: '#statutory_msg',
                        title: 'CA Comments & Notes',
                        intro: 'Provide any instructions, descriptions, or comments for the client regarding this compliance.'
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

        if (typeof introJs === 'function') {
            launch();
        } else {
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    $(document).ready(function() {
        $('#statutory_doc').on('change', function () {
            if ($(this).val() === 'Other') {
                $('#other_statutory_div').show();
                $('#other_statutory_doc').prop('required', true);
            } else {
                $('#other_statutory_div').hide();
                $('#other_statutory_doc').prop('required', false).val('');
            }
        });

        $('#start-add-statutory-tour').on('click', function(e) {
            e.preventDefault();
            startAddStatutoryTour();
        });
    });
</script>
@endsection