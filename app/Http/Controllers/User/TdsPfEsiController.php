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
use PDF;

use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class TdsPfEsiController extends Controller
{
    public function tds_returns_filing(request $request)
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

			// ✅ INNER JOIN – only employees with payslip this month
			->join('user_payslip', function ($join) use ($currentMonth, $currentYear) {
				$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
					->whereMonth('user_payslip.date', $currentMonth)
					->whereYear('user_payslip.date', $currentYear);
			})

			// TDS slab (Salary)
			->leftJoin('tds_tax_slab', function ($join) {
				$join->where('tds_tax_slab.tds_slab_name', 'Salary');
			})

			->where('employees.added_by', $userId)
			->where('employees.tds_applicable', 1)

			->select(
				'employees.employee_id',
				'employees.pan_number',
				'employees.total_addition',
				'employees.tds',
				'users.name',
				'users.email',

				'user_payslip.date as payment_date',
				'user_payslip.payslip_no',

				'tds_tax_slab.tds_slab_section',
				'tds_tax_slab.tds_slab_rate'
			)
			->get();

		return view('User.tds-returns-filing', compact('employees', 'req_type', 'userId'));
	}


	public function download_tds_returns(Request $request)
	{
		$userId = Auth::id();

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {

			$userId = session('compId'); //ca-accountant access
		}


		$periodType = $request->input('tdsPeriodType', 'month');

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

					$monthName = strtolower($request->input('month'));
					$month = $months[$monthName] ?? now()->month;

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereMonth('user_payslip.date', $month)
						->whereYear('user_payslip.date', now()->year);
				}

				// ---------- YEARLY (FINANCIAL YEAR) ----------
				elseif ($periodType === 'year') {

					if ($request->filled('year') && preg_match('/(\d{4})-(\d{4})/', $request->year, $m)) {
						$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
							->whereBetween('user_payslip.date', [
								$m[1].'-04-01',
								$m[2].'-03-31'
							]);
					}
				}

				// ---------- QUARTERLY ----------
				else {

					$quarters = [
						1 => [1,2,3],
						2 => [4,5,6],
						3 => [7,8,9],
						4 => [10,11,12],
					];

					$q = ceil(now()->month / 3);

					$join->on('user_payslip.user_emp_id', '=', 'employees.empId')
						->whereIn(DB::raw('MONTH(user_payslip.date)'), $quarters[$q])
						->whereYear('user_payslip.date', now()->year);
				}
			})

			// ================= TDS SLAB =================
			->leftJoin('tds_tax_slab', function ($join) {
				$join->where('tds_tax_slab.tds_slab_name', 'Salary');
			})

			->where('employees.added_by', $userId)
			->where('employees.tds_applicable', 1);

		// ================= OPTIONAL FILTERS =================
		if ($request->filled('name')) {
			$query->where('users.name', 'like', '%'.$request->name.'%');
		}

		if ($request->filled('vendor_id')) {
			$query->where('employees.employee_id', 'like', '%'.$request->vendor_id.'%');
		}

		// ================= DATA =================
		$employees = $query->select(
			'employees.employee_id',
			'employees.pan_number',
			'employees.total_addition',
			'employees.tds',
			'users.name',
			'users.email',
			'user_payslip.date as payment_date',
			'user_payslip.payslip_no',
			'tds_tax_slab.tds_slab_section',
			'tds_tax_slab.tds_slab_rate'
		)->get();

		// ================= EXCEL =================
		if ($request->download_format === 'excel') {

			$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="tds_returns_'.now()->format('YmdHis').'.csv"',
			];

			return response()->stream(function () use ($employees) {
				$out = fopen('php://output', 'w');

				fputcsv($out, [
					'Employee ID','Name','Email','PAN','Payment Date','Payslip No',
					'Total Addition','TDS','TDS Section','TDS Rate'
				]);

				foreach ($employees as $e) {
					fputcsv($out, [
						$e->employee_id,
						$e->name,
						$e->email,
						$e->pan_number,
						$e->payment_date,
						$e->payslip_no,
						$e->total_addition,
						$e->tds,
						$e->tds_slab_section,
						$e->tds_slab_rate,
					]);
				}

				fclose($out);
			}, 200, $headers);
		}

		// ================= PDF (NO CUT ISSUE) =================
		$html = '
		<style>
			@page { margin: 12px; }
			body { font-family: DejaVu Sans, Arial; font-size: 9px; }
			table { width: 100%; border-collapse: collapse; table-layout: fixed; }
			th, td { border: 1px solid #000; padding: 4px; word-wrap: break-word; }
			th { background: #f2f2f2; }
			h3 { text-align: center; margin-bottom: 10px; }
		</style>

		<h3>TDS Returns & Filing</h3>

		<table>
			<thead>
				<tr>
					<th>Employee ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>PAN</th>
					<th>Payment Date</th>
					<th>Payslip No</th>
					<th>Total Addition</th>
					<th>TDS</th>
					<th>TDS Section</th>
					<th>TDS Rate</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($employees as $e) {
			$html .= '
				<tr>
					<td>'.$e->employee_id.'</td>
					<td>'.$e->name.'</td>
					<td>'.$e->email.'</td>
					<td>'.$e->pan_number.'</td>
					<td>'.$e->payment_date.'</td>
					<td>'.$e->payslip_no.'</td>
					<td>'.$e->total_addition.'</td>
					<td>'.$e->tds.'</td>
					<td>'.$e->tds_slab_section.'</td>
					<td>'.$e->tds_slab_rate.'</td>
				</tr>';
		}

		$html .= '</tbody></table>';

		$pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');

		return $pdf->download('tds_returns_'.now()->format('YmdHis').'.pdf');
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

		return view('User.pf-management-list', compact('employees', 'req_type', 'userId'));
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

		return view('User.esi-management-list', compact('employees', 'req_type', 'userId'));
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

		return view('User.ptax-management-list', compact('employees', 'req_type', 'userId'));
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
