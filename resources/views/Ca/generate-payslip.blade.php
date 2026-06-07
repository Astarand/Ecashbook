@extends('App.Layout')

@section('container')
    <div class="pc-content">
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header  align-items-center justify-content-between py-3">
                                <h4 class="text-center">
                                    Generate Payslip
                                </h4>
                            </div>
                            <div class="card-body">
                            <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="inputEmail4">Select Employee Name<span class="text-danger">*</span></label>
                                        <select class="form-select">
                                            <option selected>Select</option>
                                            <option value="1">Employee 1</option>
                                            <option value="2">Employee 2</option>
                                            <option value="3">Employee 3</option>
                                            <option value="3">Employee 4</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="inputEmail4">Select Financial Year<span class="text-danger">*</span></label>
                                        <select class="form-select">
                                            <option selected>Select Financial Year</option>
                                            <option value="1">2021-2022</option>
                                            <option value="2">2022-2023</option>
                                            <option value="3">2023-2024</option>
                                            <option value="3">2024-2025</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="reportType">Select Month<span class="text-danger">*</span></label>
                                        <select class="form-select" id="monthSelect">
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
                                        <a href="#" class="btn btn-primary w-100 mt-2">Submit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-xl-6 mb-3">
                                <div class="mb-0">
                                    <label class="form-label">Payslip Number</label>
                                    <input type="text" class="form-control" placeholder="#xxxx" >
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-6 mb-3">
                                <div class="mb-0">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" >
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Employee Name:</h5></div>
                                        <div class="col-6 text-end"><h5>John Doe</h5></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Date Of Joining:</h5></div>
                                        <div class="col-6 text-end"><h5>01/01/2020</h5></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Department:</h5></div>
                                        <div class="col-6 text-end"><h5>IT</h5></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Designation:</h5></div>
                                        <div class="col-6 text-end"><h5>Software Engineer</h5></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6"><h5>PF A/C No.:</h5></div>
                                        <div class="col-6 text-end"><h5>1234567890</h5></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Pay Period:</h5></div>
                                        <div class="col-6 text-end"><h5>January 2025</h5></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Working Day:</h5></div>
                                        <div class="col-6 text-end"><h5>22</h5></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><h5>Absent Day:</h5></div>
                                        <div class="col-6 text-end"><h5>3</h5></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6"><h5>Late Attendance:</h5></div>
                                        <div class="col-6 text-end"><h5>2</h5></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h5>Detail</h5>
                                <div class="table-responsive">
                                    <div class="container">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th colspan="2">Earnings</th>
                                                    <th colspan="2">Deductions</th>
                                                </tr>
                                                <tr class="table-light">
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Basic</td>
                                                    <td>₹ 10,000</td>
                                                    <td>Provident Fund</td>
                                                    <td>₹ 1,200</td>
                                                </tr>
                                                <tr>
                                                    <td>Incentive Pay</td>
                                                    <td>₹ 1,000</td>
                                                    <td>Professional Tax</td>
                                                    <td>₹ 500</td>
                                                </tr>
                                                <tr>
                                                    <td>House Rent Allowance</td>
                                                    <td>₹ 400</td>
                                                    <td>Loan</td>
                                                    <td>₹ 400</td>
                                                </tr>
                                                <tr>
                                                    <td>Meal Allowance</td>
                                                    <td>₹ 200</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr class="table-light">
                                                    <td><strong>Total Earnings</strong></td>
                                                    <td><strong>₹ 11,600</strong></td>
                                                    <td><strong>Total Deductions</strong></td>
                                                    <td><strong>₹ 2,100</strong></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td colspan="2"><strong>Net Pay</strong></td>
                                                    <td colspan="2"><strong>₹ 9,500</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

@endsection