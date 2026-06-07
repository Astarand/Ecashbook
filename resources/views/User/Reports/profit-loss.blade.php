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
                        <li class="breadcrumb-item active" aria-current="page">Profit & Loss Statement</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Profit & Loss Statement</h2>
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
                                Generate Profit & Loss Report
                            </h4>
                        </div>
                        <div class="card-body">
                           {{--<div class="row">
                                <div class="col-md-3">
                                    <label class="form-label" for="inputEmail4">Select Financial Year<span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option selected>Select Financial Year</option>
                                        <option value="1">2021-2022</option>
                                        <option value="2">2022-2023</option>
                                        <option value="3">2023-2024</option>
                                        <option value="4">2024-2025</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="reportType">Select Report Type<span class="text-danger">*</span></label>
                                    <select class="form-select" id="reportType" onchange="toggleQuarterSelect()">
                                    <option selected>Select Report Type</option>
                                    <option value="Yearly">Yearly</option>
                                    <option value="Quarterly">Quarterly</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="quarterSelect">Select Quarter<span class="text-danger">*</span></label>
                                    <select class="form-select" id="quarterSelect" disabled>
                                    <option selected>Select Quarter</option>
                                    <option value="1">Quarter 1 (January – March)</option>
                                    <option value="2">Quarter 2 (April–June)</option>
                                    <option value="3">Quarter 3 (July–September)</option>
                                    <option value="4">Quarter 4 (October–December)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label"></label>
                                    <a href="#" class="btn btn-primary w-100 mt-2" id="generate-profit-loss-data">Submit</a>
                                </div>
                            </div>--}}
                            <div class=" row">

															<div class="col-lg-4">
																	<!-- Financial Year Dropdown -->
																	<select class="form-control w-100 mt-3" id="financial-year">
																			<option selected disabled>Select Financial Year</option>
																	</select>
															</div>
															<div class="col-lg-4">
																	<!-- Period Type Dropdown -->
																	<select class="form-control w-100 mt-3 d-none" id="period-type">
																			<option selected disabled>Select Period Type</option>
																			<option value="monthly">Monthly</option>
																			<option value="quarterly">Quarterly</option>
																			<option value="half-yearly">Half-Yearly</option>
																			<option value="full-yearly">Full Yearly</option>
																	</select>
															</div>
															<div class="col-lg-4">
 																<!-- Dynamic Period Dropdown -->
                                <select class="form-control w-100 mt-3 d-none" id="dynamic-period"></select>
															</div>
                                <div class="col-lg-12">
 																	<!-- Generate Button -->
                                <button class="btn btn-primary w-100 mt-3" id="generate-profit-loss-data">Submit</button>
																</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="card">
                <div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered mb-0">
							<thead>
								<tr style="font-size:12px;">
									<th class="text-center">#</th>
									<th class="text-center" style="width: 50px;"></th>
									<th class="text-center" colspan="2">Particulars</th>
									<th class="text-center">Note No.</th>
									<th class="text-center">Figures as at the end of Current Reporting Period</th>
									<th class="text-center">Figures as at the end of Previous Reporting Period</th>
								</tr>
								<tr>
									<th colspan="5"></th>
									<th class="text-start"><strong>From Date: <span id="find_from_date"></span></strong></th>
									<th class="text-start"><strong>To Date: <span id="find_to_date"></span></strong></th>
								</tr>
								<tr>
									<th colspan="5"></th>
									<th class="text-start"><strong>Amount In: ₹<span id="curr_total_profit_loss">00.00</span></strong></th>
									<th class="text-start"><strong>Amount In: ₹<span id="prev_total_profit_loss">00.00</span></strong></th>
								</tr>
							</thead>
							<tbody>
								<!-- Revenue Section -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>A.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6;"><strong>REVENUE / INCOME</strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>1.</strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Revenue From Operations</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Sales of Products/Goods</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalReseller">00.00</td>
									<td class="text-start" id="prev_totalReseller"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start">Income from Services</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalService">00.00</td>
									<td class="text-start" id="prev_totalService"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start">Other Operating Revenues</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start">00.00</td>
									<td class="text-start">00.00</td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Other Income</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Interest Income</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalInterestIncome">00.00</td>
									<td class="text-start" id="prev_totalInterestIncome"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start">Dividend Income</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalDividendIncome">00.00</td>
									<td class="text-start" id="prev_totalDividendIncome"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start">Rental Income</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalRentalIncome">00.00</td>
									<td class="text-start" id="prev_totalRentalIncome"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">d.</td>
									<td class="text-start">Profit on Sale of Investments</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalProfitOnSale">00.00</td>
									<td class="text-start" id="prev_totalProfitOnSale"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">e.</td>
									<td class="text-start">Other Non-operating Income</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalOtherIncome">00.00</td>
									<td class="text-start" id="prev_totalOtherIncome"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>3.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL REVENUE (1 + 2)</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="curr_total_sales_income">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="prev_total_sales_income">00.00</span></strong></td>
								</tr>

								<!-- Expenses Section -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>B.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>EXPENSES</strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Cost of Materials Consumed</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;" id="curr_cost_of_mat_consumed"></td>
									<td style="border: 1px solid #ddd;" id="prev_cost_of_mat_consumed"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Purchase of Stock-in-Trade</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;" id="curr_stock_in_trade"></td>
									<td style="border: 1px solid #ddd;" id="prev_stock_in_trade"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Changes in Inventories of Finished Goods, Work-in-Progress, and Stock-in-Trade</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;" id="curr_changes_in_inventories"></td>
									<td style="border: 1px solid #ddd;" id="prev_changes_in_inventories"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Employee Benefits Expenses</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="curr_employee_benefits"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="prev_employee_benefits"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Salaries and Wages</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_salarieswages">0.00</td>
									<td class="text-start" id="prev_salarieswages"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start">Contributions to Provident and Other Funds</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_providfunds">0.00</td>
									<td class="text-start" id="prev_providfunds"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start">Other Funds</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_otherfund">0.00</td>
									<td class="text-start" id="prev_otherfund"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Finance Costs</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="curr_finance_costs"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="prev_finance_costs"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Interest Expenses</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_interestexpenss">0.00</td>
									<td class="text-start" id="prev_interestexpenss"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start"> Other Borrowing Costs</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_otherborrowing">0.00</td>
									<td class="text-start" id="prev_otherborrowing"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Depreciation and Amortization Expense</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="curr_amortization_expense"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="prev_amortization_expense"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Other Expenses</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="curr_other_exp"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="prev_other_exp"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start"> Administrative Expenses</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_administraexpense">0.00</td>
									<td class="text-start" id="prev_administraexpense"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start"> Selling and Distribution Expenses</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_sellingexpenses">0.00</td>
									<td class="text-start" id="prev_sellingexpenses"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start"> Rent</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_rent">0.00</td>
									<td class="text-start" id="prev_rent"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">d.</td>
									<td class="text-start"> Insurance</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_insurance">0.00</td>
									<td class="text-start" id="prev_insurance"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">e.</td>
									<td class="text-start" id=""> Repairs and Maintenance</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_repairsmaintenance">0.00</td>
									<td class="text-start" id="prev_repairsmaintenance"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">f.</td>
									<td class="text-start"> Legal and Professional Fees</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_legalfee">0.00</td>
									<td class="text-start" id="prev_legalfee"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>4.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL EXPENSES</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_total_expenses">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_total_expenses">00.00</span></strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>5.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Profit/(Loss) Before Exceptional and Extraordinary Items and Tax ( 3 - 4 )</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="curr_before_exceptional_extraordinary">0.00</span></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="prev_before_exceptional_extraordinary">0.00</span></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>6.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Exceptional Items (if any)</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="curr_exceptional_items"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="prev_exceptional_items"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>7.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Profit/(Loss) before Extraordinary Items and Tax ( 5 - 6 )</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="curr_before_extraordinary">0.00</span></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="prev_before_extraordinary">0.00</span></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>8.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Profit Before Extraordinary Items and Tax </strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="curr_profit_before_extraordinary_items_tax"></td>
									<td class="text-start" style="border: 1px solid #ddd;" id="prev_profit_before_extraordinary_items_tax"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start"> Extraordinary Items</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_extraordinary_items"></td>
									<td class="text-start" id="prev_extraordinary_items"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>9.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Profit Before Tax ( 5 - 8 ) </strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_profit_before_tax"></span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_profit_before_tax"></span></strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>10.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Tax Expense</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start"> Current Tax</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_current_tax"></td>
									<td class="text-start" id="prev_current_tax"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start"> Current Tax expenses relating to prior Years</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_current_tax_expenses_prior_years">0.00</td>
									<td class="text-start" id="prev_current_tax_expenses_prior_years"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start"> Deferred Tax</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_deferred_tax">0.00</td>
									<td class="text-start" id="prev_deferred_tax"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">d.</td>
									<td class="text-start"> Minimum Alternate Tax (MAT) Credit (if applicable)</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_minimum_alternate_tax">0.00</td>
									<td class="text-start" id="prev_minimum_alternate_tax"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>11.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Profit (Loss) for the Period from continuing opeartions ( 9-10) </strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_continuing_opeartions"></span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_continuing_opeartions"></span></strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>12.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Profit/(loss) from discontinued operations</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="curr_disc_ops"></span></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="prev_disc_ops"></span></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>13.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Tax expense of discontinued operations</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="curr_tax_exp_disc_ops"></span></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="prev_tax_exp_disc_ops"></span></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>14.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Profit/(loss) from Discontinued operations (after tax) (12-13) </strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="curr_after_tax_disc_ops"></span></td>
									<td class="text-start" style="border: 1px solid #ddd;"><span id="prev_after_tax_disc_ops"></span></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>15.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Profit/(loss) for the period (11 - 14) </strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_profit_loss_period"></span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_profit_loss_period"></span></strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>16.</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td colspan="2" class="text-start" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Earnings per equity share</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
									<td class="text-start" style="border: 1px solid #ddd;"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start"> Basic</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_basic">0.00</td>
									<td class="text-start" id="prev_basic">0.00</td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start"> Diluted</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_diluted">0.00</td>
									<td class="text-start" id="prev_diluted"></td>
								</tr>
							</tbody>
						</table>
					</div>
                    <div class="col-md-12 text-end mt-4">
                        <button type="button" id="" onclick="printPLReport()"   class="btn btn-secondary me-2">Print</button>
                        <a href="javascript:void(0);" onclick="downloadPLPdf()" class="btn btn-primary">Download</a>
                    </div>
                </div>
            </div>
         </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<script>
  function toggleQuarterSelect() {
	const reportType = document.getElementById('reportType').value;
	const quarterSelect = document.getElementById('quarterSelect');
	if (reportType === 'Quarterly') {
	  quarterSelect.disabled = false; // Enable the Quarter select
	} else {
	  quarterSelect.disabled = true;  // Disable the Quarter select
	  quarterSelect.selectedIndex = 0; // Reset the selection
	}
  }

	function printPLReport() {
		let printContents = document.querySelector('.table-responsive').innerHTML;

		let originalContents = document.body.innerHTML;

		document.body.innerHTML = `
			<h3 style="text-align:center;">Bank Reconciliation Report</h3>
			${printContents}
		`;

		window.print();
		document.body.innerHTML = originalContents;
		location.reload();
	}
	
	function downloadPLPdf() {
		let tableHtml = document.querySelector('.table-responsive').innerHTML;
		$.ajax({
			url: "{{ route('plsheet.download.pdf') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				html: tableHtml
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function (response) {
				let blob = new Blob([response], { type: "application/pdf" });
				let link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = "Profit_Loss_Sheet.pdf";
				link.click();
			}
		});
	}
		function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            return date.toLocaleDateString('en-GB', options); // You can adjust the locale and options as needed.
        }
		
		function money(val) {
			val = parseFloat(val || 0);
			return val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}

		function setText(id, value) {
			$('#' + id).text(money(value));
		}
		
		function calculateRevenueTotal(revenue) {
			return (
				Number(revenue.totalReseller || 0) +
				Number(revenue.totalService || 0) +
				Number(revenue.totalInterestIncome || 0) +
				Number(revenue.totalDividendIncome || 0) +
				Number(revenue.totalRentalIncome || 0) +
				Number(revenue.totalProfitOnSale || 0) +
				Number(revenue.totalOtherIncome || 0)
			);
		}
		
		function calculateExpenseTotal(exp) {
			return (
				Number(exp.cost_of_mat_consumed || 0) +
				Number(exp.stock_in_trade || 0) +
				Number(exp.changes_in_inventories || 0) +
				Number(exp.employee_benefits || 0) +
				Number(exp.salarieswages || 0) +
				Number(exp.providfunds || 0) +
				Number(exp.otherfund || 0) +
				Number(exp.interestexpenss || 0) +
				Number(exp.otherborrowing || 0) +
				Number(exp.amortization_expense || 0) +
				Number(exp.other_exp || 0) +
				Number(exp.administraexpense || 0) +
				Number(exp.sellingexpenses || 0) +
				Number(exp.rent || 0) +
				Number(exp.insurance || 0) +
				Number(exp.repairsmaintenance || 0) +
				Number(exp.legalfee || 0)
			);
		}
		
		function calculateTotalTax(tax) {
			return (
				Number(tax.current_tax || 0) +
				Number(tax.current_tax_expenses_prior_years || 0) +
				Number(tax.deferred_tax || 0) +
				Number(tax.minimum_alternate_tax || 0)
			);
		}


		$(document).ready(function () {
            $('#generate-profit-loss-data').on('click', function () {
                const financialYear = $('#financial-year').val();
                const periodType = $('#period-type').val();
                const dynamicPeriod = $('#dynamic-period').val();

                if (!financialYear) {
                    alert('Please select a financial year.');
                    return;
                }

                if (!periodType) {
                    alert('Please select a period type.');
                    return;
                }

                if (periodType !== 'full-yearly' && !dynamicPeriod) {
                    alert('Please select a period.');
                    return;
                }
				$("#loader").show();
                $.ajax({
                    url: '/fetch-profit-loss-data',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        financial_year: financialYear,
                        period_type: periodType,
                        dynamic_period: dynamicPeriod
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
						$("#loader").hide();
                        if (data.success) {
                            $('#find_to_date').text(formatDate(data.end_date));
                            $('#find_from_date').text(formatDate(data.start_date));
							const curr = data.currentYearData;
							const prev = data.previousYearData;

							/* ======================
							   CURRENT YEAR – REVENUE
							====================== */
							setText('curr_totalReseller', curr.revenue.totalReseller);
							setText('curr_totalService', curr.revenue.totalService);
							setText('curr_totalInterestIncome', curr.revenue.totalInterestIncome);
							setText('curr_totalDividendIncome', curr.revenue.totalDividendIncome);
							setText('curr_totalRentalIncome', curr.revenue.totalRentalIncome);
							setText('curr_totalProfitOnSale', curr.revenue.totalProfitOnSale);
							setText('curr_totalOtherIncome', curr.revenue.totalOtherIncome);
							setText('curr_total_sales_income', curr.revenue.total_sales_income);
							
							/* ======================
							   CURRENT YEAR – EXPENSES
							====================== */
							setText('curr_cost_of_mat_consumed', curr.expenses.cost_of_mat_consumed);
							setText('curr_stock_in_trade', curr.expenses.stock_in_trade);
							setText('curr_changes_in_inventories', curr.expenses.changes_in_inventories);
							setText('curr_employee_benefits', curr.expenses.employee_benefits);
							setText('curr_salarieswages', curr.expenses.salarieswages);
							setText('curr_providfunds', curr.expenses.providfunds);
							setText('curr_otherfund', curr.expenses.otherfund);
							setText('curr_interestexpenss', curr.expenses.interestexpenss);
							setText('curr_otherborrowing', curr.expenses.otherborrowing);
							setText('curr_amortization_expense', curr.expenses.amortization_expense);
							setText('curr_other_exp', curr.expenses.other_exp);
							setText('curr_administraexpense', curr.expenses.administraexpense);
							setText('curr_sellingexpenses', curr.expenses.sellingexpenses);
							setText('curr_rent', curr.expenses.rent);
							setText('curr_insurance', curr.expenses.insurance);
							setText('curr_repairsmaintenance', curr.expenses.repairsmaintenance);
							setText('curr_legalfee', curr.expenses.legalfee);
							const currTotalExpense = calculateExpenseTotal(curr.expenses);
							setText('curr_total_expenses', currTotalExpense);
							
							const currBeforeExceptionalExtraordinary = calculateRevenueTotal(curr.revenue) - calculateExpenseTotal(curr.expenses);
							setText('curr_before_exceptional_extraordinary', currBeforeExceptionalExtraordinary);
							setText('curr_exceptional_items', curr.exp_items.exceptional_items);
							
							setText('curr_before_extraordinary', currBeforeExceptionalExtraordinary + curr.exp_items.exceptional_items);
							setText('curr_extraordinary_items', curr.exp_items.extraordinary_items);
							
							const currTotalProfitBeforeTax = (calculateRevenueTotal(curr.revenue) + calculateExpenseTotal(curr.expenses) - curr.exp_items.exceptional_items - curr.exp_items.extraordinary_items);							
							setText('curr_profit_before_tax', currTotalProfitBeforeTax);
							setText('curr_current_tax', curr.tax.current_tax);
							setText('curr_current_tax_expenses_prior_years', curr.tax.current_tax_expenses_prior_years);
							setText('curr_deferred_tax', curr.tax.deferred_tax);
							setText('curr_minimum_alternate_tax', curr.tax.minimum_alternate_tax);
							
							const currTotalProfitLoss = currTotalProfitBeforeTax - calculateTotalTax(curr.tax);
							setText('curr_continuing_opeartions', currTotalProfitLoss);
							
							setText('curr_disc_ops',0);
							setText('curr_tax_exp_disc_ops',0);
							setText('curr_tax_exp_disc_ops',0);
							setText('curr_after_tax_disc_ops',0);
							const currProfitLossForPeriod = (currTotalProfitLoss - 0);
							setText('curr_profit_loss_period',currProfitLossForPeriod);
							setText('curr_basic', curr.eps.basic_eps);
							setText('curr_diluted', curr.eps.diluted_eps);
							
							setText('curr_total_profit_loss', currProfitLossForPeriod);
							
							
							/* ======================
							   PREVIOUS YEAR – REVENUE
							====================== */
							setText('prev_totalReseller', prev.revenue.totalReseller);
							setText('prev_totalService', prev.revenue.totalService);
							setText('prev_totalInterestIncome', prev.revenue.totalInterestIncome);
							setText('prev_totalDividendIncome', prev.revenue.totalDividendIncome);
							setText('prev_totalRentalIncome', prev.revenue.totalRentalIncome);
							setText('prev_totalProfitOnSale', prev.revenue.totalProfitOnSale);
							setText('prev_totalOtherIncome', prev.revenue.totalOtherIncome);
							setText('prev_total_sales_income', prev.revenue.total_sales_income);
							

							/* ======================
							   PREVIOUS YEAR – EXPENSES
							====================== */
							setText('prev_cost_of_mat_consumed', prev.expenses.cost_of_mat_consumed);
							setText('prev_stock_in_trade', prev.expenses.stock_in_trade);
							setText('prev_changes_in_inventories', prev.expenses.changes_in_inventories);
							setText('prev_employee_benefits', prev.expenses.employee_benefits);
							setText('prev_salarieswages', prev.expenses.salarieswages);
							setText('prev_providfunds', prev.expenses.providfunds);
							setText('prev_otherfund', prev.expenses.otherfund);
							setText('prev_interestexpenss', prev.expenses.interestexpenss);
							setText('prev_otherborrowing', prev.expenses.otherborrowing);
							setText('prev_amortization_expense', prev.expenses.amortization_expense);
							setText('prev_other_exp', prev.expenses.other_exp);
							setText('prev_administraexpense', prev.expenses.administraexpense);
							setText('prev_sellingexpenses', prev.expenses.sellingexpenses);
							setText('prev_rent', prev.expenses.rent);
							setText('prev_insurance', prev.expenses.insurance);
							setText('prev_repairsmaintenance', prev.expenses.repairsmaintenance);
							setText('prev_legalfee', prev.expenses.legalfee);
							const prevTotalExpense = calculateExpenseTotal(prev.expenses);
							setText('prev_total_expenses', prevTotalExpense);
							
							const prevBeforeExceptionalExtraordinary = calculateRevenueTotal(prev.revenue) - calculateExpenseTotal(prev.expenses);
							setText('prev_before_exceptional_extraordinary', prevBeforeExceptionalExtraordinary);
							setText('prev_exceptional_items', prev.exp_items.exceptional_items);
							
							setText('prev_before_extraordinary', prevBeforeExceptionalExtraordinary + prev.exp_items.exceptional_items);
							setText('prev_extraordinary_items', prev.exp_items.extraordinary_items);
							

							const prevTotalProfitBeforeTax = (calculateRevenueTotal(prev.revenue) + calculateExpenseTotal(prev.expenses) - prev.exp_items.exceptional_items - prev.exp_items.extraordinary_items);
							setText('prev_profit_before_tax', prevTotalProfitBeforeTax);
							setText('prev_current_tax', prev.tax.current_tax);
							setText('prev_current_tax_expenses_prior_years', prev.tax.current_tax_expenses_prior_years);
							setText('prev_deferred_tax', prev.tax.deferred_tax);
							setText('prev_minimum_alternate_tax', prev.tax.minimum_alternate_tax);
                            
							const prevTotalProfitLoss = prevTotalProfitBeforeTax - calculateTotalTax(prev.tax);
							setText('prev_continuing_opeartions', prevTotalProfitLoss);
							
							setText('prev_disc_ops',0);
							setText('prev_tax_exp_disc_ops',0);
							setText('prev_tax_exp_disc_ops',0);
							setText('prev_after_tax_disc_ops',0);
							const prevProfitLossForPeriod = (prevTotalProfitLoss - 0);
							setText('prev_profit_loss_period',prevProfitLossForPeriod);
							setText('prev_basic', prev.eps.basic_eps);
							setText('prev_diluted', prev.eps.diluted_eps);

							setText('prev_total_profit_loss', prevProfitLossForPeriod);
                        } else {
                            $('.income-values').text("00.00");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
        });

        //------------ Year select ------------

        document.addEventListener('DOMContentLoaded', function () {
            const financialYearDropdown = document.getElementById('financial-year');
            const periodTypeDropdown = document.getElementById('period-type');
            const dynamicPeriodDropdown = document.getElementById('dynamic-period');
            const generateButton = document.getElementById('generate-balance-sheet');

            // Populate Financial Year Dropdown
            const currentYear = new Date().getFullYear();
            const financialYears = [
                `${currentYear - 1}-${currentYear}`,
                `${currentYear}-${currentYear + 1}`,
            ];
            financialYears.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = `FY ${year}`;
                financialYearDropdown.appendChild(option);
            });

            // Show "Period Type" Dropdown when Financial Year is Selected
            financialYearDropdown.addEventListener('change', function () {
                periodTypeDropdown.classList.remove('d-none'); // Show Period Type Dropdown
                dynamicPeriodDropdown.classList.add('d-none'); // Reset and hide Dynamic Period Dropdown
            });

            // Handle Period Type Selection
            periodTypeDropdown.addEventListener('change', function () {
                const selectedType = this.value;

                // Reset and hide Dynamic Period Dropdown
                dynamicPeriodDropdown.innerHTML = '';
                dynamicPeriodDropdown.classList.add('d-none');

                if (selectedType === 'monthly') {
                    // Populate Monthly Periods
                    const months = [
                        'April', 'May', 'June', 'July', 'August', 'September',
                        'October', 'November', 'December', 'January', 'February', 'March'
                    ];
                    months.forEach(month => {
                        const option = document.createElement('option');
                        option.value = month.toLowerCase();
                        option.textContent = month;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    dynamicPeriodDropdown.classList.remove('d-none');
                } else if (selectedType === 'quarterly') {
                    // Populate Quarterly Periods
                    const quarters = [
                        'April-June', 'July-September', 'October-December', 'January-March'
                    ];
                    quarters.forEach(quarter => {
                        const option = document.createElement('option');
                        option.value = quarter.toLowerCase().replace(/\s/g, '-');
                        option.textContent = quarter;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    dynamicPeriodDropdown.classList.remove('d-none');
                } else if (selectedType === 'half-yearly') {
                    // Populate Half-Yearly Periods
                    const halfYearly = ['April-September', 'October-March'];
                    halfYearly.forEach(period => {
                        const option = document.createElement('option');
                        option.value = period.toLowerCase().replace(/\s/g, '-');
                        option.textContent = period;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    dynamicPeriodDropdown.classList.remove('d-none');
                } else if (selectedType === 'full-yearly') {
                    // Hide Dynamic Dropdown for Full Yearly
                    dynamicPeriodDropdown.classList.add('d-none');
                }
            });

            // Ensure Generate Button is Always Visible
            generateButton.classList.remove('d-none');
        });
</script>

@endsection
