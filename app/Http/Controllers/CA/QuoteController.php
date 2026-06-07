<?php

namespace App\Http\Controllers\CA;

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
use App\Models\User;
use App\Models\Task_quotes;
use App\Models\Company_profiles;
use App\Models\Task_managements;
use App\Models\Ca_details;
use App\Models\Ca_assigns;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Helpers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Models\TaskCategory;

class QuoteController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function Index()
    {
        $title = 'Quote';
		$userId = Auth::user()->id;
		$quotes = DB::table('task_quotes')
				->join('task_category', 'task_quotes.task_cat', '=', 'task_category.id')
				->where('task_quotes.userId', $userId)
				->where('task_quotes.is_delete', 'no')
				->orderBy('task_quotes.id', 'DESC')
				->select('task_quotes.*', 'task_category.task_category_name as category_name')
				->get();

        
        //$this->middleware('auth');
        return view('Ca.task-quote-set')->with([
            'title' =>$title,
			'quotes'=>$quotes,

        ]);
    }

    protected function validator(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
			'task_category' => 'required',
			//'task_sub_cat' => 'required',
			'govfee' => 'required',
            'service_charge' => 'required',
        ]
		);
    }

    protected function create(array $data)
    {
		//echo "<pre>";print_r($data);exit;
        return  Task_quotes::create([
            'userId' => Auth::user()->id,
            'utype' => '2',
            'task_cat' => $data['task_category'],
            //'task_sub_cat' => $data['task_sub_cat'],
            'govfee' => $data['govfee'],
            'service_charge' => $data['service_charge'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public function AddQuote()
    {
        //$this->middleware('auth');
        return view('Ca.add-new-quote')->with([

        ]);
    }
    public function save_quote(Request $request)  {  
            
		$validation = $this->validator($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertQuote = $this->create($request->all());
			$quoteId = DB::getPdo()->lastInsertId();
			
			if ($insertQuote) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => route('ca.QuoteList'),
					'message' => 'Quote added successfully'
				);
				return response()->json($msg);	
			} else {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => route('ca.QuoteList'),
					'message' => 'Quote add failed'
				);
				return response()->json($msg);	
			}
			
				
		}	
    }

    public function edit_quote($quoteId)  {  
        
		$quoteId = base64_decode($quoteId);
		$quote = DB::table('task_quotes')
								->where('id', '=', $quoteId)
								->get();

		$quote = $quote[0];
		// print_r($quote);
		// die();
		 return view('Ca.edit-quote')->with([				
				'quote' => $quote,		
				'quoteId' => $quoteId
			]); 
    }

	public function view_quote($quoteId)  {  
        
		$quoteId = base64_decode($quoteId);
		$quote = DB::table('task_quotes')
								->where('id', '=', $quoteId)
								->get();

		$quote = $quote[0];
		 return view('Ca.view-quote')->with([				
				'quote' => $quote,		
				'quoteId' => $quoteId
			]); 
    }

    public function update_quote(Request $request)  {  
            
		//echo "<pre>";print_r($request->all());exit;
		$quoteId = $request->id;
		
		$validation = $this->validator($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update Quote
			$update = DB::table('task_quotes')
					->where('id', $quoteId)
					->update(
						 array(

                                'task_cat' => $request->task_category,
                                //'task_sub_cat' => $request->task_sub_cat,
                                'govfee' => $request->govfee,
                                'service_charge' =>$request->service_charge,
								
						 )
					);
			if ($update){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => route('ca.QuoteList'),
					'message' => 'Record details updated'
				);
				return response()->json($msg);	
			}
			else{
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'Update not success!'
					);
					return response()->json($msg);	
			}
		}	
    }

    //Delete Quote
	public function delQuote(Request $request)
    {
		//print_r($request);exit;
        $delQuote = DB::table('task_quotes')
				->where('id', $request->id)
				->update(['is_delete' => 'yes']);

		if($delQuote){
			$msg = array(
				'status' => 'success',
				'class' => 'success',
				'redirect' => url('/task-quoteset'),
				'message' => 'Quote deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'error',
				'redirect' => url('/'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

	public function taskCategoryStore(Request $request)
	{
		$request->validate([
			'task_name' => 'required|string|max:255',
		]);

		try {
			$category = TaskCategory::create([
				'task_category_name' => $request->task_name,
				'add_by' => Auth::user()->id,
			]);

			if ($category) {
				return response()->json(['message' => 'Task category added successfully.'], 200);
			} else {
				return response()->json(['message' => 'Failed to save task category.'], 500);
			}

		} catch (\Exception $e) {
			Log::error('TaskCategoryStore Error: ' . $e->getMessage());

			return response()->json([
				'message' => 'An error occurred while saving the task category.',
				'error' => $e->getMessage()
			], 500);
		}
	}
}