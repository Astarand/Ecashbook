<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Comp_directors;
use App\Models\PropDirector;

use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DirectorController extends Controller
{
	
	public function show($id)
	{
		$director = Comp_directors::find($id);

		return response()->json($director);
	}
	
    public function store(Request $request)
	{
		$compId = currentOwnerId();
		$request->validate([
			'director_name' => 'required',
			'director_designation' => 'required',
			'director_email' => 'required|email|unique:comp_directors,director_email',
			'director_phone' => 'required',
			'director_signature' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
		]);

		$signature = null;

		if ($request->hasFile('director_signature')) {
			$signature = $request->file('director_signature')
						->store('signatures', 'public');
		}

		Comp_directors::create([
			'compId' => $compId,
			'director_name' => $request->director_name,
			'director_designation' => $request->director_designation,
			'director_email' => $request->director_email,
			'director_phone' => $request->director_phone,
			'director_din' => $request->director_din,
			'director_signature' => $signature
		]);

		return response()->json(['success' => true, 'message' => 'Director added successfully']);
	}
	
	public function update(Request $request, $id)
	{
		$director = Comp_directors::find($id);

		if (!$director) {
			return response()->json(['success' => false, 'message' => 'Director not found']);
		}

		$signature = $director->director_signature;

		if ($request->hasFile('director_signature')) {
			$signature = $request->file('director_signature')
						->store('signatures', 'public');
		}

		$director->update([			
			'director_name' => $request->director_name,
			'director_designation' => $request->director_designation,
			'director_email' => $request->director_email,
			'director_phone' => $request->director_phone,
			'director_din' => $request->director_din,
			'director_signature' => $signature
		]);

		return response()->json(['success' => true, 'message' => 'Director updated']);
	}
	
	public function delete($id)
	{
		$deleted = DB::table('comp_directors')->where('id', $id)->delete();

		if ($deleted) {
			return response()->json(['success' => true, 'message' => 'Deleted Successfully']);
		} else {
			return response()->json(['success' => false, 'message' => 'Delete Failed']);
		}
	}

	//Proprietorship directors show/add/edit/delete
	public function showProprietorship($id)
	{
		$director = PropDirector::find($id);

		return response()->json($director);
	}
	
    public function storeProprietorship(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$compId = $request->compId;
		$addBy = currentOwnerId();
		$request->validate([
			'director_name' => 'required',
			'director_designation' => 'required',
			'director_email' => 'required|email|unique:prop_directors,director_email',
			'director_phone' => 'required',
			'director_signature' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
		]);

		$signature = null;

		if ($request->hasFile('director_signature')) {
			$signature = $request->file('director_signature')
						->store('signatures', 'public');
		}

		PropDirector::create([
			'compId' => $compId,
			'addBy' => $addBy,
			'director_name' => $request->director_name,
			'director_designation' => $request->director_designation,
			'director_email' => $request->director_email,
			'director_phone' => $request->director_phone,
			'director_din' => $request->director_din,
			'director_signature' => $signature
		]);

		return response()->json(['success' => true, 'message' => 'Director added successfully']);
	}
	
	public function updateProprietorship(Request $request, $id)
	{
		$director = PropDirector::find($id);

		if (!$director) {
			return response()->json(['success' => false, 'message' => 'Director not found']);
		}

		$signature = $director->director_signature;

		if ($request->hasFile('director_signature')) {
			$signature = $request->file('director_signature')
						->store('signatures', 'public');
		}

		$director->update([			
			'director_name' => $request->director_name,
			'director_designation' => $request->director_designation,
			'director_email' => $request->director_email,
			'director_phone' => $request->director_phone,
			'director_din' => $request->director_din,
			'director_signature' => $signature
		]);

		return response()->json(['success' => true, 'message' => 'Director updated']);
	}
	
	public function deleteProprietorship($id)
	{
		$deleted = DB::table('prop_directors')->where('id', $id)->delete();

		if ($deleted) {
			return response()->json(['success' => true, 'message' => 'Deleted Successfully']);
		} else {
			return response()->json(['success' => false, 'message' => 'Delete Failed']);
		}
	}


}
