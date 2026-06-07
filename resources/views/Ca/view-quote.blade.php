@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Quote</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <form action="javascript:void(0);" method="post" name="addTaskquoteFrm" id="addTaskquoteFrm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="quoteId" value="{{$quote->id}}">
                        @csrf
                        <div class="row mt-4">
                            <div class="col">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Compnay Name<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="task_cat" id="task_cat">
                                                <option value="">Select Category</option>                                                    
                                                <option value="Accounts Preparation" <?php echo ($quote->task_cat=='Accounts Preparation')? "selected":"" ?>>Accounts Preparation</option>
                                                <option value="Advisory Consulting" <?php echo ($quote->task_cat=='Advisory Consulting')? "selected":"" ?>>Advisory &amp; Consulting</option>
                                                <option value="Audits" <?php echo ($quote->task_cat=='Audits')? "selected":"" ?>>Audits</option>
                                                <option value="Business Development" <?php echo ($quote->task_cat=='Business Development')? "selected":"" ?>>Business Development</option>
                                                <option value="Case related matters" <?php echo ($quote->task_cat=='Case related matters')? "selected":"" ?>>Case related matters</option>
                                                <option value="Company Incorporation" <?php echo ($quote->task_cat=='Company Incorporation')? "selected":"" ?>>Company Incorporation</option>
                                                <option value="Company Compliances" <?php echo ($quote->task_cat=='Company Compliances')? "selected":"" ?>>Company Compliances</option>
                                                <option value="GST Returns" <?php echo ($quote->task_cat=='GST Returns')? "selected":"" ?>>GST Returns</option>
                                                <option value="HR Administration" <?php echo ($quote->task_cat=='HR Administration')? "selected":"" ?>>HR &amp; Administration</option>
                                                <option value="Income Tax Returns" <?php echo ($quote->task_cat=='Income Tax Returns')? "selected":"" ?>>Income Tax Returns</option>                                                    
                                                <option value="Legal Services" <?php echo ($quote->task_cat=='Legal Services')? "selected":"" ?>>Legal Services</option>
                                                <option value="Licensing Registration" <?php echo ($quote->task_cat=='Licensing Registration')? "selected":"" ?>>Licensing &amp; Registration</option>
                                                <option value="Outsourcing of Accountant" <?php echo ($quote->task_cat=='Outsourcing of Accountant')? "selected":"" ?>>Outsourcing of Accountant</option>
                                                <option value="Project Report / DPR" <?php echo ($quote->task_cat=='Project Report / DPR')? "selected":"" ?>>Project Report / DPR</option>
                                                <option value="P-Tax Trade License" <?php echo ($quote->task_cat=='P-Tax Trade License')? "selected":"" ?>>P-Tax &amp; Trade License</option>
                                                <option value="ROC Returns" <?php echo ($quote->task_cat=='ROC Returns')? "selected":"" ?>>ROC Returns</option>                                                    
                                                <option value="TDS, PF, ESIC" <?php echo ($quote->task_cat=='TDS, PF, ESIC')? "selected":"" ?>>TDS, PF &amp; ESIC</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Goverment Fees<span class="text-danger">*</span></label>
                                        <input type="text" name="govfee" id="govfee" value="{{$quote->govfee}}" class="form-control" placeholder="Enter Goverment Fees">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Services Charges<span class="text-danger">*</span></label>
                                        <input type="text" name="service_charge" value="{{$quote->service_charge}}" id="service_charge" class="form-control" placeholder="Enter Services Charges">
                                    </div>
                                </div>
                                 <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
                                        <a href="{{ route('ca.QuoteList') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection