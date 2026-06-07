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
use App\Models\Company_profiles;
use App\Models\Statutorys;
use App\Models\Ca_assigns;
use App\Models\Chat_messages;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class CaCompliancesController extends Controller
{
	public function CompliancesList()
	{
		//return view('ca.compliances-list');
		$title = 'Statutory';
		//$userId = Auth::user()->id;
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) { //ca, employee
			$statutory =  DB::table('statutorys')
				->select(DB::raw('statutorys.*,company_profiles.comp_name'))
				->leftJoin('company_profiles', 'statutorys.compId', '=', 'company_profiles.userId')
				->where('statutorys.added_by', '=', $userId)
				->orderBy('statutorys.id', 'DESC')->paginate(10);
		}  elseif (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) { //user
			$userId = currentOwnerId();
			checkCoreAccess('Statutory Compliance Status');
			$statutory =  DB::table('statutorys')
				->select(DB::raw('statutorys.*,company_profiles.comp_name'))
				->leftJoin('company_profiles', 'statutorys.compId', '=', 'company_profiles.userId')
				//->leftJoin('ca_assigns', 'statutorys.compId', '=', 'ca_assigns.comp_id')
				//->where('ca_assigns.ca_id','=',$userId)
				->where('statutorys.compId', '=', $userId)
				//->where('ca_assigns.ca_assign_status','=',1)
				->orderBy('id', 'DESC')->paginate(10);
		} elseif (Auth::user()->u_type == 3) { //admin
			$statutory =  DB::table('statutorys')
				->select(DB::raw('statutorys.*,company_profiles.comp_name'))
				->leftJoin('company_profiles', 'statutorys.compId', '=', 'company_profiles.userId')
				->orderBy('statutorys.id', 'DESC')->paginate(10);
		}
		$statutory_pagination = $statutory;
		//echo "<pre>"; print_r($statutory);exit;
		$array = array();
		foreach ($statutory as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['added_by'] = $val->added_by;
			$array[$val->id]['compId'] = $val->compId;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['statutory_doc'] = $val->statutory_doc;
			$array[$val->id]['statutory_due_date'] = $val->statutory_due_date;
			$array[$val->id]['statutory_msg'] = $val->statutory_msg;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['created_at'] = $val->created_at;

			if (Auth::user()->u_type == 1) {
				$compId = $val->compId;
				$compName = DB::table('users')
					->select(DB::raw('users.name'))
					->where('id', '=', $compId)
					->get();
				$array[$val->id]['messages_by'] = $compName[0]->name;
				$array[$val->id]['messages'] = DB::table('chat_messages')
					->where('c_qid', '=', $val->id)
					->where(function ($q) use ($compId) {
						$q->where(function ($q2) use ($compId) {
							$q2->where('to_user_id', Auth::user()->id)->Where('from_user_id', $compId);
						});
					})
					->where('status', '=', 0)
					->get();
			} else {
				$caId = $val->added_by;
				$compName = DB::table('users')
					->select(DB::raw('users.name'))
					->where('id', '=', $caId)
					->get();
				$array[$val->id]['messages_by'] = $compName[0]->name;
				$array[$val->id]['messages'] = DB::table('chat_messages')
					->where('c_qid', '=', $val->id)
					->where(function ($q) use ($caId) {
						$q->where(function ($q2) use ($caId) {
							$q2->where('to_user_id', Auth::user()->id)->Where('from_user_id', $caId);
						});
					})
					->where('status', '=', 0)
					->get();
			}
		}
		$statutory = json_decode(json_encode($array));

		//echo "<pre>"; print_r($statutory);exit;
		return view('Ca.compliances-list')->with([
			'title' => $title,
			'statutory' => $statutory,
			'statutory_pagination' => $statutory_pagination,
		]);
	}

	/*public function CompliancesChat()
    {
        return view('ca.compliances-chat');
    }*/

	protected function validator(array $data)
	{
		return Validator::make($data, [
			'compId' => 'required',
			'statutory_doc' => 'required',
			'statutory_due_date' => 'required',
			'statutory_msg' => 'required'
		]);
	}

	// protected function create(array $data)
	// {
	// 	return Statutorys::create([
	// 		'added_by' => Auth::user()->id,
	// 		'compId' => $data['compId'],
	// 		'statutory_doc' => $data['statutory_doc'],
	// 		'statutory_due_date' => $data['statutory_due_date'],
	// 		'statutory_msg' => $data['statutory_msg'],
	// 		'created_at' => now(),
	// 	]);
	// }

	protected function create(array $data)
	{
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$statutoryDoc = ($data['statutory_doc'] === 'Other' && !empty($data['other_statutory_doc']))
			? $data['other_statutory_doc']
			: $data['statutory_doc'];

		return Statutorys::create([
			'added_by' => $userId,
			'compId' => $data['compId'],
			'statutory_doc' => $statutoryDoc,
			'statutory_due_date' => $data['statutory_due_date'],
			'statutory_msg' => $data['statutory_msg'],
			'created_at' => now(),
		]);
	}

	public function save_statutory(Request $request)
	{
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$data = $request->all();
		$validation = $this->validator($data);

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		DB::beginTransaction();

		try {
			// Insert into statutorys table
			$insertStatutory = $this->create($data);

			// Insert into chat_messages table
			DB::table('chat_messages')->insert([
				'to_user_id' => $data['compId'],
				'from_user_id' => $userId,
				'chat_message' => $data['statutory_msg'],
				'c_qid' => $insertStatutory->id,
				'status' => 0,
				'created_at' => now(),
			]);

			DB::commit();

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/ca-compliances-list'),
				'message' => 'Statutory added successfully'
			]);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Statutory add failed: ' . $e->getMessage()
			]);
		}
	}

	public function update_statutory(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$eId = $request->id;

		$validation = $this->validator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			//start update project
			$update = DB::table('statutorys')
				->where('id', $eId)
				->update(
					array(
						'statutory_doc' => $request->statutory_doc,
						'statutory_due_date' => $request->statutory_due_date,
						'statutory_msg' => $request->statutory_msg,
						'status' => $request->status,

					)
				);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/ca-compliances-list'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
			//end update item

		}
	}	

	public function addstatutory()
	{
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$companys = DB::table('company_profiles')
			->select(DB::raw('company_profiles.userId, company_profiles.comp_name, ca_assigns.ca_id'))
			->leftJoin('ca_assigns', 'company_profiles.userId', '=', 'ca_assigns.comp_id')
			->where('ca_assigns.ca_assign_status', 1)
			->where('ca_assigns.ca_current_status', 1)
			->where('ca_assigns.ca_id', $userId)
			->orderBy('ca_assigns.created_at', 'desc')
			->get();

		return view('Ca.addstatutory')->with([
			'companys' => $companys,
		]);
	}

	// public function addstatutory()
	// {
	// 	$companys = DB::table('company_profiles')
	// 		->select(DB::raw('company_profiles.userId,company_profiles.comp_name,ca_assigns.ca_id'))
	// 		->leftJoin('ca_assigns', 'company_profiles.userId', '=', 'ca_assigns.comp_id')
	// 		->where('ca_assigns.ca_assign_status', '=', 1)
	// 		->get();

	// 	return view('Ca.addstatutory')->with([
	// 		'companys' => $companys,
	// 	]);
	// }

	public function editstatutory($eId)
	{
		if (Auth::user()->u_type == 2) {
			return redirect('/statutory');
		}
		$eId = base64_decode($eId);
		$statutory = DB::table('statutorys')
			->where('id', '=', $eId)
			->get();
		$statutory = $statutory[0];

		$companys = DB::table('company_profiles')
			->select(DB::raw('company_profiles.userId,company_profiles.comp_name,ca_assigns.ca_id'))
			->leftJoin('ca_assigns', 'company_profiles.userId', '=', 'ca_assigns.comp_id')
			->where('ca_assigns.ca_assign_status', '=', 1)
			->get();
		return view('Ca.editstatutory')->with([
			'statutory' => $statutory,
			'companys' => $companys,
			'eId' => $eId
		]);
	}

	public function viewstatutory($eId)
	{

		$eId = base64_decode($eId);
		$statutory = DB::table('statutorys')
			->where('id', '=', $eId)
			->get();
		$statutory = $statutory[0];

		$companys = DB::table('company_profiles')
			->select(DB::raw('company_profiles.userId,company_profiles.comp_name,ca_assigns.ca_id'))
			->leftJoin('ca_assigns', 'company_profiles.userId', '=', 'ca_assigns.comp_id')
			->where('ca_assigns.ca_assign_status', '=', 1)
			->get();
		return view('Ca.viewstatutory')->with([
			'statutory' => $statutory,
			'companys' => $companys,
			'eId' => $eId
		]);
	}

	//Start chat
	public function chat_response($caId, $uid, $id)
	{
		$caId = base64_decode($caId);
		$uid = base64_decode($uid);
		$id = base64_decode($id);

		$userId = currentOwnerId();
		$userType = currentOwnerUserType();

		$title = "Message Response";

		/*
		|--------------------------------------------------------------------------
		| CA / EMPLOYEE LOGIN
		|--------------------------------------------------------------------------
		*/
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4)
		{
			$compId = $uid;

			$array['id'] = $id;
			$array['uid'] = $uid;

			$compName = DB::table('users')
				->where('id', $compId)
				->value('name');

			$array['compName'] = $compName;

			/*
			|--------------------------------------------------------------------------
			| GET CHAT MESSAGES
			|--------------------------------------------------------------------------
			*/
			$array['messages'] = DB::table('chat_messages')
				->where('c_qid', $id)
				->where(function ($q) use ($compId, $userId) {

					$q->where(function ($q2) use ($compId, $userId) {

						$q2->where('from_user_id', $userId)
							->where('to_user_id', $compId);

					})->orWhere(function ($q2) use ($compId, $userId) {

						$q2->where('to_user_id', $userId)
							->where('from_user_id', $compId);

					});

				})
				//->orderBy('id', 'asc')
				->get();

			/*
			|--------------------------------------------------------------------------
			| UPDATE MESSAGE STATUS
			|--------------------------------------------------------------------------
			*/
			DB::table('chat_messages')
				->where('c_qid', $id)
				->where(function ($q) use ($compId, $userId) {

					$q->where(function ($q2) use ($compId, $userId) {

						$q2->where('to_user_id', $userId)
							->where('from_user_id', $compId);

					});

				})
				->update([
					'status' => 1,
				]);

			$quotes = json_decode(json_encode($array));

			return view('Ca.compliances-chat')->with([
				'quotes' => $quotes,
				'title' => $title
			]);
		}

		/*
		|--------------------------------------------------------------------------
		| COMPANY LOGIN
		|--------------------------------------------------------------------------
		*/
		else
		{
			$compId = $uid;

			$array['id'] = $id;
			$array['uid'] = $uid;

			/*
			|--------------------------------------------------------------------------
			| GET CA ID
			|--------------------------------------------------------------------------
			*/
			$caId = DB::table('statutorys')
				->where('id', $id)
				->value('added_by');

			/*
			|--------------------------------------------------------------------------
			| GET CA NAME
			|--------------------------------------------------------------------------
			*/
			$caName = DB::table('users')
				->where('id', $caId)
				->value('name');

			$array['caName'] = $caName;
			$array['caid'] = $caId;

			/*
			|--------------------------------------------------------------------------
			| GET CHAT MESSAGES
			|--------------------------------------------------------------------------
			*/
			$array['messages'] = DB::table('chat_messages')
				->where('c_qid', $id)
				->where(function ($q) use ($caId, $userId) {

					$q->where(function ($q2) use ($caId, $userId) {

						$q2->where('from_user_id', $userId)
							->where('to_user_id', $caId);

					})->orWhere(function ($q2) use ($caId, $userId) {

						$q2->where('to_user_id', $userId)
							->where('from_user_id', $caId);

					});

				})
				//->orderBy('id', 'asc')
				->get();

			/*
			|--------------------------------------------------------------------------
			| UPDATE MESSAGE STATUS
			|--------------------------------------------------------------------------
			*/
			DB::table('chat_messages')
				->where('c_qid', $id)
				->where(function ($q) use ($caId, $userId) {

					$q->where(function ($q2) use ($caId, $userId) {

						$q2->where('to_user_id', $userId)
							->where('from_user_id', $caId);

					});

				})
				->update([
					'status' => 1,
				]);

			/*
			|--------------------------------------------------------------------------
			| UPDATE STATUTORY STATUS
			|--------------------------------------------------------------------------
			*/
			DB::table('statutorys')
				->where('id', $id)
				->update([
					'status' => 1,
				]);

			$quotes = json_decode(json_encode($array));

			return view('Ca.compliances-chat')->with([
				'quotes' => $quotes,
				'title' => $title
			]);
		}
	}
	
	//End chat
}
