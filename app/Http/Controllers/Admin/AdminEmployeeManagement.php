<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Depertments;
use App\Models\Designations;
use App\Models\Employees;
use Carbon\Carbon;
use PDF;

// use App\Company_banks;
use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class AdminEmployeeManagement  extends Controller
{
	public function adminEmployeeList()
	{

		$userId = Auth::user()->id;

		$employees = DB::table('users')
			->select(
				'users.id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',      // Fetching email from employees table
				'employees.profile_img',   // Fetching profile image from employees table
				'depertments.dept_name',
				'designations.designation_name'
			)
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->where('users.admin_add_by', '=', $userId)
			// ->where('users.status', '=', 1) // Fetch only active users
			->orderBy('users.created_at', 'desc')
			->get();

		return view('Admin.adminEmployeelist', [
			'employees' => $employees,
		]);
	}

	public function AdminAddEmployee()
	{

		$states = State::where('country_id', '=', 101)->get();
		return view('Admin.AdminAddEmployee')->with([

			'states' => $states,

		]);
	}

	public function EmployeeDetails(Request $request)
	{
		$id = base64_decode($request->id);

		return view('User.employee-details')->with(['id' => $id]);
	}

	public function AttendanceList()
	{


		//--------------------
		$userId = Auth::user()->id;
		$currentMonth = date('Y-m');

		$employees = DB::table('users')
			->select(
				'users.id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name',
				// Attendance Calculations
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'present' THEN 1 ELSE 0 END), 0) as present_days"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'working' THEN 1 ELSE 0 END), 0) as working_days"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status IN ('present', 'working') AND TIME(attendance.in_time) < '10:10:00' THEN 1 ELSE 0 END), 0) as on_time_days"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'present' AND TIME(attendance.in_time) >= '10:10:00' THEN 1 ELSE 0 END), 0) as late_days"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'absent' THEN 1 ELSE 0 END), 0) as absent_days")
			)
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->leftJoin('attendance', function ($join) use ($currentMonth) {
				$join->on('users.id', '=', 'attendance.userId')
					->whereRaw("DATE_FORMAT(attendance.present_date, '%Y-%m') = ?", [$currentMonth]);
			})
			->where('users.admin_add_by', '=', $userId)
			->where('users.status', '=', 1)
			->groupBy(
				'users.id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->orderBy('users.created_at', 'desc')
			->get();

		// Debugging (if needed)
		// echo "<pre>"; print_r($employees); die();

		return view('User.attendance-list')->with([
			'employees' => $employees,
		]);
	}

	public function GeneratePayslip()
	{
		$userId = Auth::user()->id;

		// Fetch employees
		$employees = DB::table('users')
			->where('u_type', 6)
			->where('admin_add_by', $userId)
			->where('status', 1) // Optional: Fetch only active employees
			->orderBy('name', 'asc')
			->get();

		return view('User.generate-payslip', compact('employees'));
	}

	public function getDepartments()
	{
		// Get the currently authenticated user's ID
		$userId = Auth::user()->id;


		$departments = DB::table('depertments')
			->where(function ($query) use ($userId) {
				$query->where('created_user_id', $userId)
					->orWhere('created_user_id', 'admin');
			})
			->select('id', 'dept_name')
			->get();

		// Return the departments as JSON
		return response()->json($departments);
	}

	public function getDesignationsByDept($dept_id)
	{
		// Get the current user's ID
		$userId = Auth::user()->id;

		// Fetch designations where the dept_id matches and created_user_id is either 'admin' or the current user's ID
		$designations = DB::table('designations')
			->where('dept_id', $dept_id)
			->where(function ($query) use ($userId) {
				$query->where('created_user_id', $userId)
					->orWhere('created_user_id', 'admin');
			})
			->select('id', 'designation_name')
			->get();

		return response()->json($designations);
	}

	public function DeptStore(Request $request)
	{
		$userId = Auth::user()->id;
		$request->validate([
			'dept_name' => 'required|unique:depertments,dept_name|max:255'
		]);

		DB::table('depertments')->insert([
			'dept_name' => $request->dept_name,
			'created_user_id' => $userId
		]);

		return response()->json(['success' => true, 'message' => 'Department added successfully!']);
	}

	public function DesignationStore(Request $request)
	{
		$userId = Auth::user()->id;
		$request->validate([
			'dept_id' => 'required',
			'designation_name' => 'required|max:255',
		]);

		DB::table('designations')->insert([
			'dept_id' => $request->dept_id,
			'designation_name' => $request->designation_name,
			"created_user_id"  => $userId

		]);

		return response()->json(['success' => true, 'message' => 'Designation added successfully!']);
	}

	protected function validator(array $data)
	{
		//echo "<pre>"; print_r($data);exit;
		return Validator::make(
			$data,
			[
				'name' => 'required|min:3',
				'email' => 'required|email',
				'phone' => 'required|min:10',
				'password' => 'required',
				// 'dept_id' => 'required',
				// 'desig_id' => 'required',
				// 'dob' => 'required',
				// 'gender' => 'required',
				// 'qualification' => 'required',
				// 'c_addr_lineone' => 'required',
				// 'c_emp_country' => 'required',
				// 'c_emp_state' => 'required',
				// 'c_emp_city' => 'required',
				// 'c_emp_pincode' => 'required',
				// 'p_addr_lineone' => 'required',
				// 'p_emp_country' => 'required',
				// 'p_emp_state' => 'required',
				// 'p_emp_city' => 'required',
				// 'p_emp_pincode' => 'required',
				// 'basic_sal' => 'required',
				// 'hra' => 'required',
				// 'convayance' => 'required',
				// 'special_bonus' => 'required',
				// 'provident_fund' => 'required',
				// 'esi' => 'required',
				// 'loan' => 'required',
				// 'ptax' => 'required',
				// 'tds' => 'required',
				// 'total_deduction' => 'required',
				// 'total_addition' => 'required',
				// 'net_sal' => 'required',
				// 'net_sal_word' => 'required',
			]
		);
	}

	public function getEmployeeId()
	{
		$userId = Auth::user()->id;

		$prefix = 'emp' . $userId . '-';

		$lastEmployee = DB::table('employees')
			->select('employee_id')
			->where('employee_id', 'like', $prefix . '%')
			->orderBy('id', 'desc')
			->first();

		if ($lastEmployee && isset($lastEmployee->employee_id)) {
			$lastNumber = (int) str_replace($prefix, '', $lastEmployee->employee_id);
			$newNumber = $lastNumber + 1;
		} else {
			$newNumber = 1;
		}
		// Format the new employee_id
		$employee_id = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
		return $employee_id;
	}

	protected function createUser(array $data)
	{
		//echo "<pre>";print_r($data);exit;
		$userType = Auth::user()->u_type;
		if ($userType == "1") {
			$u_type = '4';
			$CAuserId = Auth::user()->id;
			$UserUserId = null;
			$admin_add_by = null;
		} else if ($userType == "2") {
			$u_type = '5';
			$UserUserId = Auth::user()->id;
			$CAuserId = null;
			$admin_add_by = null;
		} else if ($userType == "3") {
			$u_type = '6'; // -------For admin employee
			$UserUserId = null;
			$CAuserId = null;
			$admin_add_by = Auth::user()->id;
		}

		// $userId = Auth::user()->id;

		return User::create([
			'name' => $data['name'],
			'email' => $data['login_email'],
			'phone' => $data['phone'],
			'u_type' => $u_type,
			'password' => Hash::make($data['password']),
			'status' => 1,
			'userStatus' => 1,
			'isActive' => 1,
			'user_add_by' => $UserUserId,
			'ca_add_by' => $CAuserId,
			'admin_add_by' => $admin_add_by,
			'country_id' => '101',
			'state_id' =>  $data['c_emp_state'],
			'city_id' => $data['c_emp_city'],
			'pincode' => $data['c_emp_pincode'],
			'emp_permission' => implode(",", $data['emp_permission']),

		]);
	}

	public function add_admin_employee(Request $request)
	{


		$emp_permission = ($request->emp_permission);

		if (empty($emp_permission)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please set employee permission'
			);
			return response()->json($msg);
		}

		$validation = $this->validator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {

			$user = User::where('email', '=', $request->email)->get();
			$user = @$user[0];
			if (!empty($user)) {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Email already exists'
				);
				return response()->json($msg);
			}
			$insertEmployee = $this->createUser($request->all());

			$empId = DB::getPdo()->lastInsertId();
			$added_by = Auth::user()->id;

			$emp = new Employees;
			$emp->added_by = $added_by;
			$emp->empId = $empId;

			if ($request->hasFile('fileUpload')) {
				$file = $request->file('fileUpload');
				$fileName = time() . '.' . $file->getClientOriginalExtension();

				// Store file in storage/app/public/user_employee
				$file->storeAs('public/user_employee', $fileName);

				// Save only the file name in the database
				$emp->profile_img = $fileName;
			}

			$emp->employee_id = $this->getEmployeeId();
			$emp->gender = $request->gender;
			$emp->dob = $request->dob;
			$emp->email_id = $request->email;
			$emp->qualification = $request->qualification;

			$emp->c_addr_lineone = $request->c_addr_lineone;
			$emp->c_addr_linetwo = isset($request->c_addr_linetwo) ? $request->c_addr_linetwo : "";
			$emp->c_emp_country = '101';
			$emp->c_emp_state = $request->c_emp_state;
			$emp->c_emp_city = $request->c_emp_city;
			$emp->c_emp_pincode = $request->c_emp_pincode;

			$emp->p_addr_lineone = $request->p_addr_lineone;
			$emp->p_addr_linetwo = isset($request->p_addr_linetwo) ? $request->p_addr_linetwo : "";
			$emp->p_emp_country = "101";
			$emp->p_emp_state = $request->p_emp_state;
			$emp->p_emp_city = $request->p_emp_city;
			$emp->p_emp_pincode = $request->p_emp_pincode;

			$emp->dept_id = $request->dept_id;
			$emp->desig_id = $request->designation_id;
			$emp->basic_sal = $request->basic_sal;
			$emp->hra = $request->hra;
			$emp->convayance = $request->convayance;
			$emp->medical_allowance = $request->medical_allowance;   //---------- medical_allowance ----------
			$emp->special_bonus = $request->special_bonus;
			$emp->provident_fund = $request->provident_fund;
			$emp->esi = $request->esi;
			$emp->loan = $request->loan;
			$emp->ptax = $request->ptax;
			$emp->tds = $request->tds;
			$emp->total_deduction = $request->total_deduction;
			$emp->total_addition = $request->total_addition;
			$emp->net_sal = $request->net_sal;
			$emp->net_sal_word = $request->net_sal_word;
			$emp->joining_date = $request->emp_joining_date;

			$emp->bank_name = $request->bank_name;
			$emp->bank_branch = $request->bank_branch;
			$emp->ifsc = $request->ifsc;
			$emp->swift_code = $request->swift_code;
			$emp->account_holder_name = $request->account_holder_name;
			$emp->account_number = $request->account_number;
			$emp->upi_id = $request->upi_id;
			$emp->save();

			if ($insertEmployee) {
				$userType = Auth::user()->u_type;
				if ($userType == 2) {
					$routeLink = route('user.EmployeeList');
				} else if ($userType == 1) {
					$routeLink = route('CA.EmployeeList');
				} else if ($userType == 3) {
					$routeLink = route('Admin.EmployeeList');
				}

				return response()->json([
					'status' => 'success',
					'message' => 'Employee added successfully',
					'redirect' => $routeLink
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'message' => 'Enter all details for employee',
					'redirect' => url('/')
				]);
			}
		}
	}

	public function view_admin_employee($encodedId)
	{

		$id = base64_decode($encodedId);

		// Fetch employee details with all fields from 'users' and 'employees' tables
		$employee = DB::table('users')
			->select(
				'users.*',
				'employees.*'
			)
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->where('users.id', '=', $id)
			->first();

		// Fetch states
		$states = State::where('country_id', '=', 101)->get();
		// echo '<pre>';
		// print_r($employee);
		// die();
		return view('Admin.viewAdminEmployee')->with([
			'states'   => $states,
			'employee' => $employee,
		]);
	}

	// Edit Employee
	public function edit_admin_employee($encodedId)
	{
		$id = base64_decode($encodedId);

		// Fetch employee details with all fields from 'users' and 'employees' tables
		$employee = DB::table('users')
			->select(
				'users.*',
				'employees.*'
			)
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->where('users.id', '=', $id)
			->first();

		// Fetch states
		$states = State::where('country_id', '=', 101)->get();
		// echo '<pre>';
		// print_r($employee);
		// die();
		return view('Admin.editAdminEmployee')->with([
			'states'   => $states,
			'employee' => $employee,
		]);
	}



	// public function delet_user_employee($encodedId) {
	// 	$id = base64_decode($encodedId);
	// 	// Find the employee and update the status
	// 	$user = User::findOrFail($id);
	// 	$user->status = 0;
	// 	$user->save();

	// 	return redirect()->back()->with('success', 'Employee Delete Successfully.');
	// }

	public function delet_user_employee($encodedId)
	{
		$id = base64_decode($encodedId);
		$user = User::find($id);

		if ($user) {
			$user->status = 0;
			$user->save();

			return response()->json(['status' => 'succ', 'message' => 'Employee Deactive successfully.']);
		}

		return response()->json(['status' => 'error', 'message' => 'Employee not found.'], 404);
	}

	public function update_admin_employee(Request $request)
	{
		$empId = $request->input('id'); // Get employee ID from hidden input

		if (!$empId) {
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid Employee ID',
			]);
		}

		$employee = Employees::find($empId);
		if (!$employee) {
			return response()->json([
				'status' => 'error',
				'message' => 'Employee not found',
			]);
		}

		$user = User::find($employee->empId);
		if (!$user) {
			return response()->json([
				'status' => 'error',
				'message' => 'User not found',
			]);
		}

		// Validate input data
		$validation = Validator::make($request->all(), [
			'name' => 'required|min:3',
			'phone' => 'required|min:10',
		]);

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		// Update User Table (excluding email & password)
		$user->name = $request->name;
		$user->phone = $request->phone;
		$user->state_id = $request->c_emp_state;
		$user->city_id = $request->c_emp_city;
		$user->pincode = $request->c_emp_pincode;
		$user->emp_permission = implode(",", $request->emp_permission);
		$user->save();

		// Update Employee Table
		$employee->gender = $request->gender;
		$employee->dob = $request->dob;
		$employee->qualification = $request->qualification;

		$employee->c_addr_lineone = $request->c_addr_lineone;
		$employee->c_addr_linetwo = $request->c_addr_linetwo ?? "";
		$employee->c_emp_state = $request->c_emp_state;
		$employee->c_emp_city = $request->c_emp_city;
		$employee->c_emp_pincode = $request->c_emp_pincode;

		$employee->p_addr_lineone = $request->p_addr_lineone;
		$employee->p_addr_linetwo = $request->p_addr_linetwo ?? "";
		$employee->p_emp_state = $request->p_emp_state;
		$employee->p_emp_city = $request->p_emp_city;
		$employee->p_emp_pincode = $request->p_emp_pincode;

		$employee->dept_id = $request->dept_id;
		$employee->desig_id = $request->designation_id;
		$employee->basic_sal = $request->basic_sal;
		$employee->hra = $request->hra;
		$employee->convayance = $request->convayance;
		$employee->medical_allowance = $request->medical_allowance;

		$employee->special_bonus = $request->special_bonus;
		$employee->provident_fund = $request->provident_fund;
		$employee->esi = $request->esi;
		$employee->loan = $request->loan;
		$employee->ptax = $request->ptax;
		$employee->tds = $request->tds;
		$employee->total_deduction = $request->total_deduction;
		$employee->total_addition = $request->total_addition;
		$employee->net_sal = $request->net_sal;
		$employee->net_sal_word = $request->net_sal_word;
		$employee->joining_date = $request->emp_joining_date;

		$employee->bank_name = $request->bank_name;
		$employee->bank_branch = $request->bank_branch;
		$employee->ifsc = $request->ifsc;
		$employee->swift_code = $request->swift_code;
		$employee->account_holder_name = $request->account_holder_name;
		$employee->account_number = $request->account_number;
		$employee->upi_id = $request->upi_id;

		// Update profile image if a new file is uploaded
		if ($request->hasFile('fileUpload')) {
			$file = $request->file('fileUpload');
			$fileName = time() . '.' . $file->getClientOriginalExtension();
			$file->storeAs('public/user_employee', $fileName);
			$employee->profile_img = $fileName;
		}

		$employee->save();

		$userType = Auth::user()->u_type;
		if ($userType == 2) {
			$routeLink = route('user.EmployeeList');
		} else if ($userType == 1) {
			$routeLink = route('CA.EmployeeList');
		} else if ($userType == 3) {
			$routeLink = route('Admin.EmployeeList');
		}

		return response()->json([
			'status' => 'success',
			'message' => 'Employee updated successfully',
			'redirect' => $routeLink,
		]);
	}

	public function getMonthlyAttendance(Request $request)
	{
		$year = $request->input('year', Carbon::now()->year);
		$month = $request->input('month', Carbon::now()->month);
		$userId = $request->input('user_id'); // Fetch user_id from request

		$attendance = DB::select("
			SELECT id, present_date, in_time, out_time, present_status, reason
			FROM attendance
			WHERE userId = ? AND YEAR(present_date) = ? AND MONTH(present_date) = ?
		", [$userId, $year, $month]);

		return response()->json($attendance);
	}


	public function updateAttendance(Request $request)
	{
		$userId = $request->input('user_id');
		$presentDate = $request->input('present_date');
		$inTime = $request->input('in_time');
		$outTime = $request->input('out_time');
		$present_status = $request->input('present_status');
		$reason = $request->input('reason');

		$date = Carbon::parse($presentDate);
		$month = $date->month;
		$year = $date->year;

		// Determine present_status
		$finalPresentStatus = !empty($inTime) ? 'present' : $present_status;

		$attendance = DB::table('attendance')
			->where('userId', $userId)
			->whereDate('present_date', $presentDate)
			->first();

		if ($attendance) {
			DB::table('attendance')->where('id', $attendance->id)->update([
				'in_time' => $inTime,
				'out_time' => $outTime,
				'present_status' => $finalPresentStatus,
				'reason' => $reason,
				'updated_at' => now(),
			]);
			return response()->json([
				'message' => 'Attendance updated successfully',
				'month' => $month,
				'year' => $year
			], 200);
		} else {
			DB::table('attendance')->insert([
				'userId' => $userId,
				'present_date' => $presentDate,
				'in_time' => $inTime,
				'present_status' => $finalPresentStatus,
				'status' => '1',
				'reason' => $reason,

				'out_time' => $outTime,
				'created_at' => now(),
				'updated_at' => now(),
			]);
			return response()->json([
				'message' => 'Attendance recorded successfully',
				'month' => $month,
				'year' => $year
			], 201);
		}
	}

	public function checkPayslip(Request $request)
	{
		$employeeId = $request->employee_id;
		$financialYear = $request->select_financial_year;
		$monthName = trim($request->monthSelect, "'");

		// Convert month name to number
		$month = (int) date('m', strtotime($monthName));

		// Extract start and end years from financial year (e.g., "2024-2025")
		$financialYears = explode("-", $financialYear);
		$requestedYear = ($month >= 4) ? $financialYears[0] : $financialYears[1]; // Apr-Dec -> Start Year, Jan-Mar -> End Year

		// Debugging Log
		\Log::info("Checking payslip for Employee ID: $employeeId, Month: $month ($monthName), Year: $requestedYear");

		// Check if a payslip already exists
		$payslip = DB::table('user_payslip')
			->where('user_emp_id', $employeeId)
			->where('financial_year', $financialYear)
			->where('month', $month)
			->first();

		if ($payslip) {
			return response()->json([
				'status' => 'exists',
				'payslipId' => $payslip->id,
			]);
		}

		// else part code..................
		// Fetch employee salary slip no -------
		// PS/Employee ID/DDMMYYYY

		$payslipNo = DB::table('employees')
			->selectRaw("CONCAT('PS/', employee_id, '/', DATE_FORMAT(STR_TO_DATE(CONCAT('01-', ?, '-'), '%d-%M-%Y'), '%d%m%Y')) AS payslip_no", [$monthName . '-' . $financialYear])
			->where('empId', '=', $employeeId)
			->first();

		// Fetch employee details and attendance summary
		$employee = DB::table('employees')
			->join('depertments', 'employees.dept_id', '=', 'depertments.id')
			->join('designations', 'employees.desig_id', '=', 'designations.id')
			->join('users', 'employees.empId', '=', 'users.id')
			->leftJoin('attendance', function ($join) use ($month, $requestedYear) {
				$join->on('employees.empId', '=', 'attendance.userId')
					->whereRaw("MONTH(attendance.present_date) = ?", [$month])
					->whereRaw("YEAR(attendance.present_date) = ?", [$requestedYear]);
			})
			->select(
				'employees.*',
				'users.name',
				'users.email',
				'depertments.dept_name as department_name',
				'designations.designation_name as designation_name',

				// Attendance calculations
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'present' AND TIME(attendance.in_time) < '10:10:00' THEN 1 ELSE 0 END), 0) as present_ontime"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'working' THEN 1 ELSE 0 END), 0) as working_ontime"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status IN ('present', 'working') THEN 1 ELSE 0 END), 0) as on_time_days"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'present' AND TIME(attendance.in_time) > '10:10:00' THEN 1 ELSE 0 END), 0) as late_count"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'absent' THEN 1 ELSE 0 END), 0) as absent_days"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'holiday' THEN 1 ELSE 0 END), 0) as total_holidays"),
				DB::raw("COALESCE(SUM(CASE WHEN attendance.present_status = 'leave' THEN 1 ELSE 0 END), 0) as total_leave_days"),

				'employees.joining_date',
				'employees.regine_date',
				'employees.net_sal'
			)
			->where('employees.empId', $employeeId)
			->groupBy(
				'employees.empId',
				'users.name',
				'users.email',
				'depertments.dept_name',
				'designations.designation_name',
				'employees.joining_date',
				'employees.regine_date',
				'employees.net_sal'
			)
			->first();

		if (!$employee) {
			return response()->json(['error' => 'Employee not found or has no attendance data'], 404);
		}

		// Get the total days in the month
		$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $requestedYear);

		// Calculate number of Sundays in the given month
		$sundays = 0;
		for ($day = 1; $day <= $totalDaysInMonth; $day++) {
			if (date('N', strtotime("$requestedYear-$month-$day")) == 7) { // Sunday (N=7)
				$sundays++;
			}
		}

		// Fetch salary and attendance details
		$netSalary = $employee->net_sal ?? 0;
		$absentDays = $employee->absent_days ?? 0;

		// Calculate actual working days
		$actualWorkingDays = $employee->present_ontime + $employee->working_ontime + $sundays + $employee->total_holidays + $employee->total_leave_days;

		// Per day salary
		// $perDaySalary = ($totalDaysInMonth > 0) ? ($netSalary / $totalDaysInMonth) : 0;
		$perDaySalary = (30 > 0) ? ($netSalary / 30) : 0;

		// Late arrival penalty (every 3 late days deducts 1 salary day)
		$lateCount = $employee->late_count ?? 0;
		$latePenaltyDays = floor($lateCount / 3); // Rounds down to nearest whole number

		// Total deducted salary days
		$totalDeductedSalaryDays = min($absentDays + $latePenaltyDays, $totalDaysInMonth);

		// Salary deductions
		$salaryDeductionAmount = $perDaySalary * $totalDeductedSalaryDays;

		// Final salary calculation
		$salaryForThisMonth = ($perDaySalary * ($actualWorkingDays - $totalDeductedSalaryDays));

		// Total late deductions
		$lateDeduction = '00';
		if ($latePenaltyDays > 0) {
			$lateDeduction = $latePenaltyDays * $perDaySalary;
		}

		// Total absent Deduction
		$absentDeduction = "00";
		if ($employee->absent_days > 0) {
			$absentDeduction = $perDaySalary * $employee->absent_days;
		}

		$employeeData = (array) $employee;
		$employeeData = array_merge($employeeData, [
			'total_days_in_month' => $totalDaysInMonth,
			'actual_working_days' => $actualWorkingDays,
			'per_day_salary' => round($perDaySalary, 2),
			'total_deducted_salary_days' => $totalDeductedSalaryDays,
			'salary_deduction_amount' => round($salaryDeductionAmount, 2),
			'salary_for_this_month' => round($salaryForThisMonth, 2),
			'financialYear' => $financialYear,
			'month' => $month,
			'total_sundays' => $sundays,
			'total_holidays' => $employee->total_holidays,
			'total_leave_days' => $employee->total_leave_days,
			'late_count' => $lateCount,
			'late_penalty_days' => $latePenaltyDays,
			'onTime' => $employee->present_ontime,
			'workingOntime' => $employee->working_ontime,
			'absentDays' => $employee->absent_days,
			'lateDeduction' => $lateDeduction,
			'absentDeduction' => $absentDeduction,
			'payslipNo' => $payslipNo,
		]);

		return response()->json([
			'employee' => $employeeData,
			'total_days_in_month' => $totalDaysInMonth,
			'actual_working_days' => $actualWorkingDays,
			'per_day_salary' => round($perDaySalary, 2),
			'total_deducted_salary_days' => $totalDeductedSalaryDays,
			'salary_deduction_amount' => round($salaryDeductionAmount, 2),
			'salary_for_this_month' => round($salaryForThisMonth, 2),
			'financialYear' => $financialYear,
			'month' => $month,
			'total_sundays' => $sundays,
			'total_holidays' => $employee->total_holidays,
			'total_leave_days' => $employee->total_leave_days,
			'late_count' => $lateCount,
			'late_penalty_days' => $latePenaltyDays,
			'onTime' => $employee->present_ontime,
			'workingOntime' => $employee->working_ontime,
			'absentDays' => $employee->absent_days,
			'lateDeduction' => $lateDeduction,
			'absentDeduction' => $absentDeduction,
			'payslipNo' => $payslipNo,
		]);
	}

	public function savePayslip(Request $request)
	{
		// Insert data directly into the database
		$empResponse = urldecode($request->emp_response);

		$payslipId = DB::table('user_payslip')->insertGetId([
			'payslip_no' => $request->payslipNo,
			'user_emp_id' => $request->empId,
			'financial_year' => $request->financialYear,
			'month' => $request->month,
			'payslip_text' => $request->notes,
			'date' => $request->generate_date,
			'emp_salary_slip_response' => $empResponse,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		return response()->json([
			'success' => true,
			'message' => 'Payslip generated successfully!',
			'payslipId' => $payslipId
		]);
	}

	public function downloadPayslip($id)
	{
		$UserId = Auth::user()->id;
		$UserType = Auth::user()->u_type;

		// Fetch the payslip data from 'user_payslip' table
		$payslip = DB::table('user_payslip')
			->where('id', $id)
			->select('emp_salary_slip_response')
			->first();

		// Fetch the user's company profile info
		if ($UserType == 2) {
			$company_data = DB::table('company_profiles')
				->where('userId', $UserId)
				->first();

			// If company data is not available, redirect to company profile page
			if (!$company_data) {
				return redirect()->route('user.CompanyProfile');
			}
		} else {
			$company_data = DB::table('ca_profiles')
				->where('userId', $UserId)
				->first();

			// If company data is not available, redirect to company profile page
			if (!$company_data) {
				return redirect()->route('CA.FirmInformation');
			}
		}

		// Check if payslip exists
		if (!$payslip) {
			return abort(404, 'Payslip not found');
		}

		// Decode the stored JSON response (assuming it's stored as JSON)
		$salaryData = json_decode($payslip->emp_salary_slip_response, true);



		// Load the payslip view and pass both salary and company data
		$pdf = PDF::loadView('User.payslip-pdf', compact('salaryData', 'company_data'))
			->setOptions([
				'dpi' => 150,
				'defaultFont' => 'sans-serif',
				'isHtml5ParserEnabled' => true,
				'isRemoteEnabled' => true,
			]);

		$pdfName = 'Payslip-' . $id . '.pdf';
		return $pdf->stream($pdfName);
	}

	public function updateResignation(Request $request)
	{
		$request->validate([
			'regdate' => 'required|date',
			'reg_documet' => 'required|mimes:pdf,doc,docx,jpg,png|max:2048',
			'empId' => 'required'
		]);

		try {
			// Find employee by empId
			$employee = Employees::where('empId', $request->empId)->firstOrFail();

			// Handle file upload
			if ($request->hasFile('reg_documet')) {
				$file = $request->file('reg_documet');
				$fileName = 'resignation_' . $employee->empId . '.' . $file->getClientOriginalExtension();
				$file->storeAs('public/resignation_file', $fileName);

				// Update resignation details
				$employee->regine_date = $request->regdate;
				$employee->regine_document = $fileName;
				$employee->save();

				\DB::table('users')
					->where('id', $employee->empId)
					->update(['status' => 0]);

				return response()->json([
					'class' => 'succ',
					'message' => 'Resignation details updated successfully!'
				]);
			}

			return response()->json([
				'class' => 'error',
				'message' => 'File upload failed!'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'class' => 'error',
				'message' => 'Something went wrong: ' . $e->getMessage()
			]);
		}
	}


	public function checkEmail(Request $request)
	{
		$emailExists = User::where('email', $request->email)->exists();
		return response()->json(['exists' => $emailExists]);
	}
}
