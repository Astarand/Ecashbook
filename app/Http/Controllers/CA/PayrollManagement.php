<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
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
use App\Models\Employee_banks;
use App\Models\Location;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class PayrollManagement extends Controller
{

	public function CAEmployeeList()
	{
		$userId = currentOwnerId();

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
			->where('users.ca_add_by', '=', $userId)
			->where('users.u_type', '=', 4)
			// ->where('users.status', '=', 1) // Fetch only active users
			->orderBy('users.created_at', 'desc')
			->get();

		return view('Ca.Employeelist', [
			'employees' => $employees,
		]);
	}

	public function CAAddEmployee()
	{
		$userId = currentOwnerId();

		$states = State::where('country_id', '=', 101)->get();
		$depts = Depertments::where('id', '>', '0')->get();
		$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();
		// $basic_percentage = DB::table('ca_profiles')
							// ->where('userId', $userId)
							// ->value('basic_percentage');
		
		return view('Ca.AddEmployee')->with([
			'states' => $states,
			'depts' => $depts,
			'locations' => $locations,
			// 'basic_percentage' => $basic_percentage,
		]);
	}

	public function edit_employee($empId)
	{

		$id = base64_decode($empId);

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
		return view('Ca.edit-employee')->with([
			'states'   => $states,
			'employee' => $employee,
		]);
	}

	public function view_employee($empId)
	{
		$id = base64_decode($empId);

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
		return view('Ca.view-employee')->with([
			'states'   => $states,
			'employee' => $employee,
		]);
	}

	public function getDesignationOptions(Request $request)
	{

		$id = $request->id;
		$response = [];
		if ($id != "") {
			$deptCat = Depertments::query()
				->where('id', '=', $id)
				->get()->toArray();
			$result = Designations::query()
				->where('dept_id', '=', $deptCat[0]['id'])
				->get()->toArray();

			//echo "<pre>";print_r($result);exit;
			foreach ($result as $row) {
				$response[] = array("id" => $row['id'], "name" => $row['designation_name']);
			}
		}
		echo json_encode($response);
	}

	public function update_employee(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$empId = $request->id;

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

		$validation = $this->updateValidator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			//start update employee
			if ($request->password != "" && ($request->password != $request->conf_password)) {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Password not matched!'
				);
				return response()->json($msg);
			} else if ($request->password != "" && ($request->password == $request->conf_password)) {
				$updateUser = DB::table('users')
					->where('id', $empId)
					->update(
						array(
							'name' => $request->name,
							'phone' => $request->phone,
							'password' => Hash::make($request->password),
							'emp_permission' => implode(",", $request->emp_permission),
						)
					);
			} else {
				$updateUser = DB::table('users')
					->where('id', $empId)
					->update(
						array(
							'name' => $request->name,
							'phone' => $request->phone,
							'emp_permission' => implode(",", $request->emp_permission),
						)
					);
			}
			$update = DB::table('employees')
				->where('empId', $empId)
				->update(
					array(
						'dept_id' => $request->dept_id,
						'desig_id' => $request->desig_id,
						'dob' => $request->dob,
						'gender' => $request->gender,
						'qualification' => $request->qualification,
						'c_addr_lineone' => $request->c_addr_lineone,
						'c_addr_linetwo' => isset($request->c_addr_linetwo) ? $request->c_addr_linetwo : "",
						'c_emp_country' => 101,
						'c_emp_state' => $request->c_emp_state,
						'c_emp_city' => $request->c_emp_city,
						'c_emp_pincode' => $request->c_emp_pincode,

						'p_addr_lineone' => $request->p_addr_lineone,
						'p_addr_linetwo' => isset($request->p_addr_linetwo) ? $request->p_addr_linetwo : "",
						'p_emp_country' => 101,
						'p_emp_state' => $request->p_emp_state,
						'p_emp_city' => $request->p_emp_city,
						'p_emp_pincode' => $request->p_emp_pincode,
						'basic_sal' => $request->basic_sal,
						'hra' => $request->hra,
						'convayance' => $request->convayance,
						'special_bonus' => $request->special_bonus,
						'provident_fund' => $request->provident_fund,
						'esi' => $request->esi,
						'loan' => $request->loan,
						'ptax' => $request->ptax,
						'tds' => floatval($request->total_addition) > 1000000 ? $this->calculateTDS(floatval($request->total_addition))['amount'] : $request->tds,
						'total_deduction' => $request->total_deduction,
						'total_addition' => $request->total_addition,
						'net_sal' => $request->net_sal,
						'net_sal_word' => $request->net_sal_word
					)
				);
			//start add bank
			$userId = currentOwnerId();
			$bank_name = array_filter($request->emp_bank_name);
			$bank_branch = array_filter($request->emp_bank_branch);
			$bank_holder_name = array_filter($request->emp_bank_holder_name);
			$ac_no = array_filter($request->emp_ac_no);
			$ifsc_code = array_filter($request->emp_ifsc_code);
			$swift_code = array_filter($request->emp_swift_code);
			$ac_upid = array_filter($request->emp_ac_upid);

			if (!empty($bank_name) && !empty($bank_branch) && !empty($bank_holder_name) && !empty($ac_no) && !empty($ifsc_code)) {
				$delBank = DB::table('employee_banks')->where('eid', $empId)->delete();

				foreach ($bank_name as $index => $value) {

					$insertBank = DB::table('employee_banks')->insertGetId([
						'eid' => $empId,
						'uid' => $userId,
						'utype' => 4,
						'emp_bank_name' => isset($bank_name[$index]) ? $bank_name[$index] : "",
						'emp_bank_branch' => isset($bank_branch[$index]) ? $bank_branch[$index] : "",
						'emp_bank_holder_name' => isset($bank_holder_name[$index]) ? $bank_holder_name[$index] : "",
						'emp_ac_no' => isset($ac_no[$index]) ? $ac_no[$index] : "",
						'emp_ifsc_code' => isset($ifsc_code[$index]) ? $ifsc_code[$index] : "",
						'emp_swift_code' => isset($swift_code[$index]) ? $swift_code[$index] : "",
						'emp_ac_upid' => isset($ac_upid[$index]) ? $ac_upid[$index] : "",

					]);
				}
			}
			//end add bank
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/ca-employee-list'),
				'message' => 'Record details updated'
			);
			return response()->json($msg);
		}
	}

	//Activate employee
	public function changeEmployeeStatus(Request $request)
	{
		$user = User::find($request->id);
		$user->status = $request->status;
		$user->save();
		//return response()->json(['success'=>'Status change successfully.']);
		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/employee'),
			'message' => 'Status change successfully.'
		);
		return response()->json($msg);
	}

	//Delete employee
	public function delEmployee(Request $request)
	{
		$delUser = DB::table('users')->where('id', $request->id)->delete();
		$delEmployee = DB::table('employees')->where('empId', $request->id)->delete();
		$delEmployeeBank = DB::table('employee_banks')->where('eid', $request->id)->delete();
		if ($delEmployee) {
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/ca-employee-list'),
				'message' => 'Employee deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/ca-employee-list'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
	}

	public function add_depertment(Request $request)
	{

		$dept_name = ($request->dept_name);
		if (empty($dept_name)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please enter depertment'
			);
			return response()->json($msg);
		}
		$deptData = Depertments::where('dept_name', '=', $request->dept_name)->get();
		$deptData = @$deptData[0];
		if (!empty($deptData)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Depertment already exists'
			);
			return response()->json($msg);
		}

		$dept = new Depertments;
		$dept->dept_name = $dept_name;
		$dept->save();

		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/'),
			'message' => 'Depertment added successfully'
		);
		return response()->json($msg);
	}

	public function add_designation(Request $request)
	{

		$dept_id = ($request->dept_id);
		$designation_name = ($request->designation_name);
		if (empty($dept_id) || empty($designation_name)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please enter required fields'
			);
			return response()->json($msg);
		}
		$desigData = DB::table('designations')
			->where('dept_id', $dept_id)
			->where('designation_name', $designation_name)
			->get();
		$desigData = @$desigData[0];
		if (!empty($desigData)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Designation already exists'
			);
			return response()->json($msg);
		}

		$desig = new Designations;
		$desig->dept_id = $dept_id;
		$desig->designation_name = $designation_name;
		$desig->save();

		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/'),
			'message' => 'Designation added successfully'
		);
		return response()->json($msg);
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
				'dept_id' => 'required',
				'desig_id' => 'required',
				'dob' => 'required',
				'gender' => 'required',
				'qualification' => 'required',
				'c_addr_lineone' => 'required',
				//'c_emp_country' => 'required',
				'c_emp_state' => 'required',
				'c_emp_city' => 'required',
				'c_emp_pincode' => 'required',
				'p_addr_lineone' => 'required',
				//'p_emp_country' => 'required',
				'p_emp_state' => 'required',
				'p_emp_city' => 'required',
				'p_emp_pincode' => 'required',
				'basic_sal' => 'required',
				'hra' => 'required',
				'convayance' => 'required',
				'special_bonus' => 'required',
				'provident_fund' => 'required',
				'esi' => 'required',
				'loan' => 'required',
				'ptax' => 'required',
				'tds' => 'required',
				'total_deduction' => 'required',
				'total_addition' => 'required',
				'net_sal' => 'required',
				'net_sal_word' => 'required',
			]
		);
	}

	protected function updateValidator(array $data)
	{
		//echo "<pre>"; print_r($data);exit;
		return Validator::make(
			$data,
			[
				'name' => 'required|min:3',
				'email' => 'required|email',
				'phone' => 'required|min:10',
				'dept_id' => 'required',
				'desig_id' => 'required',
				'dob' => 'required',
				'gender' => 'required',
				'qualification' => 'required',
				'c_addr_lineone' => 'required',
				//'c_emp_country' => 'required',
				'c_emp_state' => 'required',
				'c_emp_city' => 'required',
				'c_emp_pincode' => 'required',
				'p_addr_lineone' => 'required',
				//'p_emp_country' => 'required',
				'p_emp_state' => 'required',
				'p_emp_city' => 'required',
				'p_emp_pincode' => 'required',
				'basic_sal' => 'required',
				'hra' => 'required',
				'convayance' => 'required',
				'special_bonus' => 'required',
				'provident_fund' => 'required',
				'esi' => 'required',
				'loan' => 'required',
				'ptax' => 'required',
				'tds' => 'required',
				'total_deduction' => 'required',
				'total_addition' => 'required',
				'net_sal' => 'required',
				'net_sal_word' => 'required',
			]
		);
	}

	protected function createUser(array $data)
	{
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => $data['phone'],
			'u_type' => 4,
			'password' => Hash::make($data['password']),
			'status' => 1,
			'userStatus' => 1,
			'isActive' => 1,
			'ca_add_by' => $userId,
			'emp_permission' => implode(",", $data['emp_permission']),
		]);
	}



	public function save_employee(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;

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
			$emp->dept_id = $request->dept_id;
			$emp->desig_id = $request->desig_id;
			$emp->dob = $request->dob;
			$emp->gender = $request->gender;
			$emp->qualification = $request->qualification;
			$emp->c_addr_lineone = $request->c_addr_lineone;
			$emp->c_addr_linetwo = isset($request->c_addr_linetwo) ? $request->c_addr_linetwo : "";
			$emp->c_emp_country = 101;
			$emp->c_emp_state = $request->c_emp_state;
			$emp->c_emp_city = $request->c_emp_city;
			$emp->c_emp_pincode = $request->c_emp_pincode;

			$emp->p_addr_lineone = $request->p_addr_lineone;
			$emp->p_addr_linetwo = isset($request->p_addr_linetwo) ? $request->p_addr_linetwo : "";
			$emp->p_emp_country = 101;
			$emp->p_emp_state = $request->p_emp_state;
			$emp->p_emp_city = $request->p_emp_city;
			$emp->p_emp_pincode = $request->p_emp_pincode;
			$emp->basic_sal = $request->basic_sal;
			$emp->hra = $request->hra;
			$emp->convayance = $request->convayance;
			$emp->special_bonus = $request->special_bonus;
			$emp->provident_fund = $request->provident_fund;
			$emp->esi = $request->esi;
			$emp->loan = $request->loan;
			$emp->ptax = $request->ptax;
			// Auto calculate TDS if gross salary > 10 lakh, otherwise use provided value
			$grossSalary = floatval($request->total_addition);
			if ($grossSalary > 1000000) {
				$tdsResult = $this->calculateTDS($grossSalary);
				$emp->tds = $tdsResult['amount'];
			} else {
				$emp->tds = $request->tds;
			}
			$emp->total_deduction = $request->total_deduction;
			$emp->total_addition = $request->total_addition;
			$emp->net_sal = $request->net_sal;
			$emp->net_sal_word = $request->net_sal_word;
			$emp->save();

			//start add bank
			$userId = currentOwnerId();
			$bank_name = array_filter($request->emp_bank_name);
			$bank_branch = array_filter($request->emp_bank_branch);
			$bank_holder_name = array_filter($request->emp_bank_holder_name);
			$ac_no = array_filter($request->emp_ac_no);
			$ifsc_code = array_filter($request->emp_ifsc_code);
			$swift_code = array_filter($request->emp_swift_code);
			$ac_upid = array_filter($request->emp_ac_upid);

			if (!empty($bank_name) && !empty($bank_branch) && !empty($bank_holder_name) && !empty($ac_no) && !empty($ifsc_code)) {
				$delBank = DB::table('employee_banks')->where('eid', $empId)->delete();

				foreach ($bank_name as $index => $value) {

					$insertBank = DB::table('employee_banks')->insertGetId([
						'eid' => $empId,
						'uid' => $userId,
						'utype' => 4,
						'emp_bank_name' => isset($bank_name[$index]) ? $bank_name[$index] : "",
						'emp_bank_branch' => isset($bank_branch[$index]) ? $bank_branch[$index] : "",
						'emp_bank_holder_name' => isset($bank_holder_name[$index]) ? $bank_holder_name[$index] : "",
						'emp_ac_no' => isset($ac_no[$index]) ? $ac_no[$index] : "",
						'emp_ifsc_code' => isset($ifsc_code[$index]) ? $ifsc_code[$index] : "",
						'emp_swift_code' => isset($swift_code[$index]) ? $swift_code[$index] : "",
						'emp_ac_upid' => isset($ac_upid[$index]) ? $ac_upid[$index] : "",

					]);
				}
			}
			//end add bank

			if ($insertEmployee) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/ca-employee-list'),
					'message' => 'Employee added successfully'
				);
				return response()->json($msg);
			} else {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Enter all details for employee'
				);
				return response()->json($msg);
			}
		}
	}

	public function CAEmployeeDetails()
	{
		return view('Ca.employee-details');
	}

	// public function CAAttendanceList()
	// {
	//     return view('Ca.attendance-list');
	// }
	public function CAAttendanceList()
	{
		$userId = currentOwnerId();
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
			->where('users.ca_add_by', '=', $userId)
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

		return view('Ca.attendance-list')->with([
			'employees' => $employees,
		]);
	}

	public function CAGeneratePayslip()
	{
		// return view('Ca.generate-payslip');

		$userId = currentOwnerId();

		// Fetch employees
		$employees = DB::table('users')
			->where('u_type', 4)
			->where('ca_add_by', $userId)
			->where('status', 1) // Optional: Fetch only active employees
			->orderBy('name', 'asc')
			->get();

		return view('User.generate-payslip', compact('employees'));
	}

	public function getTDSRate(Request $request)
	{
		try {
			// Get TDS rate for salary slab
			$tdsRate = DB::table('tds_tax_slab')
				->where('tds_slab_name', 'Salary')
				->first();

			if ($tdsRate) {
				return response()->json([
					'success' => true,
					'rate' => $tdsRate->tds_slab_rate
				]);
			} else {
				return response()->json([
					'success' => false,
					'message' => 'TDS rate not found for salary slab'
				]);
			}
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Error fetching TDS rate: ' . $e->getMessage()
			]);
		}
	}

	public function calculateTDS($grossSalary)
	{
		// Auto calculate TDS if gross salary is greater than 10 lakh
		if ($grossSalary > 1000000) {
			// Get TDS rate from database or use default 10%
			try {
				$tdsRate = DB::table('tds_tax_slab')
					->where('tds_slab_name', 'Salary')
					->first();

				$rate = $tdsRate ? ($tdsRate->tds_slab_rate / 100) : 0.10; // Default 10%
				return [
					'amount' => $grossSalary * $rate,
					'rate_percentage' => $tdsRate ? $tdsRate->tds_slab_rate : 10
				];
			} catch (\Exception $e) {
				// Fallback to 10% if database query fails
				return [
					'amount' => $grossSalary * 0.10,
					'rate_percentage' => 10
				];
			}
		}
		return [
			'amount' => 0,
			'rate_percentage' => 0
		]; // No TDS for salary <= 10 lakh
	}

	public function calculateTDSAjax(Request $request)
	{
		try {
			$grossSalary = floatval($request->gross_salary);
			$tdsResult = $this->calculateTDS($grossSalary);

			$message = $grossSalary > 1000000
				? "TDS auto-calculated at {$tdsResult['rate_percentage']}% for salary above 10 lakh"
				: 'No TDS required for salary below 10 lakh';

			return response()->json([
				'success' => true,
				'tds_amount' => $tdsResult['amount'],
				'tds_rate' => $tdsResult['rate_percentage'],
				'is_auto_calculated' => $grossSalary > 1000000,
				'message' => $message
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Error calculating TDS: ' . $e->getMessage()
			]);
		}
	}

	public function testTDS($salary)
	{
		$tdsAmount = $this->calculateTDS($salary);
		return response()->json([
			'salary' => $salary,
			'tds_amount' => $tdsAmount,
			'is_above_10_lakh' => $salary > 1000000
		]);
	}
	
	public function payslipList()
	{
		$userId = Auth::user()->id;
		$payslips = DB::table('user_payslip')
			->join('users', 'users.id', '=', 'user_payslip.user_emp_id')
			->select(
				'user_payslip.*',
				'users.name',
				'users.email'
			)
			 ->where('user_payslip.user_emp_id', $userId)
			->orderBy('user_payslip.id', 'desc')
			->get();

		return view('Ca.payslip-list', compact('payslips'));
	}
}
