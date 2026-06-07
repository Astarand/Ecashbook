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
use App\Country;
use App\Models\State;
use App\City;
use App\Busi_agents;
use App\Ca_profiles;
use App\Custom_invoice;
use App\Sales;
use App\Models\CustomInvoiceProduct;
// use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Helper;
use App\Http\Controllers\User\SalesController;
use App\Helpers\AuditLogger;

class CustomInvoiceController extends Controller
{
    public function CustomInvoiceList()
    {
        $userId = currentOwnerId();
        $custom_invoices = DB::table('custom_invoices')
                        ->where('added_by', $userId)
                        ->orderBy('id', 'desc')
                        ->get();

        if(Auth::user()->u_type == "1" || Auth::user()->u_type == "4"){
            return view('User.custom-invoice-list', compact('custom_invoices'));
        }elseif(Auth::user()->u_type == "2" || Auth::user()->u_type == "5"){
			checkCoreAccess('Accounting');
            return view('User.custom-invoice-list', compact('custom_invoices'));
        }


    }
    public function GenerateCustomInvoice()
    {
        $userId = currentOwnerId();

        if (Auth::user()->u_type == "1") {
            //------- CA Custom Invoice -------


            $customer_list = DB::table('users')
                        ->where('users.ca_add_by', $userId)
                        ->where('users.u_type', 2)
                        ->get();

            $comp_details = DB::table('users')
                        ->join('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
                        ->where('users.id', $userId)
                        ->select('users.*', 'ca_profiles.*')
                        ->get();

            $user_state =  DB::table('states')
                        ->where('id', $comp_details[0]->comp_bill_state)
                        ->get();
            $user_city = DB::table('cities')
                        ->where('id', $comp_details[0]->comp_bill_city)
                        ->get();

            $user_banks = DB::table('ca_banks')
                        ->where('uid', $userId)
                        ->get();

            //--------- Invoice Number -----------
            //--------- Invoice Number -----------
            $currentMonth = strtoupper(date('M'));
            $currentYear = date('y');
            $nextYear = $currentYear + 1;

            // Pattern for the current month/year
            $pattern = $currentMonth . '/' . $currentYear . '-' . $nextYear . '/%';

            // Fetch the last invoice matching the current pattern
            $lastMatchingInvoice = DB::table('custom_invoices')
            ->where('invoice_number', 'like', $pattern)
                ->orderBy('id', 'desc')
                ->first();

            $nextIncrement = 1;

            if ($lastMatchingInvoice) {
                $lastInvoiceParts = explode('/', $lastMatchingInvoice->invoice_number);
                $lastIncrementPart = end($lastInvoiceParts);
                if (is_numeric($lastIncrementPart)) {
                    $nextIncrement = (int)$lastIncrementPart + 1;
                }
            } else {

                $lastInvoice = DB::table('custom_invoices')
                ->orderBy('id', 'desc')
                ->first();

                if ($lastInvoice) {
                    $lastInvoiceParts = explode('/', $lastInvoice->invoice_number);
                    $lastIncrementPart = end($lastInvoiceParts);
                    if (is_numeric($lastIncrementPart)) {
                        $nextIncrement = (int)$lastIncrementPart + 1;
                    }
                }
            }
            // echo '<pre>';
            // print_r($customer_list);
            // die();


            $newInvoiceNumber = sprintf('%s/%s-%s/%04d', $currentMonth, $currentYear, $nextYear, $nextIncrement);
            return view('User.generate-custom-invoice', compact('customer_list','user_state','user_city','comp_details','user_banks','newInvoiceNumber'));

            // return view('User.custom-invoice-list', compact('users', 'ca_details', 'ca_banks', 'ca_state', 'ca_city', 'newInvoiceNumber'));
        } else {

            //------- User Custom Invoice -------

            $customer_list = DB::table('customers')
                    ->where('userId', $userId)
                    ->get();

            $comp_details = DB::table('users')
                        ->join('company_profiles', 'users.id', '=', 'company_profiles.userId')
                        ->where('users.id', $userId)
                        ->select('users.*', 'company_profiles.*')
                        ->get();

            $user_state = DB::table('states')
                        ->where('id', $comp_details[0]->comp_bill_state)
                        ->get();
            $user_city = DB::table('cities')
                        ->where('id', $comp_details[0]->comp_bill_city)
                        ->get();

            //--------- User Bank -------
            $user_banks = DB::table('company_banks')
                        ->where('uid', $userId)
                        ->get();

            //--------- Invoice Number -----------
            $currentMonth = strtoupper(date('M'));
            $currentYear = date('y');
            $nextYear = $currentYear + 1;

            // Pattern for the current month/year
            $pattern = $currentMonth . '/' . $currentYear . '-' . $nextYear . '/%';

            // Fetch the last invoice matching the current pattern
            $lastMatchingInvoice = DB::table('custom_invoices')
            ->where('invoice_number', 'like', $pattern)
                ->orderBy('id', 'desc')
                ->first();

            $nextIncrement = 1;

            if ($lastMatchingInvoice) {
                $lastInvoiceParts = explode('/', $lastMatchingInvoice->invoice_number);
                $lastIncrementPart = end($lastInvoiceParts);
                if (is_numeric($lastIncrementPart)) {
                    $nextIncrement = (int)$lastIncrementPart + 1;
                }
            } else {

                $lastInvoice = DB::table('custom_invoices')
                ->orderBy('id', 'desc')
                ->first();

                if ($lastInvoice) {
                    $lastInvoiceParts = explode('/', $lastInvoice->invoice_number);
                    $lastIncrementPart = end($lastInvoiceParts);
                    if (is_numeric($lastIncrementPart)) {
                        $nextIncrement = (int)$lastIncrementPart + 1;
                    }
                }
            }
            $newInvoiceNumber = sprintf('%s/%s-%s/%04d', $currentMonth, $currentYear, $nextYear, $nextIncrement);
            // echo '<pre>';
            // print_r($customer_list);
            // die();

            $salesController = new SalesController();
            $newInvoiceNumber = $salesController->generateInvoiceNumber($userId);



            return view('User.generate-custom-invoice', compact('customer_list','user_state','user_city','comp_details','user_banks','newInvoiceNumber'));
        }
    }

    public function getCustomerDetails(Request $request)
    {
        $userId = $request->input('userId');
        //$userId = Auth::user()->id;
        if (Auth::user()->u_type == "1") {
        // $customerDetails = DB::table('company_profiles')->where('userId', $userId)->first();
        $customerDetails = DB::table('company_profiles')
                            ->leftJoin('states', 'states.id', '=', 'company_profiles.comp_bill_state')
                            ->leftJoin('cities', 'cities.id', '=', 'company_profiles.comp_bill_city')
                            ->where('company_profiles.userId', $userId)
                            ->select(
                                'company_profiles.*',
                                'states.name as state_name',
                                'cities.name as city_name'
                            )
                            ->first();
                }else{
                    $customerDetailsSQL = DB::table('customers')
                                ->where('customers.id', $userId)
                                ->leftJoin('states', 'states.id', '=', 'customers.cust_bill_state')
                                ->leftJoin('cities', 'cities.id', '=', 'customers.cust_bill_city')
                                ->select(
                                    'customers.*',
                                    'states.name as state_name',  // Alias for state name
                                    'cities.name as city_name'    // Alias for city name
                                )
                                ->first();


                    $customerDetails = [
                        'comp_name' => $customerDetailsSQL->cont_name,
                        'comp_bill_addone' => $customerDetailsSQL->cust_bill_addone,
                        'comp_bill_addtwo' => $customerDetailsSQL->cust_bill_addtwo,
                        'comp_bill_pin' => $customerDetailsSQL->cust_bill_pin,
                        'comp_phone' => $customerDetailsSQL->cont_no,
                        'comp_email' => $customerDetailsSQL->cont_email,
                        'state_name' => $customerDetailsSQL->state_name,   // Add state name
                        'city_name' => $customerDetailsSQL->city_name,     // Add city name
                        'gst_no' => $customerDetailsSQL->cust_gst_no,
                        'gst_reg' => $customerDetailsSQL->gst_reg,
                    ];


                }
        if ($customerDetails) {
            return response()->json([
                'status' => 'success',
                'data' => $customerDetails
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No details found for this customer.'
            ]);
        }
    }
    public function getCaBankDetails(Request $request)
    {
        $bankId = $request->input('bankId');

        $BankDetails = DB::table('ca_banks')
                            ->where('id', $bankId)
                            ->first();

        if ($BankDetails) {
            return response()->json([
                'status' => 'success',
                'data' => $BankDetails
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No details found for this Bank.'
            ]);
        }
    }




    public function saveInvoice(Request $request)
    {
        // echo '<pre>';
        // dd($request->all());
        // die();
        try {
            $userId = currentOwnerId();
            // Retrieve invoice number from the request
            $invoice_number = $request->input('invoice_number');

            // Check if the invoice already exists
            $checkInvoice = DB::table('custom_invoices')
                ->where('invoice_number', $invoice_number)
                ->orderBy('id', 'desc')
                ->first();

            $checkSales = DB::table('sales')
                ->where('inv_num', $invoice_number)
                ->first();

            // If the invoice does not exist, proceed with saving
            if ((empty($checkInvoice)) && (empty($checkSales))) {
                // Retrieve and decode the invoice product data
                $invoiceProductData = json_decode($request->input('items'), true);
                // $invoiceProductData = $request->input('invoice_data');

                // Gather invoice data from the request
                $invoiceData = [
                    'added_by' => $userId,
                    'invoice_number' => $invoice_number,
                    'invoice_date' => $request->input('invoice_date'),


                    'issued_by_company_name' => $request->input('issued_by_company_name'),
                    'issued_by_address1' => $request->input('issued_by_address1'),
                    'issued_by_address2' => $request->input('issued_by_address2'),
                    'issued_by_city' => $request->input('issued_by_city'),
                    'issued_by_state' => $request->input('issued_by_state'),
                    'issued_by_pincode' => $request->input('issued_by_pincode'),
                    'issued_by_contact_no' => $request->input('issued_by_contact_no'),
                    'issued_by_email_address' => $request->input('issued_by_email_address'),
                    'issued_by_gst' => $request->input('issued_by_gst'),

                    'issued_to_company_name' => $request->input('issued_to_company_name'),
                    'issued_to_address1' => $request->input('issued_to_address1'),
                    'issued_to_address2' => $request->input('issued_to_address2'),
                    'issued_to_city' => $request->input('issued_to_city'),
                    'issued_to_state' => $request->input('issued_to_state'),
                    'issued_to_pincode' => $request->input('issued_to_pincode'),
                    'issued_to_contact_no' => $request->input('issued_to_contact_no'),
                    'issued_to_email_address' => $request->input('issued_to_email_address'),
                    'issued_to_gst' => $request->input('issued_to_gst'),
                    'cust_id' => $request->input('cust_id'),

                    'bank_name' => $request->input('bank_name'),
                    'account_no' => $request->input('account_no'),
                    'account_holder_name' => $request->input('account_holder_name'),
                    'branch_name' => $request->input('branch_name'),
                    'ifsc_code' => $request->input('ifsc_code'),

                    'notes' => $request->input('notes'),
                    'terms_and_conditions' => $request->input('terms_and_conditions'),
                    'total_amount' => $request->input('grand_total'),
                    'total_amount_in_words' => $request->input('total_amount_in_words'),
                    'signature_name' => $request->input('signature_name'),
                    // 'cust_id' => $request->input('cust_details') ? $request->input('cust_details') : null,
                    'discount_applied' => $request->input('discount_applied') ? true : true,
                    'discount_amount' => $request->input('discount_amount'),
                    // 'discount_type' => $request->input('discount_type'),
                    // 'discount_price' => $request->input('discount_price'),

                    'tds_applicable' => $request->input('tdsApplicable')?? null,
                    'tds_percentage' => $request->input('tdsPercentage')?? null,
                    'tds_amount' => $request->input('totalAmountWithoutTax')?? null,
                ];


                if ($request->hasFile('signatureUpload')) {
                    $file = $request->file('signatureUpload');
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    // Store the file in the specified directory
                    $file->storeAs('public/custom_invoice_img', $fileName);

                    // Save only the image name in the database
                    $invoiceData['upload_signature'] = $fileName;
                }


                // Insert invoice data into the custom_invoices table
                $insertedId = DB::table('custom_invoices')->insertGetId($invoiceData);

                // Insert related product data
                if (!empty($invoiceProductData)) {
                    foreach ($invoiceProductData as $item) {
                        CustomInvoiceProduct::create([
                            'custom_invoice_id' => $insertedId,
                            'product_name' => $item['product_name'] ?? null,
                            'price' => $item['price'] ?? 0,
                            'hsn_sac_code' => $item['hsn_sac'] ?? null,
                            'quantity' => $item['quantity'] ?? 0,
                            'gst_type' => $item['gst_mode'] ?? null,
                            'cgst' => $item['cgst'] ?? 0,
                            'sgst' => $item['sgst'] ?? 0,
                            'igst' => $item['igst'] ?? 0,
                            'total_price' => $item['total'] ?? 0,
                        ]);
                    }
                }

                if ($request->input('action') === 'save_preview') {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Invoice saved successfully!',
                        'invoice_id' => $insertedId,
                        'redirect_url' => route('ViewCustomInvoice', ['id' => base64_encode($insertedId)]),
                    ]);
                } else {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Invoice saved successfully!',
                        'redirect_url' => route('user.CustomInvoiceList'),
                    ]);
                }
            } else {
                // Return an error response if the invoice already exists
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invoice number already exists.',
                ]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Failed to save invoice: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save the invoice. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ViewCustomInvoice($id)
    {
        $id = base64_decode($id);
        // Retrieve the invoice by ID
        $invoice = DB::table('custom_invoices')->where('id', $id)->first();

        // Retrieve the associated products for the invoice
        $invoiceProducts = DB::table('custom_invoice_product')
            ->where('custom_invoice_id', $id)
            ->get();

            // echo '<pre>';
            // print_r($invoice);
            // die();

        // Pass the data to the view
        return view('User.view-invoice-details', compact('invoice', 'invoiceProducts'));

    }

    public function custom_invoice_status_update(Request $request){
        $id = $request->input('id');
        $action = $request->input('action');
		$inv = DB::table('custom_invoices')->where('id', $id)->first();
		
        $updated = DB::table('custom_invoices')
                ->where('id', $id)
                ->update(['status' => $action]);


            if ($updated) {
				$action = ($action == 1) ? 'approved' : 'cancelled';
				AuditLogger::logEntry(
						action: $action,
						module: 'Custom Invoice',
						description: 'Invoice no : ' . ($inv->invoice_number ?? 'NA'),
						oldData: null,
						newData: null
					);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Invoice status updated successfully.',
                    'updated_status' => $action
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update invoice status.'
                ]);
            }

    }

    public function checkInvoiceMatch(Request $request)
    {
        $invoiceNumber = $request->input('invoice_number');

        // Check in `custom_invoices` table
        $customInvoiceMatch = Custom_invoice::where('invoice_number', $invoiceNumber)->exists();

        // Check in `sales` table
        $salesMatch = Sales::where('inv_num', $invoiceNumber)->exists();

        return response()->json(['match' => $customInvoiceMatch || $salesMatch]);
    }




}
