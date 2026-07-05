<nav class="pc-sidebar">
	<div class="navbar-wrapper">
		<div class="m-header">
			<a href="{{ route('index') }}" class="b-brand text-primary text-center">
				<!-- ========   Change your logo from here   ============ -->
				<img src="{{ asset('assets/images/logo-small.png') }}" alt="logo image" class="logo-lg text-center" />
			</a>
		</div>

		@php
			use Illuminate\Support\Facades\DB;

			$menuFeatures = DB::table('menu_features')->get();

			$menuByCode = $menuFeatures->keyBy('code');
			$menuByParent = $menuFeatures->groupBy('parent_id');

			$permissions = Auth::user()->emp_permission
				? explode(',', Auth::user()->emp_permission)
				: [];

			$userMenu = $userFeatures;
		@endphp

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
					@if(in_array('ALL', $userMenu) || in_array('Business Overview', $userMenu))
						<li class="pc-item">
							<a href="{{ route('index') }}" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-gauge"></i>
								</span>
								<span class="pc-mtext">Business Overview</span>
							</a>
						</li>
					@endif

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-buildings"></i>
							</span>
							<span class="pc-mtext">Business Setup</span>
							<span class="pc-arrow">
								<i data-feather="chevron-right"></i>
							</span>
						</a>
						<ul class="pc-submenu">

							@if(in_array('ALL', $userMenu) || in_array('Business Profile', $userMenu))
								<li class="pc-item">
									<a href="{{ route('user.CompanyProfile') }}" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-user-switch"></i>
										</span>
										<span class="pc-mtext">Business Profile</span>
									</a>
								</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Proprietorship Profile', $userMenu))
								<li class="pc-item">
									<a href="{{ route('proprietorship.list') }}" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-warehouse"></i>
										</span>
										<span class="pc-mtext">Proprietorship Profile</span>
									</a>
								</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Subscription & Billing', $userMenu))
								<li class="pc-item">
									<a href="{{ route('user.Plans') }}" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-user-switch"></i>
										</span>
										<span class="pc-mtext">Subscription & Billing</span>
									</a>
								</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('CA Connect', $userMenu))
								<li class="pc-item">
									<a href="{{ route('user.AssignCa') }}" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-user-circle-plus"></i>
										</span>
										<span class="pc-mtext">CA Connect</span>
									</a>
								</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('CA Access Control', $userMenu))
								<li class="pc-item">
									<a href="{{ route('user.CaAccessControl') }}" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-users-three"></i>
										</span>
										<span class="pc-mtext">CA Access Control</span>
									</a>
								</li>
							@endif

						</ul>
					</li>

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-arrows-down-up"></i>
							</span>
							<span class="pc-mtext">Accounting & Finance</span>
							<span class="pc-arrow">
								<i data-feather="chevron-right"></i>
							</span>
						</a>

						<ul class="pc-submenu">

							@if(in_array('ALL', $userMenu) || in_array('Biz Operations', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-briefcase"></i></span>
									<span>Biz Operations</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.CustomerList') }}">Customers & Receivables</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.VendorList') }}">Vendors & Payables</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.ProductServiceList') }}">Products & Services</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('ca.AgentList') }}">Agent & Channel Partner</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Sales & Invoicing', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-receipt"></i></span>
									<span>Sales & Invoicing</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.SalesQuotation') }}">Quotation</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.ProformaInvoice') }}">Proforma Invoice</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.SalesInvoices') }}">Sales Invoice</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.SalesCreditDebit') }}">Credit & Debit Note</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.CustomInvoiceList') }}">Custom Invoice</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Purchase', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-shopping-cart"></i></span>
									<span>Purchase</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.PurchaseOrder') }}">Purchase Order (PO)</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.PurchaseInvoices') }}">Purchase Invoice</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.PurchaseCreditDebit') }}">Credit & Debit Note</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ url('/tds/vendor-purchase') }}">Vendor Report</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Other Income', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.OtherIncomeList') }}">
									<span class="pc-micon"><i class="ph-duotone ph-coins"></i></span>
									<span class="pc-mtext">Other Income</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Expenses', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.ExpensesList') }}">
									<span class="pc-micon"><i class="ph-duotone ph-money"></i></span>
									<span class="pc-mtext">Expenses</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Asset Management', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-buildings"></i></span>
									<span>Asset Management</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.AssetList') }}">Asset Details</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.AssetVoucherList') }}">Asset Vouchers</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Liabilities & Borrowings', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.Liabilites') }}">
									<i class="ph-duotone ph-bank me-2"></i>
									Liabilities & Borrowings
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Cash & Banking', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon"><i class="ph-duotone ph-wallet"></i></span>
									<span>Cash & Banking</span>
									<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
								</a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.PaymentVoucherList') }}">Payment / Receipt Vouchers</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.BankList') }}">Bank Account Master</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.CashManagement') }}">Cash Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.BankReconciliation') }}">Bank Reconciliation</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Journal Entry', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.JournalList') }}">
									<span class="pc-micon"><i class="ph-duotone ph-book-open"></i></span>
									<span class="pc-mtext">Journal Entry</span>
								</a>
							</li>
							@endif

						</ul>
					</li>

					@if(in_array('ALL', $userMenu) || in_array('Inventory', $userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon"><i class="ph-duotone ph-lockers"></i></span>
								<span class="pc-mtext">Inventory</span>
								<span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span>
							</a>

							<ul class="pc-submenu">
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.Inventory') }}">
										<i class="ph-duotone ph-package"></i>
										<span>Stock & Inventory</span>
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.expenses_inventory') }}">
										<i class="ph-duotone ph-receipt"></i>
										<span>Inventory Expenses</span>
									</a>
								</li>
							</ul>
						</li>
					@endif

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-user-list"></i>
							</span>
							<span class="pc-mtext">HR & Payroll</span>
							<span class="pc-arrow mt-1">
								<i data-feather="chevron-right"></i>
							</span>
						</a>

						<ul class="pc-submenu">

							@if(in_array('ALL', $userMenu) || in_array('Payroll Management', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon">
										<i class="ph-duotone ph-identification-card" style="font-size:16px;"></i>
									</span>
									<span>Payroll Management</span>
									<span class="pc-arrow">
										<i data-feather="chevron-right"></i>
									</span>
								</a>

								<ul class="pc-submenu">
									<li class="pc-item">
										<a class="pc-link" href="{{ route('user.EmployeeList') }}">Employee Master</a>
									</li>
									<li class="pc-item">
										<a class="pc-link" href="{{ route('user.GeneratePayslip') }}">Payslip Management</a>
									</li>
									<li class="pc-item">
										<a class="pc-link" href="{{ route('user.ResignEmployee') }}">Resign Employees</a>
									</li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Attendance & Leave', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon">
										<i class="ph-duotone ph-calendar-check" style="font-size:16px;"></i>
									</span>
									<span>Attendance & Leave</span>
									<span class="pc-arrow">
										<i data-feather="chevron-right"></i>
									</span>
								</a>

								<ul class="pc-submenu">
									<li class="pc-item">
										<a class="pc-link" href="{{ route('user.AttendanceList') }}">Attendance History</a>
									</li>
									<li class="pc-item">
										<a class="pc-link" href="{{ route('user.LeaveManagement') }}">Leave Management</a>
									</li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Employment Policies', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.EmployeePolicyList') }}">
									<i class="ph-duotone ph-scroll me-2"></i>
									Employment Policies
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('HR Documents', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.HRLetterList') }}">
									<i class="ph-duotone ph-file-text"></i>
									HR Documents
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Task Management', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.TaskManagement') }}">
									<i class="ph-duotone ph-check-square-offset me-2"></i>
									Task Management
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Expense Claims', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.ExpenditureClaims') }}">
									<i class="ph-duotone ph-receipt me-2"></i>
									Expense Claims
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Purchase Requisitions', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.SupplyRequisitions') }}">
									<i class="ph-duotone ph-shopping-cart me-2"></i>
									Purchase Requisitions
								</a>
							</li>
							@endif

						</ul>
					</li>

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-chat-circle-text"></i>
							</span>
							<span class="pc-mtext">Compliance Center</span>
							<span class="pc-arrow mt-1">
								<i data-feather="chevron-right"></i>
							</span>
						</a>

						<ul class="pc-submenu">

							@if(in_array('ALL', $userMenu) || in_array('Compliance Overview', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('ca.CompliancesList') }}">
									<i class="ph-duotone ph-chart-line me-2"></i>
									Compliance Overview
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Compliance Calendar', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('admin.compliance-reminder-list') }}">
									<i class="ph-duotone ph-calendar-check me-2"></i>
									Compliance Calendar
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('GST Returns & Reports', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon">
										<i class="ph-duotone ph-receipt fs-6"></i>
									</span>
									<span class="pc-mtext">GST Returns & Reports</span>
									<span class="pc-arrow">
										<i data-feather="chevron-right"></i>
									</span>
								</a>

								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTProfile') }}">GST Profile & Registration</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('User.OtherGSTProfile') }}">GSTIN Verification</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReturns') }}">GST Returns & Filing</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReports') }}">GST Insights & Reports</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTComplianceSupport') }}">GST Compliance Support</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('TDS Management', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.tds_returns_filing') }}">
									<i class="ph-duotone ph-file-arrow-down me-2"></i>
									TDS Management
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Income Tax Returns', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ url('/itr/list') }}">
									<i class="ph-duotone ph-file me-2"></i>
									Income Tax Returns
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Labour Compliance', $userMenu))
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link">
									<span class="pc-micon">
										<i class="ph-duotone ph-users-three"></i>
									</span>
									<span>Labour Compliance</span>
									<span class="pc-arrow">
										<i data-feather="chevron-right"></i>
									</span>
								</a>

								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('user.ptax_management_list') }}">Professional Tax</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.pf_management_list') }}">Provident Fund</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.esi_management_list') }}">ESI Payments</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.shop_registration') }}">Shops Registration</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.lwf_compliance') }}">Labour Welfare Fund (LWF)</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.gratuity_compliance') }}">Gratuity Payment</a></li>
								</ul>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('MCA / ROC Filing', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ url('/mca-roc/list') }}">
									<i class="ph-duotone ph-buildings me-2"></i>
									MCA / ROC Filing
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('MSME Compliance', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ url('/msme/list') }}">
									<i class="ph-duotone ph-factory me-2"></i>
									MSME Compliance
								</a>
							</li>
							@endif

						</ul>
					</li>

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-chart-bar"></i>
							</span>
							<span class="pc-mtext">Reports & Insights</span>
							<span class="pc-arrow mt-1">
								<i data-feather="chevron-right"></i>
							</span>
						</a>

						<ul class="pc-submenu">

							@if(in_array('ALL', $userMenu) || in_array('Account Ledgers', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.Ledger') }}">
									<i class="ph-duotone ph-book-open-text"></i>
									<span>Account Ledgers</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Trial Balance', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.TrialBalanceReport') }}">
									<i class="ph-duotone ph-scales"></i>
									<span>Trial Balance</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Profit & Loss', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.ProfitLossReport') }}">
									<i class="ph-duotone ph-trend-up"></i>
									<span>Profit & Loss</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Balance Sheet', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.BalanceSheetReport') }}">
									<i class="ph-duotone ph-bank"></i>
									<span>Balance Sheet</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Cashflow Statement', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.cashflow') }}">
									<i class="ph-duotone ph-arrows-left-right"></i>
									<span>Cashflow Statement</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Payroll Reports', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.PayrollReports') }}">
									<i class="ph-duotone ph-users-three"></i>
									<span>Payroll Reports</span>
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Business Health Check-up', $userMenu))
							<li class="pc-item">
								<a href="{{ route('admin.company.checks') }}" class="pc-link">
									<i class="ph-duotone ph-heartbeat"></i>
									<span>Business Health Check-up</span>
								</a>
							</li>
							@endif

						</ul>
					</li>

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-folder-lock"></i>
							</span>
							<span class="pc-mtext">Documents & Audit</span>
							<span class="pc-arrow">
								<i data-feather="chevron-right"></i>
							</span>
						</a>

						<ul class="pc-submenu">

							@if(in_array('ALL', $userMenu) || in_array('Document Locker', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.DocLocker') }}">
									<i class="ph-duotone ph-folder-lock me-2"></i>
									Document Locker
								</a>
							</li>
							@endif

							@if(in_array('ALL', $userMenu) || in_array('Audit Trail', $userMenu))
							<li class="pc-item">
								<a class="pc-link" href="{{ route('user.audit.logs') }}">
									<i class="ph-duotone ph-shield-check me-2"></i>
									Audit Trail
								</a>
							</li>
							@endif

						</ul>
					</li>

					@if(in_array('ALL',$userMenu) || in_array('MSME Benefits Hub',$userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-factory"></i>
								</span>
								<span class="pc-mtext">MSME Benefits Hub</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.msme.discover-schemes') }}">
										<i class="ph-duotone ph-compass me-2"></i>
										Discover Schemes
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.msme.eligibility-checker') }}">
										<i class="ph-duotone ph-check-circle me-2"></i>
										Eligibility Checker
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.msme.loan-and-subsidies') }}">
										<i class="ph-duotone ph-bank me-2"></i>
										Loan & Subsidy
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.msme.startup-benefits') }}">
										<i class="ph-duotone ph-rocket-launch me-2"></i>
										Startup Benefits
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.msme.govt-updates') }}">
										<i class="ph-duotone ph-newspaper me-2"></i>
										Govt Updates
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.msme.consultant-assistance') }}">
										<i class="ph-duotone ph-users-three me-2"></i>
										Consultant Assistance
									</a>
								</li>
							</ul>
						</li>
					@endif

					@if(in_array('ALL',$userMenu) || in_array('Professional Services',$userMenu))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-briefcase"></i>
								</span>
								<span class="pc-mtext">Professional Services</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								<!-- Startup Incubator -->
								<li class="pc-item">
									<a href="{{ url('/startup-filing/list') }}" class="pc-link">
										<i class="ph-duotone ph-rocket-launch"></i>
										<span>Startup Incubator Services</span>
									</a>
								</li>

								<!-- Compliance Advisory -->
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-shield-check"></i>
										</span>
										<span class="pc-mtext">Compliance Advisory</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

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
										<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">MSME Schemes & Loans Assistance</a></li>
									</ul>
								</li>

								<!-- Digital Solutions -->
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-code"></i>
										</span>
										<span class="pc-mtext">Digital Solutions</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('web-and-app') }}" target="_blank">Website & App Development</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('ui-and-ux') }}" target="_blank">Professional UI/UX Design</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('crm-erp-implementation') }}" target="_blank">CRM/ERP Implementation</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('pwa') }}" target="_blank">Progressive Web Applications</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('seo') }}" target="_blank">SEO & Search Optimization</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('smm') }}" target="_blank">Digital Marketing Strategy</a></li>
									</ul>
								</li>

								<!-- Partner Services -->
								<li class="pc-item">
									<a class="pc-link" href="#">
										<i class="ph-duotone ph-handshake"></i>
										<span>Partner Services</span>
									</a>
								</li>

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
						<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-file-plus"></i></span><span class="pc-mtext">Task
								Management</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
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
						<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-user-list"></i></span><span class="pc-mtext">HR & Payroll
								Management</span><span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item pc-hasmenu">
								<a href="#!" class="pc-link"><span>HR, Payroll & Attendance</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
								<ul class="pc-submenu">
									<li class="pc-item"><a class="pc-link" href="{{ route('CA.EmployeeList') }}">Employee Master </a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('CA.AttendanceList') }}">Attendence History</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('CA.GeneratePayslip') }}">Payslip Management</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
									<li class="pc-item"><a class="pc-link" href="#">Holiday Calendars </a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.HRLetterList') }}">HR Letter & Docs</a></li>
									<li class="pc-item"><a class="pc-link" href="#">Performance & Reviews</a>
									</li>
									{{-- <li class="pc-item"><a class="pc-link" href="{{ route('user.performace-review',$encodedId) }}">Performance & Reviews</a></li> --}}
								</ul>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.ExpenditureClaims') }}">Expense Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.SupplyRequisitions') }}">Purchase Requisitions</a></li>
							</li>
						</ul>
					</li>

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-files"></i></span><span class="pc-mtext">Tax
								Filing & Returns</span><span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
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
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.subscription-list') }}">Subscription List</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.coupon-codes') }}">Coupon Code</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.subscription-customer-list') }}">Customer List</a></li>
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
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.ticket-management') }}">Dashboard</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.customer-ticket') }}">Customer Ticket</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.ca-ticket') }}">CA
										Ticket</a></li>
							</ul>
						</li>
					@endif

					{{-- HR & Payroll Management  --}}
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
								<li class="pc-item"><a href="{{ route('user.TaskManagement') }}" class="pc-link"><span class="pc-mtext">Employee Task Management</span></a></li>
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
							<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
									Reimbursement</span><span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
										Reimbursements</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
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
								<li class="pc-item"><a class="pc-link" href="{{ route('dropdown.index') }}">Dropdown Value Set</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.tds-tax-slab-list') }}">TDS Tax Slabs Set</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('admin.compliance-reminder-list') }}">Compliance Reminder Set</a>
								</li>

								{{-- Deduction Master --}}
								@if (in_array('adminDeductionMaster', $permissions))
									<li class="pc-item">
										<a href="{{ route('tax.index') }}" class="pc-link">
											<span class="pc-mtext">
												Deduction Master (Income Tax Act.)
											</span>
										</a>
									</li>
								@endif

								{{-- Income Tax Slab Master  --}}
								@if (in_array('adminIncomeTaxSlabMaster', $permissions))
									<li class="pc-item">
										<a href="{{ route('admin.income-tax-slab-list') }}" class="pc-link">
											<span class="pc-mtext">
												Income Tax Slab Master
											</span>
										</a>
									</li>
								@endif
							</ul>

						</li>
					@endif

				</ul>
			@endif

			<!-- CA Employee Side bar -->
			@if (Auth::user()->u_type == 4)

				{{-- Common Access Menu Ca Employee --}}
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

					<li class="pc-item pc-hasmenu">
						<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
								Reimbursement</span><span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
									Reimbursements</a></li>
							<li class="pc-item"><a class="pc-link" href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
									Requisitions</a></li>
						</ul>
					</li>
					<li class="pc-item">
						<a href="{{ route('user.audit.logs') }}" class="pc-link">
							<span class="pc-micon"><i class="ph-duotone ph-shield-check"></i></span>
							<span class="pc-mtext">Audit & Log Management</span>
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
							<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-user-list"></i></span><span class="pc-mtext">HR & Payroll
									Management</span><span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
							<ul class="pc-submenu">
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link"><span>HR, Payroll & Attendance</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('CA.EmployeeList') }}">Employee Master </a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('CA.AttendanceList') }}">Attendence History</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.LeaveManagement') }}">Leave Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('CA.GeneratePayslip') }}">Payslip Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.EmployeePolicyList') }}">Employment Policies</a></li>
										{{-- <li class="pc-item"><a class="pc-link" href="#">Holiday Calendars </a></li> --}}
										<li class="pc-item"><a class="pc-link" href="{{ route('user.HRLetterList') }}">HR Letter & Docs</a></li>
										{{-- <li class="pc-item"><a class="pc-link" href="#">Performance & Reviews</a> </li> --}}

									</ul>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.ExpenditureClaims') }}">Expense Reimbursements</a></li>
									<li class="pc-item"><a class="pc-link" href="{{ route('user.SupplyRequisitions') }}">Purchase Requisitions</a></li>
								</li>
							</ul>
						</li>
					@endif
				</ul>
			@endif

			<!-- Company Employee Side bar -->
			@if (Auth::user()->u_type == 5)
				{{-- Common Access for User Employee --}}
				<ul class="pc-navbar">
					<li class="pc-item">
						<a href="{{ url('/') }}" class="pc-link">
							<span class="pc-micon">
								<i class="ph-duotone ph-gauge"></i>
							</span>
							<span class="pc-mtext">Dashboard & Overview </span>
						</a>
					</li>

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
						<a href="#!" class="pc-link"><span class="pc-micon"><i class="ph-duotone ph-folder-user"></i></span><span class="pc-mtext">Procurement &
								Reimbursement</span><span class="pc-arrow mt-1"><i data-feather="chevron-right"></i></span></a>
						<ul class="pc-submenu">
							<li class="pc-item"><a class="pc-link" href="{{ route('userEmployee.ExpenditureClaimsList') }}">Expense
									Reimbursements</a></li>
							<li class="pc-item"><a class="pc-link" href="{{ route('userEmployee.SupplyRequisitionsList') }}">Purchase
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
					{{-- End Common Access company employee --}}
					{{-- start Access company employee --}}


					{{-- Business setup --}}
					@php
						$businessSetup = $menuByCode->get('Business Setup');

						$businessChildren = $businessSetup
							? ($menuByParent[$businessSetup->id] ?? collect())
							: collect();

						$showBusinessSetup = $businessChildren->contains(function ($menu) use ($userMenu, $permissions) {
							return canAccess($menu->code, $userMenu, $permissions);
						});
					@endphp

					@if($showBusinessSetup)
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-buildings"></i>
								</span>
								<span class="pc-mtext">{{ $businessSetup->menu_name }}</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								@if(canAccess('Business Profile', $userMenu, $permissions))
									<li class="pc-item">
										<a href="{{ route('user.CompanyProfile') }}" class="pc-link">
											{{ $menuByCode['Business Profile']->menu_name }}
										</a>
									</li>
								@endif

								@if(canAccess('Proprietorship Profile', $userMenu, $permissions))
									<li class="pc-item">
										<a href="{{ route('proprietorship.list') }}" class="pc-link">
											{{ $menuByCode['Proprietorship Profile']->menu_name }}
										</a>
									</li>
								@endif

								@if(canAccess('Subscription & Billing', $userMenu, $permissions))
									<li class="pc-item">
										<a href="{{ route('user.Plans') }}" class="pc-link">
											{{ $menuByCode['Subscription & Billing']->menu_name }}
										</a>
									</li>
								@endif

								@if(canAccess('CA Connect', $userMenu, $permissions))
									<li class="pc-item">
										<a href="{{ route('user.AssignCa') }}" class="pc-link">
											{{ $menuByCode['CA Connect']->menu_name }}
										</a>
									</li>
								@endif

								@if(canAccess('CA Access Control', $userMenu, $permissions))
									<li class="pc-item">
										<a href="{{ route('user.CaAccessControl') }}" class="pc-link">
											{{ $menuByCode['CA Access Control']->menu_name }}
										</a>
									</li>
								@endif

							</ul>
						</li>
					@endif

					{{-- Accounting & Finance --}}

					@php
						$accounting = $menuByCode->get('Accounting & Finance');

						$accountingChildren = $accounting
							? ($menuByParent[$accounting->id] ?? collect())
							: collect();

						$showAccounting = $accountingChildren->contains(function ($menu) use ($userMenu, $permissions) {
							return canAccess($menu->code, $userMenu, $permissions);
						});
					@endphp

					@if($showAccounting)

						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-arrows-down-up"></i>
								</span>
								<span class="pc-mtext">{{ $accounting->menu_name }}</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								{{-- Biz Operations --}}
								@if(canAccess('Biz Operations', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon"><i class="ph-duotone ph-briefcase"></i></span>
										<span>{{ $menuByCode['Biz Operations']->menu_name }}</span>
										<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
									</a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.CustomerList') }}">Customers & Receivables</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.VendorList') }}">Vendors & Payables</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.ProductServiceList') }}">Products & Services</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.ProjectList') }}">Project & Job Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('ca.AgentList') }}">Agent & Channel Partner</a></li>
									</ul>
								</li>
								@endif


								{{-- Sales & Invoicing --}}
								@if(canAccess('Sales & Invoicing', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon"><i class="ph-duotone ph-receipt"></i></span>
										<span>{{ $menuByCode['Sales & Invoicing']->menu_name }}</span>
										<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
									</a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.SalesQuotation') }}">Quotation</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.ProformaInvoice') }}">Proforma Invoice</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.SalesInvoices') }}">Sales Invoice</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.SalesCreditDebit') }}">Credit & Debit Note</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.CustomInvoiceList') }}">Custom Invoice</a></li>
									</ul>
								</li>
								@endif


								{{-- Purchase --}}
								@if(canAccess('Purchase', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon"><i class="ph-duotone ph-shopping-cart"></i></span>
										<span>{{ $menuByCode['Purchase']->menu_name }}</span>
										<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
									</a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.PurchaseOrder') }}">Purchase Order (PO)</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.PurchaseInvoices') }}">Purchase Invoice</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.PurchaseCreditDebit') }}">Credit & Debit Note</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ url('/tds/vendor-purchase') }}">Vendor Report</a></li>
									</ul>
								</li>
								@endif


								{{-- Other Income --}}
								@if(canAccess('Other Income', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.OtherIncomeList') }}">
										<span class="pc-micon"><i class="ph-duotone ph-coins"></i></span>
										<span class="pc-mtext">{{ $menuByCode['Other Income']->menu_name }}</span>
									</a>
								</li>
								@endif


								{{-- Expenses --}}
								@if(canAccess('Expenses', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.ExpensesList') }}">
										<span class="pc-micon"><i class="ph-duotone ph-money"></i></span>
										<span class="pc-mtext">{{ $menuByCode['Expenses']->menu_name }}</span>
									</a>
								</li>
								@endif


								{{-- Asset Management --}}
								@if(canAccess('Asset Management', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon"><i class="ph-duotone ph-buildings"></i></span>
										<span>{{ $menuByCode['Asset Management']->menu_name }}</span>
										<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
									</a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.AssetList') }}">Asset Details</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.AssetVoucherList') }}">Asset Vouchers</a></li>
									</ul>
								</li>
								@endif


								{{-- Liabilities & Borrowings --}}
								@if(canAccess('Liabilities & Borrowings', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.Liabilites') }}">
										<span class="pc-micon">
											<i class="ph-duotone ph-bank"></i>
										</span>
										<span class="pc-mtext">{{ $menuByCode['Liabilities & Borrowings']->menu_name }}</span>
									</a>
								</li>
								@endif


								{{-- Cash & Banking --}}
								@if(canAccess('Cash & Banking', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon"><i class="ph-duotone ph-wallet"></i></span>
										<span>{{ $menuByCode['Cash & Banking']->menu_name }}</span>
										<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
									</a>
									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.PaymentVoucherList') }}">Payment / Receipt Vouchers</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.BankList') }}">Bank Account Master</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.CashManagement') }}">Cash Management</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.BankReconciliation') }}">Bank Reconciliation</a></li>
									</ul>
								</li>
								@endif


								{{-- Journal Entry --}}
								@if(canAccess('Journal Entry', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.JournalList') }}">
										<span class="pc-micon"><i class="ph-duotone ph-book-open"></i></span>
										<span class="pc-mtext">{{ $menuByCode['Journal Entry']->menu_name }}</span>
									</a>
								</li>
								@endif

							</ul>
						</li>

					@endif

					{{-- Inventory --}}
					@php
						$inventory = $menuByCode->get('Inventory');
					@endphp

					@if($inventory && canAccess('Inventory', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-lockers"></i>
								</span>
								<span class="pc-mtext">{{ $inventory->menu_name }}</span>
								<span class="pc-arrow mt-1">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.Inventory') }}">
										<i class="ph-duotone ph-package"></i>
										<span>Stock & Inventory</span>
									</a>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.expenses_inventory') }}">
										<i class="ph-duotone ph-receipt"></i>
										<span>Inventory Expenses</span>
									</a>
								</li>
							</ul>
						</li>
					@endif

					{{-- Hr and Payroll --}}

					@php
						$hrPayroll = $menuByCode->get('HR & Payroll');

						$hrPayrollChildren = $hrPayroll
							? ($menuByParent[$hrPayroll->id] ?? collect())
							: collect();

						$showHrPayroll = $hrPayrollChildren->contains(function ($menu) use ($userMenu, $permissions) {
							return canAccess($menu->code, $userMenu, $permissions);
						});
					@endphp

					@if($showHrPayroll)
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-user-list"></i>
								</span>
								<span class="pc-mtext">{{ $hrPayroll->menu_name }}</span>
								<span class="pc-arrow mt-1">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								{{-- Payroll Management --}}
								@if(canAccess('Payroll Management', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-identification-card" style="font-size:16px;"></i>
										</span>
										<span>{{ $menuByCode['Payroll Management']->menu_name }}</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

									<ul class="pc-submenu">
										<li class="pc-item">
											<a class="pc-link" href="{{ route('user.EmployeeList') }}">
												Employee Master
											</a>
										</li>
										<li class="pc-item">
											<a class="pc-link" href="{{ route('user.GeneratePayslip') }}">
												Payslip Management
											</a>
										</li>
										<li class="pc-item">
											<a class="pc-link" href="{{ route('user.ResignEmployee') }}">
												Resign Employees
											</a>
										</li>
									</ul>
								</li>
								@endif


								{{-- Attendance & Leave --}}
								@if(canAccess('Attendance & Leave', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-calendar-check" style="font-size:16px;"></i>
										</span>
										<span>{{ $menuByCode['Attendance & Leave']->menu_name }}</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

									<ul class="pc-submenu">
										<li class="pc-item">
											<a class="pc-link" href="{{ route('user.AttendanceList') }}">
												Attendance History
											</a>
										</li>
										<li class="pc-item">
											<a class="pc-link" href="{{ route('user.LeaveManagement') }}">
												Leave Management
											</a>
										</li>
									</ul>
								</li>
								@endif


								{{-- Employment Policies --}}
								@if(canAccess('Employment Policies', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.EmployeePolicyList') }}">
										<i class="ph-duotone ph-scroll me-2"></i>
										{{ $menuByCode['Employment Policies']->menu_name }}
									</a>
								</li>
								@endif


								{{-- HR Documents --}}
								@if(canAccess('HR Documents', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.HRLetterList') }}">
										<i class="ph-duotone ph-file-text me-2"></i>
										{{ $menuByCode['HR Documents']->menu_name }}
									</a>
								</li>
								@endif


								{{-- Task Management --}}
								@if(canAccess('Task Management', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.TaskManagement') }}">
										<i class="ph-duotone ph-check-square-offset me-2"></i>
										{{ $menuByCode['Task Management']->menu_name }}
									</a>
								</li>
								@endif


								{{-- Expense Claims --}}
								@if(canAccess('Expense Claims', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.ExpenditureClaims') }}">
										<i class="ph-duotone ph-receipt me-2"></i>
										{{ $menuByCode['Expense Claims']->menu_name }}
									</a>
								</li>
								@endif


								{{-- Purchase Requisitions --}}
								@if(canAccess('Purchase Requisitions', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.SupplyRequisitions') }}">
										<i class="ph-duotone ph-shopping-cart me-2"></i>
										{{ $menuByCode['Purchase Requisitions']->menu_name }}
									</a>
								</li>
								@endif

							</ul>
						</li>
					@endif

					{{-- Compliance Center --}}
					@php
						$compliance = $menuByCode->get('Compliance Center');

						$complianceChildren = $compliance
							? ($menuByParent[$compliance->id] ?? collect())
							: collect();

						$showCompliance = $complianceChildren->contains(function ($menu) use ($userMenu, $permissions) {
							return canAccess($menu->code, $userMenu, $permissions);
						});
					@endphp
					@if($showCompliance)

						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-chat-circle-text"></i>
								</span>
								<span class="pc-mtext">{{ $compliance->menu_name }}</span>
								<span class="pc-arrow mt-1">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								{{-- Compliance Overview --}}
								@if(canAccess('Compliance Overview', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('ca.CompliancesList') }}">
										<i class="ph-duotone ph-chart-line me-2"></i>
										{{ $menuByCode['Compliance Overview']->menu_name }}
									</a>
								</li>
								@endif


								{{-- Compliance Calendar --}}
								@if(canAccess('Compliance Calendar', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('admin.compliance-reminder-list') }}">
										<i class="ph-duotone ph-calendar-check me-2"></i>
										{{ $menuByCode['Compliance Calendar']->menu_name }}
									</a>
								</li>
								@endif


								{{-- GST Returns & Reports --}}
								@if(canAccess('GST Returns & Reports', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-receipt fs-6"></i>
										</span>
										<span>{{ $menuByCode['GST Returns & Reports']->menu_name }}</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTProfile') }}">GST Profile & Registration</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.OtherGSTProfile') }}">GSTIN Verification</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReturns') }}">GST Returns & Filing</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTReports') }}">GST Insights & Reports</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('User.GSTComplianceSupport') }}">GST Compliance Support</a></li>
									</ul>
								</li>
								@endif


								{{-- TDS Management --}}
								@if(canAccess('TDS Management', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.tds_returns_filing') }}">
										<i class="ph-duotone ph-file-arrow-down me-2"></i>
										{{ $menuByCode['TDS Management']->menu_name }}
									</a>
								</li>
								@endif


								{{-- Income Tax Returns --}}
								@if(canAccess('Income Tax Returns', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ url('/itr/list') }}">
										<i class="ph-duotone ph-file me-2"></i>
										{{ $menuByCode['Income Tax Returns']->menu_name }}
									</a>
								</li>
								@endif


								{{-- Labour Compliance --}}
								@if(canAccess('Labour Compliance', $userMenu, $permissions))
								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-users-three"></i>
										</span>
										<span>{{ $menuByCode['Labour Compliance']->menu_name }}</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('user.ptax_management_list') }}">Professional Tax</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.pf_management_list') }}">Provident Fund</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.esi_management_list') }}">ESI Payments</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.shop_registration') }}">Shops Registration</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.lwf_compliance') }}">Labour Welfare Fund (LWF)</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('user.gratuity_compliance') }}">Gratuity Payment</a></li>
									</ul>
								</li>
								@endif


								{{-- MCA / ROC Filing --}}
								@if(canAccess('MCA / ROC Filing', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ url('/mca-roc/list') }}">
										<i class="ph-duotone ph-buildings me-2"></i>
										{{ $menuByCode['MCA / ROC Filing']->menu_name }}
									</a>
								</li>
								@endif


								{{-- MSME Compliance --}}
								@if(canAccess('MSME Compliance', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ url('/msme/list') }}">
										<i class="ph-duotone ph-factory me-2"></i>
										{{ $menuByCode['MSME Compliance']->menu_name }}
									</a>
								</li>
								@endif
							</ul>
						</li>
					@endif

					{{-- Reports & Insights --}}
					@php
						$reportsMenu = $menuByCode->get('Reports & Insights');

						$reportsChildren = $reportsMenu
							? ($menuByParent[$reportsMenu->id] ?? collect())
							: collect();

						$showReportsMenu = $reportsChildren->contains(function ($menu) use ($userMenu, $permissions) {
							return canAccess($menu->code, $userMenu, $permissions);
						});
					@endphp

					@if($showReportsMenu)
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-chart-bar"></i>
								</span>
								<span class="pc-mtext">{{ $reportsMenu->menu_name }}</span>
								<span class="pc-arrow mt-1">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								@if(canAccess('Account Ledgers', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.Ledger') }}">
										<i class="ph-duotone ph-book-open-text"></i>
										<span>{{ $menuByCode['Account Ledgers']->menu_name }}</span>
									</a>
								</li>
								@endif

								@if(canAccess('Trial Balance', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.TrialBalanceReport') }}">
										<i class="ph-duotone ph-scales"></i>
										<span>{{ $menuByCode['Trial Balance']->menu_name }}</span>
									</a>
								</li>
								@endif

								@if(canAccess('Profit & Loss', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.ProfitLossReport') }}">
										<i class="ph-duotone ph-trend-up"></i>
										<span>{{ $menuByCode['Profit & Loss']->menu_name }}</span>
									</a>
								</li>
								@endif

								@if(canAccess('Balance Sheet', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.BalanceSheetReport') }}">
										<i class="ph-duotone ph-bank"></i>
										<span>{{ $menuByCode['Balance Sheet']->menu_name }}</span>
									</a>
								</li>
								@endif

								@if(canAccess('Cashflow Statement', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.cashflow') }}">
										<i class="ph-duotone ph-arrows-left-right"></i>
										<span>{{ $menuByCode['Cashflow Statement']->menu_name }}</span>
									</a>
								</li>
								@endif

								@if(canAccess('Payroll Reports', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.PayrollReports') }}">
										<i class="ph-duotone ph-users-three"></i>
										<span>{{ $menuByCode['Payroll Reports']->menu_name }}</span>
									</a>
								</li>
								@endif

								@if(canAccess('Business Health Check-up', $userMenu, $permissions))
								<li class="pc-item">
									<a href="{{ route('admin.company.checks') }}" class="pc-link">
										<i class="ph-duotone ph-heartbeat"></i>
										<span>{{ $menuByCode['Business Health Check-up']->menu_name }}</span>
									</a>
								</li>
								@endif

							</ul>
						</li>
					@endif

					{{-- Documents & Audit --}}
					@php
						$documentsMenu = $menuByCode->get('Documents & Audit');

						$documentsChildren = $documentsMenu
							? ($menuByParent[$documentsMenu->id] ?? collect())
							: collect();

						$showDocumentsMenu = $documentsChildren->contains(function ($menu) use ($userMenu, $permissions) {
							return canAccess($menu->code, $userMenu, $permissions);
						});
					@endphp

					@if($showDocumentsMenu)
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-folder-lock"></i>
								</span>
								<span class="pc-mtext">{{ $documentsMenu->menu_name }}</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								@if(canAccess('Document Locker', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.DocLocker') }}">
										<i class="ph-duotone ph-folder-lock me-2"></i>
										{{ $menuByCode['Document Locker']->menu_name }}
									</a>
								</li>
								@endif

								@if(canAccess('Audit Trail', $userMenu, $permissions))
								<li class="pc-item">
									<a class="pc-link" href="{{ route('user.audit.logs') }}">
										<i class="ph-duotone ph-shield-check me-2"></i>
										{{ $menuByCode['Audit Trail']->menu_name }}
									</a>
								</li>
								@endif

							</ul>
						</li>
					@endif

					{{-- MSME Benefits Hub --}}
					@php
						$msmeMenu = $menuByCode->get('MSME Benefits Hub');
					@endphp

					@if($msmeMenu && canAccess('MSME Benefits Hub', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-factory"></i>
								</span>
								<span class="pc-mtext">{{ $msmeMenu->menu_name }}</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">
								<li class="pc-item"><a class="pc-link" href="{{ route('user.msme.discover-schemes') }}"><i class="ph-duotone ph-compass me-2"></i>Discover Schemes</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.msme.eligibility-checker') }}"><i class="ph-duotone ph-check-circle me-2"></i>Eligibility Checker</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.msme.loan-and-subsidies') }}"><i class="ph-duotone ph-bank me-2"></i>Loan & Subsidy</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.msme.startup-benefits') }}"><i class="ph-duotone ph-rocket-launch me-2"></i>Startup Benefits</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.msme.govt-updates') }}"><i class="ph-duotone ph-newspaper me-2"></i>Govt Updates</a></li>
								<li class="pc-item"><a class="pc-link" href="{{ route('user.msme.consultant-assistance') }}"><i class="ph-duotone ph-users-three me-2"></i>Consultant Assistance</a></li>
							</ul>
						</li>
					@endif

					{{-- Professional Services --}}
					@php
						$professionalMenu = $menuByCode->get('Professional Services');
					@endphp

					@if($professionalMenu && canAccess('Professional Services', $userMenu, $permissions))
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon">
									<i class="ph-duotone ph-briefcase"></i>
								</span>
								<span class="pc-mtext">{{ $professionalMenu->menu_name }}</span>
								<span class="pc-arrow">
									<i data-feather="chevron-right"></i>
								</span>
							</a>

							<ul class="pc-submenu">

								<li class="pc-item">
									<a href="{{ url('/startup-filing/list') }}" class="pc-link">
										<i class="ph-duotone ph-rocket-launch"></i>
										<span>Startup Incubator Services</span>
									</a>
								</li>

								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-shield-check"></i>
										</span>
										<span class="pc-mtext">Compliance Advisory</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

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
										<li class="pc-item"><a class="pc-link" href="https://360bizservice.com/" target="_blank">MSME Schemes & Loans Assistance</a></li>
									</ul>
								</li>

								<li class="pc-item pc-hasmenu">
									<a href="#!" class="pc-link">
										<span class="pc-micon">
											<i class="ph-duotone ph-code"></i>
										</span>
										<span class="pc-mtext">Digital Solutions</span>
										<span class="pc-arrow">
											<i data-feather="chevron-right"></i>
										</span>
									</a>

									<ul class="pc-submenu">
										<li class="pc-item"><a class="pc-link" href="{{ route('web-and-app') }}" target="_blank">Website & App Development</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('ui-and-ux') }}" target="_blank">Professional UI/UX Design</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('crm-erp-implementation') }}" target="_blank">CRM/ERP Implementation</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('pwa') }}" target="_blank">Progressive Web Applications</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('seo') }}" target="_blank">SEO & Search Optimization</a></li>
										<li class="pc-item"><a class="pc-link" href="{{ route('smm') }}" target="_blank">Digital Marketing Strategy</a></li>
									</ul>
								</li>

								<li class="pc-item">
									<a class="pc-link" href="#">
										<i class="ph-duotone ph-handshake"></i>
										<span>Partner Services</span>
									</a>
								</li>

							</ul>
						</li>
					@endif



				</ul>
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
						<img src="../assets/images/user/avatar-1.jpg" alt="user-image" class="user-avtar wid-45 rounded-circle" />
					</div>
					<div class="flex-grow-1 ms-3">
						<div class="dropdown">
							<a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,20">
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
										<a href="{{ route('editProfile') }}" class="pc-user-links">
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



