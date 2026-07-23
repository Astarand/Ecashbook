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
        <!-- Disclaimer -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="p-4" style="background:linear-gradient(145deg, #fff7ed 0%, #ffe4e6 100%); border-radius:18px; border:1px solid #fecaca; box-shadow:0 12px 28px rgba(159,18,57,.08);">
                        <div class="d-flex align-items-center mb-3" style="gap:12px;">
                            <div style="width:44px; height:44px; border-radius:50%; background:#991b1b; color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:700;">!</div>
                            <div>
                                <h4 class="mb-0" style="color:#7f1d1d; font-weight:800; letter-spacing:.2px;">Disclaimer</h4>
                                <p class="mb-0" style="color:#9a3412; font-weight:600; font-size:13px;">Please review all points before opening GST report data.</p>
                            </div>
                        </div>

                        <div class="p-3 mb-3" style="background:#fff; border:1px solid #fecdd3; border-radius:12px;">
                            <ol style="color:#7f1d1d; line-height:1.8; padding-left:20px; font-weight:600; margin-bottom:0;">
                                <li class="mb-2">
                                    Login to
                                    <a href="https://www.gst.gov.in" target="_blank" style="color:#0f766e; font-weight:700; text-decoration:underline;">GST Portal</a>
                                    and go to <strong>Dashboard -> My Profile -> Manage API Access</strong>. Set API request to <strong>Yes</strong> for <strong>30 days</strong>.
                                </li>
                                <li class="mb-2">
                                    For <strong>Companies / LLPs</strong>, filing must be validated using <strong>DSC</strong> or <strong>EVC</strong> by an <strong>Authorised Signatory</strong>.
                                </li>
                                <li class="mb-0">
                                    If OTP or API related validation fails, re-check GST portal username and API access settings.
                                </li>
                            </ol>
                        </div>

                        <div class="form-check p-3 rounded" style="background:#ffffff; border:1px dashed #fda4af;">
                            <input class="form-check-input" type="checkbox" id="gstAgreeCheckbox" onchange="toggleAgreementButton()">
                            <label class="form-check-label fw-semibold" for="gstAgreeCheckbox">
                                I have read and agree to the above important GST notes.
                            </label>
                        </div>
                        <div class="mt-3 mb-1 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger px-4 py-2" id="agreeProceedBtn" onclick="agreeAndProceed()" disabled style="font-weight:700; border-radius:10px;">
                                Agree &amp; Proceed
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- [ Main Content ] start -->
    <div class=" row" id="gstinDataSection" style="display:none;">
        <!-- [ sample-page ] start -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h4 class="text-center mb-0">Generate GSTIN Reports</h4>
                            <div class="message-container"></div>
                        </div>
                        <div class="card-body">
                            <!-- First row for basic filters -->
							<div id="messageContainer" class="message-container"></div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label" for="financialYear">Select Financial Year<span class="text-danger">*</span></label>
                                    <select class="form-select" id="financialYear">
                                        <option selected>Select Financial Year</option>
                                        <!--<option value="2021-2022">2021-2022</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2024-2025">2024-2025</option>-->
										<option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
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
                                        <!--<option value="composition">Composition Dealer Reports</option>-->
                                        <option value="tds_tcs">TDS/TCS Reports</option>
                                        <!--<option value="isd">ISD (Input Service Distributor)</option>
                                        <option value="job_work">Job Work & Goods Movement</option>
                                        <option value="other_returns">Other GST Returns</option>-->
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
							<?php if($compData->time =="1" && $compData->otp =="1") { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="#" class="btn btn-primary w-100" onclick="generateReport()">Generate Report</a>
                                </div>
                            </div>
							<?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-1">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive report-table" id="summary_overview" style="display: none;">
                        <table class="table table-bordered myGstTable summary_overview">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Tax Period</th>
                                    <th>GST Collected (Output GST)</th>
                                    <th>GST Paid (Input GST)</th>
                                    <th>Net GST Payable/ Refund</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>01/Jan/2025 - 31/Jan/2025</td>
                                    <td>₹50,000</td>
                                    <td>₹30,000</td>
                                    <td>₹20,000</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

					<div class="table-responsive report-table" id="summary_isd_credits" style="display: none;">
                        <table class="table table-bordered myGstTable summary_isd_credits">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Doc num</th>
                                    <th>Doc date</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>CESS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>01/Jan/2025 - 31/Jan/2025</td>
                                    <td>₹50,000</td>
                                    <td>₹30,000</td>
                                    <td>₹20,000</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

					<div class="table-responsive report-table" id="summary_import_goods" style="display: none;">
                        <table class="table table-bordered myGstTable summary_import_goods">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Trade name</th>
                                    <th>Port Code</th>
                                    <th>Bill Entry Number</th>
                                    <th>Date of Bill</th>
                                    <th>Tax value</th>
                                    <th>IGST</th>
                                    <th>CESS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>01/Jan/2025 - 31/Jan/2025</td>
                                    <td>₹50,000</td>
                                    <td>₹30,000</td>
                                    <td>₹20,000</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

					<div class="table-responsive report-table" id="summary_tds_tcs_credits" style="display: none;">
                        <table class="table table-bordered myGstTable summary_tds_tcs_credits">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>gstin of the deductee </th>
                                    <th>Amount deducted </th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>01/Jan/2025 - 31/Jan/2025</td>
                                    <td>₹50,000</td>
                                    <td>₹30,000</td>
                                    <td>₹20,000</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="tax_liability_overview" style="display: none;">
                        <table class="table table-bordered myGstTable tax_liability_overview">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Category</th>
                                    <th>Current Period</th>
                                    <th>Previous Period</th>
                                    <th>% Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="compliance_status" style="display: none;">
                        <table class="table table-bordered myGstTable returnFillingStatus">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="b2b_invoices" style="display: none;">
                        <table class="table table-bordered myGstTable b2b_invoices">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="b2c_large" style="display: none;">
                        <table class="table table-bordered myGstTable b2c_large">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="b2c_small" style="display: none;">
                        <table class="table table-bordered myGstTable b2c_small">
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
                                    <!--<td>[State Code]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>-->
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="credit_debit_notes" style="display: none;">
                        <table class="table table-bordered myGstTable credit_debit_notes">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="export_invoices" style="display: none;">
                        <table class="table table-bordered myGstTable export_invoices">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="hsn_summary" style="display: none;">
                        <table class="table table-bordered myGstTable hsn_summary">
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
                                <!--<tr>
                                    <td>[Code]</td>
                                    <td>[Description]</td>
                                    <td>[UQC]</td>
                                    <td>[Quantity]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[Status]</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="itc_available" style="display: none;">
                        <table class="table table-bordered myGstTable itc_available">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Type</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>CESS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="itc_not_available" style="display: none;">
                        <table class="table table-bordered myGstTable itc_not_available">
                            <thead class="bg-dark text-white">
                                 <tr>
                                    <th>Type</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>CESS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
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
                                </tr>-->
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
                        <table class="table table-bordered myGstTable output_tax_liabilities">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Supply Type</th>
                                    <th>Taxable Value</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
									<th>CESS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>[Type]</td>
                                    <td>₹ [Amount]</td>
                                    <td>[%]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="itc_claimed" style="display: none;">
                        <table class="table table-bordered myGstTable itc_claimed">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>ITC Type</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>CESS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>[Type]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive report-table" id="tax_payments" style="display: none;">
                        <table class="table table-bordered myGstTable tax_payments">
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
                                <!--<tr>
                                    <td>[Type]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                    <td>₹ [Amount]</td>
                                </tr>-->
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
                        <table class="table table-bordered myGstTable gstr8_report">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Supplier</th>
                                    <th>Trade of GSTIN Supplier</th>
                                    <th>Net amount liable for TCS</th>
                                    <th>Gross value of supplies made to registered receiver</th>
                                    <th>Gross value of supplies returned by registered receiver</th>
                                    <th>Gross value of supplies made to unregistered receiver</th>
                                    <th>Gross value of supplies returned by unregistered receiver</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
                                    <td>29ABCDE1234F1Z5</td>
                                    <td>XYZ Enterprises</td>
                                    <td>E-commerce Sale</td>
                                    <td>₹ 50,000</td>
                                    <td>1%</td>
                                    <td>₹ 500</td>
                                    <td>15-04-2025</td>
                                    <td>Collected</td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-9 Annual Return -->
                    <div class="table-responsive report-table" id="gstr9_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr9_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-9C Reconciliation Statement -->
                    <div class="table-responsive report-table" id="gstr9c_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr9c_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-10 Final Return -->
                    <div class="table-responsive report-table" id="gstr10_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr10_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-4 Composition Annual Return -->
                    <div class="table-responsive report-table" id="gstr4_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr4_report">
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
                               <!-- <tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-5 Non-Resident Taxable Person -->
                    <div class="table-responsive report-table" id="gstr5_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr5_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-5A OIDAR Services -->
                    <div class="table-responsive report-table" id="gstr5a_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr5a_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-6 ISD Credit Distribution -->
                    <div class="table-responsive report-table" id="gstr6_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr6_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-7 TDS Report -->
                    <div class="table-responsive report-table" id="gstr7_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr7_report">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>GSTIN of Deductee</th>
                                    <th>Deductee Name</th>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
									<th>Invoice value</th>
                                    <th>Amount deducted</th>
                                    <th>IGST</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- GSTR-11 UIN Holders Report -->
                    <div class="table-responsive report-table" id="gstr11_report" style="display: none;">
                        <table class="table table-bordered myGstTable gstr11_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- ITC-04 Job Work Report -->
                    <div class="table-responsive report-table" id="itc04_report" style="display: none;">
                        <table class="table table-bordered myGstTable itc04_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- CMP-08 Quarterly Return -->
                    <div class="table-responsive report-table" id="cmp08_report" style="display: none;">
                        <table class="table table-bordered myGstTable cmp08_report">
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
                                <!--<tr>
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
                                </tr>-->
                            </tbody>
                        </table>
                    </div>
					<?php //if($compData->time =="1") { ?>
                    <div class="col-md-12 text-end mt-4">
                        <button type="button" class="btn btn-secondary me-2">Print</button>
                        <button type="button" id="downloadExcel" class="btn btn-primary">Download</button>
                    </div>
					<?php //} ?>
                </div>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>
<!-- [ Main Content ] end -->
</div>

<?php if(($compData->time =="0" || $compData->otp =="0") || $compData->gst_username =="" ) { ?>
<!---gstUserNameModalLabel------->
<div class="modal fade" id="gstUserNameModal" tabindex="-1" aria-labelledby="gstUserNameModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border:none; border-radius:16px; overflow:hidden;">
          <div class="modal-header text-white" style="background:#008CAD;">
            <h5 class="modal-title text-white" id="stockInModalLabel"><i class="fas fa-user-shield me-2"></i>GST Portal Username</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="message-container mb-3"></div>
            <form action="javascript:void(0);" method="POST" name="frmGstUserName" id="frmGstUserName" enctype="multipart/form-data">
              @csrf
              <label class="form-label fw-semibold" for="gst_username">Enter GST portal username</label>
              <input type="text" class="form-control form-control-lg" name="gst_username" id="gst_username" value="<?php echo $compData->gst_username; ?>" placeholder="Enter username" required>
              <div class="form-text mt-2">Use the username registered on <a href="https://www.gst.gov.in" target="_blank">gst.gov.in</a>.</div>
              <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success px-4">Continue</button>
              </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!---gstAuthenticateModalLabel------->
<!-- GST OTP Authentication Modal - Redesigned -->
<div class="modal fade" id="gstAuthenticateModal" tabindex="-1" aria-labelledby="gstAuthenticateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border:none; border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white" style="background:#008CAD;">
                <h5 class="modal-title text-white" id="gstAuthenticateModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>GST OTP Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div style="width:58px; height:58px; border-radius:50%; margin:auto; background:#dbeafe; color:#1d4ed8; display:flex; align-items:center; justify-content:center; font-size:24px;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <p class="text-muted mt-2 mb-0">Enter the 6-digit OTP sent to your registered mobile number</p>
                </div>
                <div class="message-container mb-2"></div>
                <form action="javascript:void(0);" method="POST" name="frmGstAuthenticate" id="frmGstAuthenticate" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="otp" class="form-label fw-semibold">Enter OTP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg text-center" id="otp" name="otp" maxlength="6" required placeholder="6-digit OTP" style="letter-spacing: 0.35em; font-size: 1.25rem; border:1px solid #93c5fd;">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Verify OTP</button>
                    </div>
                    <div class="mt-3 text-muted small text-center">
                        Didn’t receive OTP? <a href="#" id="resendOtpBtn" class="text-primary">Resend</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
		$("#downloadExcel").on("click", function() {
			var table = $(".myGstTable:visible")[0];
			var wb  = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
			var fileName = "GSTIN_Reports_" + new Date().toISOString().slice(0,19).replace(/[-T:]/g,"") + ".xlsx";
			XLSX.writeFile(wb, fileName);
		});
});

