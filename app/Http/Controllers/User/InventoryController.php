<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Inventorystocks;
use App\Models\Inventoryremovestocks;
use App\Models\Product;
use Redirect;
use DB;
use Auth;
use Validator;
use App\User;
use App\Http\Controllers\Helper; 
use Illuminate\Support\Facades\Cookie;

class InventoryController extends Controller
{
    public function Inventory(Request $request)
    {
		$title = 'Inventory';
        $userId = currentOwnerId();
		checkCoreAccess('Inventory');
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		
		//end ca-accountant access


		// service / product / mixed
		$bussType = $request->input('buss_type');
		if (empty($bussType)) {
			$bussType = 'service';
		}
		
		//CURRENT FINANCIAL YEAR
		$year = date('Y');
		$month = date('m');
		if ($month >= 4) {
			$startFY = $year . '-04-01';
			$endFY   = ($year + 1) . '-03-31';
		} else {
			$startFY = ($year - 1) . '-04-01';
			$endFY   = $year . '-03-31';
		}

		//INVENTORY INWARD (PURCHASES)
		$inventoryInward = DB::table('purchases as p')
						->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
						->join('products as pr', 'pr.id', '=', 'pv.prod_id') // join with products table
						->where('p.added_by', $userId)
						->whereBetween('p.inv_date', [$startFY, $endFY])
						->when($bussType && $bussType != 'mixed', function($query) use ($bussType) {
							$query->where('pr.item_type', $bussType); // filter by item_type if not mixed
						})
						->sum(DB::raw('(pv.amount + pv.tax_amt - pv.disc_amt)'));

		//INVENTORY OUTWARD (SALES)
		$inventoryOutward = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->join('products as pr', 'pr.id', '=', 'sv.prod_id') // join with products table
						->where('s.added_by', $userId)
						->whereBetween('s.inv_date', [$startFY, $endFY])
						->when($bussType && $bussType != 'mixed', function($query) use ($bussType) {
							$query->where('pr.item_type', $bussType); // filter by item_type if not mixed
						})
						->sum(DB::raw('(sv.amount + sv.tax_amt - sv.disc_amt)'));

		//PURCHASE DEBIT NOTES
		$purchaseDebit = DB::table('voucher_purchases as vp')
			->join('voucher_purchase_values as vpv', 'vpv.sid', '=', 'vp.id')
			->where('vp.note_type','=', 'Debit')
			->where('vp.added_by', $userId)
			->whereBetween('vp.inv_date', [$startFY, $endFY])
			->sum(DB::raw('(vpv.amount + vpv.tax_amt - vpv.disc_amt)'));

		//PURCHASE CREDIT NOTES
		$purchaseCredit = DB::table('voucher_purchases as vp')
			->join('voucher_purchase_values as vpv', 'vpv.sid', '=', 'vp.id')
			->where('vp.note_type','=', 'Credit')
			->where('vp.added_by', $userId)
			->whereBetween('vp.inv_date', [$startFY, $endFY])
			->sum(DB::raw('(vpv.amount + vpv.tax_amt - vpv.disc_amt)'));

		//SALES DEBIT NOTES
		$salesDebit = DB::table('vouchers as v')
			->join('vouchers_values as vv', 'vv.sid', '=', 'v.id')
			->where('v.note_type','=', 'Debit')
			->where('v.added_by', $userId)
			->whereBetween('v.inv_date', [$startFY, $endFY])
			->sum(DB::raw('(vv.amount + vv.tax_amt - vv.disc_amt)'));

		//SALES CREDIT NOTES
		$salesCredit = DB::table('vouchers as v')
			->join('vouchers_values as vv', 'vv.sid', '=', 'v.id')
			->where('v.note_type','=', 'Credit')
			->where('v.added_by', $userId)
			->whereBetween('v.inv_date', [$startFY, $endFY])
			->sum(DB::raw('(vv.amount + vv.tax_amt - vv.disc_amt)'));

		//DIRECT EXPENSES
		$directExpenses = DB::table('expenses')
			->where('added_by', $userId)
			->where('expense_cat', 'direct')
			->whereBetween('expense_date', [$startFY, $endFY])
			->sum('expense_amt');

		// INVENTORY WRITE-OFF / LOSS (purchase price + GST)
		$writeOffs = DB::table('inventoryremovestocks as ir')
			->join('products as p', 'p.id', '=', 'ir.prodId')
			->where('ir.added_by', $userId)
			->whereBetween('ir.created_at', [$startFY, $endFY])
			->sum(DB::raw('
				(
					p.purchase_price +(p.purchase_price * p.gst_rate / 100)
				)
			'));

		//CLOSING STOCK
		$closingStock = DB::table('products as p')
						->leftJoin('sales_values as sv', 'sv.prod_id', '=', 'p.id')
						->leftJoin('sales as s', function ($join) use ($userId, $startFY, $endFY) {
							$join->on('s.id', '=', 'sv.sid')
								 ->where('s.added_by', $userId)
								 ->whereBetween('s.inv_date', [$startFY, $endFY]);
						})
						->where('p.added_by', $userId)
						->when($bussType && $bussType != 'mixed', function ($query) use ($bussType) {
							$query->where('p.item_type', $bussType);
						})
						->select(
							'p.id as product_id',
							'p.item_type',
							'p.opening_stock_bal as opening_qty',
							'p.purchase_price',

							DB::raw('COALESCE(SUM(sv.quantity), 0) as sold_qty'),

							//CLOSING STOCK AMOUNT (VALUE)
							DB::raw("
								CASE 
									WHEN p.item_type = 'service' THEN 0
									ELSE 
										GREATEST(
											(p.opening_stock_bal - COALESCE(SUM(sv.quantity), 0)) * p.purchase_price,
											0
										)
								END as closing_stock_amount
							")
						)
						->groupBy(
							'p.id',
							'p.item_type',
							'p.opening_stock_bal',
							'p.purchase_price'
						)
						->get();

		//echo $closingStock;exit;
		$totalClosingStock = $closingStock->sum('closing_stock_amount');			

		//GROSS PROFIT
		$totalSales = DB::table('sales_values as sv')
					->join('sales as s', 's.id', '=', 'sv.sid')
					->join('products as p', 'p.id', '=', 'sv.prod_id')
					->where('s.added_by', $userId)
					->when($bussType && $bussType != 'mixed', function($query) use ($bussType) {
						$query->where('p.item_type', $bussType);
					})
					->select(DB::raw('SUM(sv.amount - sv.disc_amt) as net_sales'))
					->value('net_sales');
		
		$totalCOGS = DB::table('products as p')
				->join('sales_values as sv', 'sv.prod_id', '=', 'p.id')
				->join('sales as s', 's.id', '=', 'sv.sid')
				->where('p.added_by', $userId)
				->where('s.added_by', $userId)
				->when($bussType && $bussType != 'mixed', function($query) use ($bussType) {
					$query->where('p.item_type', $bussType);
				})
				->select(DB::raw('SUM(sv.quantity * p.purchase_price) as cogs'))
				->value('cogs');
	
		$grossProfit = ($totalSales - $totalCOGS);

			
		//Product listing
		/*$items =  DB::table('products')
							->select(DB::raw('products.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
							->where('added_by', '=', $userId)
							->when($bussType && $bussType != 'mixed', function($query) use ($bussType) {
								$query->where('item_type', $bussType);
							})
							->orderBy('created_at', 'DESC')->paginate(10);*/
					$purchaseSub = DB::table('purchase_values')
						->select('prod_id', DB::raw('SUM(quantity) as total_purchase_qty'))
						->groupBy('prod_id');

					$salesSub = DB::table('sales_values')
						->select('prod_id', DB::raw('SUM(quantity) as total_sales_qty'))
						->groupBy('prod_id');

					$items = DB::table('products as p')
						->leftJoin('company_profiles as cp', 'p.added_by', '=', 'cp.userId')
						
						->leftJoinSub($purchaseSub, 'pv', function ($join) {
							$join->on('p.id', '=', 'pv.prod_id');
						})
						
						->leftJoinSub($salesSub, 'sv', function ($join) {
							$join->on('p.id', '=', 'sv.prod_id');
						})

						->where('p.added_by', $userId)

						->when($bussType && $bussType != 'mixed', function ($query) use ($bussType) {
							$query->where('p.item_type', $bussType);
						})

						->select(
							'p.*',
							'cp.comp_name',
							DB::raw('COALESCE(pv.total_purchase_qty,0) as total_purchase_qty'),
							DB::raw('COALESCE(sv.total_sales_qty,0) as total_sales_qty'),
							DB::raw('
								(
									p.opening_stock_bal
									+ COALESCE(pv.total_purchase_qty,0)
									- COALESCE(sv.total_sales_qty,0)
								) as current_stock
							')
						)

						->orderBy('p.created_at', 'DESC')
						->paginate(10);



		/*$history =DB::table('products')
							->select(DB::raw('products.*,company_profiles.comp_name,inventorystocks.*,inventoryremovestocks.*'))
							->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
							->leftJoin('inventorystocks', 'products.id', '=', 'inventorystocks.prodId')
							->leftJoin('inventoryremovestocks', 'products.id', '=', 'inventoryremovestocks.prodId')
							->where('products.added_by', '=', $userId)
							->orderBy('products.id', 'DESC')->paginate(10);*/

		//echo "<pre>"; print_r($items);exit;
        return view('User.inventory')->with([
			'title' =>$title,
			'items'=>$items,
			'inventoryInward'    => $inventoryInward,
			'inventoryOutward'   => $inventoryOutward,
			'purchaseDebit'      => $purchaseDebit,
			'purchaseCredit'     => $purchaseCredit,
			'salesDebit'         => $salesDebit,
			'salesCredit'        => $salesCredit,
			'directExpenses'     => $directExpenses,
			'writeOffs'          => $writeOffs,
			'closingStock'       => $totalClosingStock,
			'grossProfit'        => $grossProfit,
			'req_type'           => $req_type

			//'history'=>$history,
			//'items_pagination' =>$items_pagination,
		]);
    }

	public function getinventoryhistory(Request $request)
	{
		$id = $request->id;

		// Get Product Details
		$product = DB::table('products')
			->where('id', $id)
			->select('opening_stock_bal', 'base_unit','item_type')
			->first();

		if (!$product) {
			return response()->json([]);
		}

		$openingStock = $product->opening_stock_bal ?? 0;
		$item_type = $product->item_type ?? "";

		/*
		|--------------------------------------------------------------------------
		| Purchase Item Rows (Each Line Item)
		|--------------------------------------------------------------------------
		*/
		$purchases = DB::table('purchase_values as pv')
			->join('purchases as p', 'pv.sid', '=', 'p.id')
			->where('pv.prod_id', $id)
			->select(
				'p.inv_date',
				'p.inv_num as inv_num',
				'pv.quantity',
				DB::raw("'Purchase' as type")
			)
			->get()
			->map(function ($row) {
				return [
					'date' => $row->inv_date,
					'invoice_no' => $row->inv_num,
					'quantity' => (float) $row->quantity,   // positive
					'type' => $row->type
				];
			});

		/*
		|--------------------------------------------------------------------------
		| Sales Item Rows (Each Line Item)
		|--------------------------------------------------------------------------
		*/
		$sales = DB::table('sales_values as sv')
			->join('sales as s', 'sv.sid', '=', 's.id')
			->where('sv.prod_id', $id)
			->select(
				's.inv_date',
				's.inv_num',
				'sv.quantity',
				DB::raw("'Sale' as type")
			)
			->get()
			->map(function ($row) {
				return [
					'date' => $row->inv_date,
					'invoice_no' => $row->inv_num,
					'quantity' => $row->quantity,
					'type' => $row->type
				];
			});

		/*
		|--------------------------------------------------------------------------
		| Merge + Sort by Date
		|--------------------------------------------------------------------------
		*/
		$history = $purchases
			->merge($sales)
			->sortBy(function ($row) {
				return strtotime($row['date']);
			})
			->values();

		/*
		|--------------------------------------------------------------------------
		| Running Stock Calculation
		|--------------------------------------------------------------------------
		*/
		$currentStock = $openingStock;
		$response = [];

		foreach ($history as $row) {

			$currentStock += $row['quantity'];

			$response[] = [
				"date" => date('d-m-Y', strtotime($row['date'])),
				"item_type" => $item_type,
				"invoice_no" => $row['invoice_no'],
				"units" => $product->base_unit,
				"quantity" => $row['quantity'],
				"type" => $row['type']
			];
		}

		return response()->json($response);
	}

    protected function validator(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'created_at' => 'required',
				'prod_name' => 'required',
				'quantity' => 'required',
                'units'   => 'required',
                'reason' => 'required',
			]);
			
    }

    protected function create(array $data)
    {
		//echo "<pre>";print_r($data);exit; 
        return  Inventorystocks::create([            
            'added_by' => currentOwnerId(),
			'prodId' => $data['prodId'],
            'created_at' => $data['created_at'],
			'prod_name' => $data['prod_name'],
			'quantity' => $data['quantity'],
			'units' => $data['units'],       
			'reason' => $data['reason'],
        ]);
    }
	
	public function save_stock(Request $request)  { 
		//print_r($request);exit; 
 
		$validation = $this->validator($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertData = $this->create($request->all());
			$cId = DB::getPdo()->lastInsertId();
			
			if ($insertData){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/inventory-list'),
					'message' => 'Record added successfully'
				);
				return response()->json($msg);	
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Record add failed'
				);
				return response()->json($msg);	
			}
				
		}	
    }

