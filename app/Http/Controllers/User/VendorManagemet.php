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
use Validator;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Vendor;
// use App\Company_profiles;
// use App\Company_banks;
use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;

class VendorManagemet extends Controller
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
  

    public function VendorList()
    {
      //$this->middleware('auth'); 
      $title = 'vendors';
      $userId = currentOwnerId();
	  checkCoreAccess('Accounting');
      //$vendors = DB::table('vendors')->where('userId', '=', $userId)->orderBy('id', 'DESC')->paginate(10);
      if(Auth::user()->u_type ==1){ //ca
        $vendors =  DB::table('vendors')
                ->select(DB::raw('vendors.*,company_profiles.comp_name,ca_assigns.ca_id'))
                ->leftJoin('company_profiles', 'vendors.userId', '=', 'company_profiles.userId')
                ->leftJoin('ca_assigns', 'vendors.userId', '=', 'ca_assigns.comp_id')
                ->where('ca_assigns.ca_id','=',$userId)
                ->where('ca_assigns.ca_assign_status','=',1)
                                ->orderBy('id', 'DESC')->get();
      }else if(Auth::user()->u_type ==4){ //ca employee
        $vendors =  DB::table('vendors')
                ->select(DB::raw('vendors.*,company_profiles.comp_name,ca_assigns.ca_id'))
                ->leftJoin('company_profiles', 'vendors.userId', '=', 'company_profiles.userId')
                ->leftJoin('ca_assigns', 'vendors.userId', '=', 'ca_assigns.comp_id')
                ->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
                ->where('ca_assigns.ca_assign_status','=',1)
                                ->orderBy('id', 'DESC')->get();
      }elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
        $vendors =  DB::table('vendors')
                ->select(DB::raw('vendors.*,company_profiles.comp_name'))
                ->leftJoin('company_profiles', 'vendors.userId', '=', 'company_profiles.userId')
                ->where('vendors.userId', '=', $userId)
                                ->orderBy('id', 'DESC')->get();
      }
      elseif(Auth::user()->u_type ==3){ //admin
        $vendors =  DB::table('vendors')
                ->select(DB::raw('vendors.*,company_profiles.comp_name'))
                ->leftJoin('company_profiles', 'vendors.userId', '=', 'company_profiles.userId')
                                ->orderBy('id', 'DESC')->get();
      }
      
      $vendors_pagination = $vendors;
      //echo "<pre>"; print_r($customers);exit;
      return view('User.vendor-list')->with([
        'title' =>$title,
        'vendors'=>$vendors,
        'vendors_pagination' =>$vendors_pagination,
      ]); 
    }
	
	public function addvendor(Request $request)
	{

		$countries = Country::where('id', '>', '0')->get();	
		$states = State::where('country_id', '=', 101)->get();
		return view('User.add-vendor')->with([
		  'countries'=>$countries,
		  'states'=>$states,
					
		]); 
	}

  protected function validator(array $data)
  {
      $rules = [
          'vendor_priority' => 'required',

          'gst_reg'      => 'required',
          'vendor_name'  => 'required|string|max:255',

          'vendor_pan'   => ['required','regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],

          'comp_type' => 'required',

          'vendor_bill_addone' => 'required|string',
          'vendor_bill_state'    => 'required|string',
          'vendor_bill_city'   => 'required|string',
          'vendor_bill_pin'    => 'required|digits:6',
      ];

      $messages = [
          'vendor_priority.required' => 'Vendor Type is required.',

          'gst_reg.required'     => 'GST Registration is required.',
          'vendor_name.required' => 'Company Name is required.',

          'vendor_pan.required' => 'PAN Number is required.',
          'vendor_pan.regex'    => 'Invalid PAN format. Example: AAAAA9999A',

          'comp_type.required' => 'Company Type is required.',

          'vendor_bill_addone.required' => 'Billing Address Line 1 is required.',
          'vendor_bill_state.required'    => 'Billing State is required.',
          'vendor_bill_city.required'   => 'Billing City is required.',
          'vendor_bill_pin.required'    => 'Billing Zip Code is required.',
          'vendor_bill_pin.digits'      => 'Billing Zip Code must be 6 digits.',

          'vendor_gst_no.required' => 'GST Number is required.',
          'vendor_gst_no.regex'    => 'Invalid GST Number. Example: 22AAAAA0000A1Z5',

      ];

      /**
       * GST conditional validation
       */
      if (($data['gst_reg'] ?? '') === "Yes") {
          $rules['vendor_gst_no'] = [
              'required',
              'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'
          ];
      }

      /**
       * Company-type conditional validation
       */
      $type = $data['comp_type'] ?? '';

      $cinTypes = [
          "One person Company (OPC)",
          "PVT Ltd Company",
          "LTD Company",
          "Section-8 Company"
      ];

      if (in_array($type, $cinTypes)) {
            $rules['cin'] = [
                'nullable',
                'regex:/^[A-Z]{1}[0-9]{5}[A-Z]{2}[0-9]{4}[A-Z]{3}[0-9]{6}$/'
            ];
            $rules['inc_date'] = 'nullable|date';
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


    public function getVendorId() {
      $userId = currentOwnerId();
  
      // Define prefix based on user ID
      $prefix = 'VEN' . $userId . '-';
  
      // Get the last vendor_id with the same prefix
      $lastVendor = DB::table('vendors')
          ->select('vendor_id')
          ->where('vendor_id', 'like', $prefix . '%')
          ->orderBy('id', 'desc')
          ->first();
  
      if ($lastVendor && isset($lastVendor->vendor_id)) {
          // Extract number and increment
          $lastNumber = (int) str_replace($prefix, '', $lastVendor->vendor_id);
          $newNumber = $lastNumber + 1;
      } else {
          $newNumber = 1;
      }
  
      // Format the new vendor_id
      $vendor_id = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
  
      return $vendor_id;
  }
  

    protected function create(array $data)
    {
		$userId = currentOwnerId();
		$uType = currentOwnerUserType();
        return Vendor::create([
            'vendor_id' => $this->getVendorId(),
            'userId' => $userId,
            'utype' => $uType,
            'vendor_priority' => $data['vendor_priority'],
            'gst_reg' => $data['gst_reg'],
            'vendor_gstin' => $data['vendor_gst_no'],
            'vendor_gst_type' => $data['vendor_gst_type'],
            'vendor_name' => $data['vendor_name'],
            'vendor_pan' => $data['vendor_pan'],
            'vendor_email' => $data['vendor_email'],
            'vendor_phone' => $data['vendor_phone'],
            'comp_type' => $data['comp_type'],
            'other_comp' => $data['other_comp'],
            'cin' => $data['cin'] ?? $data['llpin'] ?? $data['reg_no'] ?? null,
            'inc_date' => $data['inc_date'],

            'cont_per_name' => $data['cont_name'],
            'cont_per_number' => $data['cont_no'],
            'cont_per_email' => $data['cont_email'],
            'special_note' => $data['cont_notes'],

            'cust_bill_gstno' => $data['vendor_bill_gstno'],
            'cust_bill_contact' => $data['vendor_bill_contact'],
            'cust_bill_designa' => $data['vendor_bill_designa'],
            'cust_bill_mobilno' => $data['vendor_bill_mobilno'],
            'billing_address1' => $data['vendor_bill_addone'],
            'billing_address2' => $data['vendor_bill_addtwo'],
            'billing_state' => $data['vendor_bill_state'],
            'billing_city' => $data['vendor_bill_city'],
            'billing_pincode' => $data['vendor_bill_pin'],

            'cust_ship_gstno' => $data['vendor_ship_gstno'],
            'cust_ship_contact' => $data['vendor_ship_contact'],
            'cust_ship_designa' => $data['vendor_ship_designa'],
            'cust_ship_mobilno' => $data['vendor_ship_mobilno'],
            'shipping_address1' => $data['vendor_ship_addone'],
            'shipping_address2' => $data['vendor_ship_addtwo'],
            'shipping_state' => $data['vendor_ship_state'],
            'shipping_city' => $data['vendor_ship_city'],
            'shipping_pincode' => $data['vendor_ship_pin'],
            'status'=>1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);          
    }

    public function saveaddvendor(Request $request)
    {
        $validation = $this->validator($request->all());

        if ($validation->fails()) {
            return response()->json($validation->errors()->toArray());
        }

        // ---- Save vendor first ----
        $this->create($request->all());
        $vendorId = DB::getPdo()->lastInsertId();

        $userId = currentOwnerId();
        $utype  = currentOwnerUserType();

        $bank_name        = $request->bank_name ?? [];
        $bank_branch      = $request->bank_branch ?? [];
        $acc_holder_name  = $request->acc_holder_name ?? [];
        $acc_number       = $request->acc_number ?? [];
        $acc_ifsc         = $request->acc_ifsc ?? [];
        $acc_upi_id       = $request->acc_upi_id ?? [];

        /**
         * Check if ANY bank field has value
         */
        $hasBankData = false;

        foreach ($bank_name as $i => $val) {
            if (
                !empty($bank_name[$i]) ||
                !empty($bank_branch[$i]) ||
                !empty($acc_holder_name[$i]) ||
                !empty($acc_number[$i]) ||
                !empty($acc_ifsc[$i]) ||
                !empty($acc_upi_id[$i])
            ) {
                $hasBankData = true;
                break;
            }
        }

        /**
         * If bank data exists → validate & insert
         */
        if ($hasBankData) {

            foreach ($bank_name as $i => $val) {

                // Skip fully empty row
                if (
                    empty($bank_name[$i]) &&
                    empty($bank_branch[$i]) &&
                    empty($acc_holder_name[$i]) &&
                    empty($acc_number[$i]) &&
                    empty($acc_ifsc[$i]) &&
                    empty($acc_upi_id[$i])
                ) {
                    continue;
                }

                // Validate required bank fields
                if (
                    empty($bank_name[$i]) ||
                    empty($bank_branch[$i]) ||
                    empty($acc_holder_name[$i]) ||
                    empty($acc_number[$i]) ||
                    empty($acc_ifsc[$i])
                ) {
                    return response()->json([
                        'status'  => 'error',
                        'class'   => 'err',
                        'message' => 'Fill all required bank fields or leave entire row empty.'
                    ]);
                }

                DB::table('vendor_bank_details')->insert([
                    'vendor_id'        => $vendorId,
                    'uid'              => $userId,
                    'utype'            => $utype,
                    'bank_name'        => $bank_name[$i],
                    'bank_branch'      => $bank_branch[$i],
                    'acc_holder_name'  => $acc_holder_name[$i],
                    'acc_number'       => $acc_number[$i],
                    'acc_ifsc'         => $acc_ifsc[$i],
                    'acc_upi_id'       => $acc_upi_id[$i] ?? null,
                ]);
            }
        }

        /**
         * Final success response (always after vendor save)
         */
        return response()->json([
            'status'   => 'success',
            'class'    => 'succ',
            'redirect' => url('/vendor-list'),
            'message'  => 'Vendor saved successfully'
        ]);
    }


    public function edit_vendor($vendorId)  {  
      
      $vendorId = base64_decode($vendorId);
      //echo $vendorId;exit;
      $vendor = DB::table('vendors')
                  ->where('id', '=', $vendorId)
                  ->get();
      $bankDetails = DB::table('vendor_bank_details')->where('vendor_id', '=', $vendorId)->get();
      $bankDetails = isset($bankDetails)?$bankDetails:[];
     // echo "<pre>";print_r($bankDetails);exit;
      $vendor = $vendor[0];
      
      $countries = Country::where('id', '>', '0')->get();
          $states_bill = State::where('country_id', '=', $vendor->billing_country)->get();
      $cities_bill = City::where('state_id', '=', $vendor->billing_state)->get();
      
      $states_ship = State::where('country_id', '=', $vendor->shipping_country)->get();
      $cities_ship = City::where('state_id', '=', $vendor->shipping_state)->get();
      $states = State::where('country_id', '=', 101)->get();

       
       return view('User.edit-vendor')->with([
				'countries'=>$countries,
				'states_bill'=>$states_bill,
				'cities_bill'=>$cities_bill,
				'states_ship'=>$states_ship,
				'cities_ship'=>$cities_ship,
				'bankDetails' => $bankDetails,
				'vendor' => $vendor,
				'states' => $states
        ]); 
      }

      public function update_vendor(Request $request)
      { 
          // echo "<pre>";print_r($request->all());exit;
          $vendorId = $request->id;

          // Validation
          $validation = $this->validator($request->all());
          if ($validation->fails()) {
              return response()->json($validation->errors()->toArray());
          }

          // ===============================
          // Update Vendor Basic Details
          // ===============================
          DB::table('vendors')
              ->where('id', $vendorId)
              ->update([
                  'vendor_priority'      => $request->vendor_priority,
                  'gst_reg'              => $request->gst_reg,
                  'vendor_gstin'         => $request->vendor_gst_no,
                  'vendor_gst_type'      => $request->vendor_gst_type,
                  'vendor_name'          => $request->vendor_name,
                  'vendor_pan'           => $request->vendor_pan,
                  'vendor_email'         => $request->vendor_email,
                  'vendor_phone'         => $request->vendor_phone,
                  'comp_type'            => $request->comp_type,
                  'other_comp'           => $request->other_comp,
                  'cin'                  => $request->cin ?? $request->llpin ?? $request->reg_no ?? null,
                  'inc_date'             => $request->inc_date,
                  'cont_per_name'        => $request->cont_name,
                  'cont_per_number'      => $request->cont_no,
                  'cont_per_email'       => $request->cont_email,
                  'special_note'         => $request->cont_notes,
                  'cust_bill_gstno'      => $request->vendor_bill_gstno,
                  'cust_bill_contact'    => $request->vendor_bill_contact,
                  'cust_bill_designa'    => $request->vendor_bill_designa,
                  'cust_bill_mobilno'    => $request->vendor_bill_mobilno,
                  'billing_address1'     => $request->vendor_bill_addone,
                  'billing_address2'     => $request->vendor_bill_addtwo,
                  'billing_state'        => $request->vendor_bill_state,
                  'billing_city'         => $request->vendor_bill_city,
                  'billing_pincode'      => $request->vendor_bill_pin,
                  'cust_ship_gstno'      => $request->vendor_ship_gstno,
                  'cust_ship_contact'    => $request->vendor_ship_contact,
                  'cust_ship_designa'    => $request->vendor_ship_designa,
                  'cust_ship_mobilno'    => $request->vendor_ship_mobilno,
                  'shipping_address1'    => $request->vendor_ship_addone,
                  'shipping_address2'    => $request->vendor_ship_addtwo,
                  'shipping_state'       => $request->vendor_ship_state,
                  'shipping_city'        => $request->vendor_ship_city,
                  'shipping_pincode'     => $request->vendor_ship_pin,
              ]);

          // ===============================
          // Bank Details (Optional Section)
          // ===============================
          $userId = currentOwnerId();
          $utype  = currentOwnerUserType();

          $bank_name       = array_filter((array) $request->bank_name);
          $bank_branch     = array_filter((array) $request->bank_branch);
          $acc_holder_name = array_filter((array) $request->acc_holder_name);
          $acc_number      = array_filter((array) $request->acc_number);
          $acc_ifsc        = array_filter((array) $request->acc_ifsc);
          $acc_upi_id      = array_filter((array) $request->acc_upi_id);

          // Only run if at least one bank row exists
          if (!empty($bank_name)) {

              // Delete old bank records
              DB::table('vendor_bank_details')
                  ->where('vendor_id', $vendorId)
                  ->delete();

              foreach ($bank_name as $index => $value) {

                  // Skip incomplete bank rows
                  if (
                      empty($bank_name[$index]) ||
                      empty($bank_branch[$index]) ||
                      empty($acc_holder_name[$index]) ||
                      empty($acc_number[$index]) ||
                      empty($acc_ifsc[$index])
                  ) {
                      continue;
                  }

                  DB::table('vendor_bank_details')->insert([
                      'vendor_id'        => $vendorId,
                      'uid'              => $userId,
                      'utype'            => $utype,
                      'bank_name'        => $bank_name[$index] ?? "",
                      'bank_branch'      => $bank_branch[$index] ?? "",
                      'acc_holder_name'  => $acc_holder_name[$index] ?? "",
                      'acc_number'       => $acc_number[$index] ?? "",
                      'acc_ifsc'         => $acc_ifsc[$index] ?? "",
                      'acc_upi_id'       => $acc_upi_id[$index] ?? "",
                  ]);
              }
          }

          // ===============================
          // Final Success Response
          // ===============================
          return response()->json([
              'status'   => 'success',
              'class'    => 'succ',
              'redirect' => url('/vendor-list'),
              'message'  => 'Vendor updated successfully',
          ]);
      }



      

        public function view_vendor($vendorId)  {  
      
          $vendorId = base64_decode($vendorId);
          //echo $vendorId;exit;
          $vendor = DB::table('vendors')
                      ->where('id', '=', $vendorId)
                      ->get();
          $bankDetails = DB::table('vendor_bank_details')->where('vendor_id', '=', $vendorId)->get();
          $bankDetails = isset($bankDetails)?$bankDetails:[];
         // echo "<pre>";print_r($bankDetails);exit;
          $vendor = $vendor[0];
          
          $countries = Country::where('id', '>', '0')->get();
          $states_bill = State::where('country_id', '=', $vendor->billing_country)->get();
          $cities_bill = City::where('state_id', '=', $vendor->billing_state)->get();
      
          $states_ship = State::where('country_id', '=', $vendor->shipping_country)->get();
          $cities_ship = City::where('state_id', '=', $vendor->shipping_state)->get();
          $states = State::where('country_id', '=', 101)->get();
           
           return view('User.view-vendor')->with([
            'countries'=>$countries,
            'states_bill'=>$states_bill,
            'cities_bill'=>$cities_bill,
            'states_ship'=>$states_ship,
            'cities_ship'=>$cities_ship,
            'bankDetails' => $bankDetails,
            'vendor' => $vendor,
            'states' => $states,
            ]); 
          }
  public function deleteVendor(Request $request)
  {
    //echo('vvvvvv');exit;
    $delVendor = DB::table('vendors')->where('id', $request->id)->delete();
		if($delVendor){
			$delBank = DB::table('vendor_bank_details')->where('vendor_id', $request->id)->delete();
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/vendors'),
				'message' => 'Vendors deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/vendors'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
  }

  public function changeVendorStatus(Request $request)
	{
		try {
			// Validate request
			$request->validate([
				'id' => 'required|exists:vendors,id', // Change from 'Vendor' to 'vendors'
				'status' => 'required|in:0,1'
			]);

			// Find the customer
			$customer = Vendor::findOrFail($request->id);

			// Update status
			$customer->status = $request->status;
			$customer->save();

			// Return JSON response
			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/user.VendorList'),
				'message' => 'Status updated successfully.'
			]);

		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid data provided.',
				'errors' => $e->errors()
			], 422);

		} catch (\Exception $e) {
			\Log::error('Status Update Error: ' . $e->getMessage()); // Log error
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to update status. Please try again later.'. $e->getMessage()
			], 500);
		}
	}

  
	
}
