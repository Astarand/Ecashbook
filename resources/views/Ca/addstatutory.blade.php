@extends('App.Layout')

@section('container')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="content-page-header">
                <h5>Add statutory & Compliances</h5>
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

<script>
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
    });
</script>
@endsection