    protected function validatorremove(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'recreated_at' => 'required',
				'service_name' => 'required',
				'requantity' => 'required',
                'reunits'   => 'required',
                'servicereason' => 'required',
			]);
			
    }

    protected function createremove(array $data)
    {
		//echo "<pre>";print_r($data);exit('hrr'); 
        return  Inventoryremovestocks::create([            
            'added_by' => currentOwnerId(),
			'prodId' => $data['prodId'],
            'recreated_at' => isset($data['recreated_at'])?$data['recreated_at']:date('YYYY-MM-DD HH:MM:SS'),
			'service_name' => $data['service_name'],
			'requantity' => $data['requantity'],
			'reunits' => $data['reunits'],       
			'servicereason' => $data['servicereason'],
        ]);
    }

    public function save_removestock(Request $request)  { 
		//print_r($request);exit('llll'); 
 
		$validation = $this->validatorremove($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertData = $this->createremove($request->all());
			$cId = DB::getPdo()->lastInsertId();
			
			if ($insertData){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/inventory-list'),
					'message' => 'Record added successfully'
				);
				return response()->json($msg);	
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Record add failed'
				);
				return response()->json($msg);	
			}
				
		}	
    }

	public function expenses_inventory(Request $request)
	{
		$title = 'Expenses for Inventory';
		$userId = currentOwnerId();
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access
		checkCoreAccess('Inventory');
		$expenses = DB::table('inventory_expenses as ie')
					->leftJoin('company_profiles as cp', 'ie.uid', '=', 'cp.userId')
					->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'ie.uid')
					->select(
						'ie.*',
						DB::raw("
							CASE
								WHEN ie.propId IS NOT NULL AND ie.propId != ''
								THEN pp.comp_name
								ELSE cp.comp_name
							END as comp_name
						")
					)
					->where('ie.uid', $userId)
					->orderBy('ie.expense_date', 'DESC')
					->paginate(10);

		//echo "<pre>"; print_r($items);exit;
		return view('User.expenses-inventorylist')->with([
			'title' =>$title,
			'expenses'=>$expenses,
			'req_type' => $req_type
		]);
	}

	public function addinventoryexpenses()
	{
		//echo $title;exit;
		$title = 'Add Expenses for Inventory';
		$userId = currentOwnerId();
		$items =  DB::table('products')
							->select(DB::raw('products.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'products.added_by', '=', 'company_profiles.userId')
							->where('added_by', '=', $userId)
							->orderBy('created_at', 'DESC')->get();
		//echo "<pre>"; print_r($items);exit;
		$compType = DB::table('company_profiles')
						->where('userId', $userId)
						->value('comp_type'); 
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
						
		$purposes_of_tds = DB::table('tds_rules')
								->where('module', 'Expenses')
								->where('tds_section', '!=', '192')
								->where(function ($query) use ($compType) {
									if ($compType === 'Proprietorship') {
										// Proprietorship user → All + Proprietorship only
										$query->where('entity', 'All')
											  ->orWhere('entity', 'LIKE', '%Proprietorship%');

									} else {
										// Non-Proprietorship user → All + everything except Proprietorship
										$query->where('entity', 'All')
											  ->orWhere('entity', 'NOT LIKE', '%Proprietorship%');
									}
								})
								->get();
		return view('User.add-inventory-expenses')->with([
			'title' =>$title,
			'items'=>$items,
			'proprietorships'=>$proprietorships,
			'purposes_of_tds' => $purposes_of_tds,
		]);
	}
		
}
