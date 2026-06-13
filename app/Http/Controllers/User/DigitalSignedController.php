<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\Sales;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Sales_values;

use Redirect;
use Auth;
use Validator;
use App\User;
use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;


class DigitalSignedController extends Controller
{
    public function __construct()
	{
		
	}
    //Via without API
	public function uploadSignedPdf(Request $request)
	{

		$request->validate([
			'type'=>'required|in:sales,purchase,expense',
			'id'=>'required|integer',
			'pdf'=>'required|file|mimes:pdf|max:5120'

		]);
		$type = $request->type;
		
		// Get invoice number
		$invNum = "";
		$oldPdf = "";
		if($type == 'sales')
		{
			$invoice = DB::table('sales')
				->select('inv_num')
				->where('id',$request->id)
				->first();
			$invNum = $invoice->inv_num ?? $request->id;
			$oldPdf = $invoice->signed_pdf ?? null;
		}
		elseif($type == 'purchase')
		{
			$invoice = DB::table('purchases')
				->select('inv_num')
				->where('id',$request->id)
				->first();
			$invNum = $invoice->inv_num ?? $request->id;
			$oldPdf = $invoice->signed_pdf ?? null;
		}

		// Delete old physical file
		if(!empty($oldPdf))
		{
			$oldFile = public_path($oldPdf);
			if(File::exists($oldFile))
			{
				File::delete($oldFile);
			}
		}
		// remove special characters for file name
		$invNum = preg_replace('/[^A-Za-z0-9\-]/', '_',$invNum);
		$type = $request->type;
		$folder = public_path('uploads/signed-invoice/'.$type);
		if (!File::exists($folder)) {
			File::makeDirectory($folder,0755,true);
		}
		$file = $request->file('pdf');
		$fileName = $type.'_'.$invNum.'.'.$file->extension();
		$file->move($folder,$fileName);
		$path = 'uploads/signed-invoice/'.$type.'/'.$fileName;

		if($type=='sales') {
			DB::table('sales')
					->where('id',$request->id)
					->update([
						'signed_pdf'=>$path,
						'signed_pdf_status'=>1
					]);

		}else if($type=='purchase') {
			DB::table('purchases')
					->where('id',$request->id)
					->update([
						'signed_pdf'=>$path,
						'signed_pdf_status'=>1
					]);
		}

		return response()->json([
			'status'=>true,
			'message'=>'PDF uploaded successfully'
		]);
	}
	
	public function downloadSignedPdf($type,$id)
	{

		if($type=='sales')
		{
			$data = DB::table('sales')
						->where('id',$id)
						->first();
		}else if($type=='purchase') {
			$data = DB::table('purchases')
						->where('id',$id)
						->first();
		}

		abort_if(empty($data->signed_pdf),404);

		return response()->download(public_path($data->signed_pdf));
	}
	
}
