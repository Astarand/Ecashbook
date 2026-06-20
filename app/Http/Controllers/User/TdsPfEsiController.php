<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Depertments;
use App\Models\Designations;
use App\Models\Employees;
use App\Models\Location;
use App\Models\WorkFromHome;
use App\Models\Company_profiles;
use App\Models\EmployeeRating;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DatePeriod;
use DateInterval;
// use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class TdsPfEsiController extends Controller
{
    // public function tds_returns_filing(request $request)
	// {
	// 	$userId = Auth::id();
	// 	//start ca-accountant access
	// 	$req_type = 0;
	// 	if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
	// 		$userId = getAccessCompanyId($request);
	// 		$req_type = 1;
	// 	}
	// 	//end ca-accountant access
	// 	$currentMonth = now()->month;
	// 	$currentYear  = now()->year;

	// 	$employees = DB::table('employees')
	// 		->join('users', 'users.id', '=', 'employees.empId')

	// 		// ✅ INNER JOIN – only employees with payslip this month
	// 		->join('user_payslip', function ($join) use ($currentMonth, $currentYear) {
	// 			$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
	// 				->whereMonth('user_payslip.date', $currentMonth)
	// 				->whereYear('user_payslip.date', $currentYear);
	// 		})

	// 		// TDS slab (Salary)
	// 		->leftJoin('tds_tax_slab', function ($join) {
	// 			$join->where('tds_tax_slab.tds_slab_name', 'Salary');
	// 		})

	// 		->where('employees.added_by', $userId)
	// 		->where('employees.tds_applicable', 1)

	// 		->select(
	// 			'employees.employee_id',
	// 			'employees.pan_number',
	// 			'employees.total_addition',
	// 			'employees.tds',
	// 			'users.name',
	// 			'users.email',

	// 			'user_payslip.date as payment_date',
	// 			'user_payslip.payslip_no',

	// 			'tds_tax_slab.tds_slab_section',
	// 			'tds_tax_slab.tds_slab_rate'
	// 		)
	// 		->get();

	// 	return view('User.tds-returns-filing', compact('employees', 'req_type'));
	// }



	public function tds_returns_filing(Request $request)
	{
		$userId = Auth::id();

		// CA / CA Employee Accountant Access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}

		// User Employee Accountant Access
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		}

		/*
		|--------------------------------------------------------------------------
		| Employee TDS Records
		|--------------------------------------------------------------------------
		*/
		$employeeRecords = DB::table('employees as e')
			->leftJoin('users as u', 'u.id', '=', 'e.empId')
			->leftJoin('user_payslip as up', 'up.user_emp_id', '=', 'e.empId')
			->where('e.added_by', $userId)
			->where('e.tds_applicable', 1)
			->select(
				'e.*',
				'u.name',
				'u.email',
				'up.emp_salary_slip_response'
			)
			->orderByDesc('up.id')
			->get()
			->map(function ($row) {

				$response = !empty($row->emp_salary_slip_response)
					? json_decode($row->emp_salary_slip_response, true)
					: [];

				$row->payslip_no = data_get(
					$response,
					'visible_data.payslip_no'
				);

				$row->payment_date = data_get(
					$response,
					'visible_data.generate_date'
				);

				$row->total_addition = data_get(
					$response,
					'visible_data.salary_details.total_addition',
					$row->total_addition ?? 0
				);

				$row->tds_amount = data_get(
					$response,
					'visible_data.salary_details.tds',
					0
				);

				$row->pan_number = data_get(
					$response,
					'visible_data.employee_details.pan_number',
					$row->pan_number
				);

				// Required by Blade modal calculation
				$row->tds = $row->tds_slab_rate ?? 0;

				$row->quarter = $row->quarter ?? 'N/A';
				$row->payment_type = 'Salary';

				return $row;
			});

		/*
		|--------------------------------------------------------------------------
		| Vendor TDS Records expenses
		|--------------------------------------------------------------------------
		*/
		$vendorRecords = DB::table('expenses as ex')
			->leftJoin('vendors as v', 'v.id', '=', 'ex.vendor_id')
			->where('ex.added_by', $userId)
			->where('ex.tds_applicable', 'yes')
			->select(
				'ex.*',
				'v.vendor_name',
				'v.vendor_email',
				'v.vendor_pan',
				'v.vendor_id',
			)
			->get()
			->map(function ($row) {

				// Convert vendor data to employee-style fields
				$row->employee_id = $row->vendor_id;

				$row->name = $row->vendor_name;
				$row->email = $row->vendor_email;
				$row->pan_number = $row->vendor_pan;

				$row->tds_slab_section = $row->tds_section;
				$row->tds_slab_rate = $row->tds_rate;

				$row->total_addition = $row->expense_amt;

				$row->payment_date = $row->expense_date;

				$row->payslip_no = !empty($row->tds_id)
					? $row->tds_id
					: 'N/A';

				$row->quarter = 'N/A';

				$row->tds_amount = $row->tds_amount ?? 0;

				// Required by Blade modal calculation
				$row->tds = $row->tds_rate ?? 0;
				$row->payment_type = 'Expense';

				return $row;
			});

		/*
		|--------------------------------------------------------------------------
		| Liability TDS Records
		|--------------------------------------------------------------------------
		*/
		$liabilityRecords = DB::table('current_liabilities as cl')
			->leftJoin('company_profiles as cp', 'cp.userId', '=', 'cl.added_by')
			->where('cl.added_by', $userId)
			->where('cl.stl_tds_applicable', 'yes')
			->select(
				'cl.*',
				'cp.comp_name',
				'cp.comp_email',
				'cp.comp_pan_no'
			)
			->get()
			->map(function ($row) {

				// Map liability data to employee-style fields

				$row->employee_id = 'LIAB-' . $row->liabilities_id;

				$row->name = $row->stl_lender_name ?: ($row->comp_name ?? 'N/A');

				$row->email = $row->comp_email ?? null;

				$row->pan_number = $row->comp_pan_no ?? null;

				$row->tds_slab_section = $row->stl_tds_section;

				$row->tds_slab_rate = $row->stl_tds_rate;

				// Gross amount on which TDS is applicable
				$row->total_addition = $row->stl_interest_amount ?? $row->stl_amount_received ?? 0;

				$row->payment_date = $row->stl_disbursement_date
					?: $row->created_at;

				$row->payslip_no = $row->stl_reference ?: 'N/A';

				$row->quarter = 'N/A';

				$row->tds_amount = $row->stl_tds_amount ?? 0;

				// Required by existing Blade modal calculation
				$row->tds = $row->stl_tds_rate ?? 0;
				$row->payment_type = 'Liability';

				return $row;
			});

			/*
			|--------------------------------------------------------------------------
			| Asset TDS Records
			|--------------------------------------------------------------------------
			*/
			$assetRecords = DB::table('assets as a')
				->leftJoin('vendors as v', 'v.id', '=', 'a.vendor_id')
				->where('a.added_by', $userId)
				->where('a.tds_applicable', 'yes')
				->select(
					'a.*',
					'v.vendor_name',
					'v.vendor_email',
					'v.vendor_pan',
					'v.vendor_id'
				)
				->get()
				->map(function ($row) {

					// Asset/Vendor ID
					$row->employee_id = !empty($row->asset_id)
						? $row->asset_id
						: 'N/A';

					// Name
					$row->name = !empty($row->vendor_name)
						? $row->vendor_name
						: (!empty($row->asset_name) ? $row->asset_name : 'N/A');

					// Email
					$row->email = !empty($row->vendor_email)
						? $row->vendor_email
						: 'N/A';

					// PAN
					$row->pan_number = !empty($row->vendor_pan)
						? $row->vendor_pan
						: 'N/A';

					// Section
					$row->tds_slab_section = !empty($row->asset_category)
						? $row->asset_category
						: 'N/A';

					// TDS Rate
					$row->tds_slab_rate = !empty($row->tds_percent)
						? $row->tds_percent
						: 0;

					// Gross Amount
					$row->total_addition = !empty($row->invoice_value)
						? $row->invoice_value
						: 0;

					// Payment Date
					$row->payment_date = !empty($row->purchase_date)
						? $row->purchase_date
						: (!empty($row->invoice_date)
							? $row->invoice_date
							: $row->created_at);

					// Challan / TDS Ref
					$row->payslip_no = !empty($row->tds_id)
						? $row->tds_id
						: 'N/A';

					// Quarter
					$row->quarter = 'N/A';

					// TDS Amount
					$row->tds_amount = !empty($row->tds_amt)
						? $row->tds_amt
						: 0;

					// Required by existing Blade
					$row->tds = $row->tds_slab_rate;
					$row->payment_type = 'Asset';

					return $row;
				});

				/*
				|--------------------------------------------------------------------------
				| Income TDS Records
				|--------------------------------------------------------------------------
				*/
				$incomeRecords = DB::table('income')
					->where('addBy', $userId)
					->where('tds_applicable', 'yes')
					->where('status', 1)
					->get()
					->map(function ($row) {

						$row->employee_id = !empty($row->invoice_no)
							? $row->invoice_no
							: 'N/A';

						$row->name = !empty($row->customer_name)
							? $row->customer_name
							: (!empty($row->name) ? $row->name : 'N/A');

						$row->email = 'N/A';

						$row->pan_number = 'N/A';

						$row->tds_slab_section = !empty($row->incomeType)
							? $row->incomeType
							: 'N/A';

						$row->tds_slab_rate = !empty($row->tds_percentage)
							? $row->tds_percentage
							: 0;

						$row->total_addition = !empty($row->amount)
							? $row->amount
							: 0;

						$row->payment_date = !empty($row->dateInput)
							? $row->dateInput
							: $row->created_at;

						$row->payslip_no = !empty($row->tds_id)
							? $row->tds_id
							: 'N/A';

						$row->quarter = 'N/A';

						$row->tds_amount = !empty($row->tds_amount)
							? $row->tds_amount
							: 0;

						// Required by existing blade
						$row->tds = $row->tds_slab_rate;

						$row->payment_type = 'Other Income';

						return $row;
					});

		/*
		|--------------------------------------------------------------------------
		| Merge Employee + Vendor Records (Expenses) + Liability Records + Asset Records + Income Records
		|--------------------------------------------------------------------------
		*/
		$employees = $employeeRecords
			->concat($vendorRecords)
			->concat($liabilityRecords)
			->concat($assetRecords)
			->concat($incomeRecords)
			->sortByDesc('payment_date')
			->values();

		return view(
			'User.tds-returns-filing',
			compact('employees', 'req_type')
		);
	}

	private function getTdsRecords($userId)
	{
		/*
		|--------------------------------------------------------------------------
		| Employee TDS Records
		|--------------------------------------------------------------------------
		*/
		$employeeRecords = DB::table('employees as e')
			->leftJoin('users as u', 'u.id', '=', 'e.empId')
			->leftJoin('user_payslip as up', 'up.user_emp_id', '=', 'e.empId')
			->where('e.added_by', $userId)
			->where('e.tds_applicable', 1)
			->select(
				'e.*',
				'u.name',
				'u.email',
				'up.emp_salary_slip_response'
			)
			->orderByDesc('up.id')
			->get()
			->map(function ($row) {

				$response = !empty($row->emp_salary_slip_response)
					? json_decode($row->emp_salary_slip_response, true)
					: [];

				$row->payslip_no = data_get($response, 'visible_data.payslip_no', 'N/A');
				$row->payment_date = data_get($response, 'visible_data.generate_date');
				$row->total_addition = data_get(
					$response,
					'visible_data.salary_details.total_addition',
					0
				);
				$row->tds_amount = data_get(
					$response,
					'visible_data.salary_details.tds',
					0
				);
				$row->pan_number = data_get(
					$response,
					'visible_data.employee_details.pan_number',
					'N/A'
				);

				$row->tds = $row->tds_slab_rate ?? 0;
				$row->quarter = 'N/A';
				$row->payment_type = 'Salary';

				return $row;
			});

		/*
		|--------------------------------------------------------------------------
		| Expense / Vendor Records
		|--------------------------------------------------------------------------
		*/
		$vendorRecords = DB::table('expenses as ex')
			->leftJoin('vendors as v', 'v.id', '=', 'ex.vendor_id')
			->where('ex.added_by', $userId)
			->where('ex.tds_applicable', 'yes')
			->select(
				'ex.*',
				'v.vendor_name',
				'v.vendor_email',
				'v.vendor_pan',
				'v.vendor_id'
			)
			->get()
			->map(function ($row) {

				$row->employee_id = $row->vendor_id ?? 'N/A';
				$row->name = $row->vendor_name ?? 'N/A';
				$row->email = $row->vendor_email ?? 'N/A';
				$row->pan_number = $row->vendor_pan ?? 'N/A';

				$row->tds_slab_section = $row->tds_section ?? 'N/A';
				$row->tds_slab_rate = $row->tds_rate ?? 0;

				$row->total_addition = $row->expense_amt ?? 0;
				$row->payment_date = $row->expense_date;
				$row->payslip_no = $row->tds_id ?? 'N/A';

				$row->quarter = 'N/A';
				$row->tds_amount = $row->tds_amount ?? 0;
				$row->tds = $row->tds_rate ?? 0;
				$row->payment_type = 'Expense';

				return $row;
			});

		/*
		|--------------------------------------------------------------------------
		| Liability Records
		|--------------------------------------------------------------------------
		*/
		$liabilityRecords = DB::table('current_liabilities as cl')
			->leftJoin('company_profiles as cp', 'cp.userId', '=', 'cl.added_by')
			->where('cl.added_by', $userId)
			->where('cl.stl_tds_applicable', 'yes')
			->select(
				'cl.*',
				'cp.comp_name',
				'cp.comp_email',
				'cp.comp_pan_no'
			)
			->get()
			->map(function ($row) {

				$row->employee_id = 'LIAB-' . ($row->liabilities_id ?? 'N/A');
				$row->name = $row->stl_lender_name ?: ($row->comp_name ?? 'N/A');
				$row->email = $row->comp_email ?? 'N/A';
				$row->pan_number = $row->comp_pan_no ?? 'N/A';

				$row->tds_slab_section = $row->stl_tds_section ?? 'N/A';
				$row->tds_slab_rate = $row->stl_tds_rate ?? 0;

				$row->total_addition =
					$row->stl_interest_amount ??
					$row->stl_amount_received ??
					0;

				$row->payment_date =
					$row->stl_disbursement_date ??
					$row->created_at;

				$row->payslip_no = $row->stl_reference ?? 'N/A';

				$row->quarter = 'N/A';
				$row->tds_amount = $row->stl_tds_amount ?? 0;
				$row->tds = $row->stl_tds_rate ?? 0;
				$row->payment_type = 'Liability';

				return $row;
			});

		/*
		|--------------------------------------------------------------------------
		| Asset Records
		|--------------------------------------------------------------------------
		*/
		$assetRecords = DB::table('assets as a')
			->leftJoin('vendors as v', 'v.id', '=', 'a.vendor_id')
			->where('a.added_by', $userId)
			->where('a.tds_applicable', 'yes')
			->select(
				'a.*',
				'v.vendor_name',
				'v.vendor_email',
				'v.vendor_pan',
				'v.vendor_id'
			)
			->get()
			->map(function ($row) {

				$row->employee_id = $row->asset_id ?? 'N/A';
				$row->name = $row->vendor_name ?? $row->asset_name ?? 'N/A';
				$row->email = $row->vendor_email ?? 'N/A';
				$row->pan_number = $row->vendor_pan ?? 'N/A';

				$row->tds_slab_section = $row->asset_category ?? 'N/A';
				$row->tds_slab_rate = $row->tds_percent ?? 0;

				$row->total_addition = $row->invoice_value ?? 0;

				$row->payment_date =
					$row->purchase_date ??
					$row->invoice_date ??
					$row->created_at;

				$row->payslip_no = $row->tds_id ?? 'N/A';

				$row->quarter = 'N/A';
				$row->tds_amount = $row->tds_amt ?? 0;
				$row->tds = $row->tds_percent ?? 0;
				$row->payment_type = 'Asset';

				return $row;
			});

		/*
		|--------------------------------------------------------------------------
		| Income Records
		|--------------------------------------------------------------------------
		*/
		$incomeRecords = DB::table('income')
			->where('addBy', $userId)
			->where('tds_applicable', 'yes')
			->where('status', 1)
			->get()
			->map(function ($row) {

				$row->employee_id = $row->invoice_no ?? 'N/A';
				$row->name = $row->customer_name ?? $row->name ?? 'N/A';

				$row->email = 'N/A';
				$row->pan_number = 'N/A';

				$row->tds_slab_section = $row->incomeType ?? 'N/A';
				$row->tds_slab_rate = $row->tds_percentage ?? 0;

				$row->total_addition = $row->amount ?? 0;

				$row->payment_date =
					$row->dateInput ??
					$row->created_at;

				$row->payslip_no = $row->tds_id ?? 'N/A';

				$row->quarter = 'N/A';
				$row->tds_amount = $row->tds_amount ?? 0;
				$row->tds = $row->tds_percentage ?? 0;
				$row->payment_type = 'Other Income';

				return $row;
			});

		return $employeeRecords
			->concat($vendorRecords)
			->concat($liabilityRecords)
			->concat($assetRecords)
			->concat($incomeRecords)
			->sortByDesc('payment_date')
			->values();
	}

	public function download_tds_returns(Request $request)
	{
		$userId = Auth::id();

		// CA / CA Employee Accountant Access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}

		// User Employee Accountant Access
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		}

		/*
		|--------------------------------------------------------------------------
		| IMPORTANT
		|--------------------------------------------------------------------------
		| We must fetch all records first and then apply filters in-memory using Collections
		|
		*/
		$employees = $this->getTdsRecords($userId);
		// -----------------------------
		// Apply Filters
		// -----------------------------

		if (!empty(trim($request->name))) {
			$name = strtolower(trim($request->name));

			$employees = $employees->filter(function ($row) use ($name) {
				return str_contains(
					strtolower($row->name ?? ''),
					$name
				);
			});
		}

		if (!empty(trim($request->vendor_id))) {
			$vendorId = strtolower(trim($request->vendor_id));

			$employees = $employees->filter(function ($row) use ($vendorId) {
				return str_contains(
					strtolower($row->employee_id ?? ''),
					$vendorId
				);
			});
		}

		// -----------------------------
		// Month Filter
		// -----------------------------

		if (
			$request->tdsPeriodType == 'month' &&
			!empty($request->month)
		) {

			$monthNumber = date(
				'm',
				strtotime($request->month)
			);

			$employees = $employees->filter(function ($row) use ($monthNumber) {

				if (empty($row->payment_date)) {
					return false;
				}

				return date(
					'm',
					strtotime($row->payment_date)
				) == $monthNumber;
			});
		}

		// -----------------------------
		// Quarter Filter
		// -----------------------------

		if (
			$request->tdsPeriodType == 'quarter' &&
			!empty($request->quarter)
		) {

			$employees = $employees->filter(function ($row) use ($request) {

				if (empty($row->payment_date)) {
					return false;
				}

				$month = (int) date(
					'n',
					strtotime($row->payment_date)
				);

				switch ($request->quarter) {

					case 'q1':
						return $month >= 4 && $month <= 6;

					case 'q2':
						return $month >= 7 && $month <= 9;

					case 'q3':
						return $month >= 10 && $month <= 12;

					case 'q4':
						return $month >= 1 && $month <= 3;
				}

				return false;
			});
		}

		// -----------------------------
		// Financial Year Filter
		// -----------------------------

		if (
			$request->tdsPeriodType == 'year' &&
			!empty($request->year)
		) {

			[$startYear, $endYear] = explode(
				'-',
				$request->year
			);

			$startDate = $startYear . '-04-01';
			$endDate   = $endYear . '-03-31';

			$employees = $employees->filter(function ($row) use (
				$startDate,
				$endDate
			) {

				if (empty($row->payment_date)) {
					return false;
				}

				$date = date(
					'Y-m-d',
					strtotime($row->payment_date)
				);

				return $date >= $startDate &&
					$date <= $endDate;
			});
		}

		// -----------------------------
		// Prepare Download Data
		// -----------------------------

		$employees = $employees->values();

		$exportData = [];

		foreach ($employees as $index => $row) {

			$exportData[] = [

				'#' => $index + 1,

				'Vendor/ Employee ID' => $row->employee_id ?? 'N/A',

				'Name' => $row->name ?? 'N/A',

				'Pan' => $row->pan_number ?? 'N/A',

				'Section' => $row->tds_slab_section ?? 'N/A',

				'Nature Of Payment' => $row->payment_type ?? 'N/A',

				'Gross Amount' => $row->total_addition ?? 0,

				'TDS Rate (%)' => $row->tds_slab_rate ?? 0,

				'TDS Deduction' => $row->tds_amount ?? 0,

				'Challan No' => $row->payslip_no ?? 'N/A',

				'Payment Date' => !empty($row->payment_date)
					? date('d-m-Y', strtotime($row->payment_date))
					: 'N/A',

				'Return Quarter' => $row->quarter ?? 'N/A',

				'Remarks' => $row->remarks ?? 'Paid',
			];
		}

		// -----------------------------
		// Excel Download
		// -----------------------------

		if ($request->download_format == 'excel') {
			$fileName = 'tds_returns_' . date('d-m-Y') . '.xlsx';
			return Excel::download(
				new \App\Exports\TdsReturnsExport($exportData),
				$fileName
			);
		}

		// -----------------------------
		// PDF Download
		// -----------------------------

		if ($request->download_format == 'pdf') {

			$pdf = PDF::loadView(  
				'User.tds_filter_pdf',
				['rows' => $exportData]
			);

			$pdf->setPaper('A4', 'landscape');

			$fileName = 'tds_returns_' . date('d-m-Y') . '.pdf';

			return $pdf->download($fileName);
		}

		return back()->with(
			'error',
			'Please select a download format.'
		);
	}

	public function pf_management_list(request $request)
	{
		$userId = Auth::id();
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access
		$currentMonth = now()->month;
		$currentYear  = now()->year;

		$employees = DB::table('employees')
			->join('users', 'users.id', '=', 'employees.empId')

			// Only employees with payslip for current month
			->join('user_payslip', function ($join) use ($currentMonth, $currentYear) {
				$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
					->whereMonth('user_payslip.date', $currentMonth)
					->whereYear('user_payslip.date', $currentYear);
			})

			->where('employees.added_by', $userId)
			->where('employees.epf_applicable', 1)

			->select(
				'employees.employee_id',
				'employees.epf_no',              // ✅ UAN
				'employees.total_addition',      // ✅ Gross Wages
				'employees.provident_fund',      // ✅ PF Employee Contribution

				'users.name',

				'user_payslip.payslip_no',       // ✅ Challan No
				'user_payslip.date as payment_date'
			)
			->get();

		return view('User.pf-management-list', compact('employees', 'req_type'));
	}

	public function download_pf_filing(Request $request)
	{
		$userId = Auth::id();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$periodType = $request->input('pfPeriodType', 'month');

		$query = DB::table('employees')
			->join('users', 'users.id', '=', 'employees.empId')

			// ================= PAYSLIP JOIN =================
			->join('user_payslip', function ($join) use ($request, $periodType) {

				// ---------- MONTHLY ----------
				if ($periodType === 'month') {

					$months = [
						'january'=>1,'february'=>2,'march'=>3,'april'=>4,'may'=>5,'june'=>6,
						'july'=>7,'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12
					];

					$month = $months[strtolower($request->month)] ?? now()->month;

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereMonth('user_payslip.date', $month)
						->whereYear('user_payslip.date', now()->year);
				}

				// ---------- YEARLY (FINANCIAL YEAR) ----------
				elseif ($periodType === 'year' && preg_match('/(\d{4})-(\d{4})/', $request->year, $m)) {

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereBetween('user_payslip.date', [
							$m[1].'-04-01',
							$m[2].'-03-31'
						]);
				}

				// ---------- QUARTER ----------
				else {

					$quarters = [
						'q1' => [4,5,6],
						'q2' => [7,8,9],
						'q3' => [10,11,12],
						'q4' => [1,2,3],
					];

					$months = $quarters[$request->quarter] ?? [];

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereIn(DB::raw('MONTH(user_payslip.date)'), $months)
						->whereYear('user_payslip.date', now()->year);
				}
			})

			->where('employees.added_by', $userId)
			->where('employees.epf_applicable', 1);

		// ================= OPTIONAL FILTERS =================
		if ($request->filled('name')) {
			$query->where('users.name', 'like', '%'.$request->name.'%');
		}

		if ($request->filled('employee_id')) {
			$query->where('employees.employee_id', 'like', '%'.$request->employee_id.'%');
		}

		// ================= DATA =================
		$employees = $query->select(
			'employees.employee_id',
			'employees.epf_no',
			'employees.total_addition',
			'employees.provident_fund',
			'users.name',
			'user_payslip.date as payment_date',
			'user_payslip.payslip_no'
		)->get();

		// ================= EXCEL =================
		if ($request->download_format === 'excel') {

			$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="pf_filing_'.now()->format('YmdHis').'.csv"',
			];

			return response()->stream(function () use ($employees) {

				$out = fopen('php://output', 'w');

				fputcsv($out, [
					'Employee ID','Name','UAN','Payment Date','Challan No',
					'Gross Wages','Employee PF','Employer PF'
				]);

				foreach ($employees as $e) {
					fputcsv($out, [
						$e->employee_id,
						$e->name,
						$e->epf_no,
						$e->payment_date,
						$e->payslip_no,
						$e->total_addition,
						$e->provident_fund,
						$e->provident_fund
					]);
				}

				fclose($out);
			}, 200, $headers);
		}

		// ================= PDF =================
		$html = '
		<style>
			@page { margin: 12px; }
			body { font-family: DejaVu Sans, Arial; font-size: 9px; }
			table { width: 100%; border-collapse: collapse; }
			th, td { border: 1px solid #000; padding: 4px; }
			th { background: #f2f2f2; }
			h3 { text-align: center; margin-bottom: 10px; }
		</style>

		<h3>PF Filing Statement</h3>

		<table>
			<thead>
				<tr>
					<th>Employee ID</th>
					<th>Name</th>
					<th>UAN</th>
					<th>Payment Date</th>
					<th>Challan No</th>
					<th>Gross Wages</th>
					<th>Employee PF</th>
					<th>Employer PF</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($employees as $e) {
			$html .= '
			<tr>
				<td>'.$e->employee_id.'</td>
				<td>'.$e->name.'</td>
				<td>'.$e->epf_no.'</td>
				<td>'.$e->payment_date.'</td>
				<td>'.$e->payslip_no.'</td>
				<td>'.$e->total_addition.'</td>
				<td>'.$e->provident_fund.'</td>
				<td>'.$e->provident_fund.'</td>
			</tr>';
		}

		$html .= '</tbody></table>';

		return PDF::loadHTML($html)
			->setPaper('a4', 'landscape')
			->download('pf_filing_'.now()->format('YmdHis').'.pdf');
	}

	public function esi_management_list(request $request)
	{
		$userId = Auth::id();
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access
		$currentMonth = now()->month;
		$currentYear  = now()->year;

		$employees = DB::table('employees')
			->join('users', 'users.id', '=', 'employees.empId')

			// ✅ Only employees with payslip for current month
			->join('user_payslip', function ($join) use ($currentMonth, $currentYear) {
				$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
					->whereMonth('user_payslip.date', $currentMonth)
					->whereYear('user_payslip.date', $currentYear);
			})

			->where('employees.added_by', $userId)
			->where('employees.esic_applicable', 1)

			->select(
				'employees.employee_id',
				'employees.esic_no',           // ✅ ESIC Number
				'employees.total_addition',    // ✅ Gross Wages
				'employees.esi',               // ✅ ESI Amount (Employee)

				'users.name',

				'user_payslip.payslip_no',     // ✅ Challan No
				'user_payslip.date as payment_date'
			)
			->get();
			// echo "<pre>"; print_r($employees); echo "</pre>"; exit;

		return view('User.esi-management-list', compact('employees', 'req_type'));
	}

	public function download_esi_filing(Request $request)
	{
		$userId = Auth::id();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$periodType = $request->input('esiPeriodType', 'month');

		$query = DB::table('employees')
			->join('users', 'users.id', '=', 'employees.empId')

			// ================= PAYSLIP JOIN =================
			->join('user_payslip', function ($join) use ($request, $periodType) {

				// ---------- MONTHLY ----------
				if ($periodType === 'month') {

					$months = [
						'january'=>1,'february'=>2,'march'=>3,'april'=>4,'may'=>5,'june'=>6,
						'july'=>7,'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12
					];

					$month = $months[strtolower($request->month)] ?? now()->month;

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereMonth('user_payslip.date', $month)
						->whereYear('user_payslip.date', now()->year);
				}

				// ---------- YEARLY (FINANCIAL YEAR) ----------
				elseif ($periodType === 'year' && preg_match('/(\d{4})-(\d{4})/', $request->year, $m)) {

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereBetween('user_payslip.date', [
							$m[1].'-04-01',
							$m[2].'-03-31'
						]);
				}

				// ---------- QUARTER ----------
				else {

					$quarters = [
						'q1' => [4,5,6],
						'q2' => [7,8,9],
						'q3' => [10,11,12],
						'q4' => [1,2,3],
					];

					$months = $quarters[$request->quarter] ?? [];

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereIn(DB::raw('MONTH(user_payslip.date)'), $months)
						->whereYear('user_payslip.date', now()->year);
				}
			})

			->where('employees.added_by', $userId)
			->where('employees.esic_applicable', 1);

		// ================= OPTIONAL FILTERS =================
		if ($request->filled('name')) {
			$query->where('users.name', 'like', '%'.$request->name.'%');
		}

		if ($request->filled('employee_id')) {
			$query->where('employees.employee_id', 'like', '%'.$request->employee_id.'%');
		}

		// ================= DATA =================
		$employees = $query->select(
			'employees.employee_id',
			'employees.esic_no',
			'employees.total_addition as gross_wages',
			'employees.esi as employee_esi',     // ✅ SAME AS LIST
			'users.name',
			'user_payslip.payslip_no',            // ✅ Challan No
			'user_payslip.date as payment_date'
		)->get();

		// ================= EXCEL =================
		if ($request->download_format === 'excel') {

			$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="esi_filing_'.now()->format('YmdHis').'.csv"',
			];

			return response()->stream(function () use ($employees) {

				$out = fopen('php://output', 'w');

				fputcsv($out, [
					'Employee ID','Name','ESIC No','Payment Date','Challan No',
					'Gross Wages','Employee ESI'
				]);

				foreach ($employees as $e) {
					fputcsv($out, [
						$e->employee_id,
						$e->name,
						$e->esic_no,
						$e->payment_date,
						$e->payslip_no,
						$e->gross_wages,
						$e->employee_esi
					]);
				}

				fclose($out);
			}, 200, $headers);
		}

		// ================= PDF =================
		$html = '
		<style>
			@page { margin: 12px; }
			body { font-family: DejaVu Sans, Arial; font-size: 9px; }
			table { width: 100%; border-collapse: collapse; }
			th, td { border: 1px solid #000; padding: 4px; }
			th { background: #f2f2f2; }
			h3 { text-align: center; margin-bottom: 10px; }
		</style>

		<h3>ESI Filing Statement</h3>

		<table>
			<thead>
				<tr>
					<th>Employee ID</th>
					<th>Name</th>
					<th>ESIC No</th>
					<th>Payment Date</th>
					<th>Challan No</th>
					<th>Gross Wages</th>
					<th>Employee ESI</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($employees as $e) {
			$html .= '
			<tr>
				<td>'.$e->employee_id.'</td>
				<td>'.$e->name.'</td>
				<td>'.$e->esic_no.'</td>
				<td>'.$e->payment_date.'</td>
				<td>'.$e->payslip_no.'</td>
				<td>'.$e->gross_wages.'</td>
				<td>'.$e->employee_esi.'</td>
			</tr>';
		}

		$html .= '</tbody></table>';

		return PDF::loadHTML($html)
			->setPaper('a4', 'landscape')
			->download('esi_filing_'.now()->format('YmdHis').'.pdf');
	}

	public function ptax_management_list(request $request)
	{
		$userId = Auth::id();
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access
		$currentMonth = now()->month;
		$currentYear  = now()->year;

		$employees = DB::table('employees')
			->join('users', 'users.id', '=', 'employees.empId')

			// ✅ Only employees with payslip for current month
			->join('user_payslip', function ($join) use ($currentMonth, $currentYear) {
				$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
					->whereMonth('user_payslip.date', $currentMonth)
					->whereYear('user_payslip.date', $currentYear);
			})

			->where('employees.added_by', $userId)
			->where('employees.ptax_applicable', 1)

			->select(
				'employees.employee_id',
				'employees.total_addition as gross_salary', // ✅ Gross Salary
				'employees.ptax as ptax_amount',             // ✅ PTAX Amount

				'users.name',

				'user_payslip.payslip_no',                   // ✅ Challan No
				'user_payslip.date as payment_date'
			)
			->get();
			// echo "<pre>"; print_r($employees); echo "</pre>"; exit;

		return view('User.ptax-management-list', compact('employees', 'req_type'));
	}


	public function download_ptax_filing(Request $request)
	{
		$userId = Auth::id();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$periodType = $request->input('ptaxPeriodType', 'month');

		$query = DB::table('employees')
			->join('users', 'users.id', '=', 'employees.empId')

			// ================= PAYSLIP JOIN =================
			->join('user_payslip', function ($join) use ($request, $periodType) {

				// ---------- MONTH ----------
				if ($periodType === 'month') {

					$months = [
						'january'=>1,'february'=>2,'march'=>3,'april'=>4,'may'=>5,'june'=>6,
						'july'=>7,'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12
					];

					$month = $months[strtolower($request->month)] ?? now()->month;

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereMonth('user_payslip.date', $month)
						->whereYear('user_payslip.date', now()->year);
				}

				// ---------- FINANCIAL YEAR ----------
				elseif ($periodType === 'year' && preg_match('/(\d{4})-(\d{4})/', $request->year, $m)) {

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereBetween('user_payslip.date', [
							$m[1].'-04-01',
							$m[2].'-03-31'
						]);
				}

				// ---------- QUARTER ----------
				else {

					$quarters = [
						'q1' => [4,5,6],
						'q2' => [7,8,9],
						'q3' => [10,11,12],
						'q4' => [1,2,3],
					];

					$months = $quarters[$request->quarter] ?? [];

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereIn(DB::raw('MONTH(user_payslip.date)'), $months)
						->whereYear('user_payslip.date', now()->year);
				}
			})

			->where('employees.added_by', $userId)
			->where('employees.ptax_applicable', 1);

		// ================= OPTIONAL FILTERS =================
		if ($request->filled('name')) {
			$query->where('users.name', 'like', '%'.$request->name.'%');
		}

		if ($request->filled('employee_id')) {
			$query->where('employees.employee_id', 'like', '%'.$request->employee_id.'%');
		}

		// ================= DATA =================
		$employees = $query->select(
			'employees.employee_id',
			'employees.total_addition as gross_wages',
			'employees.ptax as ptax_amount',
			'users.name',
			'user_payslip.payslip_no',
			'user_payslip.date as payment_date'
		)->get();

		// ================= EXCEL =================
		if ($request->download_format === 'excel') {

			$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="ptax_filing_'.now()->format('YmdHis').'.csv"',
			];

			return response()->stream(function () use ($employees) {

				$out = fopen('php://output', 'w');

				fputcsv($out, [
					'Employee ID','Name','Payment Date','Challan No',
					'Gross Wages','PTAX Amount'
				]);

				foreach ($employees as $e) {
					fputcsv($out, [
						$e->employee_id,
						$e->name,
						$e->payment_date,
						$e->payslip_no,
						$e->gross_wages,
						$e->ptax_amount
					]);
				}

				fclose($out);
			}, 200, $headers);
		}

		// ================= PDF =================
		$html = '
		<style>
			@page { margin: 12px; }
			body { font-family: DejaVu Sans, Arial; font-size: 9px; }
			table { width: 100%; border-collapse: collapse; }
			th, td { border: 1px solid #000; padding: 4px; }
			th { background: #f2f2f2; }
			h3 { text-align: center; margin-bottom: 10px; }
		</style>

		<h3>Professional Tax (PTAX) Filing Statement</h3>

		<table>
			<thead>
				<tr>
					<th>Employee ID</th>
					<th>Name</th>
					<th>Payment Date</th>
					<th>Challan No</th>
					<th>Gross Wages</th>
					<th>PTAX Amount</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($employees as $e) {
			$html .= '
			<tr>
				<td>'.$e->employee_id.'</td>
				<td>'.$e->name.'</td>
				<td>'.$e->payment_date.'</td>
				<td>'.$e->payslip_no.'</td>
				<td>'.$e->gross_wages.'</td>
				<td>'.$e->ptax_amount.'</td>
			</tr>';
		}

		$html .= '</tbody></table>';

		return PDF::loadHTML($html)
			->setPaper('a4', 'landscape')
			->download('ptax_filing_'.now()->format('YmdHis').'.pdf');
	}






}
