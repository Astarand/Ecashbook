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
                            <li class="breadcrumb-item active" aria-current="page">Multiple Generate Payslip</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-generate-payslip-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  align-items-center justify-content-between py-3">
                            <h4 class="text-center">
                                Multile Generate Payslip
                            </h4>
                        </div>
                        <div class="card-body">
                            <form id="payslip_form">
                                <div class="row">
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Select Financial Year <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="select_financial_year"
                                            name="select_financial_year">
                                            <option selected>Select Financial Year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Select Month <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="monthSelect" name="monthSelect">
                                            <option selected>Select Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"></label>
                                        <button type="button" id="checkPayslipBtn"
                                            class="btn btn-primary w-100 mt-2">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>



@endsection
