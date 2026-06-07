@extends('App.Layout')

@section('container')

<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">GST Reports</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">GST Reports</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  align-items-center justify-content-between py-3">
                            <h4 class="text-center">
                                Generate GSTIN Reports
                            </h4>
							<div class="message-container"></div>
                        </div>
                        <div class="card-body">
                            <!-- First row for basic filters -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label" for="financialYear">Select Financial Year<span class="text-danger">*</span></label>
                                    <select class="form-select" id="financialYear">
                                        <option selected>Select Financial Year</option>
                                        <option value="2021-2022">2021-2022</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2024-2025">2024-2025</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="quarterSelect">Quarter<span class="text-danger">*</span></label>
                                    <select class="form-select" id="quarterSelect" onchange="updatePeriodOptions()">
                                        <option value="">Select Quarter</option>
                                        <option value="Q1">Quarter 1 (April - June)</option>
                                        <option value="Q2">Quarter 2 (July - September)</option>
                                        <option value="Q3">Quarter 3 (October - December)</option>
                                        <option value="Q4">Quarter 4 (January - March)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="periodSelect">Period<span class="text-danger">*</span></label>
                                    <select class="form-select" id="periodSelect" disabled>
                                        <option value="">Select Period</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Second row for report options -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="mainReportType">Select GST Report Type<span class="text-danger">*</span></label>
                                    <select class="form-select" id="mainReportType" onchange="updateChildReports()">
                                        <option value="">Select Report Type</option>
                                        <option value="outward_supplies">Outward Supplies (Sales Reports)</option>
                                        <option value="inward_supplies">Inward Supplies (Purchase Reports)</option>
                                        <option value="tax_payment">Tax Payment & Liability Reports</option>
                                        <option value="filing_status">Return Filing Status</option>
                                        <option value="composition">Composition Dealer Reports</option>
                                        <option value="tds_tcs">TDS/TCS Reports</option>
                                        <option value="isd">ISD (Input Service Distributor)</option>
                                        <option value="job_work">Job Work & Goods Movement</option>
                                        <option value="other_returns">Other GST Returns</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="childReportType">Report<span class="text-danger">*</span></label>
                                    <select class="form-select" id="childReportType" onchange="updateGrandchildReports()" disabled>
                                        <option value="">Select Report</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="grandchildReportType">Section<span class="text-danger">*</span></label>
                                    <select class="form-select" id="grandchildReportType" onchange="showSelectedReportTable()" disabled>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <a href="#" class="btn btn-primary w-100" onclick="generateReport()">Generate Report</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive report-table" id="summary" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Tax Period</th>
                                    <th>GST Collected (Output GST)</th>
                                    <th>GST Paid (Input GST)</th>
                                    <th>Net GST Payable/ Refund</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01/Jan/2025 - 31/Jan/2025</td>
                                    <td>₹50,000</td>
                                    <td>₹30,000</td>
                                    <td>₹20,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="tax_liability_overview" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Category</th>
                                    <th>Current Period</th>
                                    <th>Previous Period</th>
                                    <th>% Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Output Tax Liability</td>
                                    <td>₹ 45,000</td>
                                    <td>₹ 42,000</td>
                                    <td>7.14%</td>
                                </tr>
                                <tr>
                                    <td>Input Tax Credit</td>
                                    <td>₹ 32,000</td>
                                    <td>₹ 30,000</td>
                                    <td>6.67%</td>
                                </tr>
                                <tr>
                                    <td>Net Tax Liability</td>
                                    <td>₹ 13,000</td>
                                    <td>₹ 12,000</td>
                                    <td>8.33%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="compliance_status" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Return Type</th>
                                    <th>Due Date</th>
                                    <th>Filing Status</th>
                                    <th>Filed On</th>
                                    <th>ARN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>GSTR-1/IFF</td>
                                    <td>[Date]</td>
                                    <td>[Status]</td>
                                    <td>[Date]</td>
                                    <td>[ARN]</td>
                                </tr>
                                <tr>
                                    <td>GSTR-3B</td>
                                    <td>[Date]</td>
                                    <td>[Status]</td>
                                    <td>[Date]</td>
                                    <td>[ARN]</td>
                                </tr>
                                <tr>
                                    <td>GSTR-9</td>
                                    <td>[Date]</td>
                                    <td>[Status]</td>
                                    <td>[Date]</td>
                                    <td>[ARN]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="b2b_invoices" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN</th>
                                    <th>Trade Name</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Value</th>
                                    <th>Taxable Value</th>
                                    <th>Tax Rate</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[GSTIN]</td>
                                    <td>[Name]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="b2c_large" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Place of Supply</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Value</th>
                                    <th>Taxable Value</th>
                                    <th>Tax Rate</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[State Code]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="b2c_small" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Place of Supply</th>
                                    <th>Tax Rate</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[State Code]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="credit_debit_notes" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Note Type</th>
                                    <th>Note Number</th>
                                    <th>Note Date</th>
                                    <th>Against Invoice</th>
                                    <th>GSTIN</th>
                                    <th>Taxable Value</th>
                                    <th>Tax Rate</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Type]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>[Invoice No.]</td>
                                    <td>[GSTIN]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="export_invoices" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Export Type</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Port Code</th>
                                    <th>Shipping Bill</th>
                                    <th>Shipping Date</th>
                                    <th>Taxable Value</th>
                                    <th>Tax Rate</th>
                                    <th>IGST</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Type]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>[Code]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="hsn_summary" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>HSN/SAC</th>
                                    <th>Description</th>
                                    <th>UQC</th>
                                    <th>Total Quantity</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Code]</td>
                                    <td>[Description]</td>
                                    <td>[UQC]</td>
                                    <td>[Quantity]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="itc_available" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN</th>
                                    <th>Trade Name</th>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Eligibility</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[GSTIN]</td>
                                    <td>[Name]</td>
                                    <td>[Type]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="itc_not_available" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN</th>
                                    <th>Trade Name</th>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[GSTIN]</td>
                                    <td>[Name]</td>
                                    <td>[Type]</td>
                                    <td>[Number]</td>
                                    <td>[Date]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Reason]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="mismatch_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN</th>
                                    <th>Trade Name</th>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>In GSTR-2B</th>
                                    <th>In GSTR-1 of Supplier</th>
                                    <th>Difference</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[GSTIN]</td>
                                    <td>[Name]</td>
                                    <td>[Type]</td>
                                    <td>[Number]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="output_tax_liabilities" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Supply Type</th>
                                    <th>Taxable Value</th>
                                    <th>Tax Rate</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Type]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="itc_claimed" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>ITC Type</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Type]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="tax_payments" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Payment Type</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Cess</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Type]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="tax_comparison" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Parameter</th>
                                    <th>Current Period</th>
                                    <th>Previous Period</th>
                                    <th>YoY Same Period</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Outward Supply</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Icon]</td>
                                </tr>
                                <tr>
                                    <td>Total Inward Supply</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Icon]</td>
                                </tr>
                                <tr>
                                    <td>Output Tax Liability</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Icon]</td>
                                </tr>
                                <tr>
                                    <td>Input Tax Credit</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Icon]</td>
                                </tr>
                                <tr>
                                    <td>Tax Payment</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Icon]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="risk_analysis" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Risk Category</th>
                                    <th>Risk Level</th>
                                    <th>Observations</th>
                                    <th>Recommended Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Category]</td>
                                    <td>[Level]</td>
                                    <td>[Description]</td>
                                    <td>[Action]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="compliance_calendar" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Return Type</th>
                                    <th>For Period</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Reminder</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>[Type]</td>
                                    <td>[Period]</td>
                                    <td>[Date]</td>
                                    <td>[Status]</td>
                                    <td>[Setting]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-8 TCS Report -->
                    <div class="table-responsive report-table" id="gstr8_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Receiver</th>
                                    <th>Receiver Name</th>
                                    <th>Transaction Type</th>
                                    <th>Transaction Value</th>
                                    <th>Rate of Tax</th>
                                    <th>TCS Amount</th>
                                    <th>Date of Collection</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>XYZ Enterprises</td>
                                    <td>E-commerce Sale</td>
                                    <td>₹ 50,000</td>
                                    <td>1%</td>
                                    <td>₹ 500</td>
                                    <td>15-04-2025</td>
                                    <td>Collected</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-9 Annual Return -->
                    <div class="table-responsive report-table" id="gstr9_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Section</th>
                                    <th>Description</th>
                                    <th>Total Value</th>
                                    <th>Central Tax</th>
                                    <th>State/UT Tax</th>
                                    <th>Integrated Tax</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>Details of outward supplies made during the financial year</td>
                                    <td>₹ 1,50,00,000</td>
                                    <td>₹ 13,50,000</td>
                                    <td>₹ 13,50,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Details of inward supplies on which tax is payable on reverse charge basis</td>
                                    <td>₹ 20,00,000</td>
                                    <td>₹ 1,80,000</td>
                                    <td>₹ 1,80,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Details of ITC availed during the financial year</td>
                                    <td>-</td>
                                    <td>₹ 10,80,000</td>
                                    <td>₹ 10,80,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-9C Reconciliation Statement -->
                    <div class="table-responsive report-table" id="gstr9c_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Part</th>
                                    <th>Description</th>
                                    <th>As per Audited Accounts</th>
                                    <th>As per Annual Return</th>
                                    <th>Difference</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>5A</td>
                                    <td>Total Turnover</td>
                                    <td>₹ 1,55,00,000</td>
                                    <td>₹ 1,50,00,000</td>
                                    <td>₹ 5,00,000</td>
                                    <td>Credit notes issued after March</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Total ITC as per Accounts</td>
                                    <td>₹ 23,00,000</td>
                                    <td>₹ 21,60,000</td>
                                    <td>₹ 1,40,000</td>
                                    <td>Ineligible ITC under section 17(5)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-10 Final Return -->
                    <div class="table-responsive report-table" id="gstr10_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Section</th>
                                    <th>Description</th>
                                    <th>Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>Details of tax payable</td>
                                    <td>₹ 5,00,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 45,000</td>
                                    <td>₹ 45,000</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Details of ITC to be reversed</td>
                                    <td>₹ 2,00,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 18,000</td>
                                    <td>₹ 18,000</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Details of assets held on the date of cancellation</td>
                                    <td>₹ 10,00,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 90,000</td>
                                    <td>₹ 90,000</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-4 Composition Annual Return -->
                    <div class="table-responsive report-table" id="gstr4_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Table</th>
                                    <th>Description</th>
                                    <th>Total Turnover</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>4A</td>
                                    <td>Inward supplies received from registered suppliers</td>
                                    <td>₹ 15,00,000</td>
                                    <td>₹ 15,00,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 1,35,000</td>
                                    <td>₹ 1,35,000</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Details of outward supplies</td>
                                    <td>₹ 30,00,000</td>
                                    <td>₹ 30,00,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 45,000</td>
                                    <td>₹ 45,000</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Details of compounding tax payable</td>
                                    <td>₹ 30,00,000</td>
                                    <td>₹ 30,00,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 15,000</td>
                                    <td>₹ 15,000</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-5 Non-Resident Taxable Person -->
                    <div class="table-responsive report-table" id="gstr5_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Section</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Value</th>
                                    <th>Place of Supply</th>
                                    <th>Tax Rate</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>5A</td>
                                    <td>INV-001</td>
                                    <td>01-04-2025</td>
                                    <td>₹ 1,18,000</td>
                                    <td>09-Karnataka</td>
                                    <td>18%</td>
                                    <td>₹ 1,00,000</td>
                                    <td>₹ 18,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>5B</td>
                                    <td>INV-002</td>
                                    <td>15-04-2025</td>
                                    <td>₹ 1,18,000</td>
                                    <td>09-Karnataka</td>
                                    <td>18%</td>
                                    <td>₹ 1,00,000</td>
                                    <td>₹ 18,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-5A OIDAR Services -->
                    <div class="table-responsive report-table" id="gstr5a_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Month</th>
                                    <th>Place of Supply</th>
                                    <th>Service Type</th>
                                    <th>Rate</th>
                                    <th>Value of Supply</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>April 2025</td>
                                    <td>09-Karnataka</td>
                                    <td>Online Database Access</td>
                                    <td>18%</td>
                                    <td>₹ 5,00,000</td>
                                    <td>₹ 90,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>May 2025</td>
                                    <td>09-Karnataka</td>
                                    <td>Online Database Access</td>
                                    <td>18%</td>
                                    <td>₹ 4,50,000</td>
                                    <td>₹ 81,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-6 ISD Credit Distribution -->
                    <div class="table-responsive report-table" id="gstr6_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Supplier</th>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>ITC Available</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Cess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>Invoice</td>
                                    <td>INV-001</td>
                                    <td>01-04-2025</td>
                                    <td>Yes</td>
                                    <td>₹ 18,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>Invoice</td>
                                    <td>INV-002</td>
                                    <td>15-04-2025</td>
                                    <td>Yes</td>
                                    <td>₹ 27,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-7 TDS Report -->
                    <div class="table-responsive report-table" id="gstr7_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Deductee</th>
                                    <th>Deductee Name</th>
                                    <th>Document Type</th>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>Total Value</th>
                                    <th>Tax Rate</th>
                                    <th>TDS Amount</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>XYZ Enterprises</td>
                                    <td>Contract</td>
                                    <td>CONT-001</td>
                                    <td>01-04-2025</td>
                                    <td>₹ 10,00,000</td>
                                    <td>2%</td>
                                    <td>₹ 20,000</td>
                                    <td>10-05-2025</td>
                                </tr>
                                <tr>
                                    <td>27PQRST5678G1Z4</td>
                                    <td>ABC Corporation</td>
                                    <td>Contract</td>
                                    <td>CONT-002</td>
                                    <td>15-04-2025</td>
                                    <td>₹ 15,00,000</td>
                                    <td>2%</td>
                                    <td>₹ 30,000</td>
                                    <td>10-05-2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-11 UIN Holders Report -->
                    <div class="table-responsive report-table" id="gstr11_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Supplier</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Value</th>
                                    <th>Place of Supply</th>
                                    <th>Supply Type</th>
                                    <th>Tax Rate</th>
                                    <th>IGST Refund</th>
                                    <th>CGST Refund</th>
                                    <th>SGST Refund</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>INV-001</td>
                                    <td>01-04-2025</td>
                                    <td>₹ 1,18,000</td>
                                    <td>09-Karnataka</td>
                                    <td>Goods</td>
                                    <td>18%</td>
                                    <td>₹ 18,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                                <tr>
                                    <td>27PQRST5678G1Z4</td>
                                    <td>INV-002</td>
                                    <td>15-04-2025</td>
                                    <td>₹ 2,36,000</td>
                                    <td>09-Karnataka</td>
                                    <td>Services</td>
                                    <td>18%</td>
                                    <td>₹ 36,000</td>
                                    <td>₹ 0</td>
                                    <td>₹ 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ITC-04 Job Work Report -->
                    <div class="table-responsive report-table" id="itc04_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Job Worker</th>
                                    <th>Challan Number</th>
                                    <th>Challan Date</th>
                                    <th>Description</th>
                                    <th>Nature of Processing</th>
                                    <th>Quantity</th>
                                    <th>Taxable Value</th>
                                    <th>Expected Return Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>CH-001</td>
                                    <td>01-04-2025</td>
                                    <td>Raw Materials</td>
                                    <td>Processing</td>
                                    <td>100 Kg</td>
                                    <td>₹ 50,000</td>
                                    <td>01-07-2025</td>
                                    <td>Sent</td>
                                </tr>
                                <tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>CH-002</td>
                                    <td>15-03-2025</td>
                                    <td>Semi-finished Goods</td>
                                    <td>Assembly</td>
                                    <td>50 Units</td>
                                    <td>₹ 1,00,000</td>
                                    <td>15-06-2025</td>
                                    <td>Received</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- CMP-08 Quarterly Return -->
                    <div class="table-responsive report-table" id="cmp08_report" style="display: none;">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Quarter</th>
                                    <th>Financial Year</th>
                                    <th>Outward Supplies</th>
                                    <th>Tax Rate</th>
                                    <th>Tax Amount</th>
                                    <th>Payment Date</th>
                                    <th>ARN</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Q1</td>
                                    <td>2025-26</td>
                                    <td>₹ 15,00,000</td>
                                    <td>1%</td>
                                    <td>₹ 15,000</td>
                                    <td>18-07-2025</td>
                                    <td>AB330525001234</td>
                                    <td>Filed</td>
                                </tr>
                                <tr>
                                    <td>Q4</td>
                                    <td>2024-25</td>
                                    <td>₹ 18,00,000</td>
                                    <td>1%</td>
                                    <td>₹ 18,000</td>
                                    <td>18-04-2025</td>
                                    <td>AB330525005678</td>
                                    <td>Filed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
					<?php if($compData->time =="1") { ?>
                    <div class="col-md-12 text-end mt-4">
                        <button type="button" class="btn btn-secondary me-2">Print</button>
                        <button type="button" class="btn btn-primary">Download</button>
                    </div>
					<?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>
<!-- [ Main Content ] end -->
</div>

<?php if($compData->time =="0") { ?>
<!---gstUserNameModalLabel------->
<div class="modal fade" id="gstUserNameModal" tabindex="-1" aria-labelledby="gstUserNameModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="stockInModalLabel">Gst User Name</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<form action="javascript:void(0);" method="POST" name="frmGstUserName" id="frmGstUserName" enctype="multipart/form-data">
			  @csrf
			  <input type="text" name="gst_username" id="gst_username" value="<?php echo $compData->gst_username; ?>">
			  <div class="row g-3 mb-3">
					<div class="col-md-12 mt-5">
					  <h6>Please enter register username of <a href="https://www.gst.gov.in">https://www.gst.gov.in</a></h6>
					</div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">Submit</button>
				  </div>
			  </div>
			</form>
		</div>
	  </div>
	</div>
</div>

<!---gstAuthenticateModalLabel------->
<div class="modal fade" id="gstAuthenticateModal" tabindex="-1" aria-labelledby="gstAuthenticateModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="stockInModalLabel">Gst Authenticate</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<div class="message-container"></div>
			<form action="javascript:void(0);" method="POST" name="frmGstAuthenticate" id="frmGstAuthenticate" enctype="multipart/form-data">			  
			  @csrf
			  <!-- Quantity and Units -->
			  <div class="row g-3 mb-3">
				<div class="col-md-6">
				  <label for="itemDate" class="form-label">OTP <span class="text-danger">*</span></label>
				  <input type="text" required class="form-control" id="otp" name="otp">
				</div>
				
			  <div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger">Submit</button>
			  </div>
			</form>
		  </div>

		</div>
	  </div>
	</div>
</div>
<?php } ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
		$("#gstUserNameModal").modal('show');
});	
    function togglePeriodSelect() {
        const reportType = document.getElementById('reportType').value;
        const monthSelectContainer = document.getElementById('monthSelectContainer');
        const quarterSelectContainer = document.getElementById('quarterSelectContainer');
        const monthSelect = document.getElementById('monthSelect');
        const quarterSelect = document.getElementById('quarterSelect');

        // Hide both dropdowns initially
        monthSelectContainer.style.display = 'none';
        quarterSelectContainer.style.display = 'none';
        monthSelect.disabled = true;
        quarterSelect.disabled = true;

        // Show appropriate dropdown based on selection
        if (reportType === 'Monthly') {
            monthSelectContainer.style.display = 'block';
            monthSelect.disabled = false;
        } else if (reportType === 'Quarterly') {
            quarterSelectContainer.style.display = 'block';
            quarterSelect.disabled = false;
        }
    }

    function toggleReportTypeSelect() {
        const reportCategory = document.getElementById('reportCategory').value;
        const summaryTypeContainer = document.getElementById('summaryTypeContainer');
        const detailedReportContainer = document.getElementById('detailedReportContainer');

        // Hide all option containers first
        summaryTypeContainer.style.display = 'none';
        detailedReportContainer.style.display = 'none';
        hideAllDetailedReportOptions();
        hideAllTables();

        // Show appropriate container based on selection
        if (reportCategory === 'Summary') {
            summaryTypeContainer.style.display = 'block';
        } else if (reportCategory === 'Detailed') {
            detailedReportContainer.style.display = 'block';
        }
    }

    function toggleDetailedReportOptions() {
        const detailedReportType = document.getElementById('detailedReportType').value;

        // Hide all containers first
        hideAllDetailedReportOptions();
        hideAllTables();

        // Show appropriate container based on selection
        if (detailedReportType === 'gstr1') {
            document.getElementById('gstr1Container').style.display = 'block';
        } else if (detailedReportType === 'gstr2b') {
            document.getElementById('gstr2bContainer').style.display = 'block';
        } else if (detailedReportType === 'gstr3b') {
            document.getElementById('gstr3bContainer').style.display = 'block';
        } else if (detailedReportType === 'analytics') {
            document.getElementById('analyticsContainer').style.display = 'block';
        }
    }

    function hideAllDetailedReportOptions() {
        document.getElementById('gstr1Container').style.display = 'none';
        document.getElementById('gstr2bContainer').style.display = 'none';
        document.getElementById('gstr3bContainer').style.display = 'none';
        document.getElementById('analyticsContainer').style.display = 'none';
    }

    function hideAllTables() {
        document.querySelectorAll(".report-table").forEach(table => {
            table.style.display = "none";
        });
    }

    function showSelectedTable() {
        hideAllTables();

        let selectedTable = null;

        // Check which category is active
        const reportCategory = document.getElementById('reportCategory').value;

        if (reportCategory === 'Summary') {
            // Handle summary dashboard selection
            const summaryType = document.getElementById('summaryType').value;
            if (summaryType && summaryType !== 'Select Summary Type') {
                selectedTable = document.getElementById(summaryType);
            }
        } else if (reportCategory === 'Detailed') {
            // Handle detailed reports based on which detailed type is selected
            const detailedReportType = document.getElementById('detailedReportType').value;

            if (detailedReportType === 'gstr1') {
                const gstr1Type = document.getElementById('gstr1Type').value;
                if (gstr1Type && gstr1Type !== 'Select Type') {
                    selectedTable = document.getElementById(gstr1Type);
                }
            } else if (detailedReportType === 'gstr2b') {
                const gstr2bType = document.getElementById('gstr2bType').value;
                if (gstr2bType && gstr2bType !== 'Select Type') {
                    selectedTable = document.getElementById(gstr2bType);
                }
            } else if (detailedReportType === 'gstr3b') {
                const gstr3bType = document.getElementById('gstr3bType').value;
                if (gstr3bType && gstr3bType !== 'Select Type') {
                    selectedTable = document.getElementById(gstr3bType);
                }
            } else if (detailedReportType === 'analytics') {
                const analyticsType = document.getElementById('analyticsType').value;
                if (analyticsType && analyticsType !== 'Select Type') {
                    selectedTable = document.getElementById(analyticsType);
                }
            }
        }

        // Display the selected table if found
        if (selectedTable) {
            selectedTable.style.display = "block";
        }
    }

    function updatePeriodOptions() {
        const quarterSelect = document.getElementById('quarterSelect');
        const periodSelect = document.getElementById('periodSelect');

        // Clear previous options
        periodSelect.innerHTML = '<option value="">Select Period</option>';

        // Enable/disable based on selection
        if (quarterSelect.value === '') {
            periodSelect.disabled = true;
            return;
        }

        periodSelect.disabled = false;

        // Add appropriate months based on selected quarter
        let months = [];

        switch (quarterSelect.value) {
            case 'Q1':
                months = [{
                        value: '04',
                        text: 'April'
                    },
                    {
                        value: '05',
                        text: 'May'
                    },
                    {
                        value: '06',
                        text: 'June'
                    }
                ];
                break;
            case 'Q2':
                months = [{
                        value: '07',
                        text: 'July'
                    },
                    {
                        value: '08',
                        text: 'August'
                    },
                    {
                        value: '09',
                        text: 'September'
                    }
                ];
                break;
            case 'Q3':
                months = [{
                        value: '10',
                        text: 'October'
                    },
                    {
                        value: '11',
                        text: 'November'
                    },
                    {
                        value: '12',
                        text: 'December'
                    }
                ];
                break;
            case 'Q4':
                months = [{
                        value: '01',
                        text: 'January'
                    },
                    {
                        value: '02',
                        text: 'February'
                    },
                    {
                        value: '03',
                        text: 'March'
                    }
                ];
                break;
        }

        // Add options to select
        months.forEach(month => {
            const option = document.createElement('option');
            option.value = month.value;
            option.textContent = month.text;
            periodSelect.appendChild(option);
        });
    }

    // Report structure definition
    const gstReportStructure = {
        outward_supplies: {
            label: "1. Outward Supplies (Sales Reports)",
            children: {
                gstr1: {
                    label: "GSTR-1 (Invoice-level monthly/quarterly return)",
                    children: {
                        b2b: "B2B (Business to Business)",
                        b2cl: "B2CL (B2C Large – Interstate > ₹2.5L)",
                        b2cs: "B2CS (B2C Small – Others)",
                        cdnr: "CDNR (Credit/Debit Notes – Registered)",
                        cdnur: "CDNUR (Credit/Debit Notes – Unregistered)",
                        exp: "EXP (Exports)",
                        at: "AT (Advance Receipts)",
                        atadj: "ATADJ (Advance Adjustments)",
                        nil: "Nil Rated/Exempted",
                        hsn: "HSN Summary"
                    }
                },
                gstr3b_outward: {
                    label: "GSTR-3B – Outward Summary",
                    children: {}
                },
                gstr9_sales: {
                    label: "GSTR-9 – Annual Sales Summary",
                    children: {}
                }
            }
        },
        inward_supplies: {
            label: "2. Inward Supplies (Purchase Reports)",
            children: {
                gstr2a: {
                    label: "GSTR-2A (Auto-drafted dynamic return)",
                    children: {
                        b2b_invoices: "B2B Invoices",
                        cdns_received: "CDNs Received",
                        isd_credits: "ISD Credits",
                        import_goods: "Import of Goods (from SEZ/Overseas)",
                        tds_tcs_credits: "TDS/TCS Credits"
                    }
                },
                gstr2b: {
                    label: "GSTR-2B (Static ITC statement)",
                    children: {
                        eligible_itc: "Eligible ITC",
                        ineligible_itc: "Ineligible ITC",
                        blocked_itc: "Blocked ITC (Section 17(5))"
                    }
                },
                gstr3b_itc: {
                    label: "GSTR-3B – ITC Claimed & Summary",
                    children: {}
                },
                gstr9_purchase: {
                    label: "GSTR-9 – Annual Purchase Summary",
                    children: {}
                }
            }
        },
        tax_payment: {
            label: "3. Tax Payment & Liability Reports",
            children: {
                gstr3b_tax: {
                    label: "GSTR-3B – Tax Payable, ITC, and Payments",
                    children: {}
                },
                pmt06: {
                    label: "PMT-06 – Challan Payments Summary",
                    children: {}
                },
                drc03: {
                    label: "DRC-03 – Voluntary Tax Payment Summary",
                    children: {}
                },
                gstr9c: {
                    label: "GSTR-9C – Reconciliation & Audit Report",
                    children: {}
                }
            }
        },
        filing_status: {
            label: "4. Return Filing Status",
            children: {
                gstr1_status: {
                    label: "GSTR-1 Filing Status (Monthly/Quarterly)",
                    children: {}
                },
                gstr3b_status: {
                    label: "GSTR-3B Filing Status",
                    children: {}
                },
                gstr4_status: {
                    label: "GSTR-4 (Composition Annual Return) Status",
                    children: {}
                },
                cmp08_status: {
                    label: "CMP-08 (Composition Quarterly) Status",
                    children: {}
                },
                gstr9_status: {
                    label: "GSTR-9 & 9C Filing Status",
                    children: {}
                },
                gstr10_status: {
                    label: "GSTR-10 (Final Return) Filing Status",
                    children: {}
                }
            }
        },
        composition: {
            label: "5. Composition Dealer Reports",
            children: {
                cmp08: {
                    label: "CMP-08 (Quarterly Return)",
                    children: {}
                },
                gstr4: {
                    label: "GSTR-4 (Annual Return)",
                    children: {}
                }
            }
        },
        tds_tcs: {
            label: "6. TDS/TCS Reports",
            children: {
                gstr7: {
                    label: "GSTR-7 (TDS Deducted by Govt Entities)",
                    children: {}
                },
                gstr8: {
                    label: "GSTR-8 (TCS Collected by E-Commerce Operators)",
                    children: {}
                }
            }
        },
        isd: {
            label: "7. ISD (Input Service Distributor)",
            children: {
                gstr6: {
                    label: "GSTR-6 (ISD Input Credit Distribution)",
                    children: {}
                }
            }
        },
        job_work: {
            label: "8. Job Work & Goods Movement",
            children: {
                itc04: {
                    label: "ITC-04 (Goods Sent/Received for Job Work)",
                    children: {}
                }
            }
        },
        other_returns: {
            label: "9. Other GST Returns",
            children: {
                gstr5: {
                    label: "GSTR-5 (Non-Resident Foreign Taxpayers)",
                    children: {}
                },
                gstr5a: {
                    label: "GSTR-5A (OIDAR – Online Services from Abroad)",
                    children: {}
                },
                gstr10: {
                    label: "GSTR-10 (Final Return on Cancellation)",
                    children: {}
                },
                gstr11: {
                    label: "GSTR-11 (UIN Holders – Embassies/UN)",
                    children: {}
                }
            }
        }
    };

    // Mapping report types to their respective table IDs
    const reportTableMapping = {
        // GSTR-1 sections
        b2b: "b2b_invoices",
        b2cl: "b2c_large",
        b2cs: "b2c_small",
        cdnr: "credit_debit_notes",
        cdnur: "credit_debit_notes",
        exp: "export_invoices",
        at: "tax_liability_overview",
        atadj: "tax_liability_overview",
        nil: "summary",
        hsn: "hsn_summary",

        // GSTR-2A/2B sections
        b2b_invoices: "b2b_invoices",
        cdns_received: "credit_debit_notes",
        isd_credits: "summary",
        import_goods: "summary",
        tds_tcs_credits: "summary",
        eligible_itc: "itc_available",
        ineligible_itc: "itc_not_available",
        blocked_itc: "itc_not_available",

        // Main report types direct mapping
        gstr1: "b2b_invoices",
        gstr2a: "b2b_invoices",
        gstr2b: "itc_available",
        gstr3b_outward: "output_tax_liabilities",
        gstr3b_itc: "itc_claimed",
        gstr3b_tax: "output_tax_liabilities",
        gstr9_sales: "gstr9_report",
        gstr9_purchase: "gstr9_report",
        pmt06: "tax_payments",
        drc03: "tax_payments",
        gstr9c: "gstr9c_report",

        // Filing status reports
        gstr1_status: "compliance_status",
        gstr3b_status: "compliance_status",
        gstr4_status: "compliance_status",
        cmp08_status: "compliance_status",
        gstr9_status: "compliance_status",
        gstr10_status: "compliance_status",

        // Composition reports
        cmp08: "cmp08_report",
        gstr4: "gstr4_report",

        // TDS/TCS reports
        gstr7: "gstr7_report",
        gstr8: "gstr8_report",

        // ISD reports
        gstr6: "gstr6_report",

        // Job work
        itc04: "itc04_report",

        // Other returns
        gstr5: "gstr5_report",
        gstr5a: "gstr5a_report",
        gstr10: "gstr10_report",
        gstr11: "gstr11_report",

        // Analytics
        tax_comparison: "tax_comparison",
        compliance_calendar: "compliance_calendar"
    };

    function updateChildReports() {
        const mainReportType = document.getElementById('mainReportType').value;
        const childReportSelect = document.getElementById('childReportType');
        const grandchildReportSelect = document.getElementById('grandchildReportType');

        // Reset child and grandchild dropdowns
        childReportSelect.innerHTML = '<option value="">Select Report</option>';
        grandchildReportSelect.innerHTML = '<option value="">Select Section</option>';

        // Disable by default
        childReportSelect.disabled = true;
        grandchildReportSelect.disabled = true;

        // Hide all tables
        hideAllTables();

        if (mainReportType && mainReportType !== "") {
            const mainReport = gstReportStructure[mainReportType];

            if (mainReport && mainReport.children) {
                // Enable child dropdown
                childReportSelect.disabled = false;

                // Populate child reports
                Object.keys(mainReport.children).forEach(childKey => {
                    const option = document.createElement('option');
                    option.value = childKey;
                    option.textContent = mainReport.children[childKey].label;
                    childReportSelect.appendChild(option);
                });
            }
        }
    }

    function updateGrandchildReports() {
        const mainReportType = document.getElementById('mainReportType').value;
        const childReportType = document.getElementById('childReportType').value;
        const grandchildReportSelect = document.getElementById('grandchildReportType');

        // Reset grandchild dropdown
        grandchildReportSelect.innerHTML = '<option value="">Select Section</option>';

        // Disable by default
        grandchildReportSelect.disabled = true;

        // Hide all tables
        hideAllTables();

        if (mainReportType && childReportType && mainReportType !== "" && childReportType !== "") {
            const mainReport = gstReportStructure[mainReportType];

            if (mainReport && mainReport.children && mainReport.children[childReportType]) {
                const childReport = mainReport.children[childReportType];

                if (childReport && childReport.children) {
                    // Check if there are any grandchild reports
                    const grandchildKeys = Object.keys(childReport.children);

                    if (grandchildKeys.length > 0) {
                        // Enable grandchild dropdown
                        grandchildReportSelect.disabled = false;

                        // Populate grandchild reports
                        grandchildKeys.forEach(grandchildKey => {
                            const option = document.createElement('option');
                            option.value = grandchildKey;
                            option.textContent = childReport.children[grandchildKey];
                            grandchildReportSelect.appendChild(option);
                        });
                    } else {
                        // No grandchild reports, we can directly show the child report table
                        showReportTableForSelection(mainReportType, childReportType);
                    }
                } else {
                    // If childReport doesn't have children property, still show the table
                    showReportTableForSelection(mainReportType, childReportType);
                }
            }
        }
    }

    function showSelectedReportTable() {
        const mainReportType = document.getElementById('mainReportType').value;
        const childReportType = document.getElementById('childReportType').value;
        const grandchildReportType = document.getElementById('grandchildReportType').value;

        hideAllTables();

        if (grandchildReportType && grandchildReportType !== "") {
            showReportTableForSelection(mainReportType, childReportType, grandchildReportType);
        }
    }

    function showReportTableForSelection(mainType, childType, grandchildType = null) {
        // Determine which table to show based on the selection
        let tableId = null;

        if (grandchildType && reportTableMapping[grandchildType]) {
            // If we have a grandchild and it's in our mapping, use it
            tableId = reportTableMapping[grandchildType];
            console.log("Showing table for grandchild: " + grandchildType + " => " + tableId);
        } else if (reportTableMapping[childType]) {
            // Otherwise use the child type if it's in our mapping
            tableId = reportTableMapping[childType];
            console.log("Showing table for child: " + childType + " => " + tableId);
        } else {
            // Default to summary table if no specific mapping found
            tableId = "summary";
            console.log("No specific table found, defaulting to summary");
        }

        // If we found a mapping, show the table
        if (tableId) {
            const table = document.getElementById(tableId);
            if (table) {
                table.style.display = "block";
                console.log("Successfully displayed table: " + tableId);
            } else {
                // If the table doesn't exist, show the summary table as a fallback
                const summaryTable = document.getElementById("summary");
                if (summaryTable) {
                    summaryTable.style.display = "block";
                    console.log("Table " + tableId + " not found, showing summary table instead");
                }
            }

            // Show the print and download buttons
            document.querySelectorAll('.col-md-12.text-end.mt-4')[0].style.display = "block";
        }
    }

    function generateReport() {
		
        const mainReportType = document.getElementById('mainReportType').value;
        const childReportType = document.getElementById('childReportType').value;
        const grandchildReportType = document.getElementById('grandchildReportType').value;

        if (!mainReportType || mainReportType === "") {
            alert("Please select a GST Report Type");
            return;
        }

        if (!childReportType || childReportType === "") {
            alert("Please select a Report");
            return;
        }

        // Check if the report has grandchild options and if one is selected
        const mainReport = gstReportStructure[mainReportType];
        if (mainReport && mainReport.children && mainReport.children[childReportType]) {
            const childReport = mainReport.children[childReportType];
            const hasGrandchildren = childReport.children && Object.keys(childReport.children).length > 0;

            if (hasGrandchildren && (!grandchildReportType || grandchildReportType === "")) {
                alert("Please select a Section");
                return;
            }
        }

        // If validation passes, show the appropriate table
        if (grandchildReportType && grandchildReportType !== "") {
            showReportTableForSelection(mainReportType, childReportType, grandchildReportType);
        } else {
            showReportTableForSelection(mainReportType, childReportType);
        }

		//Start API call
		var financialYear = $("#financialYear").children("option:selected").val();
		var quarterSelect = $("#quarterSelect").children("option:selected").val();
		var periodSelect = $("#periodSelect").children("option:selected").val();
		var mainRepType = $("#mainReportType").children("option:selected").val();
		var childRepType = $("#childReportType").children("option:selected").val();
		var grandchildRepType = $("#grandchildReportType").children("option:selected").val();
		$.ajax({
			method: "POST",
			url: "/generate_GSTReports",
			data: { 
					financialYear:financialYear,
					quarterSelect:quarterSelect,
					periodSelect:periodSelect,
					mainReportType: mainRepType,
					childReportType: childRepType,
					grandchildRepType: grandchildRepType 
				  },
			success: function (response) {
				
				console.log(response);
				if (response.class == "succ") {
					var resData = JSON.parse(response.data);
					console.log(resData);
					$(".message-container").html(
						'<div class="' +
							response.class +
							'">' +
							resData.header.gst_username +
							"</div>"
					);
					$("#txn").val(resData.header.txn);  // need to set resData.header.txn
					$("#gstAuthenticateModal").modal('show');
					
				} else {
					$.each(response, function (idx, obj) {
						$(".message-container").html(
							'<div class="err">' + obj + "</div>"
						);
					});
				}
			},
		});
    }
	
	
	$("form#frmGstUserName").bind("submit", function() {
      var gst_username = $("#gst_username").val();
	  if(gst_username !=""){
		  $('#loader').show();
		  var suburl = "/gst/whitebookOtpRequest";
		  $.ajax({
			headers: {
			  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			url: suburl,
			type: "POST",
			data: { gst_username: gst_username},
			success: function(response) {
			  console.log(response);
			  $('#loader').hide();
			  if (response.class == "succ") {
					$("#gstUserNameModal").modal("hide");
					$("#gstAuthenticateModal").modal("show");
			  } else {
				$.each(response, function (idx, obj) {
					$(".message-container").html(
						'<div class="err">' + obj.message + "</div>"
					);
				});
			  }
			},
		  });
	  }else{
		  alert("Please enter gst user name");
	  }
    });
	
	$("form#frmGstAuthenticate").bind("submit", function() {
      var gst_username = $("#gst_username").val();
      var otp = $("#otp").val();
	  if(otp !="" && gst_username !=""){
		  $('#loader').show();
		  var suburl = "/gst/whitebookAuthenticationRequest";
		  $.ajax({
			headers: {
			  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			url: suburl,
			type: "POST",
			data: { gst_username: gst_username, otp: otp },
			success: function(response) {
			  console.log(response);
			  $('#loader').hide();
			  if (response.class == "succ") {
				  $("#gstAuthenticateModal").modal("hide");
			  } else {
				$.each(response, function (idx, obj) {
					$(".message-container").html(
						'<div class="err">' + obj + "</div>"
					);
				});
			  }
			},
		  });
	  }else{
		  alert("Please enter OTP");
	  }
    });
	
	
	//End GST API cal

    // Initialize the form on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners for main dropdowns
        document.getElementById('financialYear').addEventListener('change', function() {
            // Code for financial year change event
        });

        document.getElementById('quarterSelect').addEventListener('change', updatePeriodOptions);

        // Add event listeners for report selection dropdowns
        document.getElementById('mainReportType').addEventListener('change', updateChildReports);
        document.getElementById('childReportType').addEventListener('change', updateGrandchildReports);
        document.getElementById('grandchildReportType').addEventListener('change', showSelectedReportTable);
    });
</script>

@endsection