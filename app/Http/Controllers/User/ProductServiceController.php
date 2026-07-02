<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Redirect;
use DB;
use Auth;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\AuditLogger;
use Illuminate\Support\Facades\Validator;


class ProductServiceController extends Controller
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

    public function ProductServiceList()
    {
		//echo"Hello";exit;
        $title = 'products';
        $userId = currentOwnerId();
		checkCoreAccess('Biz Operations');
		if(Auth::user()->u_type ==1){ //ca
			$items =  DB::table('products')
							->select(DB::raw('products.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'products.added_by', '=', 'ca_assigns.comp_id')
							->where('ca_assigns.ca_id','=',$userId)
							->where('ca_assigns.ca_assign_status','=',1)
							->orderBy('created_at', 'DESC')->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca-employee
			$items =  DB::table('products')
							->select(DB::raw('products.*,company_profiles.comp_name,ca_assigns.ca_id,users.id as uid'))
							->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'products.added_by', '=', 'ca_assigns.comp_id')
							->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
							->where('ca_assigns.ca_assign_status','=',1)
							->orderBy('created_at', 'DESC')->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			// $items =  DB::table('products')
			// 				->select(DB::raw('products.*,company_profiles.comp_name'))
			// 				->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
			// 				->where('added_by', '=', $userId)
			// 				->orderBy('created_at', 'DESC')->paginate(10);

			$items = DB::table('products')
					    ->select(DB::raw('products.*, company_profiles.comp_name'))
					    ->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
					    ->where('added_by', '=', $userId)
					    ->orderBy('created_at', 'DESC')
					    ->get();
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$items =  DB::table('products')
							->select(DB::raw('products.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
							->orderBy('created_at', 'DESC')->paginate(10);
		}
		$items_pagination = $items;
        return view('User.product-service-list')->with([
			'title' =>$title,
			'items'=>$items,
			'items_pagination' =>$items_pagination,
		]);
    }

    // public function AddProductService()
    // {
    //     return view('User.add-product-service');
    // }

	public function AddProductService()
	{
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			return redirect()->back()->with('error', 'Access Denied.');
		}

		return view('User.add-product-service');
	}

	

	protected function validator(array $data)
	{
		return Validator::make($data, [

			// Always required
			'item_type' => 'required|in:product,service',

			// PRODUCT validation
			'item_name'         => 'required_if:item_type,product|nullable|string|max:255',
			'hsn_code'          => 'required_if:item_type,product|nullable|string|max:50',
			'opening_stock_bal' => 'required_if:item_type,product|nullable|numeric|min:0',
			'gst_rate_prod'     => 'required_if:item_type,product|nullable|numeric|min:0',
			'base_unit'         => 'required_if:item_type,product|nullable',
			'purchase_price'    => 'required_if:item_type,product|nullable|numeric|min:0',
			'selling_price'     => 'required_if:item_type,product|nullable|numeric|min:0',

			// SERVICE validation
			'service_name'      => 'required_if:item_type,service|nullable|string|max:255',
			'sac_code'          => 'required_if:item_type,service|nullable|string|max:50',
			'gst_rate_service'  => 'required_if:item_type,service|nullable|numeric|min:0',
			'ser_selling_price' => 'required_if:item_type,service|nullable|numeric|min:0',
			'gov_pay' => 'required_if:item_type,product|nullable|numeric|min:0',
			'ser_pay' => 'required_if:item_type,service|nullable|numeric|min:0',
			
		]);
	}

	public function generateProdId($uid, $type)
	{
		// Decide prefix
		$prefix = ($type === 'product') ? 'PRO' : 'SER';

		// Find last ID
		$last = Product::where('prodId', 'LIKE', $prefix . $uid . '-%')
						->orderBy('prodId', 'desc')
						->first();

		if ($last && preg_match("/{$prefix}{$uid}-(\d+)/", $last->prodId, $m)) {
			$new = intval($m[1]) + 1;
		} else {
			$new = 1;
		}

		return $prefix . $uid . '-' . str_pad($new, 5, '0', STR_PAD_LEFT);
	}

	protected function create(array $data)
	{
		$uid = currentOwnerId();
		$type = $data['item_type'];

		$item = Product::create([
			'added_by' => $uid,
			'item_type' => $type,

			// Codes
			'sac_code' => ($type === 'service') ? ($data['sac_code'] ?? '') : '',
			'hsn_code' => ($type === 'product') ? ($data['hsn_code'] ?? '') : '',

			// GST
			'gst_rate' => ($type === 'product')
				? ($data['gst_rate_prod'] ?? 0)
				: ($data['gst_rate_service'] ?? 0),

			// Names
			'item_name'     => $data['item_name'] ?? '',
			'service_name'  => $data['service_name'] ?? '',
			'gov_pay' => $data['gov_pay'] ?? 0,
			'ser_pay' => $data['ser_pay'] ?? 0,

			// Stock & pricing
			'opening_stock_bal' => $data['opening_stock_bal'] ?? 0,
			'purchase_price'    => $data['purchase_price'] ?? 0,
			'selling_price'     => $data['selling_price'] ?? 0,
			'ser_selling_price' => $data['ser_selling_price'] ?? 0,

			// Units
			'base_unit' => $data['base_unit'] ?? '',

			// Discounts
			'disc_sell'          => $data['disc_sell'] ?? '',
			'ser_disc_sell'      => $data['ser_disc_sell'] ?? '',
			'disc_sell_type'     => $data['disc_sell_type'] ?? '',
			'ser_disc_sell_type' => $data['ser_disc_sell_type'] ?? '',

			// Descriptions
			'prod_desc' => $data['prod_desc'] ?? '',
			'ser_desc'  => $data['ser_desc'] ?? '',

			// Extra
			'gov_pay' => $data['gov_pay'] ?? 0,
			'ser_pay' => $data['ser_pay'] ?? 0,

			'prod_image' => "",
			'ser_image'  => $data['ser_image'] ?? "",
			'created_at' => now(),
		]);

		// Generate custom product/service ID
		$item->prodId = $this->generateProdId($uid, $type);
		$item->save();

		return $item;
	}

    public function save_product(Request $request)
	{
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json([
				'status' => 'validation_error',
				'errors' => $validation->errors()
			]);
		}

		$insertItem = $this->create($request->all());

		if ($insertItem) {

			// Save price history
			$this->product_price_history($insertItem->id, $request);

			// Upload images
			if ($request->hasFile('prod_image')) {
				foreach ($request->file('prod_image') as $file) {

					$fileName = now()->format('YmdHis') . '-' . $file->getClientOriginalName();
					$file->storeAs('public/product_images', $fileName);

					DB::table('product_images')->insert([
						'product_id' => $insertItem->id,
						'image_path' => $fileName,
						'created_at' => now(),
						'updated_at' => now(),
					]);
				}
			}

			return response()->json([
				'status'   => 'success',
				'class'    => 'succ',
				'redirect' => url('/product-service-list'),
				'message'  => 'Item added successfully'
			]);
		}

		return response()->json([
			'status'   => 'error',
			'class'    => 'err',
			'redirect' => url('/'),
			'message'  => 'Item add failed'
		]);
	}

	public function editProductService($prodId)
    {
		$prodId = base64_decode($prodId);
		$product = DB::table('products')
								->where('id', '=', $prodId)
								->get();
		$productImages = DB::table('product_images')
						->where('product_id', $prodId)
						->get();
		//echo "<pre>";print_r($product);exit;
		$product = $product[0];
        return view('User.edit-product-service')->with([
			'product' => $product,
			'productImages' => $productImages,
			'prodId' =>$prodId,
		]);
    }

	public function product_price_history($prodId, $request)
	{
		// Get product_id from products table
        $productData = DB::table('products')->where('id', $prodId)->first();
		DB::table('product_price_history')->insert([
			'product_table_id' => $prodId,
			'product_id' => $productData->prodId,
			'purchase_price' => $request->purchase_price,
			'selling_price' => $request->selling_price,
			'discount_selling_price' => $request->disc_sell,
			'discount_selling_type' => ($request->item_type == 'product') ? $request->disc_sell_type : $request->ser_disc_sell_type,
			'created_at' => now(),
			'updated_at' => now(),
		]);
	}

	
	protected function updateValidator(array $data)
	{
		return Validator::make($data, [
			'item_type' => 'required|in:product,service',

			// PRODUCT
			'item_name'      => 'required_if:item_type,product|nullable|string|max:255',
			'hsn_code'       => 'required_if:item_type,product|nullable|string|max:50',
			'gst_rate'  => 'required_if:item_type,product|nullable|numeric|min:0',
			'base_unit'      => 'required_if:item_type,product|nullable',
			'purchase_price' => 'required_if:item_type,product|nullable|numeric|min:0',
			'selling_price'  => 'required_if:item_type,product|nullable|numeric|min:0',
			'gov_pay' => 'required_if:item_type,product|nullable|numeric|min:0',

			// SERVICE
			'service_name'      => 'required_if:item_type,service|nullable|string|max:255',
			'sac_code'          => 'required_if:item_type,service|nullable|string|max:50',
			'gst_rate'  => 'required_if:item_type,service|nullable|numeric|min:0',
			'ser_selling_price' => 'required_if:item_type,service|nullable|numeric|min:0',
			'ser_pay' => 'required_if:item_type,service|nullable|numeric|min:0',
		]);
	}


	public function update_product(Request $request)
	{
		//echo "<pre>";print_r($request);exit;
		$prodId = $request->id;

		$validation = $this->updateValidator($request->all());
		if ($validation->fails())  {
			return response()->json([
				'status' => 'validation_error',
				'errors' => $validation->errors()
			]);
		}
		else{

		  $oldProduct = DB::table('products')->where('id', $prodId)->first();
          //start update customers
          $update = DB::table('products')
              ->where('id', $prodId)
              ->update(
                array(
                        'added_by' => currentOwnerId(),
						'item_type' => $request->item_type,
						'sac_code' => ($request->sac_code && $request->item_type=='service')?$request->sac_code:"",
						'hsn_code' => ($request->hsn_code && $request->item_type!='service')?$request->hsn_code:"",
						'gst_rate' => $request->gst_rate,
						'gov_pay' => isset($request->gov_pay)?$request->gov_pay:0,
						'ser_pay' => isset($request->ser_pay)?$request->ser_pay:0,
						'item_name' => $request->item_name,
						'service_name' =>$request->service_name,
						// 'opening_stock_bal' => isset($request->opening_stock_bal)?$request->opening_stock_bal:0,
						'selling_price' => $request->selling_price,
						'ser_selling_price' => $request->ser_selling_price,
						'base_unit'=>$request->base_unit,
						'disc_sell' => isset($request->disc_sell)?$request->disc_sell:"",
						'ser_disc_sell' => isset($request->ser_disc_sell)?$request->ser_disc_sell:"",
						'purchase_price'=>isset($request->purchase_price)?$request->purchase_price:0,
						'disc_sell_type' => $request->disc_sell_type,
						'ser_disc_sell_type' => $request->ser_disc_sell_type,
						'prod_desc' => isset($request->prod_desc)?$request->prod_desc:"",
						'ser_desc' => isset($request->ser_desc)?$request->ser_desc:"",
						'prod_image' => "",
						'ser_image' => isset($request->ser_image)?$request->ser_image:"",
						'created_at' => date('Y-m-d H:i:s'),
                )
              );

			  // Save product price history
        	$this->product_price_history($prodId, $request);
			//Add image			
			if ($request->hasFile('prod_image')) {
				foreach ($request->file('prod_image') as $file) {
					$fileName = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->storeAs('public/product_images', $fileName);

					DB::table('product_images')->insert([
						'product_id' => $prodId,
						'image_path' => $fileName,
						'created_at' => now(),
						'updated_at' => now(),
					]);
				}
			}
			
			//Log entry start
			$oldData = (array) $oldProduct;
			$newData = [
						'item_type' => $request->item_type,
						'sac_code' => ($request->sac_code && $request->item_type == 'service') ? $request->sac_code : "",
						'hsn_code' => ($request->hsn_code && $request->item_type != 'service') ? $request->hsn_code : "",
						'gst_rate' => $request->gst_rate,
						'gov_pay' => isset($request->gov_pay)?$request->gov_pay:0,
						'ser_pay' => isset($request->ser_pay)?$request->ser_pay:0,
						'item_name' => $request->item_name,
						'service_name' => $request->service_name,
						'selling_price' => $request->selling_price,
						'ser_selling_price' => $request->ser_selling_price,
						'base_unit' => $request->base_unit,
						'disc_sell' => $request->disc_sell ?? "",
						'ser_disc_sell' => $request->ser_disc_sell ?? "",
						'purchase_price' => $request->purchase_price ?? 0,
						'disc_sell_type' => $request->disc_sell_type,
						'ser_disc_sell_type' => $request->ser_disc_sell_type,
						'prod_desc' => $request->prod_desc ?? "",
						'ser_desc' => $request->ser_desc ?? "",
						'prod_image' => $request->prod_image ?? "",
						'ser_image' => $request->ser_image ?? ""
					];
			$changedOld = [];
			$changedNew = [];

			foreach ($newData as $key => $value) {
				if (array_key_exists($key, $oldData) && $oldData[$key] != $value) {
					$changedOld[$key] = $oldData[$key];
					$changedNew[$key] = $value;
				}
			}
			if (!empty($changedNew)) {
				$itemName = $request->item_type === 'service'? ($request->service_name ?? 'Unnamed Service'): ($request->item_name ?? 'Unnamed Product');
				AuditLogger::logEntry(
					action: 'update',
					module: 'Product/Service',
					description: "Product/Service updated :{$itemName}",
					oldData: $changedOld,
					newData: $changedNew
				);
			}


			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/product-service-list'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
		}
	}

	public function viewProductService($prodId)
    {
		$prodId = base64_decode($prodId);
		$product = DB::table('products')
								->where('id', '=', $prodId)
								->get();
		$productImages = DB::table('product_images')
						->where('product_id', $prodId)
						->get();
		//echo "<pre>";print_r($productImages);exit;
		$product = $product[0];
        return view('User.view-product-service')->with([
			'product' => $product,
			'productImages' => $productImages,
			'prodId' =>$prodId,
		]);

    }

	public function delProduct($id)
	{
		$product = Product::find($id);

		if (!$product) {
			return response()->json(['message' => 'Product not found'], 404);
		}

		/* =========================
		   CAPTURE OLD DATA
		========================== */
		$oldData = $product->toArray();
		$product->delete();
		/* =========================
		   AUDIT LOG ENTRY
		========================== */
		AuditLogger::logEntry(
			action: 'delete',
			module: 'Product/Service',
			description: 'Product/Service deleted',
			oldData: $oldData,
			newData: null
		);

		return response()->json(['message' => 'Product deleted successfully']);
	}
	
	public function deleteImage($id)
	{
		$img = DB::table('product_images')->where('id',$id)->first();

		if($img){
			@unlink(storage_path('app/public/product_images/'.$img->image_path));
			DB::table('product_images')->where('id',$id)->delete();
		}

		return response()->json(['success'=>true]);
	}


}
