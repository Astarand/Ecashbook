@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/deduction-master') }}">Deduction Master</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add Deduction</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Add Deduction</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('deduction-master.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Deduction Name *</label>
                        <input type="text" name="deduction_name" value="{{ old('deduction_name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Income Tax Section *</label>
                        <input type="text" name="income_tax_section" value="{{ old('income_tax_section') }}" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Deduction Category</label>
                        <select name="deduction_category" class="form-control">
                            <option value="">Select Category</option>

                            <option value="Business Deduction" {{ old('deduction_category') == 'Business Deduction' ? 'selected' : '' }}>
                                Business Deduction
                            </option>

                            <option value="Asset Deduction" {{ old('deduction_category') == 'Asset Deduction' ? 'selected' : '' }}>
                                Asset Deduction
                            </option>

                            <option value="Employee Deduction" {{ old('deduction_category') == 'Employee Deduction' ? 'selected' : '' }}>
                                Employee Deduction
                            </option>

                            <option value="Statutory Deduction" {{ old('deduction_category') == 'Statutory Deduction' ? 'selected' : '' }}>
                                Statutory Deduction
                            </option>

                            <option value="Startup Deduction" {{ old('deduction_category') == 'Startup Deduction' ? 'selected' : '' }}>
                                Startup Deduction
                            </option>

                            <option value="Donation Deduction" {{ old('deduction_category') == 'Donation Deduction' ? 'selected' : '' }}>
                                Donation Deduction
                            </option>

                            <option value="Research Deduction" {{ old('deduction_category') == 'Research Deduction' ? 'selected' : '' }}>
                                Research Deduction
                            </option>

                            <option value="Partnership Deduction" {{ old('deduction_category') == 'Partnership Deduction' ? 'selected' : '' }}>
                                Partnership Deduction
                            </option>

                            <option value="Export Deduction" {{ old('deduction_category') == 'Export Deduction' ? 'selected' : '' }}>
                                Export Deduction
                            </option>

                            <option value="Loss Adjustment" {{ old('deduction_category') == 'Loss Adjustment' ? 'selected' : '' }}>
                                Loss Adjustment
                            </option>

                            <option value="Tax Credit Adjustment" {{ old('deduction_category') == 'Tax Credit Adjustment' ? 'selected' : '' }}>
                                Tax Credit Adjustment
                            </option>
                        </select>
                    </div>
                    {{-- <div class="col-md-4 mb-3">
                        <label class="form-label">Deduction Amount Logic</label>
                        <input type="text" name="deduction_amount_logic" value="{{ old('deduction_amount_logic') }}" class="form-control" placeholder="Actual Expense, As per Asset Register, etc.">
                    </div> --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Deduction Type</label>
                        <select name="deduction_type" class="form-control">
                            <option value="">Select Type</option>

                            <option value="Business Expense" {{ old('deduction_type') == 'Business Expense' ? 'selected' : '' }}>
                                Business Expense
                            </option>

                            <option value="Asset Related" {{ old('deduction_type') == 'Asset Related' ? 'selected' : '' }}>
                                Asset Related
                            </option>

                            <option value="Employee Related" {{ old('deduction_type') == 'Employee Related' ? 'selected' : '' }}>
                                Employee Related
                            </option>

                            <option value="Statutory Payment" {{ old('deduction_type') == 'Statutory Payment' ? 'selected' : '' }}>
                                Statutory Payment
                            </option>

                            <option value="Startup Benefit" {{ old('deduction_type') == 'Startup Benefit' ? 'selected' : '' }}>
                                Startup Benefit
                            </option>

                            <option value="Donation" {{ old('deduction_type') == 'Donation' ? 'selected' : '' }}>
                                Donation
                            </option>

                            <option value="Research & Development" {{ old('deduction_type') == 'Research & Development' ? 'selected' : '' }}>
                                Research & Development
                            </option>

                            <option value="Partner Related" {{ old('deduction_type') == 'Partner Related' ? 'selected' : '' }}>
                                Partner Related
                            </option>

                            <option value="Export Related" {{ old('deduction_type') == 'Export Related' ? 'selected' : '' }}>
                                Export Related
                            </option>

                            <option value="Loss Adjustment" {{ old('deduction_type') == 'Loss Adjustment' ? 'selected' : '' }}>
                                Loss Adjustment
                            </option>

                            <option value="Tax Credit" {{ old('deduction_type') == 'Tax Credit' ? 'selected' : '' }}>
                                Tax Credit
                            </option>

                            <option value="Investment Related" {{ old('deduction_type') == 'Investment Related' ? 'selected' : '' }}>
                                Investment Related
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Limit Type</label>
                        <select name="limit_type" class="form-control">
                            <option value="">Select Limit Type</option>

                            <option value="Formula Based" {{ old('limit_type') == 'Formula Based' ? 'selected' : '' }}>
                                Formula Based
                            </option>

                            <option value="Percentage Based" {{ old('limit_type') == 'Percentage Based' ? 'selected' : '' }}>
                                Percentage Based
                            </option>

                            <option value="Actual Eligible Amount" {{ old('limit_type') == 'Actual Eligible Amount' ? 'selected' : '' }}>
                                Actual Eligible Amount
                            </option>

                            <option value="Carry Forward Based" {{ old('limit_type') == 'Carry Forward Based' ? 'selected' : '' }}>
                                Carry Forward Based
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Base Amount Source</label>
                        <select name="base_amount_source" class="form-control">
                            <option value="">Select Base Amount Source</option>

                            <option value="Actual Expense Amount" {{ old('base_amount_source') == 'Actual Expense Amount' ? 'selected' : '' }}>
                                Actual Expense Amount
                            </option>

                            <option value="Asset Register Value" {{ old('base_amount_source') == 'Asset Register Value' ? 'selected' : '' }}>
                                Asset Register Value
                            </option>

                            <option value="Eligible Salary" {{ old('base_amount_source') == 'Eligible Salary' ? 'selected' : '' }}>
                                Eligible Salary
                            </option>

                            <option value="Actual Paid Amount" {{ old('base_amount_source') == 'Actual Paid Amount' ? 'selected' : '' }}>
                                Actual Paid Amount
                            </option>

                            <option value="Capital Balance" {{ old('base_amount_source') == 'Capital Balance' ? 'selected' : '' }}>
                                Capital Balance
                            </option>

                            <option value="Donation Amount" {{ old('base_amount_source') == 'Donation Amount' ? 'selected' : '' }}>
                                Donation Amount
                            </option>

                            <option value="Profit Before Deduction" {{ old('base_amount_source') == 'Profit Before Deduction' ? 'selected' : '' }}>
                                Profit Before Deduction
                            </option>

                            <option value="Carried Forward Loss" {{ old('base_amount_source') == 'Carried Forward Loss' ? 'selected' : '' }}>
                                Carried Forward Loss
                            </option>

                            <option value="Project Cost" {{ old('base_amount_source') == 'Project Cost' ? 'selected' : '' }}>
                                Project Cost
                            </option>

                            <option value="Eligible Turnover" {{ old('base_amount_source') == 'Eligible Turnover' ? 'selected' : '' }}>
                                Eligible Turnover
                            </option>

                            <option value="Export Profit" {{ old('base_amount_source') == 'Export Profit' ? 'selected' : '' }}>
                                Export Profit
                            </option>

                            <option value="Net Profit" {{ old('base_amount_source') == 'Net Profit' ? 'selected' : '' }}>
                                Net Profit
                            </option>

                            <option value="Partner Capital" {{ old('base_amount_source') == 'Partner Capital' ? 'selected' : '' }}>
                                Partner Capital
                            </option>

                            <option value="Taxable Profit" {{ old('base_amount_source') == 'Taxable Profit' ? 'selected' : '' }}>
                                Taxable Profit
                            </option>

                            <option value="Eligible Investment Amount" {{ old('base_amount_source') == 'Eligible Investment Amount' ? 'selected' : '' }}>
                                Eligible Investment Amount
                            </option>

                            <option value="MAT Credit Balance" {{ old('base_amount_source') == 'MAT Credit Balance' ? 'selected' : '' }}>
                                MAT Credit Balance
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Automation Mode</label>
                        <select name="automation_mode" class="form-control">
                            <option value="">Select Mode</option>

                            <option value="Fully Auto" {{ old('automation_mode') == 'Fully Auto' ? 'selected' : '' }}>
                                Fully Auto
                            </option>

                            <option value="Semi Auto" {{ old('automation_mode') == 'Semi Auto' ? 'selected' : '' }}>
                                Semi Auto
                            </option>

                            <option value="Manual" {{ old('automation_mode') == 'Manual' ? 'selected' : '' }}>
                                Manual
                            </option>

                            <option value="CA Verified" {{ old('automation_mode') == 'CA Verified' ? 'selected' : '' }}>
                                CA Verified
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Limit Value</label>
                        <input type="text" name="limit_value" value="{{ old('limit_value') }}" class="form-control" placeholder="Enter limit value">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Limit Formula</label>
                        <input type="text" name="limit_formula" value="{{ old('limit_formula') }}" class="form-control" placeholder="Enter limit formula">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Applicable FY</label>
                        <input type="text" name="applicable_fy" value="{{ old('applicable_fy') }}" class="form-control" placeholder="2025-2026">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Linked Module</label>
                        <select name="linked_module" class="form-control">
                            <option value="">Select Module</option>

                            <option value="Accounting Module" {{ old('linked_module') == 'Accounting Module' ? 'selected' : '' }}>
                                Accounting Module
                            </option>

                            <option value="Asset Module" {{ old('linked_module') == 'Asset Module' ? 'selected' : '' }}>
                                Asset Module
                            </option>

                            <option value="Payroll Module" {{ old('linked_module') == 'Payroll Module' ? 'selected' : '' }}>
                                Payroll Module
                            </option>

                            <option value="GST Module" {{ old('linked_module') == 'GST Module' ? 'selected' : '' }}>
                                GST Module
                            </option>

                            <option value="TDS Module" {{ old('linked_module') == 'TDS Module' ? 'selected' : '' }}>
                                TDS Module
                            </option>

                            <option value="Purchase Module" {{ old('linked_module') == 'Purchase Module' ? 'selected' : '' }}>
                                Purchase Module
                            </option>

                            <option value="Sales Module" {{ old('linked_module') == 'Sales Module' ? 'selected' : '' }}>
                                Sales Module
                            </option>

                            <option value="Loan Module" {{ old('linked_module') == 'Loan Module' ? 'selected' : '' }}>
                                Loan Module
                            </option>

                            <option value="Donation Module" {{ old('linked_module') == 'Donation Module' ? 'selected' : '' }}>
                                Donation Module
                            </option>

                            <option value="Startup Module" {{ old('linked_module') == 'Startup Module' ? 'selected' : '' }}>
                                Startup Module
                            </option>

                            <option value="Partner Capital Module" {{ old('linked_module') == 'Partner Capital Module' ? 'selected' : '' }}>
                                Partner Capital Module
                            </option>

                            <option value="Export Module" {{ old('linked_module') == 'Export Module' ? 'selected' : '' }}>
                                Export Module
                            </option>

                            <option value="Tax Computation Module" {{ old('linked_module') == 'Tax Computation Module' ? 'selected' : '' }}>
                                Tax Computation Module
                            </option>

                            <option value="MAT Register" {{ old('linked_module') == 'MAT Register' ? 'selected' : '' }}>
                                MAT Register
                            </option>

                            <option value="Manual Adjustment Module" {{ old('linked_module') == 'Manual Adjustment Module' ? 'selected' : '' }}>
                                Manual Adjustment Module
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Remarks / Notes</label>
                        <input type="text" name="remarks" value="{{ old('remarks') }}" class="form-control" placeholder="Enter remarks or notes">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Allow / Disallow</label>
                        <select name="allow_disallow_status" class="form-control">
                            <option value="allow" {{ old('allow_disallow_status') == 'allow' ? 'selected' : '' }}>Allow</option>
                            <option value="disallow" {{ old('allow_disallow_status') == 'disallow' ? 'selected' : '' }}>Disallow</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Active Status</label>
                        <select name="active_status" class="form-control">
                            <option value="1" {{ old('active_status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('active_status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('deduction-master.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Deduction</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
