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
                        <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">GST Returns & Filing</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-gst-returns-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">GST Returns & Filing</h2>
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
								<p class="mb-0" style="color:#9a3412; font-weight:600; font-size:13px;">Please review all points before continuing to GST filing.</p>
							</div>
						</div>

						<div class="p-3 mb-3" style="background:#fff; border:1px solid #fecdd3; border-radius:12px;">
							<ol style="color:#7f1d1d; line-height:1.8; padding-left:20px; font-weight:600; margin-bottom:0;">
								<li class="mb-2">
									Login to 
									<a href="https://www.gst.gov.in" target="_blank" style="color:#0f766e; font-weight:700; text-decoration:underline;">
										GST Portal
									</a>
									and go to <strong>Dashboard → My Profile → Manage API Access</strong>.
									Set API Request to <strong>Yes</strong> for <strong>30 days</strong>.
								</li>

								<li class="mb-2">
									For <strong>Companies / LLPs</strong>, filing must be validated through
									<strong>DSC</strong> or <strong>EVC</strong> by an <strong>Authorised Signatory</strong>.
								</li>

								<li class="mb-2">
									If you see
									<span style="background:#fff1f2; padding:2px 6px; border-radius:6px; border:1px solid #fecdd3; color:#be123c;">User does not have authorized signatory for given PAN and GSTIN</span>,
									update your <strong>Personal PAN Number</strong> in company profile.
								</li>

								<li class="mb-2">
									All GST return payments are redirected to the <strong>official GST Government Portal</strong>.
								</li>

								<li class="mb-0">
									<strong>Payment Flow:</strong> Login → Services → Payments → Create Challan →
									Select reason (<em>Any Other Payment</em>) → Enter amount → Choose payment mode → Generate challan.
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

		<!-- GSTIN Data -->
	<div class="row" id="gstinDataSection" style="display: none;">
        <!-- Filter Section -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
					<h4 class="text-center">GSTIN Returns</h4>
          <div class="message-container messageContainer mb-3"></div>

          <!-- Period Selection -->
					<div class="row mb-3" id="periodSelectionSection" style="display: none;">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Financial Year <span class="text-danger">*</span></label>
                            <select class="form-select" id="financialYear">
                                <option value="">Select Financial Year</option>
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
                            <label class="form-label fw-semibold">Quarter <span class="text-danger">*</span></label>
                            <select class="form-select" id="quarterSelect" onchange="updatePeriodOptions()">
                                <option value="">Select Quarter</option>
                                <option value="Q1">Q1 (April - June)</option>
                                <option value="Q2">Q2 (July - September)</option>
                                <option value="Q3">Q3 (October - December)</option>
                                <option value="Q4">Q4 (January - March)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Period <span class="text-danger">*</span></label>
                            <select class="form-select" id="periodSelect" disabled>
                                <option value="">Select Period</option>
                            </select>
                        </div>
                    </div>

                    <!-- Return Type Selection -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">GST Return Type <span class="text-danger">*</span></label>
                            <?php if($compData->comp_tran_type=='Regular'){ ?>
								<select class="form-select form-select-lg" id="mainReportType" onchange="updateChildReports()">
									<option value="">Select Return Type</option>
									<option value="gstr1">GSTR-1 (Outward Supplies)</option>
									<option value="gstr3b">GSTR-3B (Summary Return)</option>
									<option value="gstr9">GSTR-9 (Annual Return)</option>
									<option value="gstr9c">GSTR-9C (Reconciliation)</option>
									<!--<option value="gstr10">GSTR-10 (Final Return)</option>-->
								</select>
							<?php }else{ ?>
								<select class="form-select form-select-lg" id="mainReportType" onchange="updateChildReports()">
									<option value="">Select Return Type</option>
									<option value="gstr4">GSTR-4 (Composition Annual)</option>
									<option value="cmp08">CMP-08 (Composition Quarterly)</option>
								</select>
							<?php } ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Report Type</label>
                            <select class="form-select form-select-lg" id="childReportType" disabled>
                                <option value="">Select Return Type First</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="nilToggleContainer" style="display: none;">
                            <label class="form-label fw-semibold d-block">Filing Mode</label>
                            <div class="form-check form-switch" style="padding-top: 8px;">
                                <input class="form-check-input" type="checkbox" role="switch" id="isNilReturn"
                                       onchange="updateChildReports()" style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                <label class="form-check-label fw-bold ms-2" for="isNilReturn" style="font-size: 1.1rem;">
                                    NIL Return
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-lg w-100" onclick="generateReturnData()">
                                <i class="fas fa-file-invoice me-2"></i>
                                <span id="generateBtnText">Generate Report</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Display / NIL Declaration Card -->
        <div class="col-md-12 mt-3">
            <div class="card" id="dataDisplayCard" style="display: none;">
                <div class="card-body">
                    <!-- NIL Declaration Form -->
                    <div id="nilDeclarationForm" style="display: none;">
                        <div class="text-center mb-4">
                            <i class="fas fa-file-circle-check text-info" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">NIL Return Declaration</h4>
                            <p class="text-muted" id="nilReturnTitle">NIL Return for October 2024</p>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Important:</strong> By filing a NIL return, you declare that there were no taxable transactions during the selected period.
                        </div>
                        <div class="form-check p-3 bg-light rounded">
                            <input class="form-check-input" type="checkbox" id="nilDeclaration" style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                            <label class="form-check-label ms-2 fs-5" for="nilDeclaration" style="cursor: pointer;">
                                I hereby declare that there were no outward/inward supplies during the selected period.
                            </label>
                        </div>
                    </div>

                    <!-- Data Tables Container -->
                    <div id="dataTablesContainer">

                        <!-- ==================== GSTR-1 TABLES ==================== -->

                        <!-- B2B Invoices Table -->
                        <div class="table-responsive report-table" id="b2b_invoices" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable b2b_invoices">
                                <thead class="table-dark">
                                    <tr>
                                        <th>GSTIN</th>
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Value</th>
                                        <th>Taxable Value</th>
                                        <th>Tax Rate</th>
                                        <th>IGST</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>CESS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--<tr>
                                        <td>29ABCDE1234F1Z5</td>
                                        <td>INV/2024/001</td>
                                        <td>01-Oct-2024</td>
                                        <td>₹11,800</td>
                                        <td>₹10,000</td>
                                        <td>18%</td>
                                        <td>₹1,800</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td>27XYZAB5678G2Z1</td>
                                        <td>INV/2024/002</td>
                                        <td>05-Oct-2024</td>
                                        <td>₹5,900</td>
                                        <td>₹5,000</td>
                                        <td>18%</td>
                                        <td>₹900</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td>24PQRST9012H3Z8</td>
                                        <td>INV/2024/003</td>
                                        <td>10-Oct-2024</td>
                                        <td>₹23,600</td>
                                        <td>₹20,000</td>
                                        <td>18%</td>
                                        <td>₹3,600</td>
                                        <td>₹0</td>
                                    </tr>-->
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <!--<th colspan="3" class="text-end">Total:</th>
                                        <th>₹41,300</th>
                                        <th>₹35,000</th>
                                        <th>-</th>
                                        <th>₹6,300</th>
                                        <th>₹0</th>-->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- B2CL Invoices Table -->
                        <div class="table-responsive report-table" id="b2c_large" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable b2c_large">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Place of Supply</th>
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Value</th>
                                        <th>Taxable Value</th>
                                        <th>Tax Rate</th>
                                        <th>IGST</th>
                                        <th>CESS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--<tr>
                                        <td>29-Karnataka</td>
                                        <td>INV/B2CL/001</td>
                                        <td>12-Oct-2024</td>
                                        <td>₹2,95,000</td>
                                        <td>₹2,50,000</td>
                                        <td>18%</td>
                                        <td>₹45,000</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td>27-Maharashtra</td>
                                        <td>INV/B2CL/002</td>
                                        <td>18-Oct-2024</td>
                                        <td>₹3,54,000</td>
                                        <td>₹3,00,000</td>
                                        <td>18%</td>
                                        <td>₹54,000</td>
                                        <td>₹0</td>
                                    </tr>-->
                                </tbody>
                                <tfoot class="table-secondary">
                                    <!--<tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>₹6,49,000</th>
                                        <th>₹5,50,000</th>
                                        <th>-</th>
                                        <th>₹99,000</th>
                                        <th>₹0</th>
                                    </tr>-->
                                </tfoot>
                            </table>
                        </div>

                        <!-- B2CS Summary Table -->
                        <div class="table-responsive report-table" id="b2c_small" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable b2c_small">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Supply Type</th>
                                        <th>Place of Supply</th>
                                        <th>Type</th>
                                        <th>Taxable Value</th>
                                        <th>Tax Rate</th>
                                        <th>IGST</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>CESS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--<tr>
                                        <td>Intra-State</td>
                                        <td>19-West Bengal</td>
                                        <td>OE</td>
                                        <td>₹50,000</td>
                                        <td>18%</td>
                                        <td>₹0</td>
                                        <td>₹4,500</td>
                                        <td>₹4,500</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td>Inter-State</td>
                                        <td>27-Maharashtra</td>
                                        <td>OE</td>
                                        <td>₹30,000</td>
                                        <td>18%</td>
                                        <td>₹5,400</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td>Intra-State</td>
                                        <td>19-West Bengal</td>
                                        <td>OE</td>
                                        <td>₹25,000</td>
                                        <td>12%</td>
                                        <td>₹0</td>
                                        <td>₹1,500</td>
                                        <td>₹1,500</td>
                                        <td>₹0</td>
                                    </tr>-->
                                </tbody>
                                <tfoot class="table-secondary">
                                    <!--<tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>₹1,05,000</th>
                                        <th>-</th>
                                        <th>₹5,400</th>
                                        <th>₹6,000</th>
                                        <th>₹6,000</th>
                                        <th>₹0</th>
                                    </tr>-->
                                </tfoot>
                            </table>
                        </div>

                        <!-- ==================== GSTR-3B TABLE ==================== -->

                        <div class="table-responsive report-table" id="output_tax_liabilities" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable output_tax_liabilities">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 45%;">Nature of Supplies</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>IGST (₹)</th>
                                        <th>CGST (₹)</th>
                                        <th>SGST/UTGST (₹)</th>
                                        <th>CESS (₹)</th>
                                    </tr>
                                </thead>
                                <tbody id="gstTableBody">
                                    <!--<tr>
                                        <td><strong>3.1(a)</strong> Outward taxable supplies (other than zero rated, nil rated and exempted)</td>
                                        <td>₹10,00,000</td>
                                        <td>₹90,000</td>
                                        <td>₹90,000</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>3.1(b)</strong> Outward taxable supplies (zero rated)</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>3.1(c)</strong> Other outward supplies (nil rated, exempted)</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>3.1(d)</strong> Inward supplies (liable to reverse charge)</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>3.1(e)</strong> Non-GST outward supplies</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>-->
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                       <th>Total Tax Liability:</th>
										<th id="total_txval">₹0</th>
										<th id="total_iamt">₹0</th>
										<th id="total_camt">₹0</th>
										<th id="total_samt">₹0</th>
										<th id="total_csamt">₹0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- ==================== GSTR-4 TABLE ==================== -->

                        <div class="table-responsive report-table" id="gstr4_table" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 50%;">Particulars</th>
                                        <th>Turnover (₹)</th>
                                        <th>Rate</th>
                                        <th>Tax Paid (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Total Annual Turnover</strong></td>
                                        <td>₹45,00,000</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Composition Tax (Manufacturers/Traders)</strong></td>
                                        <td>₹40,00,000</td>
                                        <td>1%</td>
                                        <td>₹40,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Composition Tax (Service Providers)</strong></td>
                                        <td>₹5,00,000</td>
                                        <td>6%</td>
                                        <td>₹30,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Interest/Late Fee (if any)</strong></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>₹500</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th>Total Tax Liability:</th>
                                        <th>₹45,00,000</th>
                                        <th>-</th>
                                        <th>₹70,500</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- ==================== GSTR-9 TABLE ==================== -->

                        <div class="table-responsive report-table" id="gstr9_table" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable gstr9_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40%;">Section / Particulars</th>
                                        <th>Tax Paid (₹)</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>IGST (₹)</th>
                                        <th>CGST (₹)</th>
                                        <th>SGST (₹)</th>
                                        <th>CESS (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
									<!-- PART 4 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 4: Details of Inward and Outward Supplies</strong></td>
									</tr>
									
									<tr>
									  <td>B2B Supplies</td>
									  <td>-</td>
									  <td><input id="b2b_txval" class="form-control g9"></td>
									  <td><input id="b2b_igst" class="form-control g9"></td>
									  <td><input id="b2b_cgst" class="form-control g9"></td>
									  <td><input id="b2b_sgst" class="form-control g9"></td>
									  <td><input id="b2b_cess" class="form-control g9"></td>
									</tr>

									<tr>
									  <td>B2C Supplies</td>
									  <td>-</td>
									  <td><input id="b2c_txval" class="form-control g9"></td>
									  <td><input id="b2c_igst" class="form-control g9"></td>
									  <td><input id="b2c_cgst" class="form-control g9"></td>
									  <td><input id="b2c_sgst" class="form-control g9"></td>
									  <td><input id="b2c_cess" class="form-control g9"></td>
									</tr>
									<tr>
									  <td>Total Taxable Inward and Outward Supplies (Annual)</td>
									  <td>-</td>
									  <td><input type="text" id="out_txval" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_igst" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_cgst" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_sgst" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_cess" readonly class="form-control g9"></td>
									</tr>

									<!-- PART 6 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 6: ITC Availed during the financial year</strong></td>
									</tr>
									<tr>
									  <td>ITC Availed</td>
									  <td>-</td>
									  <td>-</td>
									  <td><input type="text" id="itc_igst" class="form-control g9"></td>
									  <td><input type="text" id="itc_cgst" class="form-control g9"></td>
									  <td><input type="text" id="itc_sgst" class="form-control g9"></td>
									  <td><input type="text" id="itc_cess" class="form-control g9"></td>
									</tr>

									<!-- PART 8 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 8: Other ITC</strong></td>
									</tr>
									<tr>
									  <td>Other ITC related Information</td>
									  <td>-</td>
									  <td>-</td>
									  <td><input type="text" id="other_itc_igst" class="form-control g9"></td>
									  <td><input type="text" id="other_itc_cgst" class="form-control g9"></td>
									  <td><input type="text" id="other_itc_sgst" class="form-control g9"></td>
									  <td><input type="text" id="other_itc_cess" class="form-control g9"></td>
									</tr>

									<!-- PART 9 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 9: Tax Paid</strong></td>
									</tr>
									<tr style="display:none;">									  
									  <td><input type="text" id="tax_iamt" class="form-control g9"></td>
									  <td><input type="text" id="tax_camt" class="form-control g9"></td>
									  <td><input type="text" id="tax_samt" class="form-control g9"></td>
									  <td><input type="text" id="tax_csamt" class="form-control g9"></td>
									  <td><input type="text" id="tax_intr" class="form-control g9"></td>
									  <td><input type="text" id="tax_fee" class="form-control g9"></td>
									  <td><input type="text" id="tax_other" class="form-control g9"></td>
									  <td><input type="text" id="tax_pen" class="form-control g9"></td>
									</tr>
									<tr>
									  <td>Tax payable</td>
									  <td><input type="text" id="tax_payable" class="form-control g9"></td>
									  <td colspan="5"></td>
									</tr>
									<tr>
									  <td>Paid through ITC</td>
									  <td><input type="text" id="tax_pay_itc" class="form-control g9"></td>
									  <td colspan="5"></td>
									</tr>
									<tr>
									  <td>Paid through cash</td>
									  <td><input type="text" id="tax_pay_cash" class="form-control g9"></td>
									  <td colspan="5"></td>
									</tr>

									<!-- TABLE 10–13 -->
									<tr class="table-light"><td colspan="7">10–13 Particulars of transactions</td></tr>
									<tr>
									  <td colspan="2"></td>
									  <td><input type="text" id="a_txval" class="form-control g9"></td>
									  <td><input type="text" id="a_igst" class="form-control g9"></td>
									  <td><input type="text" id="a_cgst" class="form-control g9"></td>
									  <td><input type="text" id="a_sgst" class="form-control g9"></td>
									  <td><input type="text" id="a_cess" class="form-control g9"></td>
									</tr>

									<!-- TABLE 14 -->
									<tr class="table-light"><td colspan="7">14. Differential Tax Paid</td></tr>
									<tr>
									  <td colspan="3"></td>
									  <td><input type="text" id="b_igst" class="form-control g9"></td>
									  <td><input type="text" id="b_cgst" class="form-control g9"></td>
									  <td><input type="text" id="b_sgst" class="form-control g9"></td>
									  <td><input type="text" id="b_cess" class="form-control g9"></td>
									</tr>

									<!-- TABLE 17 -->
									<tr class="table-light"><td colspan="7">17. HSN summary of outward supplies</td></tr>
									<tr style="display:none;">
									  <td colspan="5"></td>
									  <td><input type="text" id="e_hsn_sc" class="form-control g9"></td>
									  <td><input type="text" id="e_rt" class="form-control g9"></td>
									</tr>
									<tr>
									  <td colspan="2"></td>
									  <td><input type="text" id="e_txval" class="form-control g9"></td>
									  <td><input type="text" id="e_igst" class="form-control g9"></td>
									  <td><input type="text" id="e_cgst" class="form-control g9"></td>
									  <td><input type="text" id="e_sgst" class="form-control g9"></td>
									  <td><input type="text" id="e_cess" class="form-control g9"></td>
									</tr>

									<!-- TABLE 18 -->
									<tr class="table-light"><td colspan="7">18. HSN summary of inward supplies</td></tr>
									<tr style="display:none;">
									  <td colspan="5"></td>
									  <td><input type="text" id="f_hsn_sc" class="form-control g9"></td>
									  <td><input type="text" id="f_rt" class="form-control g9"></td>
									</tr>
									<tr>
									  <td colspan="2"></td>
									  <td><input type="text" id="f_txval" class="form-control g9"></td>
									  <td><input type="text" id="f_igst" class="form-control g9"></td>
									  <td><input type="text" id="f_cgst" class="form-control g9"></td>
									  <td><input type="text" id="f_sgst" class="form-control g9"></td>
									  <td><input type="text" id="f_cess" class="form-control g9"></td>
									</tr>

								</tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th></th>
										<th id="net_txpaid"></th>
                                        <th id="net_txval"></th>
										<th id="net_igst"></th>
										<th id="net_cgst"></th>
										<th id="net_sgst"></th>
										<th id="net_cess"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- ==================== GSTR-9C TABLE ==================== -->
						
						<div class="table-responsive report-table" id="gstr9c_table" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable gstr9c_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40%;">Section / Particulars</th>
                                        <th>Tax Paid (₹)</th>
                                        <th>Taxable Value (₹)</th>
                                        <th>IGST (₹)</th>
                                        <th>CGST (₹)</th>
                                        <th>SGST (₹)</th>
                                        <th>CESS (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
									<!-- PART 4 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 4: Details of Inward and Outward Supplies</strong></td>
									</tr>
									
									<tr>
									  <td>B2B Supplies</td>
									  <td>-</td>
									  <td><input id="b2b_txval_c" class="form-control g9"></td>
									  <td><input id="b2b_igst_c" class="form-control g9"></td>
									  <td><input id="b2b_cgst_c" class="form-control g9"></td>
									  <td><input id="b2b_sgst_c" class="form-control g9"></td>
									  <td><input id="b2b_cess_c" class="form-control g9"></td>
									</tr>

									<tr>
									  <td>B2C Supplies</td>
									  <td>-</td>
									  <td><input id="b2c_txval_c" class="form-control g9"></td>
									  <td><input id="b2c_igst_c" class="form-control g9"></td>
									  <td><input id="b2c_cgst_c" class="form-control g9"></td>
									  <td><input id="b2c_sgst_c" class="form-control g9"></td>
									  <td><input id="b2c_cess_c" class="form-control g9"></td>
									</tr>
									<tr>
									  <td>Total Taxable Inward and Outward Supplies (Annual)</td>
									  <td>-</td>
									  <td><input type="text" id="out_txval_c" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_igst_c" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_cgst_c" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_sgst_c" readonly class="form-control g9"></td>
									  <td><input type="text" id="out_cess_c" readonly class="form-control g9"></td>
									</tr>

									<!-- PART 6 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 6: ITC Availed during the financial year</strong></td>
									</tr>
									<tr>
									  <td>ITC Availed</td>
									  <td>-</td>
									  <td>-</td>
									  <td><input type="text" id="itc_igst_c" class="form-control g9"></td>
									  <td><input type="text" id="itc_cgst_c" class="form-control g9"></td>
									  <td><input type="text" id="itc_sgst_c" class="form-control g9"></td>
									  <td><input type="text" id="itc_cess_c" class="form-control g9"></td>
									</tr>

									<!-- PART 8 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 8: Other ITC</strong></td>
									</tr>
									<tr>
									  <td>Other ITC related Information</td>
									  <td>-</td>
									  <td>-</td>
									  <td><input type="text" id="other_itc_igst_c" class="form-control g9"></td>
									  <td><input type="text" id="other_itc_cgst_c" class="form-control g9"></td>
									  <td><input type="text" id="other_itc_sgst_c" class="form-control g9"></td>
									  <td><input type="text" id="other_itc_cess_c" class="form-control g9"></td>
									</tr>

									<!-- PART 9 -->
									<tr class="table-light">
									  <td colspan="7"><strong>Part 9: Tax Paid</strong></td>
									</tr>
									<tr style="display:none;">									  
									  <td><input type="text" id="tax_iamt_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_camt_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_samt_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_csamt_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_intr_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_fee_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_other_c" class="form-control g9"></td>
									  <td><input type="text" id="tax_pen_c" class="form-control g9"></td>
									</tr>
									<tr>
									  <td>Tax payable</td>
									  <td><input type="text" id="tax_payable_c" class="form-control g9"></td>
									  <td colspan="5"></td>
									</tr>
									<tr>
									  <td>Paid through ITC</td>
									  <td><input type="text" id="tax_pay_itc_c" class="form-control g9"></td>
									  <td colspan="5"></td>
									</tr>
									<tr>
									  <td>Paid through cash</td>
									  <td><input type="text" id="tax_pay_cash_c" class="form-control g9"></td>
									  <td colspan="5"></td>
									</tr>

									<!-- TABLE 10–13 -->
									<tr class="table-light"><td colspan="7">10–13 Particulars of transactions</td></tr>
									<tr>
									  <td colspan="2"></td>
									  <td><input type="text" id="a_txval_c" class="form-control g9"></td>
									  <td><input type="text" id="a_igst_c" class="form-control g9"></td>
									  <td><input type="text" id="a_cgst_c" class="form-control g9"></td>
									  <td><input type="text" id="a_sgst_c" class="form-control g9"></td>
									  <td><input type="text" id="a_cess_c" class="form-control g9"></td>
									</tr>

									<!-- TABLE 14 -->
									<tr class="table-light"><td colspan="7">14. Differential Tax Paid</td></tr>
									<tr>
									  <td colspan="3"></td>
									  <td><input type="text" id="b_igst_c" class="form-control g9"></td>
									  <td><input type="text" id="b_cgst_c" class="form-control g9"></td>
									  <td><input type="text" id="b_sgst_c" class="form-control g9"></td>
									  <td><input type="text" id="b_cess_c" class="form-control g9"></td>
									</tr>

									<!-- TABLE 17 -->
									<tr class="table-light"><td colspan="7">17. HSN summary of outward supplies</td></tr>
									<tr style="display:none;">
									  <td colspan="5"></td>
									  <td><input type="text" id="e_hsn_sc_c" class="form-control g9"></td>
									  <td><input type="text" id="e_rt_c" class="form-control g9"></td>
									</tr>
									<tr>
									  <td colspan="2"></td>
									  <td><input type="text" id="e_txval_c" class="form-control g9"></td>
									  <td><input type="text" id="e_igst_c" class="form-control g9"></td>
									  <td><input type="text" id="e_cgst_c" class="form-control g9"></td>
									  <td><input type="text" id="e_sgst_c" class="form-control g9"></td>
									  <td><input type="text" id="e_cess_c" class="form-control g9"></td>
									</tr>

									<!-- TABLE 18 -->
									<tr class="table-light"><td colspan="7">18. HSN summary of inward supplies</td></tr>
									<tr style="display:none;">
									  <td colspan="5"></td>
									  <td><input type="text" id="f_hsn_sc_c" class="form-control g9"></td>
									  <td><input type="text" id="f_rt_c" class="form-control g9"></td>
									</tr>
									<tr>
									  <td colspan="2"></td>
									  <td><input type="text" id="f_txval_c" class="form-control g9"></td>
									  <td><input type="text" id="f_igst_c" class="form-control g9"></td>
									  <td><input type="text" id="f_cgst_c" class="form-control g9"></td>
									  <td><input type="text" id="f_sgst_c" class="form-control g9"></td>
									  <td><input type="text" id="f_cess_c" class="form-control g9"></td>
									</tr>

								</tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th></th>
										<th id="net_txpaid_c"></th>
                                        <th id="net_txval_c"></th>
										<th id="net_igst_c"></th>
										<th id="net_cgst_c"></th>
										<th id="net_sgst_c"></th>
										<th id="net_cess_c"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!--<div class="table-responsive report-table" id="gstr9c_table" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable gstr9c_table">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40%;">Particulars</th>
                                        <th>As per Books (₹)</th>
                                        <th>As per GSTR (₹)</th>
                                        <th>Difference (₹)</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Annual Turnover</strong></td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td class="text-danger">₹0</td>
                                        <td>Exempted supplies adjustment</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax Liability (Outward)</strong></td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td class="text-danger">₹0</td>
                                        <td>Rate difference adjustment</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ITC Claimed</strong></td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td class="text-danger">₹0</td>
                                        <td>Blocked credit reversal</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Net Tax Paid</strong></td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                        <td class="text-danger">₹0</td>
                                        <td>Reconciled & adjusted</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-warning">
                                    <tr>
                                        <th colspan="3">Auditor Certification Status:</th>
                                        <th colspan="2" class="text-success"><i class="fas fa-check-circle me-2"></i>Certified by CA</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>-->

                        <!-- ==================== GSTR-10 TABLE ==================== -->

                        <div class="table-responsive report-table" id="gstr10_table" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40%;">Particulars</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Value (₹)</th>
                                        <th>ITC to Reverse (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td colspan="5"><strong>Closing Stock Details</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Raw Materials</strong></td>
                                        <td>Raw materials in stock</td>
                                        <td>500 Kg</td>
                                        <td>₹2,50,000</td>
                                        <td>₹45,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Finished Goods</strong></td>
                                        <td>Finished goods inventory</td>
                                        <td>200 Units</td>
                                        <td>₹5,00,000</td>
                                        <td>₹90,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Capital Goods</strong></td>
                                        <td>Plant & machinery</td>
                                        <td>1 Set</td>
                                        <td>₹10,00,000</td>
                                        <td>₹1,50,000</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td colspan="5"><strong>ITC Reversal Summary</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total ITC to be Reversed</strong></td>
                                        <td>On closure of business</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>₹2,85,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Final Tax Payment Due</strong></td>
                                        <td>To be paid before cancellation</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-danger fw-bold">₹2,85,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- ==================== CMP-08 TABLE ==================== -->

                        <div class="table-responsive report-table" id="cmp08_table" style="display: none;">
                            <table class="table table-bordered table-hover myGstTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40%;">Particulars</th>
                                        <th>Turnover (₹)</th>
                                        <th>Rate</th>
                                        <th>Tax Payable (₹)</th>
                                        <th>Tax Paid (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Supplies made during the quarter</strong></td>
                                        <td>₹12,00,000</td>
                                        <td>1%</td>
                                        <td>₹12,000</td>
                                        <td>₹12,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Inward supplies liable to tax under RCM</strong></td>
                                        <td>₹50,000</td>
                                        <td>18%</td>
                                        <td>₹9,000</td>
                                        <td>₹9,000</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Late Fee (if any)</strong></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>₹0</td>
                                        <td>₹0</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th>Total:</th>
                                        <th>₹12,50,000</th>
                                        <th>-</th>
                                        <th>₹21,000</th>
                                        <th>₹21,000</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Quarterly Period:</strong> October - December 2024 (Q3)
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-md-12 mt-3" id="submitButtonCard" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-success btn-lg w-100" onclick="submit_GSTReturns()">
                        <i class="fas fa-paper-plane me-2"></i>
                        Review & Submit with EVC
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(($compData->time =="0" || $compData->otp =="0") || $compData->gst_username =="" ) { ?>
	<!-- GST Username Modal -->
	<div class="modal fade" id="gstUserNameModal" tabindex="-1" data-bs-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content shadow-lg">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title text-white"><i class="fas fa-user me-2"></i>GST Portal Username</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="message-container mb-3"></div>
					<form id="frmGstUserName" onsubmit="return submitGstUsername(event);">
						@csrf
						<div class="mb-3">
							<label for="gst_username" class="form-label fw-semibold">Username</label>
							<input type="text" class="form-control form-control-lg" id="gst_username"
								   name="gst_username" value="<?php echo $compData->gst_username; ?>" placeholder="Enter GST portal username" required>
							<div class="form-text">
								<i class="fas fa-info-circle me-1"></i>
								Enter your registered username from <a href="https://www.gst.gov.in" target="_blank">gst.gov.in</a>
							</div>
						</div>
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary btn-lg">
								<i class="fas fa-key me-2"></i>Request OTP
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- GST Authentication OTP Modal (1st OTP) -->
	<div class="modal fade" id="gstAuthenticateModal" tabindex="-1" data-bs-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content shadow-lg">
				<div class="modal-header bg-success text-white">
					<h5 class="modal-title"><i class="fas fa-shield-alt me-2"></i>GST Authentication</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="text-center mb-4">
						<i class="fas fa-mobile-alt text-success" style="font-size: 3rem;"></i>
						<p class="mt-3 text-muted">OTP sent to your registered mobile number</p>
					</div>
					<div class="message-container mb-3"></div>
					<form id="frmGstAuthenticate" onsubmit="return submitAuthOtp(event);">
						@csrf
						<div class="mb-4">
							<label class="form-label fw-semibold text-center d-block">Enter 6-Digit OTP</label>
							<div class="otp-input-group d-flex justify-content-center gap-2">
								<input type="text" class="form-control text-center otp-digit" maxlength="1"
									   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
								<input type="text" class="form-control text-center otp-digit" maxlength="1"
									   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
								<input type="text" class="form-control text-center otp-digit" maxlength="1"
									   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
								<span style="font-size: 2rem; font-weight: bold;">-</span>
								<input type="text" class="form-control text-center otp-digit" maxlength="1"
									   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
								<input type="text" class="form-control text-center otp-digit" maxlength="1"
									   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
								<input type="text" class="form-control text-center otp-digit" maxlength="1"
									   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
							</div>
							<input type="hidden" id="auth_otp" name="otp">
						</div>
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-success btn-lg">
								<i class="fas fa-check-circle me-2"></i>Verify OTP
							</button>
						</div>
						<div class="text-center mt-3">
							<a href="#" id="resendAuthOtp" class="text-decoration-none">
								<i class="fas fa-redo me-1"></i>Resend OTP
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!-- EVC OTP Modal (2nd OTP - Filing) -->
<div class="modal fade" id="evcOtpModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-file-signature me-2"></i>File Return with EVC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border">
                    <h6 class="fw-bold mb-3"><i class="fas fa-list-check me-2"></i>Filing Summary</h6>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <small class="text-muted">Return Type:</small><br>
                            <strong id="evcReturnType">-</strong>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted">Period:</small><br>
                            <strong id="evcPeriod">-</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Details:</small><br>
                            <span id="evcDetails">-</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <i class="fas fa-lock text-warning" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Enter EVC OTP to complete filing</p>
                </div>

                <div class="message-container mb-3"></div>

                <form id="frmEvcOtp" onsubmit="return submitEvcOtp(event);">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-center d-block">Enter 6-Digit EVC OTP</label>
                        <div class="otp-input-group d-flex justify-content-center gap-2">
                            <input type="text" class="form-control text-center otp-digit-evc" maxlength="1"
                                   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                            <input type="text" class="form-control text-center otp-digit-evc" maxlength="1"
                                   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                            <input type="text" class="form-control text-center otp-digit-evc" maxlength="1"
                                   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                            <span style="font-size: 2rem; font-weight: bold;">-</span>
                            <input type="text" class="form-control text-center otp-digit-evc" maxlength="1"
                                   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                            <input type="text" class="form-control text-center otp-digit-evc" maxlength="1"
                                   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                            <input type="text" class="form-control text-center otp-digit-evc" maxlength="1"
                                   style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                        </div>
                        <input type="hidden" id="evc_otp" name="evc_otp">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg text-dark fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>File Return Now
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <!--<a href="#" id="resendEvcOtp" class="text-decoration-none">
                            <i class="fas fa-redo me-1"></i>Resend EVC OTP
                        </a>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Filing Successful</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                <h4 class="mt-4 mb-3" id="successReturnType">Return Filed Successfully</h4>
                <div class="alert alert-success">
                    <strong>ARN:</strong> <span id="successArn" class="fs-5">-</span>
                </div>
                <p class="text-muted">
                    <i class="far fa-calendar-alt me-2"></i>
                    Filed on: <span id="successDate">-</span>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="downloadReceipt()">
                    <i class="fas fa-download me-2"></i>Download Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="outstandingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title">Outstanding Liabilities</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	  </div>
	  <div class="modal-body">
		<p>
			Please click below button to clear the Outstanding Liabilities(GST govt. portal) <br/>
			<h6><a class="btn btn-primary" target="_blank" href="https://services.gst.gov.in/services/login">Click</a></h6>
		</p>
		<p>
		Payment’s Process : 
		1. Go to the Gst portal, login with id and password
		2. Go to Services tab > returns > return dashboard 
		3. Select FY, Quarter, and period
		4. Click on monthly return (Gstr -3B) > proceed to payment
		5. Check outstanding due and click on "create challan"
		6. Select payment mode and bank name
		7. The generate challan
		</p>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
	  </div>
	</div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
$(document).ready(function() {
		// OTP modal should open only after user agrees to disclaimer.

});

function n(id) {
    const v = $("#" + id).val();
    return v ? Number(v.replace(/,/g, "")) : 0;
}
function s(id) {
    return $("#" + id).val()?.trim() || "";
}

function gstr9payload()
{
	const e_hsn = s("e_hsn_sc") || "0";
	let table17Item = {
		isconcesstional: "N",
		hsn_sc: e_hsn,
		rt: n("e_rt") || 0,
		txval: n("e_txval") || 0,
		iamt: n("e_igst") || 0,
		camt: n("e_cgst") || 0,
		samt: n("e_sgst") || 0,
		csamt: n("e_cess") || 0
	};
	// Only include uqc & qty when HSN is present
	if (e_hsn == "0") {
		table17Item.uqc = "";
		table17Item.qty = 0;
	}
	
	const payload = {
        /* ---------------- TABLE 4 ---------------- */
        table4: {
            b2b: {
                txval: n("b2b_txval"),
                iamt : n("b2b_igst"),
                camt : n("b2b_cgst"),
                samt : n("b2b_sgst"),
                csamt: n("b2b_cess")
            },
            b2c: {
                txval: n("b2c_txval"),
                iamt : n("b2c_igst"),
                camt : n("b2c_cgst"),
                samt : n("b2c_sgst"),
                csamt: n("b2c_cess")
            }
        },

        /* ---------------- TABLE 6 ---------------- */
        table6: {
            itc_clmd: {
                iamt : n("itc_igst"),
                camt : n("itc_cgst"),
                samt : n("itc_sgst"),
                csamt: n("itc_cess")
            }
        },

        /* ---------------- TABLE 8 ---------------- */
        table8: {
            itc_inwd_supp: {
                iamt : n("other_itc_igst"),
                camt : n("other_itc_cgst"),
                samt : n("other_itc_sgst"),
                csamt: n("other_itc_cess")
            },
			itc_nt_availd: {
                iamt : n("other_itc_igst"),
                camt : n("other_itc_cgst"),
                samt : n("other_itc_sgst"),
                csamt: n("other_itc_cess")
            }
        },

        /* ---------------- TABLE 9 ---------------- */
		table9: {
			iamt: {
				txpyble: n("tax_iamt")
			},
			camt: {
				txpyble: n("tax_camt")
			},
			samt: {
				txpyble: n("tax_samt")
			},
			csamt: {
				txpyble: n("tax_csamt")
			},
			intr: {
				txpyble: n("tax_intr")
			},
			fee: {
				txpyble: n("tax_fee")
			},
			pen: {
				txpyble: n("tax_pen")
			},
			other: {
				txpyble: n("tax_other")
			}
		},

        /* ---------------- TABLE 10 ---------------- */
        table10: {
            dbn_amd: {
                txval: n("a_txval"),
                iamt : n("a_igst"),
                camt : n("a_cgst"),
                samt : n("a_sgst"),
                csamt: n("a_cess")
            }
        },

        /* ---------------- TABLE 14 ---------------- */
        table14: {
            iamt: { txpaid: n("b_igst") },
            camt: { txpaid: n("b_cgst") },
            samt: { txpaid: n("b_sgst") },
            csamt:{ txpaid: n("b_cess") },
			intr: {
				txpyble: 0,
				txpaid : 0
			}
        },

        /* ---------------- TABLE 17 ---------------- */
        table17: {
            items: [table17Item]
        },

        /* ---------------- TABLE 18 ---------------- */
        table18: {
            items: [{
				isconcesstional: "N",
				hsn_sc: s("f_hsn_sc") || "0",
				uqc: "",
				qty: 0,
				rt: n("f_rt") || 0,
				txval: n("f_txval") || 0,
				iamt: n("f_igst") || 0,
				camt: n("f_cgst") || 0,
				samt: n("f_sgst") || 0,
				csamt: n("f_cess") || 0
            }]
        }
    };
	return payload;
}

function gstr9cpayload()
{
	const e_hsn = s("e_hsn_sc_c") || "0";

	const table17Item = {
		isconcesstional: "N",
		hsn_sc: e_hsn,
		rt: n("e_rt") || 0,
		txval: n("e_txval_c") || 0,
		iamt: n("e_igst_c") || 0,
		camt: n("e_cgst_c") || 0,
		samt: n("e_sgst_c") || 0,
		csamt: n("e_cess_c") || 0
	};

	if (e_hsn === "0") {
		table17Item.uqc = "";
		table17Item.qty = 0;
	}

	const payload = {
		gstr9cdata: {
			audited_data: {

				/* ---------- TABLE 5 (Turnover) ---------- */
				table5: {
					turnovr: n("e_txval_c"),
					unbil_rev_beg: 0,
					unadj_adv_end: 0,
					dmd_sup: 0,
					crd_nts_issued: 0,
					trd_dis: 0,
					turnovr_apr_jun: 0,
					unbil_rev_end: 0,
					unadj_adv_beg: 0,
					crd_note_acc: 0,
					adj_dta: 0,
					turnovr_comp: 0,
					adj_turn_sec: 0,
					adj_turn_fef: 0,
					adj_turn_othrsn: 0,
					annul_turn_adj: n("e_txval_c"),
					annul_turn_decl: n("e_txval_c"),
					unrec_turnovr: 0
				},

				/* ---------- TABLE 7 (Taxable Turnover) ---------- */
				table7: {
					annul_turn_adj: n("e_txval_c"),
					othr_turnovr: 0,
					zero_sup: 0,
					rev_sup: 0,
					tax_turn_annul: n("e_txval_c"),
					tax_turn_adj: n("e_txval_c"),
					unrec_tax_turn: 0
				},

				/* ---------- TABLE 9 (Tax Payable / Paid) ---------- */
				table9: {
					rate: [{
						desc: "GST",
						tax_val: n("e_txval_c"),
						cgst: n("tax_camt_c"),
						sgst: n("tax_samt_c"),
						igst: n("tax_iamt_c"),
						cess: n("tax_csamt_c")
					}],
					inter: {
						cgst: n("tax_intr_c"),
						sgst: 0,
						igst: 0,
						cess: 0
					},
					late_fee: {
						cgst: n("tax_fee_c"),
						sgst: 0,
						igst: 0,
						cess: 0
					},
					pen: {
						cgst: n("tax_pen_c"),
						sgst: 0,
						igst: 0,
						cess: 0
					},
					oth: {
						cgst: n("tax_other_c"),
						sgst: 0,
						igst: 0,
						cess: 0
					},
					tot_amt_payable: {
						cgst: n("tax_camt_c"),
						sgst: n("tax_samt_c"),
						igst: n("tax_iamt_c"),
						cess: n("tax_csamt_c")
					},
					unrec_amt: {
						cgst: 0,
						sgst: 0,
						igst: 0,
						cess: 0
					},
					tot_amt_paid: {
						cgst: n("b_cgst_c"),
						sgst: n("b_sgst_c"),
						igst: n("b_igst_c"),
						cess: n("b_cess_c")
					}
				},

				/* ---------- TABLE 12 (ITC) ---------- */
				table12: {
					itc_avail: n("itc_igst_c"),
					itc_book_earl: 0,
					itc_book_curr: 0,
					itc_avail_audited: n("itc_igst_c"),
					itc_claim: 0,
					unrec_itc: 0
				},

				/* ---------- TABLE 16 (Additional Liability) ---------- */
				table16: {
					cgst: n("tax_camt_c"),
					sgst: n("tax_samt_c"),
					igst: n("tax_iamt_c"),
					cess: n("tax_csamt_c"),
					inter: n("tax_intr_c"),
					pen: n("tax_pen_c")
				}
			}
		},

		/* ---------- DOCUMENTS ---------- */
		dcupdtls: {
			balance_sheet: [],
			profitloss: [],
			otherdoc1: [],
			otherdoc2: []
		}
	};
	return payload;
}
// Period Options Update
function updatePeriodOptions() {
    const quarterSelect = document.getElementById('quarterSelect');
    const periodSelect = document.getElementById('periodSelect');
    periodSelect.innerHTML = '<option value="">Select Period</option>';

    if (quarterSelect.value === '') {
        periodSelect.disabled = true;
        return;
    }

    periodSelect.disabled = false;
    let months = [];

    switch (quarterSelect.value) {
        case 'Q1': months = [{value: '04', text: 'April'}, {value: '05', text: 'May'}, {value: '06', text: 'June'}]; break;
        case 'Q2': months = [{value: '07', text: 'July'}, {value: '08', text: 'August'}, {value: '09', text: 'September'}]; break;
        case 'Q3': months = [{value: '10', text: 'October'}, {value: '11', text: 'November'}, {value: '12', text: 'December'}]; break;
        case 'Q4': months = [{value: '01', text: 'January'}, {value: '02', text: 'February'}, {value: '03', text: 'March'}]; break;
    }

    months.forEach(month => {
        const option = document.createElement('option');
        option.value = month.value;
        option.textContent = month.text;
        periodSelect.appendChild(option);
    });
}

function hideAllTables() {
    document.querySelectorAll(".report-table").forEach(table => {
        table.style.display = "none";
    });
}

// NIL-supported returns list
const nilSupportedReturns = ['gstr1', 'gstr3b', 'gstr4', 'gstr9', 'gstr10'];

// Update Child Reports & NIL Toggle Visibility
function updateChildReports() {
    const mainReportType = document.getElementById('mainReportType').value;
    const childReportSelect = document.getElementById('childReportType');
    const isNilReturn = document.getElementById('isNilReturn').checked;
    const nilToggleContainer = document.getElementById('nilToggleContainer');
    const dataDisplayCard = document.getElementById('dataDisplayCard');
    const submitButtonCard = document.getElementById('submitButtonCard');
    const nilDeclarationForm = document.getElementById('nilDeclarationForm');
    const dataTablesContainer = document.getElementById('dataTablesContainer');
    const generateBtnText = document.getElementById('generateBtnText');

    // Hide all
    hideAllTables();
    dataDisplayCard.style.display = 'none';
    submitButtonCard.style.display = 'none';
    nilDeclarationForm.style.display = 'none';
    dataTablesContainer.style.display = 'block';

    // Show NIL toggle only for supported returns
    if (nilSupportedReturns.includes(mainReportType)) {
        nilToggleContainer.style.display = 'block';
    } else {
        nilToggleContainer.style.display = 'none';
        document.getElementById('isNilReturn').checked = false;
    }

    // NIL Mode
    if (isNilReturn && nilSupportedReturns.includes(mainReportType)) {
        childReportSelect.disabled = true;
        childReportSelect.innerHTML = '<option value="">Not Applicable (NIL)</option>';
        generateBtnText.textContent = 'File NIL Return';
        return;
    }

    // Normal Mode
    generateBtnText.textContent = 'Generate Report';

    // GSTR-1: Enable child dropdown with B2B/B2CL/B2CS
    if (mainReportType === 'gstr1') {
        childReportSelect.disabled = false;
        childReportSelect.innerHTML = `
            <option value="">Select Type</option>
            <option value="b2b">B2B (Business to Business)</option>
            <option value="b2cl">B2CL (B2C Large)</option>
            <option value="b2cs">B2CS (B2C Small)</option>
        `;
    }
    // All others: Disable child dropdown
    else if (mainReportType) {
        childReportSelect.disabled = true;
        childReportSelect.innerHTML = '<option value="">Not Applicable</option>';
    } else {
        childReportSelect.disabled = true;
        childReportSelect.innerHTML = '<option value="">Select Return Type First</option>';
    }
}

// Generate Return Data
function generateReturnData() {
	var gst_username = $("#gst_username").val();
    const mainReportType = document.getElementById('mainReportType').value;
    const childReportType = document.getElementById('childReportType').value;
    const isNilReturn = document.getElementById('isNilReturn').checked;
    const financialYear = $('#financialYear').val();
    const quarterSelect = $('#quarterSelect').val();
    const periodSelect = $('#periodSelect').val();

    // Validation
    if (!mainReportType) {
        alert("Please select GST Return Type");
        return;
    }
    if (!financialYear || !quarterSelect || !periodSelect) {
        alert("Please select Financial Year, Quarter and Period");
        return;
    }
	if(gst_username ===""){
		alert("Please enter register username of https://www.gst.gov.in");
		location.reload();
		return;
	}

    const dataDisplayCard = document.getElementById('dataDisplayCard');
    const submitButtonCard = document.getElementById('submitButtonCard');
    const nilDeclarationForm = document.getElementById('nilDeclarationForm');
    const dataTablesContainer = document.getElementById('dataTablesContainer');
    const nilReturnTitle = document.getElementById('nilReturnTitle');

    // NIL Mode: Show declaration
    if (isNilReturn) {
        const monthName = $('#periodSelect option:selected').text();
        const year = financialYear.split('-')[0];
        nilReturnTitle.textContent = `NIL ${mainReportType.toUpperCase()} for ${monthName} ${year}`;

        dataDisplayCard.style.display = 'block';
        nilDeclarationForm.style.display = 'block';
        dataTablesContainer.style.display = 'none';
        submitButtonCard.style.display = 'block';
        return;
    }

    // Normal Mode: Show static tables
    if (mainReportType === 'gstr1' && !childReportType) {
        alert("Please select Report Type (B2B/B2CL/B2CS)");
        return;
    }

    dataDisplayCard.style.display = 'block';
    nilDeclarationForm.style.display = 'none';
    dataTablesContainer.style.display = 'block';
    submitButtonCard.style.display = 'block';
    hideAllTables();

    // Show appropriate table based on selection
    if(mainReportType == "gstr1"){
        if(childReportType == "b2b"){
            $('#b2b_invoices').show();
        } else if(childReportType == "b2cl"){
            $('#b2c_large').show();
        } else if(childReportType == "b2cs"){
            $('#b2c_small').show();
        }
    } else if(mainReportType == "gstr3b"){
        $('#output_tax_liabilities').show();
    } else if(mainReportType == "gstr4"){
        $('#gstr4_table').show();
    } else if(mainReportType == "gstr9"){
        $('#gstr9_table').show();
    } else if(mainReportType == "gstr9c"){
        $('#gstr9c_table').show();
    } else if(mainReportType == "gstr10"){
        $('#gstr10_table').show();
    } else if(mainReportType == "cmp08"){
        $('#cmp08_table').show();
    }

	//start ajax call
	$('#loader').show();
		$.ajax({
			method: "POST",
			url: "/getGstReturnsData",
			data: {
					financialYear:financialYear,
					quarterSelect:quarterSelect,
					periodSelect:periodSelect,
					mainReportType: mainReportType,
					childReportType: childReportType,
					isNilReturn: isNilReturn
				  },
			success: function (response) {
				console.log(response);
				$('#loader').hide();
				$(".messageContainer").html('');
				if (response && response)
				{
					if(mainReportType =="gstr1" && childReportType =="b2b"){
						let table = $('.b2b_invoices');
						const tbody = document.querySelector('.b2b_invoices tbody');
						table.find('tbody').empty();
						if (response && response){
							console.log(response.b2b);

							// Initialize totals
							let totalVal = 0;
							let totalTxval = 0;
							let totalIamt = 0;
							let totalCamt = 0;
							let totalSamt = 0;
							let totalCsamt = 0;

							response.b2b.forEach(b2bItem => {
								const ctin = b2bItem.ctin;
								b2bItem.inv.forEach(inv => {
									const { inum, idt, val, pos } = inv;
									totalVal += val; // total invoice value

									inv.itms.forEach(item => {
										const { num, itm_det } = item;
										const { rt, txval, iamt,camt,samt, csamt } = itm_det;

										// Accumulate totals
										totalTxval += txval;
										totalIamt += iamt;
										totalCamt += camt;
										totalSamt += samt;
										totalCsamt += csamt;

										// Create table row
										const tr = document.createElement('tr');
										tr.innerHTML = `
											<td>${ctin}</td>
											<td>${inum}</td>
											<td>${idt}</td>
											<td>₹${val.toFixed(2)}</td>
											<td>₹${txval.toFixed(2)}</td>
											<td>${rt}%</td>
											<td>₹${iamt.toFixed(2)}</td>
											<td>₹${camt.toFixed(2)}</td>
											<td>₹${samt.toFixed(2)}</td>
											<td>₹${csamt.toFixed(2)}</td>
										`;
										tbody.appendChild(tr);
									});
								});
							});

							// After loop: add total row
							const totalRow = document.createElement('tr');
							totalRow.innerHTML = `
								<th colspan="3" class="text-end" >Total:</th>
								<th><b>₹${totalVal.toFixed(2)}</b></th>
								<th><b>₹${totalTxval.toFixed(2)}</b></th>
								<th>-</td>
								<th><b>₹${totalIamt.toFixed(2)}</b></th>
								<th><b>₹${totalCamt.toFixed(2)}</b></th>
								<th><b>₹${totalSamt.toFixed(2)}</b></th>
								<th><b>₹${totalCsamt.toFixed(2)}</b></th>
							`;
							tbody.appendChild(totalRow);


						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="gstr1" && childReportType =="b2cl"){
						let table = $('.b2c_large');
						const tbody = document.querySelector('.b2c_large tbody');
						table.find('tbody').empty();
						if (response && response){
								console.log(response.b2cl);
								let totalVal = 0;
								let totalTxval = 0;
								let totalIamt = 0;
								let totalCsamt = 0;

								response.b2cl.forEach(b2clItem => {
									const pos = b2clItem.pos;

									b2clItem.inv.forEach(inv => {
										const { inum, idt, val, itms } = inv;

										// sum all item details under this invoice
										let invTxval = 0;
										let invIamt = 0;
										let invCsamt = 0;

										itms.forEach(item => {
											const { txval, iamt, csamt } = item.itm_det;
											invTxval += txval;
											invIamt += iamt;
											invCsamt += csamt;
										});

										// Add to total
										totalVal += val;
										totalTxval += invTxval;
										totalIamt += invIamt;
										totalCsamt += invCsamt;

										// Create single row per invoice
										const tr = document.createElement('tr');
										tr.innerHTML = `
											<td>${pos}</td>
											<td>${inum}</td>
											<td>${idt}</td>
											<td>₹${val.toFixed(2)}</td>
											<td>₹${invTxval.toFixed(2)}</td>
											<td>18%</td> <!-- optional: could use first item rate if same -->
											<td>₹${invIamt.toFixed(2)}</td>
											<td>₹${invCsamt.toFixed(2)}</td>
										`;
										tbody.appendChild(tr);
									});
								});

								// Add totals footer
								const totalRow = document.createElement('tr');
								totalRow.innerHTML = `
									<th colspan="3" class="text-end" >Total:</th>
									<th><b>₹${totalVal.toFixed(2)}</b></th>
									<th><b>₹${totalTxval.toFixed(2)}</b></th>
									<th>-</td>
									<th><b>₹${totalIamt.toFixed(2)}</b></th>
									<th><b>₹${totalCsamt.toFixed(2)}</b></th>
								`;
								tbody.appendChild(totalRow);

						}else{
                            table.find('tbody').append(`<tr class="text-center"><td colspan="10">No record found</td></tr>`);
						}
					}else if(mainReportType =="gstr1" && childReportType =="b2cs"){
						let table = $('.b2c_small');
						const tbody = document.querySelector('.b2c_small tbody');
						table.find('tbody').empty();
						if (response && response){
							console.log(response.b2cs);
							let totalTxval = 0;
							let totalIamt = 0;
							let totalCamt = 0;
							let totalSamt = 0;
							let totalCsamt = 0;

							response.b2cs.forEach((item) => {
							  // Accumulate totals
							  totalTxval += item.txval;
							  totalIamt += item.iamt;
							  totalCsamt += item.csamt;
							  totalCamt += (item.iamt/2);
							  totalSamt += (item.iamt/2);

							  // Create table row
							  const row = `
								<tr>
								  <td>${item.sply_ty}</td>
								  <td>${item.pos}</td>
								  <td>${item.typ}</td>
								  <td>₹${item.txval.toFixed(2)}</td>
								  <td>${item.rt}%</td>
								  <td>₹${item.iamt.toFixed(2)}</td>
								  <td>₹${item.iamt.toFixed(2)/2}</td>
								  <td>₹${item.iamt.toFixed(2)/2}</td>
								  <td>₹${item.csamt.toFixed(2)}</td>
								</tr>
							  `;
							  tbody.insertAdjacentHTML('beforeend', row);
							});

							// Add totals footer
							const totalRow = document.createElement('tr');
							totalRow.innerHTML = `
								<th colspan="3" class="text-end" >Total:</th>
								<th><b>₹${totalTxval.toFixed(2)}</b></th>
								<th>-</th>
								<th><b>₹${totalIamt.toFixed(2)}</b></th>
								<th><b>₹${totalCamt.toFixed(2)}</b></th>
								<th><b>₹${totalSamt.toFixed(2)}</b></th>
								<th><b>₹${totalCsamt.toFixed(2)}</b></th>
							`;
							tbody.appendChild(totalRow);
						}else{
							table.find('tbody').append(`<tr class="text-center"><td colspan="8">No record found</td></tr>`);
						}
					}else if(mainReportType =="gstr3b"){
						let table = $('.output_tax_liabilities');
						table.find('tbody').empty();
						if (response && response.data){
							console.log(response.data);
							const gstData  = response.data;
							const labels = {
							  "osup_det": "<strong>3.1(a)</strong> Outward taxable supplies (other than zero rated, nil rated and exempted)",
							  "osup_zero": "<strong>3.1(b)</strong> Outward taxable supplies (zero rated)",
							  "osup_nil_exmp": "<strong>3.1(c)</strong> Other outward supplies (nil rated, exempted)",
							  "isup_rev": "<strong>3.1(d)</strong> Inward supplies (liable to reverse charge)",
							  "osup_nongst": "<strong>3.1(e)</strong> Non-GST outward supplies"
							};
							// Render rows
							const tbody = document.getElementById("gstTableBody");
							Object.entries(gstData.sup_details).forEach(([key, val]) => {
							  const row = document.createElement("tr");
							  row.innerHTML = `
								<td>${labels[key] || key}</td>
								<td><input type="number" class="form-control calc" data-field="txval" value="${val.txval || 0}" readonly></td>
								<td><input type="number" class="form-control calc" data-field="iamt" value="${val.iamt || 0}" readonly readonly></td>
								<td><input type="number" class="form-control calc" data-field="camt" value="${val.camt || 0}" readonly></td>
								<td><input type="number" class="form-control calc" data-field="samt" value="${val.samt || 0}" readonly></td>
								<td><input type="number" class="form-control calc" data-field="csamt" value="${val.csamt || 0}" readonly></td>
							  `;
							  tbody.appendChild(row);
							});
							
							// ===============================
							// Render ITC Net (Table 4)
							// ===============================
							if (gstData.itc_elg && gstData.itc_elg.itc_net) {
								const itc = gstData.itc_elg.itc_net;

								const itcRow = document.createElement("tr");
								itcRow.innerHTML = `
									<td><strong>4. Eligible ITC (Net)</strong></td>
									<td><input type="number" class="form-control calc" data-field="txval" value="0" readonly></td>
									<td><input type="number" class="form-control calc" data-field="iamt" value="${itc.iamt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="camt" value="${itc.camt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="samt" value="${itc.samt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="csamt" value="${itc.csamt || 0}" readonly></td>
								`;

								tbody.appendChild(itcRow);
							}
							
							// Render Interest & Late Fees
							if (gstData.intr_ltfee) {

								// Interest Details Row
								const intr = gstData.intr_ltfee.intr_details || {};
								const intrRow = document.createElement("tr");
								intrRow.innerHTML = `
									<td><strong>Interest</strong></td>
									<td><input type="number" class="form-control calc" data-field="txval" value="0" readonly></td>
									<td><input type="number" class="form-control calc" data-field="iamt" value="${intr.iamt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="camt" value="${intr.camt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="samt" value="${intr.samt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="csamt" value="${intr.csamt || 0}" readonly></td>
								`;
								tbody.appendChild(intrRow);

								// Late Fee Details Row
								const ltfee = gstData.intr_ltfee.ltfee_details || {};
								const ltfeeRow = document.createElement("tr");
								ltfeeRow.innerHTML = `
									<td><strong>Late Fee</strong></td>
									<td><input type="number" class="form-control calc" data-field="txval" value="0" readonly></td>
									<td><input type="number" class="form-control calc" data-field="iamt" value="${ltfee.iamt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="camt" value="${ltfee.camt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="samt" value="${ltfee.samt || 0}" readonly></td>
									<td><input type="number" class="form-control calc" data-field="csamt" value="${ltfee.csamt || 0}" readonly></td>
								`;
								tbody.appendChild(ltfeeRow);
							}
							
							// Calculate totals
							function calculateTotals() {
							  const totals = { txval: 0, iamt: 0, camt: 0, samt: 0, csamt: 0 };
							  document.querySelectorAll("#gstTableBody tr").forEach(row => {
								row.querySelectorAll("input").forEach(input => {
								  const field = input.dataset.field;
								  totals[field] += parseFloat(input.value || 0);
								});
							  });
							  for (const [key, value] of Object.entries(totals)) {
								document.getElementById(`total_${key}`).innerText = "₹" + value.toLocaleString('en-IN');
							  }
							}

							// Initial calculation
							calculateTotals();

							// Event: allow only numbers and dot
							document.querySelectorAll(".calc").forEach(input => {
							  // Restrict to digits + dot
							  input.addEventListener("input", e => {
								e.target.value = e.target.value.replace(/[^0-9.]/g, '');
							  });

							  // Prevent multiple dots
							  input.addEventListener("blur", e => {
								const val = e.target.value;
								if ((val.match(/\./g) || []).length > 1) {
								  e.target.value = val.substring(0, val.lastIndexOf('.'));
								}
							  });

							  // Recalculate totals on change
							  input.addEventListener("input", calculateTotals);
							});
						}else{
							$(".messageContainer").html('<div class="err">' + response.error.message + "</div>");
							table.find('tbody').append(`<tr class="text-center"><td colspan="6">No record found</td></tr>`);
						}
					}else if(mainReportType =="gstr9"){
						if (response && response.data){
							const data = response.data; 
							function get(obj, key) {
								return obj && obj[key] !== undefined ? obj[key] : 0;
							}

							// Extract table4, table6, table9
							const t4  = data.table4  || {};
							const t5  = data.table5  || {};
							const t6  = data.table6  || {};
							const t8  = data.table8  || {};
							const t9  = data.table9  || {};
							
							// Initialize totals
							let totalPayable = 0;
							let totalPaidITC = 0;
							let totalPaidCash = 0;

							// -----------------------------
							// PART IV : Outward Supplies
							// -----------------------------
							
							const outwardB2B = {
							  txval: get(t4.b2b, "txval"),
							  igst : get(t4.b2b, "iamt"),
							  cgst : get(t4.b2b, "camt"),
							  sgst : get(t4.b2b, "samt"),
							  cess : get(t4.b2b, "csamt")
							};

							const outwardB2C = {
							  txval: get(t4.b2c, "txval"),
							  igst : get(t4.b2c, "iamt"),
							  cgst : get(t4.b2c, "camt"),
							  sgst : get(t4.b2c, "samt"),
							  cess : get(t4.b2c, "csamt")
							};
							
							let outward = {
								txval: get(t4.b2b, "txval") + get(t4.b2c, "txval") + get(t4.exp, "txval"),
								igst : get(t4.b2b, "iamt")  + get(t4.b2c, "iamt")  + get(t4.exp, "iamt"),
								cgst : get(t4.b2b, "camt")  + get(t4.b2c, "camt"),
								sgst : get(t4.b2b, "samt")  + get(t4.b2c, "samt"),
								cess : get(t4.b2b, "csamt") + get(t4.b2c, "csamt")
							};

							// -----------------------------
							// PART VI : ITC Availed
							// -----------------------------
							let itc = {
								igst: get(t6.total_itc_availed, "iamt"),
								cgst: get(t6.total_itc_availed, "camt"),
								sgst: get(t6.total_itc_availed, "samt"),
								cess: get(t6.total_itc_availed, "csamt")
							};

							// -----------------------------
							// PART VIII : Other ITC
							// -----------------------------
							let otherItc = {
								igst: get(t8.itc_2b, "iamt"),
								cgst: get(t8.itc_2b, "camt"),
								sgst: get(t8.itc_2b, "samt"),
								cess: get(t8.itc_2b, "csamt")
							};
							
							// -----------------------------
							// PART IX : Details of Tax Paid
							// -----------------------------
							Object.keys(t9).forEach(key => {
								const section = t9[key];

								if (typeof section === "object") {

									// Add tax payable
									totalPayable += get(section, "txpyble");

									// Add paid through cash
									totalPaidCash += get(section, "txpaid_cash");

									// Add paid through ITC (it can have different keys)
									Object.keys(section).forEach(subKey => {
										if (subKey.startsWith("tax_paid_itc")) {
											totalPaidITC += get(section, subKey);
										}
									});
								}
							});
							
							$("#tax_iamt").val((get(t9.iamt,  "txpyble")));
							$("#tax_camt").val((get(t9.camt,  "txpyble")));
							$("#tax_samt").val((get(t9.samt,  "txpyble")));
							$("#tax_csamt").val((get(t9.csamt, "txpyble")));
							$("#tax_intr").val((get(t9.intr,  "txpyble")));
							$("#tax_fee").val((get(t9.fee,   "txpyble")));
							$("#tax_other").val((get(t9.other,"txpyble")));
							$("#tax_pen").val((get(t9.pen,   "txpyble")));
							
							// -----------------------------
							// PART VIII : Tax Paid
							// -----------------------------
							let taxPaid = {
								igst: get(t9.iamt, "txpaid_cash"),
								cgst: get(t9.camt, "txpaid_cash"),
								sgst: get(t9.samt, "txpaid_cash"),
								cess: get(t9.csamt, "txpaid_cash")
							};

							// -----------------------------
							// NET TAX POSITION
							// -----------------------------
							let net = {
								txval: outward.txval,
								igst : taxPaid.igst,
								cgst : taxPaid.cgst,
								sgst : taxPaid.sgst,
								cess : taxPaid.cess
							};
							
							//Tables 10–13 (MERGED) sumGstr9Table_10to13
							const t10 = data.table10 || {};
							const t11 = data.table11 || {};
							const t12 = data.table12 || {};
							const t13 = data.table13 || {};

							const s10 = sumGstr9Table_10to13(t10);
							const s11 = sumGstr9Table_10to13(t11);
							const s12 = sumGstr9Table_10to13(t12);
							const s13 = sumGstr9Table_10to13(t13);
							const sectionA = {
								txval: s10.txval + s11.txval + s12.txval + s13.txval,
								igst : s10.igst  + s11.igst  + s12.igst  + s13.igst,
								cgst : s10.cgst  + s11.cgst  + s12.cgst  + s13.cgst,
								sgst : s10.sgst  + s11.sgst  + s12.sgst  + s13.sgst,
								cess : s10.cess  + s11.cess  + s12.cess  + s13.cess
							};
							//Differential tax paid on account
							const t14 = data.table14 || {};
							const sectionB = {
								igst : get(t14.iamt, "txpyble"),
								cgst : get(t14.camt, "txpyble"),
								sgst : get(t14.samt, "txpyble"),
								cess : get(t14.csamt, "txpyble")
							};
							//Demand and Refund → Table 15
							const t15 = data.table15 || {};
							const sectionC = {
								igst : get(t15.iamt, "txval"),
								cgst : get(t15.camt, "txval"),
								sgst : get(t15.samt, "txval"),
								cess : get(t15.csamt, "txval")
							};
							//Supplies received from composition → Table 16
							const t16 = data.table16 || {};
							const sectionD = {
								igst : get(t16.iamt, "txval"),
								cgst : get(t16.camt, "txval"),
								sgst : get(t16.samt, "txval"),
								cess : get(t16.csamt, "txval")
							};
							//HSN summary of Outward supplies → Table 17 (HSN SUMMARY)
							const t17 = data.table17 || {};
							let sectionE = {
								txval: 0,
								igst : 0,
								cgst : 0,
								sgst : 0,
								cess : 0,
								hsn_sc_arr: [],   
								rt_arr: []   
							};

							if (Array.isArray(t17.items)) {
								t17.items.forEach(i => {
									// your existing total logic (unchanged)
									sectionE.txval += get(i, "txval");
									sectionE.igst  += get(i, "iamt");
									sectionE.cgst  += get(i, "camt");
									sectionE.sgst  += get(i, "samt");
									sectionE.cess  += get(i, "csamt");
									// minimal addition (no logic change)
									sectionE.hsn_sc_arr.push(i.hsn_sc || "");
									sectionE.rt_arr.push(i.rt || 0);
								});
							}

							//HSN summary of Inward supplies → Table 18
							const t18 = data.table18 || {};
							let sectionF = {
								txval: 0,
								igst : 0,
								cgst : 0,
								sgst : 0,
								cess : 0,
								hsn_sc_arr: [],   
								rt_arr: []   
							};

							if (Array.isArray(t18.items)) {
								t17.items.forEach(i => {
									// your existing total logic (unchanged)
									sectionF.txval += get(i, "txval");
									sectionF.igst  += get(i, "iamt");
									sectionF.cgst  += get(i, "camt");
									sectionF.sgst  += get(i, "samt");
									sectionF.cess  += get(i, "csamt");
									// minimal addition (no logic change)
									sectionF.hsn_sc_arr.push(i.hsn_sc || "");
									sectionF.rt_arr.push(i.rt || 0);
								});
							}
							//Late fees → Table 19
							const t19 = data.table19 || {};
							const sectionG = {
								igst : get(t19.iamt, "txval"),
								cgst : get(t19.camt, "txval"),
								sgst : get(t19.samt, "txval"),
								cess : get(t19.csamt, "txval")
							};



							// -----------------------------
							// FILL TABLE IN HTML
							// -----------------------------
							$("#b2b_txval").val(formatIndianNumber(outwardB2B.txval));
							$("#b2b_igst").val(formatIndianNumber(outwardB2B.igst));
							$("#b2b_cgst").val(formatIndianNumber(outwardB2B.cgst));
							$("#b2b_sgst").val(formatIndianNumber(outwardB2B.sgst));
							$("#b2b_cess").val(formatIndianNumber(outwardB2B.cess));

							$("#b2c_txval").val(formatIndianNumber(outwardB2C.txval));
							$("#b2c_igst").val(formatIndianNumber(outwardB2C.igst));
							$("#b2c_cgst").val(formatIndianNumber(outwardB2C.cgst));
							$("#b2c_sgst").val(formatIndianNumber(outwardB2C.sgst));
							$("#b2c_cess").val(formatIndianNumber(outwardB2C.cess));

							$("#out_txval").val(formatIndianNumber(outward.txval));
							$("#out_igst").val(formatIndianNumber(outward.igst));
							$("#out_cgst").val(formatIndianNumber(outward.cgst));
							$("#out_sgst").val(formatIndianNumber(outward.sgst));
							$("#out_cess").val(formatIndianNumber(outward.cess));

							$("#itc_igst").val(formatIndianNumber(itc.igst));
							$("#itc_cgst").val(formatIndianNumber(itc.cgst));
							$("#itc_sgst").val(formatIndianNumber(itc.sgst));
							$("#itc_cess").val(formatIndianNumber(itc.cess));

							$("#other_itc_igst").val(formatIndianNumber(otherItc.igst));
							$("#other_itc_cgst").val(formatIndianNumber(otherItc.cgst));
							$("#other_itc_sgst").val(formatIndianNumber(otherItc.sgst));
							$("#other_itc_cess").val(formatIndianNumber(otherItc.cess));

							$("#tax_payable").val(formatIndianNumber(totalPayable));
							$("#tax_pay_itc").val(formatIndianNumber(totalPaidITC));
							$("#tax_pay_cash").val(formatIndianNumber(totalPaidCash));
							
							$("#a_txval").val(formatIndianNumber(sectionA.txval));
							$("#a_igst").val(formatIndianNumber(sectionA.igst));
							$("#a_cgst").val(formatIndianNumber(sectionA.cgst));
							$("#a_sgst").val(formatIndianNumber(sectionA.sgst));
							$("#a_cess").val(formatIndianNumber(sectionA.cess));

							$("#b_igst").val(formatIndianNumber(sectionB.igst));
							$("#b_cgst").val(formatIndianNumber(sectionB.cgst));
							$("#b_sgst").val(formatIndianNumber(sectionB.sgst));
							$("#b_cess").val(formatIndianNumber(sectionB.cess));

							$("#e_hsn_sc").val(sectionE.hsn_sc_arr[0] || "");
							$("#e_rt").val(sectionE.rt_arr[0] || "");
							$("#e_txval").val(formatIndianNumber(sectionE.txval));
							$("#e_igst").val(formatIndianNumber(sectionE.igst));
							$("#e_cgst").val(formatIndianNumber(sectionE.cgst));
							$("#e_sgst").val(formatIndianNumber(sectionE.sgst));
							$("#e_cess").val(formatIndianNumber(sectionE.cess));

							$("#f_hsn_sc").val(sectionF.hsn_sc_arr[0] || "");
							$("#f_rt").val(sectionF.rt_arr[0] || "");
							$("#f_txval").val(formatIndianNumber(sectionF.txval));
							$("#f_igst").val(formatIndianNumber(sectionF.igst));
							$("#f_cgst").val(formatIndianNumber(sectionF.cgst));
							$("#f_sgst").val(formatIndianNumber(sectionF.sgst));
							$("#f_cess").val(formatIndianNumber(sectionF.cess));

							

						}else{
							$(".messageContainer").html('<div class="err">' + response.error.message + "</div>");							
						}
					}else if(mainReportType =="gstr9c"){
						if (response && response.data){
							const data = response.data; 
							function get(obj, key) {
								return obj && obj[key] !== undefined ? obj[key] : 0;
							}

							// Extract table4, table6, table9
							const t4  = data.table4  || {};
							const t5  = data.table5  || {};
							const t6  = data.table6  || {};
							const t8  = data.table8  || {};
							const t9  = data.table9  || {};
							
							// Initialize totals
							let totalPayable = 0;
							let totalPaidITC = 0;
							let totalPaidCash = 0;

							// -----------------------------
							// PART IV : Outward Supplies
							// -----------------------------
							
							const outwardB2B = {
							  txval: get(t4.b2b, "txval"),
							  igst : get(t4.b2b, "iamt"),
							  cgst : get(t4.b2b, "camt"),
							  sgst : get(t4.b2b, "samt"),
							  cess : get(t4.b2b, "csamt")
							};

							const outwardB2C = {
							  txval: get(t4.b2c, "txval"),
							  igst : get(t4.b2c, "iamt"),
							  cgst : get(t4.b2c, "camt"),
							  sgst : get(t4.b2c, "samt"),
							  cess : get(t4.b2c, "csamt")
							};
							
							let outward = {
								txval: get(t4.b2b, "txval") + get(t4.b2c, "txval") + get(t4.exp, "txval"),
								igst : get(t4.b2b, "iamt")  + get(t4.b2c, "iamt")  + get(t4.exp, "iamt"),
								cgst : get(t4.b2b, "camt")  + get(t4.b2c, "camt"),
								sgst : get(t4.b2b, "samt")  + get(t4.b2c, "samt"),
								cess : get(t4.b2b, "csamt") + get(t4.b2c, "csamt")
							};

							// -----------------------------
							// PART VI : ITC Availed
							// -----------------------------
							let itc = {
								igst: get(t6.total_itc_availed, "iamt"),
								cgst: get(t6.total_itc_availed, "camt"),
								sgst: get(t6.total_itc_availed, "samt"),
								cess: get(t6.total_itc_availed, "csamt")
							};

							// -----------------------------
							// PART VIII : Other ITC
							// -----------------------------
							let otherItc = {
								igst: get(t8.itc_2b, "iamt"),
								cgst: get(t8.itc_2b, "camt"),
								sgst: get(t8.itc_2b, "samt"),
								cess: get(t8.itc_2b, "csamt")
							};
							
							// -----------------------------
							// PART IX : Details of Tax Paid
							// -----------------------------
							Object.keys(t9).forEach(key => {
								const section = t9[key];

								if (typeof section === "object") {

									// Add tax payable
									totalPayable += get(section, "txpyble");

									// Add paid through cash
									totalPaidCash += get(section, "txpaid_cash");

									// Add paid through ITC (it can have different keys)
									Object.keys(section).forEach(subKey => {
										if (subKey.startsWith("tax_paid_itc")) {
											totalPaidITC += get(section, subKey);
										}
									});
								}
							});
							
							$("#tax_iamt_c").val((get(t9.iamt,  "txpyble")));
							$("#tax_camt_c").val((get(t9.camt,  "txpyble")));
							$("#tax_samt_c").val((get(t9.samt,  "txpyble")));
							$("#tax_csamt_c").val((get(t9.csamt, "txpyble")));
							$("#tax_intr_c").val((get(t9.intr,  "txpyble")));
							$("#tax_fee_c").val((get(t9.fee,   "txpyble")));
							$("#tax_other_c").val((get(t9.other,"txpyble")));
							$("#tax_pen_c").val((get(t9.pen,   "txpyble")));
							
							// -----------------------------
							// PART VIII : Tax Paid
							// -----------------------------
							let taxPaid = {
								igst: get(t9.iamt, "txpaid_cash"),
								cgst: get(t9.camt, "txpaid_cash"),
								sgst: get(t9.samt, "txpaid_cash"),
								cess: get(t9.csamt, "txpaid_cash")
							};

							// -----------------------------
							// NET TAX POSITION
							// -----------------------------
							let net = {
								txval: outward.txval,
								igst : taxPaid.igst,
								cgst : taxPaid.cgst,
								sgst : taxPaid.sgst,
								cess : taxPaid.cess
							};
							
							//Tables 10–13 (MERGED) sumGstr9Table_10to13
							const t10 = data.table10 || {};
							const t11 = data.table11 || {};
							const t12 = data.table12 || {};
							const t13 = data.table13 || {};

							const s10 = sumGstr9Table_10to13(t10);
							const s11 = sumGstr9Table_10to13(t11);
							const s12 = sumGstr9Table_10to13(t12);
							const s13 = sumGstr9Table_10to13(t13);
							const sectionA = {
								txval: s10.txval + s11.txval + s12.txval + s13.txval,
								igst : s10.igst  + s11.igst  + s12.igst  + s13.igst,
								cgst : s10.cgst  + s11.cgst  + s12.cgst  + s13.cgst,
								sgst : s10.sgst  + s11.sgst  + s12.sgst  + s13.sgst,
								cess : s10.cess  + s11.cess  + s12.cess  + s13.cess
							};
							//Differential tax paid on account
							const t14 = data.table14 || {};
							const sectionB = {
								igst : get(t14.iamt, "txpyble"),
								cgst : get(t14.camt, "txpyble"),
								sgst : get(t14.samt, "txpyble"),
								cess : get(t14.csamt, "txpyble")
							};
							//Demand and Refund → Table 15
							const t15 = data.table15 || {};
							const sectionC = {
								igst : get(t15.iamt, "txval"),
								cgst : get(t15.camt, "txval"),
								sgst : get(t15.samt, "txval"),
								cess : get(t15.csamt, "txval")
							};
							//Supplies received from composition → Table 16
							const t16 = data.table16 || {};
							const sectionD = {
								igst : get(t16.iamt, "txval"),
								cgst : get(t16.camt, "txval"),
								sgst : get(t16.samt, "txval"),
								cess : get(t16.csamt, "txval")
							};
							//HSN summary of Outward supplies → Table 17 (HSN SUMMARY)
							const t17 = data.table17 || {};
							let sectionE = {
								txval: 0,
								igst : 0,
								cgst : 0,
								sgst : 0,
								cess : 0,
								hsn_sc_arr: [],   
								rt_arr: []   
							};

							if (Array.isArray(t17.items)) {
								t17.items.forEach(i => {
									// your existing total logic (unchanged)
									sectionE.txval += get(i, "txval");
									sectionE.igst  += get(i, "iamt");
									sectionE.cgst  += get(i, "camt");
									sectionE.sgst  += get(i, "samt");
									sectionE.cess  += get(i, "csamt");
									// minimal addition (no logic change)
									sectionE.hsn_sc_arr.push(i.hsn_sc || "");
									sectionE.rt_arr.push(i.rt || 0);
								});
							}

							//HSN summary of Inward supplies → Table 18
							const t18 = data.table18 || {};
							let sectionF = {
								txval: 0,
								igst : 0,
								cgst : 0,
								sgst : 0,
								cess : 0,
								hsn_sc_arr: [],   
								rt_arr: []   
							};

							if (Array.isArray(t18.items)) {
								t17.items.forEach(i => {
									// your existing total logic (unchanged)
									sectionF.txval += get(i, "txval");
									sectionF.igst  += get(i, "iamt");
									sectionF.cgst  += get(i, "camt");
									sectionF.sgst  += get(i, "samt");
									sectionF.cess  += get(i, "csamt");
									// minimal addition (no logic change)
									sectionF.hsn_sc_arr.push(i.hsn_sc || "");
									sectionF.rt_arr.push(i.rt || 0);
								});
							}
							//Late fees → Table 19
							const t19 = data.table19 || {};
							const sectionG = {
								igst : get(t19.iamt, "txval"),
								cgst : get(t19.camt, "txval"),
								sgst : get(t19.samt, "txval"),
								cess : get(t19.csamt, "txval")
							};



							// -----------------------------
							// FILL TABLE IN HTML
							// -----------------------------
							$("#b2b_txval_c").val(formatIndianNumber(outwardB2B.txval));
							$("#b2b_igst_c").val(formatIndianNumber(outwardB2B.igst));
							$("#b2b_cgst_c").val(formatIndianNumber(outwardB2B.cgst));
							$("#b2b_sgst_c").val(formatIndianNumber(outwardB2B.sgst));
							$("#b2b_cess_c").val(formatIndianNumber(outwardB2B.cess));

							$("#b2c_txval_c").val(formatIndianNumber(outwardB2C.txval));
							$("#b2c_igst_c").val(formatIndianNumber(outwardB2C.igst));
							$("#b2c_cgst_c").val(formatIndianNumber(outwardB2C.cgst));
							$("#b2c_sgst_c").val(formatIndianNumber(outwardB2C.sgst));
							$("#b2c_cess_c").val(formatIndianNumber(outwardB2C.cess));

							$("#out_txval_c").val(formatIndianNumber(outward.txval));
							$("#out_igst_c").val(formatIndianNumber(outward.igst));
							$("#out_cgst_c").val(formatIndianNumber(outward.cgst));
							$("#out_sgst_c").val(formatIndianNumber(outward.sgst));
							$("#out_cess_c").val(formatIndianNumber(outward.cess));

							$("#itc_igst_c").val(formatIndianNumber(itc.igst));
							$("#itc_cgst_c").val(formatIndianNumber(itc.cgst));
							$("#itc_sgst_c").val(formatIndianNumber(itc.sgst));
							$("#itc_cess_c").val(formatIndianNumber(itc.cess));

							$("#other_itc_igst_c").val(formatIndianNumber(otherItc.igst));
							$("#other_itc_cgst_c").val(formatIndianNumber(otherItc.cgst));
							$("#other_itc_sgst_c").val(formatIndianNumber(otherItc.sgst));
							$("#other_itc_cess_c").val(formatIndianNumber(otherItc.cess));

							$("#tax_payable_c").val(formatIndianNumber(totalPayable));
							$("#tax_pay_itc_c").val(formatIndianNumber(totalPaidITC));
							$("#tax_pay_cash_c").val(formatIndianNumber(totalPaidCash));
							
							$("#a_txval_c").val(formatIndianNumber(sectionA.txval));
							$("#a_igst_c").val(formatIndianNumber(sectionA.igst));
							$("#a_cgst_c").val(formatIndianNumber(sectionA.cgst));
							$("#a_sgst_c").val(formatIndianNumber(sectionA.sgst));
							$("#a_cess_c").val(formatIndianNumber(sectionA.cess));

							$("#b_igst_c").val(formatIndianNumber(sectionB.igst));
							$("#b_cgst_c").val(formatIndianNumber(sectionB.cgst));
							$("#b_sgst_c").val(formatIndianNumber(sectionB.sgst));
							$("#b_cess_c").val(formatIndianNumber(sectionB.cess));

							$("#e_hsn_sc_c").val(sectionE.hsn_sc_arr[0] || "");
							$("#e_rt_c").val(sectionE.rt_arr[0] || "");
							$("#e_txval_c").val(formatIndianNumber(sectionE.txval));
							$("#e_igst_c").val(formatIndianNumber(sectionE.igst));
							$("#e_cgst_c").val(formatIndianNumber(sectionE.cgst));
							$("#e_sgst_c").val(formatIndianNumber(sectionE.sgst));
							$("#e_cess_c").val(formatIndianNumber(sectionE.cess));

							$("#f_hsn_sc_c").val(sectionF.hsn_sc_arr[0] || "");
							$("#f_rt_c").val(sectionF.rt_arr[0] || "");
							$("#f_txval_c").val(formatIndianNumber(sectionF.txval));
							$("#f_igst_c").val(formatIndianNumber(sectionF.igst));
							$("#f_cgst_c").val(formatIndianNumber(sectionF.cgst));
							$("#f_sgst_c").val(formatIndianNumber(sectionF.sgst));
							$("#f_cess_c").val(formatIndianNumber(sectionF.cess));

							

						}else{
							$(".messageContainer").html('<div class="err">' + response.error.message + "</div>");							
						}
							
					}else{
						$(".messageContainer").html('<div class="err">' + response.error.message + "</div>");
						$('.b2b_invoices').find('tbody').html('');
						$('.b2b_invoices').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						$('.b2c_large').find('tbody').html('');
						$('.b2c_large').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

						$('.b2c_small').find('tbody').html('');
						$('.b2c_small').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);
						$('.output_tax_liabilities').find('tbody').html('');
						$('.output_tax_liabilities').find('tbody').append(`<tr class="text-center"><td colspan="5">No record found</td></tr>`);

					}
				}
			},error: function(xhr) {
				$('#loader').hide();
			}
		});
	//end ajax call

}



// Submit GST Returns (Request EVC OTP)
function submit_GSTReturns() {
    const isNilReturn = $('#isNilReturn').is(':checked');
    const mainReportType = $('#mainReportType').val();
	var childReportType = $("#childReportType").children("option:selected").val();

	var gst_username = $("#gst_username").val();
	var financialYear = $("#financialYear").children("option:selected").val();
	var quarterSelect = $("#quarterSelect").children("option:selected").val();
	var periodSelect = $("#periodSelect").children("option:selected").val();
	if(gst_username ===""){
		alert("Please enter register username of https://www.gst.gov.in");
		location.reload();
		return;
	}
	if (!financialYear || financialYear === "") {
		alert("Please select a financial year");
		return;
	}
	if (!quarterSelect || quarterSelect === "") {
		alert("Please select a quarter");
		return;
	}
	if (!periodSelect || periodSelect === "") {
		alert("Please select a period");
		return;
	}


    // NIL: Check declaration
    if (isNilReturn) {
        if (!$('#nilDeclaration').is(':checked')) {
            alert('Please check the NIL declaration checkbox');
            return;
        }
    }

    // Set summary in modal
    const monthName = $('#periodSelect option:selected').text();
    const year = $('#financialYear').val().split('-')[0];
    $('#evcReturnType').text(mainReportType.toUpperCase() + (isNilReturn ? ' (NIL)' : ''));
    $('#evcPeriod').text(`${monthName} ${year}`);

    if (isNilReturn) {
        $('#evcDetails').html('<span class="badge bg-info">NIL Return - No transactions</span>');
    } else {
        $('#evcDetails').text('Data verified and ready to file');
    }

    // Show EVC OTP Modal
    //$('#evcOtpModal').modal('show');
	var uipayload = null;
	if(mainReportType == 'gstr9'){
		uipayload = gstr9payload();
	}else if(mainReportType == 'gstr9c'){
		uipayload = gstr9cpayload();
	}

	$('#loader').show();
	$.ajax({
		method: "POST",
		url: "/submit_GSTReturns",
		data: {
				financialYear:financialYear,
				quarterSelect:quarterSelect,
				periodSelect:periodSelect,
				mainReportType: mainReportType,
				childReportType: childReportType,
				isNilReturn: isNilReturn,
				uipayload: JSON.stringify(uipayload)
			  },
			success: function (response) {
				console.log(response);
				$('#loader').hide();
				$(".messageContainer").html('');
				if (response && response.status_cd==1)
				{
					//var succMsg = "Applied successfully";
					//$(".messageContainer").html('<div class="succ">' + succMsg + "</div>");
					$('#evcOtpModal').modal('show');
				}else{
					$(".messageContainer").html('<div class="err">' + response.error.message + "</div>");
				}
			},error: function(xhr) {
				$('#loader').hide();
			}
	});


}

// OTP Input Auto-Focus
$(document).on('input', '.otp-digit', function() {
    if (this.value.length === 1) {
        $(this).next('.otp-digit').focus();
    }
    let otp = '';
    $('.otp-digit').each(function() {
        otp += $(this).val();
    });
    $('#auth_otp').val(otp);
});

$(document).on('input', '.otp-digit-evc', function() {
    if (this.value.length === 1) {
        $(this).next('.otp-digit-evc').focus();
    }
    let otp = '';
    $('.otp-digit-evc').each(function() {
        otp += $(this).val();
    });
    $('#evc_otp').val(otp);
});

// Submit GST Username
function submitGstUsername(e) {
    e.preventDefault();
    const gst_username = $('#gst_username').val();

    if (!gst_username) {
        alert("Please enter GST username");
        return false;
    }

    $('#loader').show();
    $.ajax({
		headers: {
			  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		},
        url: "/gst/whitebookOtpRequest",
        type: "POST",
        data: { gst_username: gst_username },
        success: function(response) {
            $('#loader').hide();
            if (response.class == "succ") {
                $("#gstUserNameModal").modal("hide");
                $("#gstAuthenticateModal").modal("show");
            } else {
                $(".message-container").html('<div class="alert alert-danger">' + response.message + "</div>");
            }
        }
    });
    return false;
}

// Submit Auth OTP
function submitAuthOtp(e) {
    e.preventDefault();
    const gst_username = $("#gst_username").val();
    const otp = $("#auth_otp").val();

    if (otp.length !== 6) {
        alert("Please enter complete 6-digit OTP");
        return false;
    }

    $('#loader').show();
    $.ajax({
		headers: {
			  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
		},
        url: "/gst/whitebookAuthenticationRequest",
        type: "POST",
        data: { gst_username: gst_username, otp: otp },
        success: function(response) {
            $('#loader').hide();
            if (response.class == "succ") {
                $("#gstAuthenticateModal").modal("hide");
                alert('Authentication successful!');
				location.reload();
            } else {
                $(".message-container").html('<div class="alert alert-danger">' + response.message + "</div>");
            }
        }
    });
    return false;
}

// Submit EVC OTP (Final Filing)
function submitEvcOtp(e) {
    e.preventDefault();
    const evc_otp = $("#evc_otp").val();
    const isNilReturn = $('#isNilReturn').is(':checked');

    if (evc_otp.length !== 6) {
        alert("Please enter complete 6-digit EVC OTP");
        return false;
    }

    $('#loader').show();
    $.ajax({
        method: "POST",
        url: "/final_submit_GSTReturns",
        data: {
            financialYear: $('#financialYear').val(),
            quarterSelect: $('#quarterSelect').val(),
            periodSelect: $('#periodSelect').val(),
            mainReportType: $('#mainReportType').val(),
            childReportType: $('#childReportType').val(),
            isNilReturn: isNilReturn ? 'Y' : 'N',
            evc_otp: evc_otp
        },
        success: function (response) {
            $('#loader').hide();

            if (response && response.status_cd == 1) {
				$('#evcOtpModal').modal('hide');
                $('#successReturnType').text($('#mainReportType').val().toUpperCase() + ' Filed Successfully');
                $('#successArn').text(response?.data?.reference_id ?? response?.data?.ack_num ?? '');
                $('#successDate').text(new Date().toLocaleString('en-IN'));
                $('#successModal').modal('show');
            } else if (response && response.error.error_cd == 'RT-3BAS1079') {
				$('#outstandingModal').modal('show');
			} else {
                alert('Error: ' + (response.error?.message || 'Filing failed'));
            }
        },
        error: function(xhr) {
            $('#loader').hide();
            alert('Error filing return. Please try again.');
        }
    });
    return false;
}

 async function downloadReceipt() {
    //alert('Receipt download functionality to be implemented');
	const modalContent = document.querySelector("#successModal .modal-content");

    // Hide buttons temporarily (optional)
    const footer = modalContent.querySelector(".modal-footer");
    footer.style.display = "none";

    // Use html2canvas to capture modal
    const canvas = await html2canvas(modalContent, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    // Create PDF
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF("p", "mm", "a4");

    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

    pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
    pdf.save("Filing_Success_Receipt.pdf");

    // Restore footer buttons
    footer.style.display = "";
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateChildReports();
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
	const periodSection = document.getElementById('periodSelectionSection');
	const gstUserNameModal = document.getElementById('gstUserNameModal');

	if (!checkbox || !gstinSection || !checkbox.checked) {
		return;
	}

	gstinSection.style.display = 'flex';
	if (periodSection) {
		periodSection.style.display = 'flex';
	}

	if (gstUserNameModal) {
		$("#gstUserNameModal").modal('show');
	}
}

function mapGSTR9CData(data) {

	// Example mapping
	const rows = [
		{
			books: data.table10.total_turnover.txval,
			gstr: data.table5.total_tover.txval,
			diff: data.table10.total_turnover.txval - data.table5.total_tover.txval,
			remark: "Exempted supplies adjustment"
		},
		{
			books: data.table9.iamt.total_tax_paid,
			gstr: data.table9.camt.total_tax_paid,
			diff: data.table9.iamt.total_tax_paid - data.table9.camt.total_tax_paid,
			remark: "Rate difference adjustment"
		},
		{
			books: data.table6.itc_clmd.iamt,
			gstr: data.table6.itc_net.iamt,
			diff: data.table6.itc_clmd.iamt - data.table6.itc_net.iamt,
			remark: "Blocked credit reversal"
		},
		{
			books: data.table9.iamt.txpaid_cash,
			gstr: data.table9.camt.txpaid_cash,
			diff: data.table9.iamt.txpaid_cash - data.table9.camt.txpaid_cash,
			remark: "Reconciled & adjusted"
		}
	];

	const tbody = document.querySelector("#gstr9c_table tbody");
	tbody.innerHTML = ""; 

	rows.forEach((r, i) => {
		const tr = `
			<tr>
				<td><strong>${getTitle(i)}</strong></td>
				<td>₹${r.books.toLocaleString()}</td>
				<td>₹${r.gstr.toLocaleString()}</td>
				<td class="${r.diff < 0 ? 'text-danger' : 'text-success'}">₹${r.diff.toLocaleString()}</td>
				<td>${r.remark}</td>
			</tr>
		`;
		tbody.insertAdjacentHTML("beforeend", tr);
	});

	document.getElementById("gstr9c_table").style.display = "block";
}

function getTitle(i) {
	return [
		"Annual Turnover",
		"Tax Liability (Outward)",
		"ITC Claimed",
		"Net Tax Paid"
	][i];
}

function formatIndianNumber(x) {
    return x.toLocaleString('en-IN');
}

function get(obj, key) {
    return obj && obj[key] !== undefined ? obj[key] : 0;
}

function sumKeys(obj, keys) {
    return keys.reduce((t, k) => t + get(obj, k), 0);
}

function sumGstr9Table_10to13(table) {
    const total = { txval: 0, igst: 0, cgst: 0, sgst: 0, cess: 0 };

    if (!table || typeof table !== "object") return total;

    Object.keys(table).forEach(key => {

        //skip unwanted nodes
        if (key === "total_turnover" || key === "chksum") return;

        const row = table[key];
        if (typeof row !== "object") return;

        total.txval += Number(row.txval || 0);
        total.igst  += Number(row.iamt  || 0);
        total.cgst  += Number(row.camt  || 0);
        total.sgst  += Number(row.samt  || 0);
        total.cess  += Number(row.csamt || 0);
    });

    return total;
}


    function startGstReturnsTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'GST Returns Portal Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Monitor outward sales (GSTR-1), purchases (GSTR-2B), and monthly summaries (GSTR-3B).</p></div>'
                },
                {
                    title: 'GST Returns Portal',
                    intro: 'Monitor outward sales (GSTR-1), purchases (GSTR-2B), and monthly summaries (GSTR-3B).'
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
        $('#start-gst-returns-tour').on('click', function(e) {
            e.preventDefault();
            startGstReturnsTour();
        });
    });
</script>

@endsection
