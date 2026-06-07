<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Customers;
// use App\Models\Customer_banks;
use Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Models\ExpenditureClaim;
use App\Models\SupplyRequisition;
use Illuminate\Support\Facades\Validator;

class CustomerManagement extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function CustomerList(Request $request)
    {
		
		//$this->middleware('auth'); 
		$title = 'Customers';
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		//start search filter
		$data_arry = array();
		if($request->custName !="" ){
			$data_arry["custName"] = $request->custName;
		}
		if($request->custEmail !="" ){
			$data_arry["custEmail"] = $request->custEmail;
		}
		if($request->custPhone !="" ){
			$data_arry["custPhone"] = $request->custPhone;
		}
		if($request->allstatus =="on" ){
			$data_arry["allstatus"] = $request->allstatus;
		}
		if($request->activestatus =="on" ){
			$data_arry["activestatus"] = $request->activestatus;
		}
		if($request->inactivestatus =="on" ){
			$data_arry["inactivestatus"] = $request->inactivestatus;
		}
		//end search filter
		/*$customers = DB::table('customers')
					->where('userId', '=', $userId)
					//start search filter
					->where(function ($query) use ($data_arry) {
						if(isset($data_arry['custName'])) {
							$query->where('cust_name', 'LIKE', '%' . $data_arry['custName'] . '%');
						}
						if(isset($data_arry['custEmail'])) {
							$query->where('cust_email', 'LIKE', '%' . $data_arry['custEmail'] . '%');
						}
						if(isset($data_arry['custPhone'])) {
							$query->where('cust_phone', 'LIKE', '%' . $data_arry['custPhone'] . '%');
						}
						if(isset($data_arry['allstatus'])) {
							$query->whereIn('status', array(0, 1));
						}
						if(isset($data_arry['activestatus'])) {
							$query->where('status', '=', 1);
						}
						if(isset($data_arry['inactivestatus'])) {
							$query->where('status', '=', 0);
						}
						
					})
				->orderBy('id', 'DESC') 
                ->get(); */
		$customers = DB::table('customers as c')
				->leftJoin('sales as s', 's.inv_name', '=', 'c.id')
				->where('c.userId', $userId)
				->select(
					'c.id',
					'c.customer_id',
					'c.cust_name',
					'c.cust_phone',
					'c.cust_email',
					'c.created_at',
					'c.status',
					DB::raw('COUNT(s.id) as total_invoices')
				)
				->where(function ($query) use ($data_arry) {

					if(isset($data_arry['custName'])) {
						$query->where('c.cust_name', 'LIKE', '%' . $data_arry['custName'] . '%');
					}

					if(isset($data_arry['custEmail'])) {
						$query->where('c.cust_email', 'LIKE', '%' . $data_arry['custEmail'] . '%');
					}

					if(isset($data_arry['custPhone'])) {
						$query->where('c.cust_phone', 'LIKE', '%' . $data_arry['custPhone'] . '%');
					}

					if(isset($data_arry['allstatus'])) {
						$query->whereIn('c.status', [0,1]);
					}

					if(isset($data_arry['activestatus'])) {
						$query->where('c.status', 1);
					}

					if(isset($data_arry['inactivestatus'])) {
						$query->where('c.status', 0);
					}

				})
				->groupBy(
					'c.id',
					'c.customer_id',
					'c.cust_name',
					'c.cust_phone',
					'c.cust_email',
					'c.created_at',
					'c.status'
				)
				->orderBy('c.id', 'DESC')
				->get();

		$customers_pagination = $customers;
// 		echo "<pre>"; print_r($customers);exit;
		return view('User.customer-list')->with([
			'title' =>$title,
			'customers'=>$customers,
			'data_arry'=>$data_arry,
			'customers_pagination' =>$customers_pagination,
		]); 
    }

	public function getCustomerId(){

		$userId = currentOwnerId();
		$prefix = 'CUS' . $userId . '-';

		// Get the last customer_id with the same prefix
		$lastCustomer = DB::table('customers')
			->select('customer_id')
			->where('customer_id', 'like', $prefix . '%')
			->orderBy('id', 'desc')
			->first();

		if ($lastCustomer && isset($lastCustomer->customer_id)) {
			// Extract the numeric part after the prefix and increment
			$lastNumber = (int) str_replace($prefix, '', $lastCustomer->customer_id);
			$newNumber = $lastNumber + 1;
		} else {
			// Start with 1 if no existing ID with this prefix
			$newNumber = 1;
		}

		// Format the new customer_id
		$customer_id = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
		return $customer_id;
	}
	
	public function addcustomer()
    {
		//$this->middleware('auth');
		$countries = Country::where('id', '>', '0')->get();	
		$states = State::where('country_id', '=', 101)->get();	
		return view('User.add-customer')->with([
			'countries'=>$countries,	
			'states'=>$states,	
		]); 
    }


	protected function validator(array $data)
	{
		$rules = [
			'gst_reg'    => 'required',
			'cust_name'  => 'required|string|max:255',
			'cust_pan'   => ['nullable','regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/'],
			'cust_email' => 'nullable|email',
			'cust_phone' => 'nullable|digits:10',
			'comp_type'  => 'required',

			'cust_bill_addone' => 'required|string',
			'cust_bill_state'  => 'required|string',
			'cust_bill_city'   => 'required|string',
			'cust_bill_pin'    => 'required|digits:6',
		];

		$messages = [
			'gst_reg.required'   => 'GST Registration is required.',
			'cust_name.required' => 'Company Name is required.',

			//'cust_pan.required' => 'PAN Number is required.',
			'cust_pan.regex'    => 'Invalid PAN format. Example: AAAAA9999A',

// 			'cust_email.required' => 'Email is required.',
			'cust_email.email'    => 'Enter a valid email address.',

// 			'cust_phone.required' => 'Phone Number is required.',
			'cust_phone.digits'   => 'Phone Number must be 10 digits.',

			'comp_type.required' => 'Company Type is required.',

			'cust_bill_addone.required' => 'Billing Address Line 1 is required.',
			'cust_bill_state.required'  => 'Billing State is required.',
			'cust_bill_city.required'   => 'Billing City is required.',
			'cust_bill_pin.required'    => 'Billing Zip Code is required.',
			'cust_bill_pin.digits'      => 'Billing Zip Code must be 6 digits.',

			'cust_gst_no.required' => 'GST Number is required.',
			'cust_gst_no.regex'    => 'Invalid GST format. Example: 22AAAAA0000A1Z5',
			'cust_gst_type.required' => 'GSTIN Type is required.',

			'other_comp.required' => 'Custom Company Type is required.',
		];

		/**
		 * GST conditional validation
		 */
		if (($data['gst_reg'] ?? '') === "Yes") {
			$rules['cust_gst_no']   = ['required','regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'];
			$rules['cust_gst_type'] = 'required';
		}

		/**
		 * Company type conditional validation
		 */
		$type = $data['comp_type'] ?? '';

		if (in_array($type, [
            "One person Company (OPC)",
            "PVT Ltd Company",
            "LTD Company",
            "Section-8 Company"
        ])) {
            $rules['cin']      = ['nullable','regex:/^[A-Z]{1}[0-9]{5}[A-Z]{2}[0-9]{4}[A-Z]{3}[0-9]{6}$/'];
            $rules['inc_date'] = 'nullable|date';
        
            $messages['cin.regex'] = 'Invalid CIN format. Example: L12345MH2020PLC123456';
        }
        
        elseif ($type === "LLP Company") {
            $rules['llpin']    = 'nullable|string|max:20';
            $rules['inc_date'] = 'nullable|date';
        }
        
        elseif ($type === "Society/Trust") {
            $rules['reg_no']   = 'nullable|string|max:50';
            $rules['inc_date'] = 'nullable|date';
        }

		elseif ($type === "Other") {
			$rules['other_comp'] = 'required|string|max:255';
		}

		return Validator::make($data, $rules, $messages);
	}



    protected function create(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$uType = currentOwnerUserType();
        //return Customers::create([
		$customer = Customers::create([
            'userId' => $userId,
            'utype' => $uType,
            'cust_value' => $data['cust_value'],
			'gst_reg' => $data['gst_reg'],
            'cust_pan' => $data['cust_pan'],
            'cust_name' => isset($data['cust_name'])?$data['cust_name']:"",
			'comp_type' => $data['comp_type'],
			'cin' => $data['cin'] ?? $data['llpin'] ?? $data['reg_no'] ?? null,
			'inc_date' => $data['inc_date'] ?? null,
            'cust_gst_no' => $data['cust_gst_no'],
            'cust_gst_type' => $data['cust_gst_type'],
			'cust_email' => $data['cust_email'],
			'cust_phone' => $data['cust_phone'],
			'cont_name' => $data['cont_name'],
			'cont_no' => $data['cont_no'],
			'cont_email' => $data['cont_email'],
			'cont_notes' => $data['cont_notes'],
			//'other_comp' => $data['other_comp'], // khokan
			
			'cust_bill_gstno' => $data['cust_bill_gstno'],
			'cust_bill_contact' => $data['cust_bill_contact'],
			'cust_bill_mobilno' => $data['cust_bill_mobilno'],
			'cust_bill_designa' => $data['cust_bill_designa'],

			
			'cust_bill_addone' => $data['cust_bill_addone'],
			'cust_bill_addtwo' => isset($data['cust_bill_addtwo'])?$data['cust_bill_addtwo']:"",
			'cust_bill_country' => $data['cust_bill_country'] ?? '101',
			'cust_bill_state' => $data['cust_bill_state'],
			'cust_bill_city' => $data['cust_bill_city'],
			'cust_bill_pin' => $data['cust_bill_pin'],

			'cust_ship_gstno' => $data['cust_ship_gstno'],
			'cust_ship_contact' => $data['cust_ship_contact'],
			'cust_ship_mobilno' => $data['cust_ship_mobilno'],
			'cust_ship_designa' => $data['cust_ship_designa'],
			
			
			'cust_ship_addone' => $data['cust_ship_addone'],
			'cust_ship_addtwo' => isset($data['cust_ship_addtwo'])?$data['cust_ship_addtwo']:"",
			'cust_ship_country' => $data['cust_ship_country'] ?? '101',
			'cust_ship_state' => $data['cust_ship_state'],
			'cust_ship_city' => $data['cust_ship_city'],
			'cust_ship_pin' => $data['cust_ship_pin'],
			'created_at' => date('Y-m-d H:i:s'),
			'status'=> '1',
        ]);

		 // Format the customer_id as CID-000010
		// $formattedCustomerId = 'CID-' . str_pad($customer->id, 6, '0', STR_PAD_LEFT);

		// Update the customer with the formatted customer_id
		$customer->customer_id = $this->getCustomerId();
		$customer->save();
	
		return $customer;
    }

	public function add_customer(Request $request)
	{
		// Validate request
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json([
				'class'   => 'err',
				'message' => $validation->errors()->first()
			]);
		}

		// Insert customer
		$insertCustomer = $this->create($request->all());

		$userId = currentOwnerId();
		$utype = currentOwnerUserType();

		// Get bank arrays safely
		$cust_bank_name        = $request->cust_bank_name ?? [];
		$cust_bank_branch      = $request->cust_bank_branch ?? [];
		$cust_bank_holder_name = $request->cust_bank_holder_name ?? [];
		$cust_ac_no            = $request->cust_ac_no ?? [];
		$cust_ifsc_code        = $request->cust_ifsc_code ?? [];
		$cust_ac_upid          = $request->cust_ac_upid ?? [];

		$hasBankInserted = false;

		/**
		 * Insert only fully completed bank rows
		 */
		foreach ($cust_bank_name as $index => $name) {

			if (
				!empty($name) &&
				!empty($cust_bank_branch[$index]) &&
				!empty($cust_bank_holder_name[$index]) &&
				!empty($cust_ac_no[$index]) &&
				!empty($cust_ifsc_code[$index])
			) {
				DB::table('customer_banks')->insert([
					'custId' => $insertCustomer->id,
					'uid'    => $userId,
					'utype'  => $utype,
					'cust_bank_name'        => $name,
					'cust_bank_branch'      => $cust_bank_branch[$index],
					'cust_bank_holder_name' => $cust_bank_holder_name[$index],
					'cust_ac_no'            => $cust_ac_no[$index],
					'cust_ifsc_code'        => $cust_ifsc_code[$index],
					'cust_ac_upid'          => $cust_ac_upid[$index] ?? null,
				]);

				$hasBankInserted = true;
			}
		}

		/**
		 * Final response
		 */
		return response()->json([
			'status'   => 'success',
			'class'    => 'succ',
			'redirect' => url('customer-list'),
			'message'  => $hasBankInserted
				? 'Customer and bank details saved successfully.'
				: 'Customer saved successfully (no bank details added).'
		]);
	}

	
	public function edit_customer($custId)  {  
        
		$custId = base64_decode($custId);
		$customer = DB::table('customers')
								->where('id', '=', $custId)
								->get();
		$bankDetails = DB::table('customer_banks')->where('custId', '=', $custId)->get();
		$bankDetails = isset($bankDetails)?$bankDetails:[];

		$customer = $customer[0];
		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('country_id', '=', $customer->cust_bill_country)->get();
		$cities_bill = City::where('state_id', '=', $customer->cust_bill_state)->get();
		
		$states_ship = State::where('country_id', '=', $customer->cust_ship_country)->get();
		$cities_ship = City::where('state_id', '=', $customer->cust_ship_state)->get();
		$states = State::where('country_id', '=', 101)->get();

		// echo '<pre>'; print_r($customer); exit;
		
		return view('User.edit-customer')->with([
				'countries'=>$countries,
				'states_bill'=>$states_bill,
				'cities_bill'=>$cities_bill,
				'states_ship'=>$states_ship,
				'cities_ship'=>$cities_ship,
				'customer' => $customer,		
				'bankDetails' => $bankDetails,
				'custId' => $custId,
				'states' => $states
			]); 
    }
	
	public function view_customer($custId)  {  
        
		$custId = base64_decode($custId);
		$customer = DB::table('customers')
								->where('id', '=', $custId)
								->get();
		$bankDetails = DB::table('customer_banks')->where('custId', '=', $custId)->get();
		$bankDetails = isset($bankDetails)?$bankDetails:[];

		$customer = $customer[0];
		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('country_id', '=', $customer->cust_bill_country)->get();
		$cities_bill = City::where('state_id', '=', $customer->cust_bill_state)->get();
		
		$states_ship = State::where('country_id', '=', $customer->cust_ship_country)->get();
		$cities_ship = City::where('state_id', '=', $customer->cust_ship_state)->get();
		$states = State::where('country_id', '=', 101)->get();
		 
		 return view('User.view-customer')->with([
				'countries'=>$countries,
				'states_bill'=>$states_bill,
				'cities_bill'=>$cities_bill,
				'states_ship'=>$states_ship,
				'cities_ship'=>$cities_ship,
				'customer' => $customer,		
				'bankDetails' => $bankDetails,
				'custId' => $custId,
				'states' => $states
			]); 
    }

	public function update_customer(Request $request)
	{
		$custId = $request->id;

		// Validate same as add_customer
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json([
				'class'   => 'err',
				'message' => $validation->errors()->first()
			]);
		}

		/**
		 * Update customer details
		 */
		DB::table('customers')
			->where('id', $custId)
			->update([
				'cust_value' => $request->cust_value,
				'gst_reg'    => $request->gst_reg,
				'cust_pan'   => $request->cust_pan,
				'cust_name'  => $request->cust_name,
				'comp_type' => $request->comp_type,
				'cin' 		=> $request->cin ?? $request->llpin ?? $request->reg_no ?? null,
				'inc_date' 	=> $request->inc_date,

				'cust_gst_no'   => $request->cust_gst_no,
				'cust_gst_type' => $request->cust_gst_type,
				'cust_email' => $request->cust_email,
				'cust_phone' => $request->cust_phone,
				'cont_name'  => $request->cont_name,
				'cont_no'    => $request->cont_no,
				'cont_email' => $request->cont_email,
				'cont_notes' => $request->cont_notes,
				'other_comp' => $request->other_comp,

				'cust_bill_gstno'  => $request->cust_bill_gstno,
				'cust_bill_contact'=> $request->cust_bill_contact,
				'cust_bill_mobilno'=> $request->cust_bill_mobilno,
				'cust_bill_designa'=> $request->cust_bill_designa,
				'cust_bill_name'   => $request->cust_bill_name,
				'cust_bill_addone' => $request->cust_bill_addone,
				'cust_bill_addtwo' => $request->cust_bill_addtwo,
				'cust_bill_country'=> $request->cust_bill_country ?? '101',
				'cust_bill_state'  => $request->cust_bill_state,
				'cust_bill_city'   => $request->cust_bill_city,
				'cust_bill_pin'    => $request->cust_bill_pin,

				'cust_ship_gstno'   => $request->cust_ship_gstno,
				'cust_ship_contact' => $request->cust_ship_contact,
				'cust_ship_mobilno' => $request->cust_ship_mobilno,
				'cust_ship_designa' => $request->cust_ship_designa,
				'cust_ship_name'    => $request->cust_ship_name,
				'cust_ship_addone'  => $request->cust_ship_addone,
				'cust_ship_addtwo'  => $request->cust_ship_addtwo,
				'cust_ship_country' => $request->cust_ship_country ?? '101',
				'cust_ship_state'   => $request->cust_ship_state,
				'cust_ship_city'    => $request->cust_ship_city,
				'cust_ship_pin'     => $request->cust_ship_pin,
			]);

		/**
		 * Bank handling (same logic as add)
		 */
		$userId = currentOwnerId();
		$utype = currentOwnerUserType();

		$cust_bank_name        = $request->cust_bank_name ?? [];
		$cust_bank_branch      = $request->cust_bank_branch ?? [];
		$cust_bank_holder_name = $request->cust_bank_holder_name ?? [];
		$cust_ac_no            = $request->cust_ac_no ?? [];
		$cust_ifsc_code        = $request->cust_ifsc_code ?? [];
		$cust_ac_upid          = $request->cust_ac_upid ?? [];

		$hasBankInserted = false;

		// Delete old banks first
		DB::table('customer_banks')->where('custId', $custId)->delete();

		foreach ($cust_bank_name as $index => $name) {

			if (
				!empty($name) &&
				!empty($cust_bank_branch[$index]) &&
				!empty($cust_bank_holder_name[$index]) &&
				!empty($cust_ac_no[$index]) &&
				!empty($cust_ifsc_code[$index])
			) {
				DB::table('customer_banks')->insert([
					'custId' => $custId,
					'uid'    => $userId,
					'utype'  => $utype,
					'cust_bank_name'        => $name,
					'cust_bank_branch'      => $cust_bank_branch[$index],
					'cust_bank_holder_name' => $cust_bank_holder_name[$index],
					'cust_ac_no'            => $cust_ac_no[$index],
					'cust_ifsc_code'        => $cust_ifsc_code[$index],
					'cust_ac_upid'          => $cust_ac_upid[$index] ?? null,
				]);

				$hasBankInserted = true;
			}
		}

		/**
		 * Final response
		 */
		return response()->json([
			'status'   => 'success',
			'class'    => 'succ',
			'redirect' => url('customer-list'),
			'message'  => $hasBankInserted
				? 'Customer and bank details updated successfully.'
				: 'Customer updated successfully (no bank details added).'
		]);
	}

	
	//Activate customer
	public function changeStatus(Request $request)
	{
		try {
			// Validate request
			$request->validate([
				'id' => 'required|exists:customers,id',
				'status' => 'required|in:0,1'
			]);

			// Find the customer
			$customer = Customers::findOrFail($request->id);

			// Update status
			$customer->status = $request->status;
			$customer->save();

			// Return JSON response
			return response()->json([
				'status' => true,
				'class' => 'succ',
				'redirect' => url('/customers'),
				'message' => 'Status updated successfully.'
			]);

		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'status' => false,
				'message' => 'Invalid data provided.',
				'errors' => $e->errors()
			], 422);

		} catch (\Exception $e) {
			\Log::error('Status Update Error: ' . $e->getMessage()); // Log error
			return response()->json([
				'status' => false,
				'message' => 'Failed to update status. Please try again later.'
			], 500);
		}
	}


	
	public function delCustomer($id)
	{
		$customer = Customers::find($id);

		if (!$customer) {
			return response()->json(['status' => false, 'message' => 'Customer not found'], 404);
		}

		// Update the status to 0 instead of deleting
		$customer->status = 0;
		$customerUpdated = $customer->save();

		// Also update the related customer_banks table (if needed)
		$bankUpdated = DB::table('customer_banks')->where('custId', $id)->update(['status' => 0]);

		if ($customerUpdated) {
			return response()->json(['status' => true, 'message' => 'Customer status updated to inactive']);
		} else {
			return response()->json(['status' => false, 'message' => 'Failed to update customer status']);
		}
	}

	// Show Expenditure Claims
    public function expenditureClaims()
    {	
        $userId = currentOwnerId();
		checkCoreAccess('HR & Payroll Management');
		$claims = \DB::table('expenditure_claims')
				->join('employees', 'employees.employee_id', '=', 'expenditure_claims.employee_id')
				->join('users', 'users.id', '=', 'employees.empId')
				->where('expenditure_claims.added_by', $userId)
				->select(
					'expenditure_claims.*',
					'users.name as employee_name'
				)
				->get();

		$employees = \DB::table('employees')
				->join('users', 'users.id', '=', 'employees.empId')
				->where('employees.added_by', $userId)
				->select('employees.employee_id as employee_id', 'users.name as employee_name')
				->get();

		// echo "<pre>"; print_r($claims);exit;
		return view('User.expenditure_claims', compact('claims', 'employees'));
        
    }

    // Store Expenditure Claim
    public function expenditureStore(Request $request)
	{
		$userId = currentOwnerId();
		$uType = currentOwnerUserType();
		try {
			$request->validate([
				'employee_id'    => 'required',
				'date'           => 'required|date',
				'category'       => 'required|string|max:255',
				'claim_amount'   => 'required|numeric',
				'details'        => 'required|string',
				'payment_method' => 'required|string|max:50',
				'receipt'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
				'comments'       => 'nullable|string',
			]);

			$filePath = null;
			if ($request->hasFile('receipt')) {
				$file = $request->file('receipt');
				// Store file in "receipts" folder
				$path = $file->store('receipts', 'public');

				// Get only the filename (without folder)
				$filePath = basename($path);
			}

			ExpenditureClaim::create([
				'employee_id'    => $request->employee_id,
				'claim_date'     => $request->date,
				'category'       => $request->category,
				'claim_amount'   => $request->claim_amount,
				'description'    => $request->details,
				'payment_method' => $request->payment_method,
				'receipt'        => $filePath,
				'comments'       => $request->comments,
				'added_by'       => $userId,
				'u_type'         => $uType,
			]);

			return response()->json([
				'status'  => 'success',
				'message' => 'Expenditure claim submitted successfully!'
			]);

		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'status'  => 'error',
				'message' => $e->errors()
			], 422);

		} catch (\Exception $e) {
			return response()->json([
				'status'  => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}

	// Update Expenditure Claim
    public function expenditureUpdate(Request $request, $id)
	{
		$claim = \App\Models\ExpenditureClaim::findOrFail($id);

		$request->validate([
			'employee_id'    => 'required',
			'date'           => 'required|date',
			'category'       => 'required|string|max:255',
			'claim_amount'   => 'required|numeric',
			'details'        => 'required|string',
			'payment_method' => 'required|string|max:50',
			'receipt'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
			'comments'       => 'nullable|string',
			'status'         => 'required|in:Pending,Approved,Rejected',
		]);

		// Handle receipt
		if ($request->hasFile('receipt')) {
			// Delete old file if exists
			if ($claim->receipt && \Storage::disk('public')->exists('receipts/' . $claim->receipt)) {
				\Storage::disk('public')->delete('receipts/' . $claim->receipt);
			}

			// Store file in "receipts" folder
			$path = $request->file('receipt')->store('receipts', 'public');

			// Save only the filename
			$filePath = basename($path);
		} else {
			$filePath = $claim->receipt;
		}

		$claim->update([
			'employee_id'    => $request->employee_id,
			'claim_date'     => $request->date,
			'category'       => $request->category,
			'claim_amount'   => $request->claim_amount,
			'description'    => $request->details,
			'payment_method' => $request->payment_method,
			'receipt'        => $filePath,
			'comments'       => $request->comments,
			'status'         => $request->status,
		]);

		return response()->json(['status' => true, 'message' => 'Expenditure claim updated successfully!']);
	}



    // Show Supply Requisitions
    public function supplyRequisitions()
    {
        $userId = currentOwnerId();
		checkCoreAccess('HR & Payroll Management');
		$requisitions = \DB::table('supply_requisitions')
				->join('employees', 'employees.employee_id', '=', 'supply_requisitions.employee_id')
				->join('users', 'users.id', '=', 'employees.empId')
				->where('supply_requisitions.added_by', $userId)
				->select(
					'supply_requisitions.*',
					'users.name as employee_name'
				)
				->get();

		$employees = \DB::table('employees')
				->join('users', 'users.id', '=', 'employees.empId')
				->where('employees.added_by', $userId)
				->select('employees.employee_id as employee_id', 'users.name as employee_name')
				->get();
		// echo "<pre>"; print_r($requisitions);exit;

        return view('User.supply_requisitions', compact('requisitions','employees'));
    }

    // Store Supply Requisition
    public function supplyRequisitionsStore(Request $request)
	{
		$userId = currentOwnerId();
		$uType = currentOwnerUserType();
		$request->validate([
			'employee_id'     => 'required',
			'date'            => 'required|date',
			'category'        => 'required|string|max:255',
			'details'         => 'required|string',
			'quantity'        => 'required|integer|min:1',
			'amount'          => 'required|numeric|min:0',
			'priority'        => 'required|string',
			'return_exchange' => 'nullable|string',
			'attachment'      => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:5120',
			'comments'        => 'nullable|string',
		]);

		try {
			$fileName = null;
			if ($request->hasFile('attachment')) {
				$fileName = time() . '_' . $request->file('attachment')->getClientOriginalName();
				$request->file('attachment')->storeAs('attachments', $fileName, 'public');
			}

			SupplyRequisition::create([
				'employee_id'     => $request->employee_id,
				'requisition_date'=> $request->date,
				'category'        => $request->category,
				'details'         => $request->details,
				'quantity'        => $request->quantity,
				'amount'          => $request->amount,
				'priority'        => $request->priority,
				'return_exchange' => $request->return_exchange,
				'attachment'      => $fileName,
				'comments'        => $request->comments,
				'added_by'        => $userId,
				'u_type'          => $uType,
			]);

			return response()->json(['success' => true, 'message' => 'Supply requisition submitted successfully!']);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => 'Something went wrong: '.$e->getMessage()]);
		}
	}

    
	public function supplyRequisitionsUpdate(Request $request, $id)
	{
		$requisition = \App\Models\SupplyRequisition::findOrFail($id);

		$request->validate([
			'employee_id'     => 'required',
			'date'            => 'required|date',
			'category'        => 'required|string|max:255',
			'details'         => 'required|string',
			'quantity'        => 'required|integer|min:1',
			'amount'          => 'required|numeric|min:0',
			'priority'        => 'required|string',
			'return_exchange' => 'nullable|string',
			'attachment'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'comments'        => 'nullable|string',
			'status'          => 'required|in:Pending,Approved,Rejected',
		]);

		// Handle attachment
		if ($request->hasFile('attachment')) {
			// Delete old file if exists
			if ($requisition->attachment && \Storage::disk('public')->exists('attachments/' . $requisition->attachment)) {
				\Storage::disk('public')->delete('attachments/' . $requisition->attachment);
			}
			$fileName = time() . '_' . $request->file('attachment')->getClientOriginalName();
			$request->file('attachment')->storeAs('attachments', $fileName, 'public');
		} else {
			$fileName = $requisition->attachment;
		}

		$requisition->update([
			'employee_id'     => $request->employee_id,
			'requisition_date'=> $request->date,
			'category'        => $request->category,
			'details'         => $request->details,
			'quantity'        => $request->quantity,
			'amount'          => $request->amount,
			'priority'        => $request->priority,
			'return_exchange' => $request->return_exchange,
			'attachment'      => $fileName,
			'comments'        => $request->comments,
			'status'          => $request->status,
		]);

		return response()->json(['success' => true, 'message' => 'Supply requisition updated successfully!']);
	}
	

}