function toggleAgreementButton() {
    const checkbox = document.getElementById('gstAgreeCheckbox');
    const button = document.getElementById('agreeProceedBtn');
    if (!checkbox || !button) {
        return;
    }
    button.disabled = !checkbox.checked;
}

function agreeAndProceed() {
    const checkbox = document.getElementById('gstAgreeCheckbox');
    const gstinSection = document.getElementById('gstinDataSection');
    const gstUserNameModal = document.getElementById('gstUserNameModal');

    if (!checkbox || !gstinSection || !checkbox.checked) {
        return;
    }

    gstinSection.style.display = 'flex';
    if (gstUserNameModal) {
        $("#gstUserNameModal").modal('show');
    }
}
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
                /*pmt06: {
                    label: "PMT-06 – Challan Payments Summary",
                    children: {}
                },
                drc03: {
                    label: "DRC-03 – Voluntary Tax Payment Summary",
                    children: {}
                },*/
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
                /*gstr4_status: {
                    label: "GSTR-4 (Composition Annual Return) Status",
                    children: {}
                },
                cmp08_status: {
                    label: "CMP-08 (Composition Quarterly) Status",
                    children: {}
                },*/
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
    isd_credits: "summary_isd_credits",
    import_goods: "summary_import_goods",
    tds_tcs_credits: "summary_tds_tcs_credits",
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
            tableId = "summary_overview";
            console.log("No specific table found, defaulting to summary_overview");
        }

        // If we found a mapping, show the table
        if (tableId) {
            const table = document.getElementById(tableId);
            if (table) {
                table.style.display = "block";
                console.log("Successfully displayed table: " + tableId);
            } else if(tableId =='summary_isd_credits') {
                const summary_isd_credits_table = document.getElementById("summary_isd_credits");
                if (summary_isd_credits_table) {
                    summary_isd_credits_table.style.display = "block";
                }
            }else if(tableId =='summary_import_goods') {
                const summary_import_goods_table = document.getElementById("summary_import_goods");
                if (summary_import_goods_table) {
                    summary_import_goods_table.style.display = "block";
                }
            }else if(tableId =='summary_tds_tcs_credits') {
                const summary_tds_tcs_credits_table = document.getElementById("summary_tds_tcs_credits");
                if (summary_tds_tcs_credits_table) {
                    summary_tds_tcs_credits_table.style.display = "block";
                }
            }else {
                // If the table doesn't exist, show the summary_overview table as a fallback
                const summaryTable = document.getElementById("summary_overview");
                if (summaryTable) {
                    summaryTable.style.display = "block";
                    console.log("Table " + tableId + " not found, showing summary_overview table instead");
                }
            }

            // Show the print and download buttons
            document.querySelectorAll('.col-md-12.text-end.mt-4')[0].style.display = "block";
        }
    }

    function generateReport() {
		var gst_username = $("#gst_username").val();
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

		if(gst_username ===""){
			alert("Please enter register username of https://www.gst.gov.in");
			location.reload();
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
		$('#loader').show();
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
				$('#loader').hide();
				$("#messageContainer").html('');
				if (response && response.status_cd =='1')
				{
					if(mainReportType =="filing_status" && childReportType =="gstr1_status" && grandchildRepType==""){
						let table = $('.returnFillingStatus');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>${item.rtntype}</td>
										<td>${item.ret_prd}</td>
										<td>${item.status}</td>
										<td>${item.dof}</td>
										<td>${item.arn}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
                            table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="b2b"){
						let table = $('.b2b_invoices');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.b2b);
							$.each(response.data.b2b, function (index, item) {
								let row1 = `
									<tr>
										<td>${item.ctin}</td>
										<td></td>
										<td>${item.inv[0].inum}</td>
										<td>${item.inv[0].idt}</td>
										<td>₹${item.inv[0].val}</td>
										<td>₹${item.inv[0].itms[0].itm_det.txval}</td>
										<td>${item.inv[0].itms[0].itm_det.rt}%</td>
										<td>₹${item.inv[0].itms[0].itm_det.iamt ?? 0}</td>
										<td>₹${item.inv[0].itms[0].itm_det.camt ?? 0}</td>
										<td>₹${item.inv[0].itms[0].itm_det.samt ?? 0}</td>
										<td>${item.inv[0].rchrg}</td>
									</tr>`;
								 table.find('tbody').append(row1);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="b2cl"){
						let table = $('.b2c_large');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.b2cl);
							$.each(response.data.b2cl, function (index, item) {
								let row = `
									<tr>
										<td>${item.pos}</td>
										<td>${item.inv[0].inum}</td>
										<td>${item.inv[0].idt}</td>
										<td>₹${item.inv[0].val}</td>
										<td>₹${item.inv[0].itms[0].itm_det.txval ?? 0}</td>
										<td>${item.inv[0].itms[0].itm_det.rt}%</td>
										<td>₹${item.inv[0].itms[0].itm_det.iamt ?? 0}</td>
										<td>₹0</td>
										<td>₹0</td>
										<td></td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
                            table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="b2cs"){
						let table = $('.b2c_small');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.b2cs);
							$.each(response.data.b2cs, function (index, item) {
								let row = `
									<tr>
										<td>${item.pos ?? ""}</td>
										<td>${item.rt}%</td>
										<td>₹${item.txval ?? 0}</td>
										<td>₹${item.iamt ?? 0}</td>
										<td>₹${item.csamt ?? 0}</td>
										<td>₹${item.ssamt ?? 0}</td>
										<td></td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="cdnr"){
						let table = $('.credit_debit_notes');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.cdnr);
							$.each(response.data.cdnr, function (index, item) {
								let row = `
									<tr>
										<td>${item.nt[0].ntty}</td>
										<td>${item.nt[0].nt_num}</td>
										<td>${item.nt[0].nt_dt}</td>
										<td></td>
										<td>${item.ctin}</td>
										<td>₹${item.nt[0].itms[0].itm_det.txval}</td>
										<td>${item.nt[0].itms[0].itm_det.rt}%</td>
										<td>₹${item.nt[0].itms[0].itm_det.iamt ?? 0}</td>
										<td>₹${item.nt[0].itms[0].itm_det.camt ?? 0}</td>
										<td>₹${item.nt[0].itms[0].itm_det.samt ?? 0}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="cdnur"){
						let table = $('.credit_debit_notes');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.cdnur);
							$.each(response.data.cdnur, function (index, item) {
								let row = `
									<tr>
										<td>${item.ntty}</td>
										<td>${item.nt_num}</td>
										<td>${item.nt_dt}</td>
										<td>${item.inum}</td>
										<td></td>
										<td>₹${item.itms[0].itm_det.txval}</td>
										<td>${item.itms[0].itm_det.rt}%</td>
										<td>₹${item.itms[0].itm_det.iamt ?? 0}</td>
										<td>₹${item.itms[0].itm_det.camt ?? 0}</td>
										<td>₹${item.itms[0].itm_det.samt ?? 0}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="exp"){
						let table = $('.export_invoices');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.exp);
							$.each(response.data.exp, function (index, item) {
								let row = `
									<tr>
										<td>${item.exp_typ}</td>
										<td>${item.inv[0].inum}</td>
										<td>${item.inv[0].idt}</td>
										<td>${item.inv[0].sbpcode}</td>
										<td>${item.inv[0].sbnum}</td>
										<td>${item.inv[0].sbdt}</td>
										<td>₹${item.inv[0].itms[0].txval}</td>
										<td>${item.inv[0].itms[0].rt}%</td>
										<td>₹${item.inv[0].itms[0].iamt ?? 0}</td>
										<td></td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="at"){
						let table = $('.tax_liability_overview');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.at);
							$.each(response.data.at, function (index, item) {
								let row = `
									<tr>
										<td>${item.sply_ty}</td>
										<td>₹${item.itms[0].iamt ?? 0} (IGST)</td>
										<td>₹${item.itms[0].ad_amt ?? 0}  (Ad Amt)</td>
										<td>${item.diff_percent ?? 0}%</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
                            table.find('tbody').append(`<tr class="text-center"><td colspan="4">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="atadj"){
						let table = $('.tax_liability_overview');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.ata);
							$.each(response.data.ata, function (index, item) {
								let row = `
									<tr>
										<td>${item.sply_ty}</td>
										<td>₹${item.itms[0].ad_amt}</td>
										<td>₹${item.itms[0].csamt}</td>
										<td>${item.diff_percent}%</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="4">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="nil"){
                        let table = $('.summary_overview');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.nil);
							$.each(response.data.nil, function (index, item) {
								let row = `
									<tr>
										<td>${item.inv[0].sply_ty}</td>
										<td>₹${item.inv[0].expt_amt}</td>
										<td>₹${item.inv[0].nil_amt}</td>
										<td>₹${item.inv[0].ngsup_amt}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="4">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr1" && grandchildRepType=="hsn"){
						let table = $('.hsn_summary');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.hsn.data);
							$.each(response.data.hsn.data, function (index, item) {
								let row = `
									<tr>
										<td>${item.hsn_sc}</td>
										<td style="word-wrap: break-word; white-space: normal; width: 200px;">${item.desc}</td>
										<td>${item.uqc}</td>
										<td>${item.qty}</td>
										<td>₹ ${item.txval}</td>
										<td>₹ ${item.iamt}</td>
										<td>₹ ${item.camt}</td>
										<td>₹ ${item.samt}</td>
										<td></td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="4">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr3b_outward" && grandchildRepType==""){
						let table = $('.output_tax_liabilities');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.sup_details.osup_det);
							//$.each(response.data.sup_details, function (index, item) {
								let row1 = `
									<tr>
										<td>Outward suppliers</td>
										<td>₹${response.data.sup_details.osup_det.txval}</td>
										<td>₹${response.data.sup_details.osup_det.iamt}</td>
										<td>₹${response.data.sup_details.osup_det.camt}</td>
										<td>₹${response.data.sup_details.osup_det.samt}</td>
										<td>₹${response.data.sup_details.osup_det.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row1);

								 let row2 = `
									<tr>
										<td>Outward suppliers(zero rated)</td>
										<td>₹${response.data.sup_details.osup_zero.txval}</td>
										<td>₹${response.data.sup_details.osup_zero.iamt}</td>
										<td>₹${response.data.sup_details.osup_zero.camt}</td>
										<td>₹${response.data.sup_details.osup_zero.samt}</td>
										<td>₹${response.data.sup_details.osup_zero.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row2);

								 let row3 = `
									<tr>
										<td>Outward suppliers(nil rated)</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.txval}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.iamt}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.camt}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.samt}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row3);

								 let row4 = `
									<tr>
										<td>Inward suppliers(reverse charge)</td>
										<td>₹${response.data.sup_details.isup_rev.txval}</td>
										<td>₹${response.data.sup_details.isup_rev.iamt}</td>
										<td>₹${response.data.sup_details.isup_rev.camt}</td>
										<td>₹${response.data.sup_details.isup_rev.samt}</td>
										<td>₹${response.data.sup_details.isup_rev.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row4);

								 let row5 = `
									<tr>
										<td>Non-GST outward supplies</td>
										<td>₹${response.data.sup_details.osup_nongst.txval}</td>
										<td>₹${response.data.sup_details.osup_nongst.iamt}</td>
										<td>₹${response.data.sup_details.osup_nongst.camt}</td>
										<td>₹${response.data.sup_details.osup_nongst.samt}</td>
										<td>₹${response.data.sup_details.osup_nongst.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row5);
							//});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="outward_supplies" && childReportType =="gstr9_sales" && grandchildRepType==""){
						let table = $('.gstr9_report');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.table10.total_turnover.samt);
							let row = `
								<tr>
									<td>dr_nt</td>
									<td>Debit notes</td>
									<td>₹${response.data.table4.dr_nt.txval ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.camt ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.samt ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.iamt ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row);

							let row1 = `
								<tr>
									<td>cr_nt</td>
									<td>Credit notes</td>
									<td>₹${response.data.table4.cr_nt.txval ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.camt ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.samt ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.iamt ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row1);

							let row2 = `
								<tr>
									<td>b2c</td>
									<td>B2C</td>
									<td>₹${response.data.table4.b2c.txval ?? 0}</td>
									<td>₹${response.data.table4.b2c.camt ?? 0}</td>
									<td>₹${response.data.table4.b2c.samt ?? 0}</td>
									<td>₹${response.data.table4.b2c.iamt ?? 0}</td>
									<td>₹${response.data.table4.b2c.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row2);
							let row3 = `
								<tr>
									<td>b2b</td>
									<td>Business to Business</td>
									<td>₹${response.data.table4.b2b.txval ?? 0}</td>
									<td>₹${response.data.table4.b2b.camt ?? 0}</td>
									<td>₹${response.data.table4.b2b.samt ?? 0}</td>
									<td>₹${response.data.table4.b2b.iamt ?? 0}</td>
									<td>₹${response.data.table4.b2b.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row3);
							let row4 = `
								<tr>
									<td>sez</td>
									<td>sez</td>
									<td>₹${response.data.table4.sez.txval ?? 0}</td>
									<td>₹${response.data.table4.sez.camt ?? 0}</td>
									<td>₹${response.data.table4.sez.samt ?? 0}</td>
									<td>₹${response.data.table4.sez.iamt ?? 0}</td>
									<td>₹${response.data.table4.sez.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row4);
							let row5 = `
								<tr>
									<td>exp</td>
									<td>Exports</td>
									<td>₹${response.data.table4.exp.txval ?? 0}</td>
									<td>₹${response.data.table4.exp.camt ?? 0}</td>
									<td>₹${response.data.table4.exp.samt ?? 0}</td>
									<td>₹${response.data.table4.exp.iamt ?? 0}</td>
									<td>₹${response.data.table4.exp.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row5);
							 let row6 = `
								<tr>
									<td>rchrg</td>
									<td>Reverse charge</td>
									<td>₹${response.data.table4.rchrg.txval ?? 0}</td>
									<td>₹${response.data.table4.rchrg.camt ?? 0}</td>
									<td>₹${response.data.table4.rchrg.samt ?? 0}</td>
									<td>₹${response.data.table4.rchrg.iamt ?? 0}</td>
									<td>₹${response.data.table4.rchrg.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row6);

						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr2a" && grandchildRepType=="b2b_invoices"){
						let table = $('.b2b_invoices');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.b2b);
							$.each(response.data.b2b, function (index, item) {
								let row = `
									<tr>
										<td>${item.ctin}</td>
										<td></td>
										<td>${item.inv[0].inum}</td>
										<td>${item.inv[0].idt}</td>
										<td>₹${item.inv[0].val}</td>
										<td>₹${item.inv[0].itms[0].itm_det.txval}</td>
										<td>${item.inv[0].itms[0].itm_det.rt}%</td>
										<td>₹${item.inv[0].itms[0].itm_det.iamt ?? 0}</td>
										<td>₹${item.inv[0].itms[0].itm_det.camt ?? 0}</td>
										<td>₹${item.inv[0].itms[0].itm_det.samt ?? 0}</td>
										<td></td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr2a" && grandchildRepType=="cdns_received"){
						let table = $('.credit_debit_notes');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.cdn);
							$.each(response.data.cdn, function (index, item) {
								let row = `
									<tr>
										<td>${item.nt[0].ntty}</td>
										<td>${item.nt[0].nt_num}</td>
										<td>${item.nt[0].nt_dt}</td>
										<td>${item.nt[0].p_gst ?? ""}</td>
										<td>${item.ctin}</td>
										<td>₹${item.nt[0].itms[0].itm_det.txval}</td>
										<td>${item.nt[0].itms[0].itm_det.rt}%</td>
										<td>₹${item.nt[0].itms[0].itm_det.iamt ?? 0}</td>
										<td>₹${item.nt[0].itms[0].itm_det.camt ?? 0}</td>
										<td>₹${item.nt[0].itms[0].itm_det.samt ?? 0}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
                    }else if(mainReportType =="inward_supplies" && childReportType =="gstr2a" && grandchildRepType=="isd_credits"){
                        let table = $('.summary_isd_credits');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.isd);
							response.data.isd.forEach(isdEntry => {
							  isdEntry.doclist.forEach(doc => {
								let row = `
									<tr>
										<td>${doc.docnum ?? ""}</td>
										<td>${doc.docdt ??  ""}</td>
										<td>₹${doc.iamt ??  ""}</td>
										<td>₹${doc.camt ??  ""}</td>
										<td>₹${doc.samt ??  ""}</td>
										<td>₹${doc.cess ??  ""}</td>
									</tr>`;
								 table.find('tbody').append(row);

							  });
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
                    }else if(mainReportType =="inward_supplies" && childReportType =="gstr2a" && grandchildRepType=="import_goods"){
                        let table = $('.summary_import_goods');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.impgsez);
							response.data.impgsez.forEach(item => {
							let row = `
									<tr>
										<td>${item.tdname}</td>
										<td>${item.portcd}</td>
										<td>${item.benum}</td>
										<td>${item.bedt}</td>
										<td>₹${item.txval}</td>
										<td>₹${item.iamt}</td>
										<td>₹${item.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
                    }else if(mainReportType =="inward_supplies" && childReportType =="gstr2a" && grandchildRepType=="tds_tcs_credits"){
                        let table = $('.summary_tds_tcs_credits');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.tds);
							response.data.tds.forEach(item => {
							let row = `
									<tr>
										<td>${item.gstin_ded}</td>
										<td>₹${item.amt_ded ?? 0}</td>
										<td>₹${item.iamt ?? 0}</td>
										<td>₹${item.camt ?? 0}</td>
										<td>₹${item.samt ?? 0}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr2b" && grandchildRepType=="eligible_itc"){
						let table = $('.itc_available');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.itc_elg);
							$.each(response.data.itc_elg.itc_avl, function (index, item) {
								let row = `
									<tr>
										<td>${item.ty}</td>
										<td>₹${item.iamt ?? 0}</td>
										<td>₹${item.camt ?? 0}</td>
										<td>₹${item.samt ?? 0}</td>
										<td>₹${item.csamt ?? 0}</td>

									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr2b" && grandchildRepType=="ineligible_itc"){
						let table = $('.itc_not_available');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.itc_elg);
							$.each(response.data.itc_elg.itc_inelg, function (index, item) {
								let row = `
									<tr>
										<td>${item.ty}</td>
										<td>₹${item.iamt ?? 0}</td>
										<td>₹${item.camt ?? 0}</td>
										<td>₹${item.samt ?? 0}</td>
										<td>₹${item.csamt ?? 0}</td>

									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr2b" && grandchildRepType=="blocked_itc"){
						let table = $('.itc_not_available');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.itc_elg);
							$.each(response.data.itc_elg.itc_inelg, function (index, item) {
								let row = `
									<tr>
										<td>${item.ty}</td>
										<td>₹${item.iamt ?? 0}</td>
										<td>₹${item.camt ?? 0}</td>
										<td>₹${item.samt ?? 0}</td>
										<td>₹${item.csamt ?? 0}</td>

									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr3b_itc" && grandchildRepType==""){
						let table = $('.itc_claimed');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data);
							let row1 = `
									<tr>
										<td>Outward suppliers</td>
										<td>₹${response.data.sup_details.osup_det.iamt}</td>
										<td>₹${response.data.sup_details.osup_det.camt}</td>
										<td>₹${response.data.sup_details.osup_det.samt}</td>
										<td>₹${response.data.sup_details.osup_det.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row1);

								 let row2 = `
									<tr>
										<td>Outward suppliers(zero rated)</td>
										<td>₹${response.data.sup_details.osup_zero.iamt}</td>
										<td>₹${response.data.sup_details.osup_zero.camt}</td>
										<td>₹${response.data.sup_details.osup_zero.samt}</td>
										<td>₹${response.data.sup_details.osup_zero.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row2);

								 let row3 = `
									<tr>
										<td>Outward suppliers(nil rated)</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.iamt}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.camt}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.samt}</td>
										<td>₹${response.data.sup_details.osup_nil_exmp.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row3);

								 let row4 = `
									<tr>
										<td>Inward suppliers(reverse charge)</td>
										<td>₹${response.data.sup_details.isup_rev.iamt}</td>
										<td>₹${response.data.sup_details.isup_rev.camt}</td>
										<td>₹${response.data.sup_details.isup_rev.samt}</td>
										<td>₹${response.data.sup_details.isup_rev.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row4);

								 let row5 = `
									<tr>
										<td>Non-GST outward supplies</td>
										<td>₹${response.data.sup_details.osup_nongst.iamt}</td>
										<td>₹${response.data.sup_details.osup_nongst.camt}</td>
										<td>₹${response.data.sup_details.osup_nongst.samt}</td>
										<td>₹${response.data.sup_details.osup_nongst.csamt}</td>
									</tr>`;
								 table.find('tbody').append(row5);

						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="inward_supplies" && childReportType =="gstr9_purchase" && grandchildRepType==""){
						let table = $('.gstr9_report');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.table10.total_turnover.samt);
							let row = `
								<tr>
									<td>dr_nt</td>
									<td>Debit notes</td>
									<td>₹${response.data.table4.dr_nt.txval ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.camt ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.samt ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.iamt ?? 0}</td>
									<td>₹${response.data.table4.dr_nt.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row);

							let row1 = `
								<tr>
									<td>cr_nt</td>
									<td>Credit notes</td>
									<td>₹${response.data.table4.cr_nt.txval ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.camt ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.samt ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.iamt ?? 0}</td>
									<td>₹${response.data.table4.cr_nt.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row1);

							let row2 = `
								<tr>
									<td>b2c</td>
									<td>b2c</td>
									<td>₹${response.data.table4.b2c.txval ?? 0}</td>
									<td>₹${response.data.table4.b2c.camt ?? 0}</td>
									<td>₹${response.data.table4.b2c.samt ?? 0}</td>
									<td>₹${response.data.table4.b2c.iamt ?? 0}</td>
									<td>₹${response.data.table4.b2c.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row2);
							let row3 = `
								<tr>
									<td>b2b</td>
									<td>b2b</td>
									<td>₹${response.data.table4.b2b.txval ?? 0}</td>
									<td>₹${response.data.table4.b2b.camt ?? 0}</td>
									<td>₹${response.data.table4.b2b.samt ?? 0}</td>
									<td>₹${response.data.table4.b2b.iamt ?? 0}</td>
									<td>₹${response.data.table4.b2b.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row3);
							let row4 = `
								<tr>
									<td>sez</td>
									<td>sez</td>
									<td>₹${response.data.table4.sez.txval ?? 0}</td>
									<td>₹${response.data.table4.sez.camt ?? 0}</td>
									<td>₹${response.data.table4.sez.samt ?? 0}</td>
									<td>₹${response.data.table4.sez.iamt ?? 0}</td>
									<td>₹${response.data.table4.sez.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row4);
							let row5 = `
								<tr>
									<td>exp</td>
									<td>Exports</td>
									<td>₹${response.data.table4.exp.txval ?? 0}</td>
									<td>₹${response.data.table4.exp.camt ?? 0}</td>
									<td>₹${response.data.table4.exp.samt ?? 0}</td>
									<td>₹${response.data.table4.exp.iamt ?? 0}</td>
									<td>₹${response.data.table4.exp.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row5);
							 let row6 = `
								<tr>
									<td>rchrg</td>
									<td>Reverse Charge</td>
									<td>₹${response.data.table4.rchrg.txval ?? 0}</td>
									<td>₹${response.data.table4.rchrg.camt ?? 0}</td>
									<td>₹${response.data.table4.rchrg.samt ?? 0}</td>
									<td>₹${response.data.table4.rchrg.iamt ?? 0}</td>
									<td>₹${response.data.table4.rchrg.csamt ?? 0}</td>
								</tr>`;
							 table.find('tbody').append(row6);
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="tax_payment" && childReportType =="gstr3b_tax" && grandchildRepType==""){
						let table = $('.output_tax_liabilities');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.tx_pmt.tx_py);
							$.each(response.data.tx_pmt.tx_py, function (index, item) {
								let row = `
									<tr>
										<td>${item.tran_desc}</td>
										<td>₹0</td>
										<td>₹${item.igst.tx + item.igst.intr + item.igst.fee}</td>
										<td>₹${item.cgst.tx + item.cgst.intr + item.cgst.fee}</td>
										<td>₹${item.sgst.tx + item.sgst.intr + item.sgst.fee}</td>
										<td>₹${item.cess.tx + item.cess.intr + item.cess.fee}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});

							$.each(response.data.tx_pmt.pdcash, function (index, item) {
								let row = `
									<tr>
										<td>Tax Paid A(Paid by Cash)</td>
										<td>₹0</td>
										<td>₹${item.i_intrpd + item.i_lfeepd}</td>
										<td>₹${item.c_intrpd + item.c_lfeepd}</td>
										<td>₹${item.s_intrpd + item.s_lfeepd}</td>
										<td>₹${item.cs_intrpd + item.cs_lfeepd}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="tax_payment" && childReportType =="pmt06" && grandchildRepType==""){
						let table = $('.tax_payments');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data);
							$.each(response.data, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>₹0</td>
										<td>₹0</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});		;
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="tax_payment" && childReportType =="drc03" && grandchildRepType==""){
						let table = $('.tax_payments');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data);
							$.each(response.data, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>₹0</td>
										<td>₹0</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});		;
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="tax_payment" && childReportType =="gstr9c" && grandchildRepType==""){
						let table = $('.gstr9c_report');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data.gstr9cdata.audited_data);
							$.each(response.data.gstr9cdata.audited_data.table14.items, function (index, item) {
								let row = `
									<tr>
										<td></td>
										<td>${item.desc}</td>
										<td>₹${item.val}</td>
										<td>₹${item.itc_amt}</td>
										<td>₹${item.itc_avail}</td>
										<td></td>
									</tr>`;
								 table.find('tbody').append(row);
							});		;
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="filing_status" && childReportType =="gstr3b_status" && grandchildRepType==""){
						let table = $('.returnFillingStatus');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>${item.rtntype}</td>
										<td>${item.ret_prd}</td>
										<td>${item.status}</td>
										<td>${item.dof}</td>
										<td>${item.arn}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="filing_status" && childReportType =="gstr4_status" && grandchildRepType==""){
						let table = $('.returnFillingStatus');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>${item.rtntype}</td>
										<td>${item.dof}</td>
										<td>${item.status}</td>
										<td>${item.dof}</td>
										<td>${item.arn}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="filing_status" && childReportType =="cmp08_status" && grandchildRepType==""){
						let table = $('.returnFillingStatus');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>${item.rtntype}</td>
										<td>${item.dof}</td>
										<td>${item.status}</td>
										<td>${item.dof}</td>
										<td>${item.arn}</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="filing_status" && childReportType =="gstr9_status" && grandchildRepType==""){
						let table = $('.returnFillingStatus');
						table.find('tbody').empty();
						if (response && response.data){
							//$.each(response.data.gstr9cdata, function (index, item) {
								let row = `
									<tr>
										<td>${response.data.gstr9cdata.audited_data.trd_name}</td>
										<td>${response.data.gstr9cdata.audited_data.fp}</td>
										<td>${response.data.gstr9cdata.audited_data.act_name}</td>
										<td>${response.data.gstr9cdata.audited_data.arn_date}</td>
										<td>${response.data.gstr9cdata.audited_data.arn}</td>
									</tr>`;
								 table.find('tbody').append(row);
							//});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="filing_status" && childReportType =="gstr10_status" && grandchildRepType==""){
						let table = $('.returnFillingStatus');
						table.find('tbody').empty();
						if (response && response.data){
							//$.each(response.data.gstr9cdata, function (index, item) {
								let row = `
									<tr>
										<td>${response.data.gstr9cdata.audited_data.trd_name}</td>
										<td>${response.data.gstr9cdata.audited_data.fp}</td>
										<td>${response.data.gstr9cdata.audited_data.act_name}</td>
										<td>${response.data.gstr9cdata.audited_data.arn_date}</td>
										<td>${response.data.gstr9cdata.audited_data.arn}</td>
									</tr>`;
								 table.find('tbody').append(row);
							//});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="composition" && childReportType =="cmp08" && grandchildRepType==""){
						let table = $('.cmp08_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>Q1</td>
										<td>test</td>
										<td>₹test</td>
										<td>test %</td>
										<td>₹test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="composition" && childReportType =="gstr4" && grandchildRepType==""){
						let table = $('.gstr4_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>4A</td>
										<td>test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="tds_tcs" && childReportType =="gstr7" && grandchildRepType==""){
						let table = $('.gstr7_report');
						table.find('tbody').empty();
						if (response && response.data){
							response.data.tds.forEach(item => {
								item.inv.forEach(invItem => {
								  const row = `<tr>
									<td>${item.gstin_ded ?? ''}</td>
									<td>${item.deductee_name ?? ''}</td>
									<td>${invItem.item.inum ?? ''}</td>
									<td>${invItem.item.idt ?? ''}</td>
									<td>${invItem.item.ival ?? ''}</td>
									<td>₹${invItem.item.amt_ded ?? 0}</td>
									<td>₹${invItem.item.iamt ?? 0}</td>
									<td>₹${invItem.camt ?? 0}</td>
									<td>₹${invItem.samt ?? 0}</td>
								  </tr>`;
								  table.find('tbody').append(row);
								});
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="9">No record found</td></tr>`);
						}
					}else if(mainReportType =="tds_tcs" && childReportType =="gstr8" && grandchildRepType==""){
						let table = $('.gstr8_report');
						table.find('tbody').empty();
						if (response && response.data){
							response.data.tcs.forEach(item => {
							  const row = `<tr>
								<td>${item.stin || ''}</td>
								<td>${item.stin_name || ''}</td>
								<td>₹${item.amt ?? 0}</td>
								<td>₹${item.supR ?? 0}</td>
								<td>₹${item.retsupR ?? 0}</td>
								<td>₹${item.supU ?? 0}</td>
								<td>₹${item.retsupU ?? 0}</td>
								<td>₹${item.iamt ?? 0}</td>
								<td>₹${item.camt ?? 0}</td>
								<td>₹${item.samt ?? 0}</td>
							  </tr>`;
							  table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="isd" && childReportType =="gstr6" && grandchildRepType==""){
						let table = $('.gstr6_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="job_work" && childReportType =="itc04" && grandchildRepType==""){
						let table = $('.itc04_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>test</td>
										<td>Sent</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="other_returns" && childReportType =="gstr5" && grandchildRepType==""){
						let table = $('.gstr5_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>5A</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="other_returns" && childReportType =="gstr5a" && grandchildRepType==""){
						let table = $('.gstr5a_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="other_returns" && childReportType =="gstr10" && grandchildRepType==""){
						let table = $('.gstr10_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}else if(mainReportType =="other_returns" && childReportType =="gstr11" && grandchildRepType==""){
						let table = $('.gstr11_report');
						table.find('tbody').empty();
						if (response && response.data){
							$.each(response.data.EFiledlist, function (index, item) {
								let row = `
									<tr>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>test</td>
										<td>test</td>
										<td>test</td>
										<td>₹test</td>
										<td>₹test</td>
										<td>₹test</td>
									</tr>`;
								 table.find('tbody').append(row);
							});
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						}
					}
				}else{
					$("#messageContainer").html('<div class="err">' + response.error.message + "</div>");
					$('.returnFillingStatus').find('tbody').html('');
					$('.returnFillingStatus').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.b2b_invoices').find('tbody').html('');
					$('.b2b_invoices').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.b2c_large').find('tbody').html('');
					$('.b2c_large').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.b2c_small').find('tbody').html('');
					$('.b2c_small').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.credit_debit_notes').find('tbody').html('');
					$('.credit_debit_notes').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.export_invoices').find('tbody').html('');
					$('.export_invoices').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.tax_liability_overview').find('tbody').html('');
					$('.tax_liability_overview').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.summary_overview').find('tbody').html('');
					$('.summary_overview').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.summary_isd_credits').find('tbody').html('');
					$('.summary_isd_credits').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.summary_import_goods').find('tbody').html('');
					$('.summary_import_goods').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.summary_tds_tcs_credits').find('tbody').html('');
					$('.summary_tds_tcs_credits').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.hsn_summary').find('tbody').html('');
					$('.hsn_summary').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					$('.output_tax_liabilities').find('tbody').html('');
					$('.output_tax_liabilities').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.gstr9_report').find('tbody').html('');
					$('.gstr9_report').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.gstr9c_report').find('tbody').html('');
					$('.gstr9c_report').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
					$('.tax_payments').find('tbody').html('');
					$('.tax_payments').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
				}
			},error: function(xhr) {
				$('#loader').hide();
			}
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
				  location.reload();
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
