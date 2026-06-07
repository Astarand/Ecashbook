	<nav class="pc-sidebar">
		<div class="navbar-wrapper">
			<div class="m-header">
				<a href="{{ route('index') }}" class="b-brand text-primary text-center">
					<!-- ========   Change your logo from here   ============ -->
					<img src="{{ asset('assets/images/logo-small.png') }}" alt="logo image" class="logo-lg text-center" />
				</a>
			</div>
			<?php //echo "<pre>";print_r(Auth::user()->emp_permission);exit;?>
			@php
				$permissions = [];
				if (Auth::check() && Auth::user()->emp_permission) {
					$permissions = explode(',', Auth::user()->emp_permission);
				}
				$userMenu = $userFeatures;
				//echo "<pre>";print_r($userMenu);exit;
				
				function canAccess($key, $userMenu, $permissions) 
				{
					// Must be in subscription
					if (!in_array('ALL', $userMenu) && !in_array($key, $userMenu)) {
						return false;
					}

					// Must be in employee permission
					if (!in_array('ALL', $permissions) && !in_array($key, $permissions)) {
						return false;
					}

					return true;
				}
			@endphp

			<div class="navbar-content">

				<!-- UserPanel Side bar -->
				@if (Auth::user()->u_type == 2)
					<ul class="pc-navbar">
						@if(in_array('ALL', $userMenu) || in_array('Dashboard', $userMenu))
						<li class="pc-item">
							<a href="{{ route('index') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-gauge"></i>
								</span>
								<span class="pc-mtext">Dashboard & Overview </span>
							</a>
						</li>
						@endif
				
						<li class="pc-item">
							<a href="{{ route('user.CompanyProfile') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-switch"></i>
								</span>
								<span class="pc-mtext">Organization Profile</span>
							</a>
						</li>
						@if(in_array('ALL', $userMenu) || in_array('Multi Proprietorships', $userMenu))
						<li class="pc-item">
							<a href="{{ route('proprietorship.list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-warehouse"></i>
								</span>
								<span class="pc-mtext">Proprietorship Profile</span>
							</a>
						</li>
						@endif
						@if(in_array('ALL', $userMenu) || in_array('Secure Doc Locker', $userMenu))
						<li class="pc-item">
							<a href="{{ route('user.DocLocker') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-folder-lock"></i>
								</span>
								<span class="pc-mtext">Secure Doc Locker</span>
							</a>
						</li>
						@endif
						<li class="pc-item">
							<a href="{{ route('user.Plans') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-switch"></i>
								</span>
								<span class="pc-mtext">Subscription & Billing</span>
							</a>
						</li>
						@if (in_array('ALL', $userMenu) || in_array('Assign CA Firm', $userMenu))
						<li class="pc-item">
							<a href="{{ route('user.AssignCa') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-circle-plus"></i>
								</span>
								<span class="pc-mtext">Connect Your CA/Accountant</span>
							</a>
						</li>
						@endif
						@if (in_array('ALL', $userMenu) || in_array('Accounting', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-arrows-down-up"></i> </span><span class="pc-mtext">Accounting &
									Finance </span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Business Operations</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.CustomerList') }}">Customers & Receivables</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.VendorList') }}">Vendors
												& Payables</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.ProductServiceList') }}">Products & Services</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('ca.AgentList') }}">Agent & Channel Partner</a>
										</li>
									</ul>
								</li>
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Sales & Revenue</span> <span class="pc-arrow"><i
												data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.SalesQuotation') }}">Quotation</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.ProformaInvoice') }}">Proforma Invoice</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.SalesInvoices') }}">Sales Invoice</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.SalesCreditDebit') }}">Credit & Debit Note</a></li>
									</ul>
								</li>
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Purchase & Procurement</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.PurchaseOrder') }}">Purchase Order (PO)</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.PurchaseInvoices') }}">Purchase Invoice</a></li>									
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.PurchaseCreditDebit') }}">Credit & Debit Note</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ url('/tds/vendor-purchase') }}" class="nav-link">Vendor Report</a></li>
									</ul>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.OtherIncomeList') }}">Income &
										other Revenue</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.ExpensesList') }}">Expense
										Management</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.CustomInvoiceList') }}">Custom Invoice</a></li>
							</ul>
						</li>
						@endif
						@if (in_array('ALL', $userMenu) || in_array('Assets & Liabilities', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-buildings"></i></span><span class="pc-mtext">Assets &
									Liabilities</span><span class="pc-arrow"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Asset Management</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.AssetList') }}">Asset Details</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.AssetVoucherList') }}">Asset Vouchers</a></li>
									</ul>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.Liabilites') }}">Liabilities & Borrowings</a></li>
							</ul>
						</li>
						@endif

						@if(in_array('ALL', $userMenu) || in_array('Cash & Banking', $userMenu))
						<li class="pc-item pc-hasmenu">							
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-wallet"></i></span><span class="pc-mtext">Cash
									& Banking</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.PaymentVoucherList') }}">Payment / Receipt Vouchers</a>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.BankList') }}">Bank Account
										Master</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.CashManagement') }}">Cash
										Management</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.BankReconciliation') }}">Bank Reconciliation</a></li>
							{{--	<li class="pc-item"><a class="pc-link" href="{{ route('user.LoanList') }}">Loan Register
										& Master</a></li> --}}
							</ul>
						</li>
						@endif

						@if(in_array('ALL', $userMenu) || in_array('Financial Reports', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon"><i data-feather="bar-chart-2"></i></span>
								<span class="pc-mtext">Financial Reports</span>
								<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
							</a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.Ledger') }}">Accounts & Ledger</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.JournalList') }}">Journal Entry</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.TrialBalanceReport') }}">Trial
										Balance (TB)</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.ProfitLossReport') }}">Profit &
										Loss Statement</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.BalanceSheetReport') }}">Balance Sheet</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.cashflow') }}">Cash Flow Statement</a></li>                           
							</ul>
						</li>
						@endif

						@if(in_array('ALL', $userMenu) || in_array('Inventory Management', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-lockers"></i></span><span class="pc-mtext">Inventory
									Management</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.Inventory') }}">Inventory
										Management</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.expenses_inventory') }}">Inventory Expenses</a></li>
							</ul>
						</li>
						@endif

						@if(in_array('ALL', $userMenu) || in_array('Tax Filing & Returns', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-files"></i></span><span class="pc-mtext">Tax
									Filing & Returns</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>GST Management & Returns</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTProfile') }}">GST
												Profile & Registration</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('User.OtherGSTProfile') }}">GSTIN Verification</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReturns') }}">GST
												Returns & Filing</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReports') }}">GST
												Insights & Reports</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTComplianceSupport')}}">GST Compliance Support</a>
										</li>
									</ul>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.tds_returns_filing') }}">TDS Management & Returns</a></li>
											{{-- <li class="pc-item pc-hasmenu">
							  <a href="#!" class="pc-link"><span>TDS Management</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							  <ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route ('user.tds_returns_filing') }}">TDS Returns & Filing</a></li>
							  </ul>
							</li> --}}
								<li class="pc-item"><a class="pc-link" href="{{ route('user.ptax_management_list') }}">Professional Tax Payment</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.pf_management_list') }}">Provident Fund (PF/EPF)</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.esi_management_list') }}">Employees' State Insurance
										(ESI)</a>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ url('/msme/list') }}">MSME Compliance </a></li>
								 <li class="pc-item"><a class="pc-link" href="{{ url('/mca-roc/list') }}">MCA/ROC Filings </a></li>
								<li class="pc-item"><a class="pc-link" href="{{ url('/itr/list') }}">Income Tax Return Filing</a></li>                            

							</ul>
						</li>
						@endif

						
						@if(in_array('ALL', $userMenu) || in_array('Statutory Compliance Status', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
							<span class="pc-micon"><i class="ph-duotone ph-chat-circle-text"></i></span>
							<span class="pc-mtext">Statutory Compliance</span>
							<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.compliance-reminder-list') }}">Compliance Calendar</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('ca.CompliancesList') }}">My Compliance Dashboard</a></li>
							</ul>
						</li>
						@endif

						@if(in_array('ALL', $userMenu) || in_array('HR & Payroll Management', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-user-list"></i></span><span class="pc-mtext">HR & Payroll
									Management</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>HR, Payroll & Attendance</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.EmployeeList') }}">Employee Master </a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.AttendanceList') }}">Attendence History</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.GeneratePayslip') }}">Payslip Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
										{{-- <li class="pc-item"><a class="pc-link" href="#">Holiday Calendars </a></li> --}}
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.HRLetterList') }}">HR
												Letter & Docs</a></li>
										{{--<li class="pc-item"><a class="pc-link" href="#">Performance & Reviews</a>--}}
										</li>
										{{-- <li class="pc-item"><a class="pc-link" href="{{ route('user.performace-review',$encodedId) }}">Performance & Reviews</a></li> --}}
										<li class="pc-item"><a class="pc-link" href="{{ route('user.ResignEmployee') }}">Resign Employees</a></li>
									</ul>

								<li class="pc-item">
									<a href="{{ route('user.TaskManagement') }}" class="pc-link">
										<span class="pc-mtext">Employee Task Management</span>
									</a>
								</li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.ExpenditureClaims') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.SupplyRequisitions') }}">Purchase Requisitions</a></li>

							</li>

						</ul>
					</li>
					@endif

					@if(in_array('ALL', $userMenu) || in_array('Business Health Check-up', $userMenu))
					<li class="pc-item">
						<a href="{{ route('admin.company.checks') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-activity"></i>
							</span>
							<span class="pc-mtext">Business Health Check-up</span>
						</a>
					</li>
					@endif
					
					@if(in_array('ALL', $userMenu) || in_array('Audit & Log Management', $userMenu))
					<li class="pc-item">
						<a href="{{ route('user.audit.logs') }}" class="pc-link">
							<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
							<span class="pc-mtext">Audit & Log Management</span>
						</a>
					</li>
					@endif

					@if(in_array('ALL', $userMenu) || in_array('Startup Incubator Services', $userMenu))
					<li class="pc-item">
						<a href="{{ url('/startup-filing/list') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-briefcase"></i>
							</span>
							<span class="pc-mtext">Startup Incubator Services</span>
						</a>
					</li>
					@endif

					@if(in_array('ALL', $userMenu) || in_array('Professional Services', $userMenu))
					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link"><span class="pc-micon"><i
									class="ph-duotone ph-detective"></i></span><span class="pc-mtext">Compliance advisory</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Company Incorporation</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Udyam Registration (MSME)</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">GST Registration & Returns</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Licensing & Compliance</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Tax Audit & ROC Filing</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Accounting Outsourcing</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Financial Planning & Analysis</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Legal Advisory Support</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Digital Security Services</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">Advisory & Consulting</a></li>
							<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">MSME Schemes & Loans Assistance</a>
							</li>
						</ul>
					</li>
					@endif

					@if(in_array('ALL', $userMenu) || in_array('Technology Services', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-wrench"></i></span><span class="pc-mtext">Digital Business Solutions</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								{{-- <li class="pc-item"><a class="pc-link" href="{{ route('company-branding') }}" target="_blank">Company Branding</a></li> --}}
								<li class="pc-item"><a class="pc-link" href="{{ route('web-and-app') }}"
										target="_blank">Website & App Development</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('ui-and-ux') }}"
										target="_blank">Professional UI/UX Design</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('crm-erp-implementation') }}"
										target="_blank">CRM/ERP Implementation</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('pwa') }}"
										target="_blank">Progressive Web Applications</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('seo') }}" target="_blank">SEO &
										Search Optimization</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('smm') }}" target="_blank">Digital
										Marketing Strategy</a></li>
							</ul>
						</li>
					@endif	

					</ul>
				@endif

				<!-- CA Side bar -->
				@if (Auth::user()->u_type == 1 && Auth::user()->isCaActive == 1)
					<ul class="pc-navbar">
						<li class="pc-item">
							<a href="{{ route('index') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-gauge"></i>
								</span>
								<span class="pc-mtext">Dashboard</span>
							</a>
						</li>

						<li class="pc-item">
							<a href="{{ route('CA.FirmInformation') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-duotone ph-bank"></i>
								</span>
								<span class="pc-mtext">Firm Profile</span>
							</a>
						</li>
						
						<li class="pc-item">
							<a href="{{ route('user.DocLocker') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-folder-lock"></i>
								</span>
								<span class="pc-mtext">Doc Locker Permission</span>
							</a>
						</li>

						<li class="pc-item">
							<a href="{{ route('Ca.CompanyList') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-garage"></i>
								</span>
								<span class="pc-mtext">Client Management </span>
							</a>
						</li>

						<li class="pc-item">
							<a href="{{ route('CA.CompanyAssignment') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-users"></i>
								</span>
								<span class="pc-mtext">Company Assignment</span>
							</a>
						</li>

						<li class="pc-item">
							<a href="{{ route('ca.CompliancesList') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-switch"></i>
								</span>
								<span class="pc-mtext">Compliances Management </span>
							</a>
						</li>

						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-file-plus"></i></span><span class="pc-mtext">Task
									Management</span><span class="pc-arrow"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('ca.TaskList') }}">Task List</a>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('ca.QuoteList') }}">Task wise
										Quotes</a></li>
							</ul>
						</li>

						<li class="pc-item">
							<a href="{{ route('ca.EarningDetails') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-currency-circle-dollar"></i>
								</span>
								<span class="pc-mtext">Earning Management</span>
							</a>
						</li>

						<li class="pc-item">
							<a href="{{ route('ca.PaymentHistory') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ti ti-currency-rupee"></i>
								</span>
								<span class="pc-mtext">Billing & Payments </span>
							</a>
						</li>

						{{-- <li class="pc-item pc-hasmenu">
			  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-person-arms-spread"></i></span><span class="pc-mtext">HR & Payroll Management </span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
			  <ul class="pc-submenu">
				<li class="pc-item"><a class="pc-link" href="{{ route('CA.EmployeeList') }}">Employee List</a></li>
				<li class="pc-item"><a class="pc-link" href="{{ route('CA.AttendanceList') }}">Attendence List</a></li>
				<li class="pc-item"><a class="pc-link" href="{{ route('CA.GeneratePayslip') }}">Generate Payslip</a></li>
			  </ul>
			</li> --}}

						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-user-list"></i></span><span class="pc-mtext">HR & Payroll
									Management</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>HR, Payroll & Attendance</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('CA.EmployeeList') }}">Employee Master </a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('CA.AttendanceList') }}">Attendence History</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('CA.GeneratePayslip') }}">Payslip Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
										<li class="pc-item"><a class="pc-link" href="#">Holiday Calendars </a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.HRLetterList') }}">HR Letter & Docs</a></li>
										<li class="pc-item"><a class="pc-link" href="#">Performance & Reviews</a>
										</li>
										{{-- <li class="pc-item"><a class="pc-link" href="{{ route('user.performace-review',$encodedId) }}">Performance & Reviews</a></li> --}}
									</ul>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.ExpenditureClaims') }}">Expense Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.SupplyRequisitions') }}">Purchase Requisitions</a></li>

							</li>

						</ul>
					</li>

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link"><span class="pc-micon"><i
									class="ph-duotone ph-files"></i></span><span class="pc-mtext">Tax
								Filing & Returns</span><span class="pc-arrow mt-1"><i
									data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="{{ route('user.tds_returns_filing') }}">TDS management </a></li>
							<li class="pc-item"><a class="pc-link" href="{{ route('user.pf_management_list') }}">EPF Management</a></li>
							<li class="pc-item"><a class="pc-link" href="{{ route('user.esi_management_list') }}">ESIC Management</a></li>
							<li class="pc-item"><a class="pc-link" href="{{ route('user.ptax_management_list') }}">P-Tax Management</a></li>
						</ul>
					</li>

					<li class="pc-item">
						<a href="{{ route('CA.Reminder') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-user-plus"></i>
							</span>
							<span class="pc-mtext">Notifications & Communication </span>
						</a>
					</li>

					<li class="pc-item">
						<a href="{{ route('ca.AgentList') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-user-list"></i>
							</span>
							<span class="pc-mtext">Agent & Channel Partner </span>
						</a>
					</li>

					<li class="pc-item">
						<a href="{{ route('user.CustomInvoiceList') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-file-text"></i>
							</span>
							<span class="pc-mtext">Custom Invoice</span>
						</a>
					</li>

					<li class="pc-item">
						<a href="#" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-chart-line"></i>
							</span>
							<span class="pc-mtext">Reporting & Analytics</span>
						</a>
					</li>
					
					<li class="pc-item">
						<a href="{{ route('user.audit.logs') }}" class="pc-link">
							<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
							<span class="pc-mtext">Audit & Log Management</span>
						</a>
					</li>

					</ul>
				@endif

				<!-- Admin Side bar -->
				@if (Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
					<ul class="pc-navbar">

						{{-- Dashboard --}}
						<li class="pc-item">
							<a href="{{ url('/') }}" class="pc-link">
								<span class="pc-micon"><i class="ph-duotone ph-gauge"></i></span>
								<span class="pc-mtext">Dashboard & Analytics</span>
							</a>
						</li>

						{{-- Admin Profile --}}
						@if (in_array('adminProfile', $permissions))
							<li class="pc-item">
								<a href="{{ route('admin.AdminProfile') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-user-circle"></i></span>
									<span class="pc-mtext">Admin Profile</span>
								</a>
							</li>
						@endif

						{{-- Subscription Management --}}
						@if (in_array('adminSubscription', $permissions))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-credit-card"></i></span>
									<span class="pc-mtext">Subscription Management</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link"
											href="{{ route('admin.subscription-list') }}">Subscription List</a></li>
									<li class="pc-item"><a class="pc-link"
											href="{{ route('admin.coupon-codes') }}">Coupon Code</a></li>
									<li class="pc-item"><a class="pc-link"
											href="{{ route('admin.subscription-customer-list') }}">Customer List</a></li>
								</ul>
							</li>
						@endif

						{{-- User Management --}}
						@if (in_array('adminCustomer', $permissions))
							<li class="pc-item">
								<a href="{{ route('admin.admin-customer-list') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-users-three"></i></span>
									<span class="pc-mtext">User Management</span>
								</a>
							</li>
						@endif

						{{-- CA Management --}}
						@if (in_array('adminCa', $permissions))
							<li class="pc-item">
								<a href="{{ route('admin.ca-list') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-user-square"></i></span>
									<span class="pc-mtext">CA Management</span>
								</a>
							</li>
						@endif

						{{-- Business & Earnings --}}
						@if (in_array('adminBusinessEarnings', $permissions))
							<li class="pc-item">
								<a href="{{ route('admin.business-earnings') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-briefcase"></i></span>
									<span class="pc-mtext">Business & Earnings</span>
								</a>
							</li>
						@endif

						{{-- Payment Management --}}
						@if (in_array('adminPayment', $permissions))
							<li class="pc-item">
								<a href="{{ route('admin.payment-management') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-currency-dollar"></i></span>
									<span class="pc-mtext">Payment Management</span>
								</a>
							</li>
						@endif

						{{-- Reminder & Communication --}}
						@if (in_array('adminReminder', $permissions))
							<li class="pc-item">
								<a href="{{ route('CA.Reminder') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-bell-ringing"></i></span>
									<span class="pc-mtext">Reminder & Communication</span>
								</a>
							</li>
						@endif

						{{-- Ticket Management --}}
						@if (in_array('adminTicket', $permissions))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-ticket"></i></span>
									<span class="pc-mtext">Ticket Management</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link"
											href="{{ route('admin.ticket-management') }}">Dashboard</a></li>
									<li class="pc-item"><a class="pc-link"
											href="{{ route('admin.customer-ticket') }}">Customer Ticket</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('admin.ca-ticket') }}">CA
											Ticket</a></li>
								</ul>
							</li>
						@endif

						{{-- HR & Payroll Management --}}
						@if (in_array('adminHrPayroll', $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon"><i class="ph-duotone ph-user-list"></i></span>
								<span class="pc-mtext">HR & Payroll Management</span>
								<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
							</a>
							<ul class="pc-submenu">

								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span>Employee Master</span>
										<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
									</a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.EmployeeList') }}">Employee Details</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.AttendanceList') }}">Attendance History</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.GeneratePayslip') }}">Payslip Management</a></li>

									</ul>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.HRLetterList') }}">HR Letter & Docs</a></li>

							</ul>
						</li>
						@elseif (Auth::user()->u_type == 6)
						<li class="pc-item">
							<a href="{{ url('/payslips') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Payslips</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.attendance_history') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Attendance History</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.performance-review') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-chart-line-up"></i>
								</span>
								<span class="pc-mtext">Performance & Review</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('ca.payslip.list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-identification-badge"></i>
								</span>
								<span class="pc-mtext">Generate Payslip</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.policy-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-circle-gear"></i>
								</span>
								<span class="pc-mtext">Employer Policies</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.assign-task-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-scales"></i>
								</span>
								<span class="pc-mtext">Task Management</span>
							</a>
						</li>
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
									Reimbursement</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
										Requisitions</a></li>
							</ul>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.user-leave-request') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-mountains"></i>
								</span>
								<span class="pc-mtext">Leave Management</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.hr-letters') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-list"></i>
								</span>
								<span class="pc-mtext">HR Letter</span>
							</a>
						</li>	
						@endif


						{{-- Tax Filling & Returns --}}
						@if (in_array('adminTaxFilling', $permissions))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-file-text"></i></span>
									<span class="pc-mtext">Tax Filing & Returns</span>
									<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.tds_returns_filing') }}">TDS Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.pf_management_list') }}">EPF Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.esi_management_list') }}">ESIC Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.ptax_management_list') }}">P-Tax Management</a></li>
									 <li class="pc-item"><a class="pc-link" href="{{ url('/msme/list') }}">MSME Compliance </a></li>
								</ul>
							</li>
						@endif

						{{-- Agent & Channel Partner --}}
						@if (in_array('adminAgent', $permissions))
							<li class="pc-item">
								<a href="{{ route('ca.AgentList') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-handshake"></i></span>
									<span class="pc-mtext">Agent & Channel Partner</span>
								</a>
							</li>
						@endif

						{{-- Direct Business Desk --}}
						@if (in_array('adminDirectBusiness', $permissions))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-buildings"></i></span>
									<span class="pc-mtext">Direct Business Desk</span>
									<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ url('/mca-roc/list') }}">MCA/ROC Filings</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ url('/itr/list') }}">Income Tax Return Filing</a>
									</li>
									<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTComplianceSupport')}}">GST Compliance Support</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('admin.company.checks') }}">Business Health Check-up</a>
									</li>
									<li class="pc-item"><a class="pc-link" href="{{ url('/startup-filing/list') }}">Startup Incubator Services</a>
									</li>
								</ul>
							</li>
						@endif

						{{-- Report Section --}}
						@if (in_array('adminReport', $permissions))
							<li class="pc-item">
								<a href="#" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-chart-line"></i></span>
									<span class="pc-mtext">Report Section</span>
								</a>
							</li>
						@endif

						{{-- Audit & Log Management --}}
						@if (in_array('adminAudit', $permissions))
							<li class="pc-item">
								<a href="{{ route('user.audit.logs') }}" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
									<span class="pc-mtext">Audit & Log Management</span>
								</a>
							</li>
						@endif

						{{-- Settings & Administration --}}
						@if (in_array('adminSettings', $permissions))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-gear"></i></span>
									<span class="pc-mtext">Settings & Administration</span>
									<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link"
											href="{{ route('admin.tds-tax-slab-list') }}">TDS Tax Slabs Set</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('admin.compliance-reminder-list') }}">Compliance Reminder Set</a>
									</li>
								</ul>
							</li>
						@endif

					</ul>
				@endif



				<!-- CA Employee Side bar -->
				@if (Auth::user()->u_type == 4)
					<ul class="pc-navbar">
						<li class="pc-item">
							<a href="{{ route('index') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-gauge"></i>
								</span>
								<span class="pc-mtext">Dashboard</span>
							</a>
						</li>					
						<li class="pc-item">
							<a href="{{ route('ca.TaskList') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-scales"></i>
								</span>
								<span class="pc-mtext">Task Management</span>
							</a>
						</li>
						
						@if (in_array('client_management', $permissions))
						<li class="pc-item">
							<a href="{{ route('Ca.CompanyList') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-garage"></i>
								</span>
								<span class="pc-mtext">Client Management </span>
							</a>
						</li>
						@endif
						@if (in_array('company_assignment', $permissions))
						<li class="pc-item">
							<a href="{{ route('CA.CompanyAssignment') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-users"></i>
								</span>
								<span class="pc-mtext">Company Assignment</span>
							</a>
						</li>
						@endif
						@if (in_array('compliances_management', $permissions))
						<li class="pc-item">
							<a href="{{ route('ca.CompliancesList') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-switch"></i>
								</span>
								<span class="pc-mtext">Compliances Management </span>
							</a>
						</li>
						@endif
						@if (in_array('HR & Payroll Management', $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-user-list"></i></span><span class="pc-mtext">HR & Payroll
									Management</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>HR, Payroll & Attendance</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('CA.EmployeeList') }}">Employee Master </a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('CA.AttendanceList') }}">Attendence History</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('CA.GeneratePayslip') }}">Payslip Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
										{{-- <li class="pc-item"><a class="pc-link" href="#">Holiday Calendars </a></li> --}}
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.HRLetterList') }}">HR Letter & Docs</a></li>
										{{-- <li class="pc-item"><a class="pc-link" href="#">Performance & Reviews</a> --}}
										</li> 
									</ul>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.ExpenditureClaims') }}">Expense Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.SupplyRequisitions') }}">Purchase Requisitions</a></li>

							</li>

							</ul>
						</li>
						@else
						<li class="pc-item">
							<a href="{{ url('/payslips') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Payslips</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.attendance_history') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Attendance History</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.performance-review') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-chart-line-up"></i>
								</span>
								<span class="pc-mtext">Performance & Review</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('ca.payslip.list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-identification-badge"></i>
								</span>
								<span class="pc-mtext">Generate Payslip</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.policy-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-circle-gear"></i>
								</span>
								<span class="pc-mtext">Employer Policies</span>
							</a>
						</li>                   
						<li class="pc-item">
							<a href="{{ route('userEmployee.user-leave-request') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-mountains"></i>
								</span>
								<span class="pc-mtext">Leave Management</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.hr-letters') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-list"></i>
								</span>
								<span class="pc-mtext">HR Letter</span>
							</a>
						</li>
						@endif
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
									Reimbursement</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
										Requisitions</a></li>
							</ul>
						</li>
						<li class="pc-item">
							<a href="{{ route('user.audit.logs') }}" class="pc-link">
								<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
								<span class="pc-mtext">Audit & Log Management</span>
							</a>
						</li>
					</ul>
				@endif

				<!-- Company Employee Side bar -->
				@if (Auth::user()->u_type == 5)
					
					<ul class="pc-navbar">
						<li class="pc-item">
							<a href="{{ url('/') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-gauge"></i>
								</span>
								<span class="pc-mtext">Dashboard & Overview </span>
							</a>
						</li>
				
						@if(canAccess('Secure Doc Locker', $userMenu, $permissions))
						<li class="pc-item">
							<a href="{{ route('user.DocLocker') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-folder-lock"></i>
								</span>
								<span class="pc-mtext">Secure Doc Locker</span>
							</a>
						</li>
						@endif
						@if(canAccess('Plans', $userMenu, $permissions))
						<li class="pc-item">
							<a href="{{ route('user.Plans') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-switch"></i>
								</span>
								<span class="pc-mtext">Subscription & Billing</span>
							</a>
						</li>
						@endif
						@if(canAccess('Assign CA Firm', $userMenu, $permissions))
						<li class="pc-item">
							<a href="{{ route('user.AssignCa') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-circle-plus"></i>
								</span>
								<span class="pc-mtext">Connect Your CA/Accountant</span>
							</a>
						</li>
						@endif

						@if(canAccess('Accounting', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-arrows-down-up"></i> </span><span class="pc-mtext">Accounting &
									Finance </span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Business Operations</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.CustomerList') }}">Customers & Receivables</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.VendorList') }}">Vendors
												& Payables</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.ProductServiceList') }}">Products & Services</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('ca.AgentList') }}">Agent & Channel Partner</a>
										</li>
									</ul>
								</li>
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Sales & Revenue</span> <span class="pc-arrow"><i
												data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.SalesQuotation') }}">Quotation</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.ProformaInvoice') }}">Proforma Invoice</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.SalesInvoices') }}">Sales Invoice</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.SalesCreditDebit') }}">Credit & Debit Note</a></li>
									</ul>
								</li>
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Purchase & Procurement</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.PurchaseOrder') }}">Purchase Order (PO)</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.PurchaseInvoices') }}">Purchase Invoice</a></li>									
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.PurchaseCreditDebit') }}">Credit & Debit Note</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ url('/tds/vendor-purchase') }}" class="nav-link">Vendor Report</a></li>
									</ul>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.OtherIncomeList') }}">Income &
										other Revenue</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.ExpensesList') }}">Expense
										Management</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.CustomInvoiceList') }}">Custom Invoice</a></li>
							</ul>
						</li>
						@endif
						@if(canAccess('Assets & Liabilities', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-buildings"></i></span><span class="pc-mtext">Assets &
									Liabilities</span><span class="pc-arrow"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>Asset Management</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.AssetList') }}">Asset Details</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.AssetVoucherList') }}">Asset Vouchers</a></li>
									</ul>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.Liabilites') }}">Liabilities & Borrowings</a></li>
							</ul>
						</li>
						@endif

						@if(canAccess('Cash & Banking', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-wallet"></i></span><span class="pc-mtext">Cash
									& Banking</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.BankList') }}">Bank Account
										Master</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.CashManagement') }}">Cash
										Management</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.BankReconciliation') }}">Bank Reconciliation</a></li>
								{{-- <li class="pc-item"><a class="pc-link" href="{{ route('user.LoanList') }}">Loan Register
										& Master</a></li> --}}
							</ul>
						</li>
						@endif

						@if(canAccess('Financial Reports', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon"><i data-feather="bar-chart-2"></i></span>
								<span class="pc-mtext">Financial Reports</span>
								<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
							</a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.Ledger') }}">Accounts & Ledger</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.TrialBalanceReport') }}">Trial
										Balance (TB)</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.ProfitLossReport') }}">Profit &
										Loss Statement</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.BalanceSheetReport') }}">Balance Sheet</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.cashflow') }}">Cash Flow Statement</a></li>                           
							</ul>
						</li>
						@endif

						@if(canAccess('Inventory Management', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-lockers"></i></span><span class="pc-mtext">Inventory
									Management</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.Inventory') }}">Inventory
										Management</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.expenses_inventory') }}">Inventory Expenses</a></li>
							</ul>
						</li>
						@endif

						@if(canAccess('Tax Filing & Returns', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-files"></i></span><span class="pc-mtext">Tax
									Filing & Returns</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>GST Management & Returns</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTProfile') }}">GST
												Profile & Registration</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('User.OtherGSTProfile') }}">GSTIN Verification</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReturns') }}">GST
												Returns & Filing</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReports') }}">GST
												Insights & Reports</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTComplianceSupport')}}">GST Compliance Support</a>
										</li>
									</ul>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.tds_returns_filing') }}">TDS Management & Returns</a></li>
											{{-- <li class="pc-item pc-hasmenu">
							  <a href="#!" class="pc-link"><span>TDS Management</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							  <ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route ('user.tds_returns_filing') }}">TDS Returns & Filing</a></li>
							  </ul>
							</li> --}}
								<li class="pc-item"><a class="pc-link" href="{{ route('user.ptax_management_list') }}">Professional Tax Payment</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.pf_management_list') }}">Provident Fund (PF/EPF)</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.esi_management_list') }}">Employees' State Insurance
										(ESI)</a>
								</li>
								<li class="pc-item"><a class="pc-link" href="{{ url('/msme/list') }}">MSME Compliance </a></li>
								 <li class="pc-item"><a class="pc-link" href="{{ url('/mca-roc/list') }}">MCA/ROC Filings </a></li>
								<li class="pc-item"><a class="pc-link" href="{{ url('/itr/list') }}">Income Tax Return Filing</a></li>                            

							</ul>
						</li>
						@endif

						
						@if(canAccess('Statutory Compliance Status', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
							<span class="pc-micon"><i class="ph-duotone ph-chat-circle-text"></i></span>
							<span class="pc-mtext">Statutory Compliance</span>
							<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.compliance-reminder-list') }}">Compliance Calendar</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('ca.CompliancesList') }}">My Compliance Dashboard</a></li>
							</ul>
						</li>
						@endif

						@if(canAccess('HR & Payroll Management', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-user-list"></i></span><span class="pc-mtext">HR & Payroll
									Management</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>HR, Payroll & Attendance</span> <span
											class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.EmployeeList') }}">Employee Master </a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.AttendanceList') }}">Attendence History</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.GeneratePayslip') }}">Payslip Management</a></li>
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
										{{-- <li class="pc-item"><a class="pc-link" href="#">Holiday Calendars </a></li> --}}
										<li class="pc-item"><a class="pc-link"
												href="{{ route('user.HRLetterList') }}">HR
												Letter & Docs</a></li>
										{{--<li class="pc-item"><a class="pc-link" href="#">Performance & Reviews</a>--}}
										</li>
										{{-- <li class="pc-item"><a class="pc-link" href="{{ route('user.performace-review',$encodedId) }}">Performance & Reviews</a></li> --}}
									</ul>

								<li class="pc-item">
									<a href="{{ route('user.TaskManagement') }}" class="pc-link">
										<span class="pc-mtext">Employee Task Management</span>
									</a>
								</li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.ExpenditureClaims') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('user.SupplyRequisitions') }}">Purchase Requisitions</a></li>

							</li>

						</ul>
					</li>
					@elseif(in_array('ALL', $userMenu) || in_array('HR & Payroll Management', $userMenu))
						<li class="pc-item">
							<a href="{{ url('/payslips') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Payslips</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.attendance_history') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Attendance History</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.performance-review') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-chart-line-up"></i>
								</span>
								<span class="pc-mtext">Performance & Review</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('ca.payslip.list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-identification-badge"></i>
								</span>
								<span class="pc-mtext">Generate Payslip</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.policy-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-circle-gear"></i>
								</span>
								<span class="pc-mtext">Employer Policies</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.assign-task-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-scales"></i>
								</span>
								<span class="pc-mtext">Task Management</span>
							</a>
						</li>
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
									Reimbursement</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
										Requisitions</a></li>
							</ul>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.user-leave-request') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-mountains"></i>
								</span>
								<span class="pc-mtext">Leave Management</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.hr-letters') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-list"></i>
								</span>
								<span class="pc-mtext">HR Letter</span>
							</a>
						</li>
					@endif

					@if(canAccess('Business Health Check-up', $userMenu, $permissions))
					<li class="pc-item">
						<a href="{{ route('admin.company.checks') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-activity"></i>
							</span>
							<span class="pc-mtext">Business Health Check-up</span>
						</a>
					</li>
					@endif
					
					@if(canAccess('Audit & Log Management', $userMenu, $permissions))
					<li class="pc-item">
						<a href="{{ route('user.audit.logs') }}" class="pc-link">
							<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
							<span class="pc-mtext">Audit & Log Management</span>
						</a>
					</li>
					@endif

					@if(canAccess('Startup Incubator Services', $userMenu, $permissions))
					<li class="pc-item">
						<a href="{{ url('/startup-filing/list') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-briefcase"></i>
							</span>
							<span class="pc-mtext">Startup Incubator Services</span>
						</a>
					</li>
					@endif

					@if(canAccess('Professional Services', $userMenu, $permissions))
					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link"><span class="pc-micon"><i
									class="ph-duotone ph-detective"></i></span><span class="pc-mtext">Professional
								Services</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="#">Company Incorporation</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Udyam Registration (MSME)</a></li>
							<li class="pc-item"><a class="pc-link" href="#">GST Registration & Returns</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Licensing & Compliance</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Tax Audit & ROC Filing</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Accounting Outsourcing</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Financial Planning & Analysis</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Legal Advisory Support</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Digital Security Services</a></li>
							<li class="pc-item"><a class="pc-link" href="#">Advisory & Consulting</a></li>
							<li class="pc-item"><a class="pc-link" href="#">MSME Schemes & Loans Assistance</a>
							</li>
						</ul>
					</li>
					@endif

					@if(canAccess('Technology Services', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-wrench"></i></span><span class="pc-mtext">Technology
									Services</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								{{-- <li class="pc-item"><a class="pc-link" href="{{ route('company-branding') }}" target="_blank">Company Branding</a></li> --}}
								<li class="pc-item"><a class="pc-link" href="{{ route('web-and-app') }}"
										target="_blank">Website & App Development</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('ui-and-ux') }}"
										target="_blank">Professional UI/UX Design</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('crm-erp-implementation') }}"
										target="_blank">CRM/ERP Implementation</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('pwa') }}"
										target="_blank">Progressive Web Applications</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('seo') }}" target="_blank">SEO &
										Search Optimization</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('smm') }}" target="_blank">Digital
										Marketing Strategy</a></li>
							</ul>
						</li>
					 </ul>
					@endif	

				   
					<!--<ul class="pc-navbar">
						<li class="pc-item">
							<a href="{{ url('/') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-gauge"></i>
								</span>
								<span class="pc-mtext">Dashboard</span>
							</a>
						</li>
						@if(in_array('ALL', $userMenu) || in_array('HR & Payroll Management', $userMenu))
						<li class="pc-item">
							<a href="{{ route('userEmployee.attendance_history') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-calendar"></i>
								</span>
								<span class="pc-mtext">Attendance History</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.performance-review') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-chart-line-up"></i>
								</span>
								<span class="pc-mtext">Performance & Review</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('ca.payslip.list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-identification-badge"></i>
								</span>
								<span class="pc-mtext">Generate Payslip</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.policy-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-circle-gear"></i>
								</span>
								<span class="pc-mtext">Employer Policies</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.assign-task-list') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-scales"></i>
								</span>
								<span class="pc-mtext">Task Management</span>
							</a>
						</li>
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link"><span class="pc-micon"><i
										class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
									Reimbursement</span><span class="pc-arrow mt-1"><i
										data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link"
										href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
										Requisitions</a></li>
							</ul>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.user-leave-request') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-mountains"></i>
								</span>
								<span class="pc-mtext">Leave Management</span>
							</a>
						</li>
						<li class="pc-item">
							<a href="{{ route('userEmployee.hr-letters') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-list"></i>
								</span>
								<span class="pc-mtext">HR Letter</span>
							</a>
						</li>
						@endif
						@if(in_array('ALL', $userMenu) || in_array('Audit & Log Management', $userMenu))
						<li class="pc-item">
							<a href="{{ route('user.audit.logs') }}" class="pc-link">
								<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
								<span class="pc-mtext">Audit & Log Management</span>
							</a>
						</li>
						@endif
					</ul>-->
				@endif






				<div class="card nav-action-card bg-brand-color-4">
					<div class="card-body" style="background-image: url('../assets/images/layout/nav-card-bg.svg')">
						<h5 class="text-dark">Help Center</h5>
						<p class="text-dark text-opacity-75">Please contact us for more questions.</p>
						<a href="{{ route('help-center') }}" class="btn btn-primary" target="_blank">Help Center</a>
					</div>
				</div>
			</div>
			<div class="card pc-user-card">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-shrink-0">
							<img src="../assets/images/user/avatar-1.jpg" alt="user-image"
								class="user-avtar wid-45 rounded-circle" />
						</div>
						<div class="flex-grow-1 ms-3">
							<div class="dropdown">
								<a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown"
									aria-expanded="false" data-bs-offset="0,20">
									<div class="d-flex align-items-center">
										<div class="flex-grow-1 me-2">
											<h6 class="mb-0">{{ Auth::user()->name }}</h6>
											<!--<small>Owner</small>-->
										</div>
										<div class="flex-shrink-0">
											<div class="btn btn-icon btn-link-secondary avtar">
												<i class="ph-duotone ph-windows-logo"></i>
											</div>
										</div>
									</div>
								</a>
								<div class="dropdown-menu">
									<ul>
										<li>
											<a  href="{{ route('editProfile') }}" class="pc-user-links">
												<i class="ph-duotone ph-user"></i>
												<span>Edit Account</span>
											</a>
										</li>

										<li>
											<a href="javascript:void(0);" id="lockScreenBtn" class="pc-user-links">
												<i class="ph-duotone ph-lock-key"></i>
												<span>Lock Screen</span>
											</a>
										</li>
										<li>
											<a href="{{ url('/logout') }}" class="pc-user-links">
												<i class="ph-duotone ph-power"></i>
												<span>Logout</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</nav>

<script>
$('#lockScreenBtn').click(function () {

    let email = "{{ auth()->user()->email }}";
	let name = "{{ auth()->user()->name }}";

    // store in localStorage
    localStorage.setItem('lock_email', email);
    localStorage.setItem('nm', name);
	localStorage.setItem('lock_redirect', window.location.href);

    // redirect to lock screen
    window.location.href = "/lock-screen";
});
</script>