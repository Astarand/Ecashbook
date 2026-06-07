<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Company_profiles;
use App\Models\Statutorys;
use App\Models\Ca_assigns;
use App\Models\Chat_messages;
use App\Models\User_tickets;
use App\Models\User_ticket_chats;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TicketManagementController extends Controller
{
    public function TicketManagement()
    {
		$recentTickets = DB::table('user_tickets')
							->leftJoin('users', 'users.id', '=', 'user_tickets.added_by')
							->select(
								'user_tickets.id',
								'user_tickets.msg',
								'user_tickets.isActive',
								'user_tickets.updated_at',
							)
							->orderBy('user_tickets.id', 'DESC')
							->limit(5)
							->get();
		//Total CA tickets
		$ticketCaCounts = DB::table('user_tickets as t')
							->join('users as u', 'u.id', '=', 't.added_by')  // or t.uid
							->select(
								DB::raw('SUM(CASE WHEN t.isActive = 0 THEN 1 ELSE 0 END) as open_tickets'),
								DB::raw('SUM(CASE WHEN t.isActive = 1 THEN 1 ELSE 0 END) as resolved_tickets'),
								DB::raw('SUM(CASE WHEN t.isActive = 2 THEN 1 ELSE 0 END) as running_tickets'),
								DB::raw('COUNT(*) as total_tickets')
							)
							->where('u.u_type', 1)
							->first();
		//Total customer tickets
		$ticketCustCounts = DB::table('user_tickets as t')
							->join('users as u', 'u.id', '=', 't.added_by')  // or t.uid
							->select(
								DB::raw('SUM(CASE WHEN t.isActive = 0 THEN 1 ELSE 0 END) as open_tickets'),
								DB::raw('SUM(CASE WHEN t.isActive = 1 THEN 1 ELSE 0 END) as resolved_tickets'),
								DB::raw('SUM(CASE WHEN t.isActive = 2 THEN 1 ELSE 0 END) as running_tickets'),
								DB::raw('COUNT(*) as total_tickets')
							)
							->where('u.u_type', 2)
							->first();
		//Total support tickets
		$ticketTotalCounts = DB::table('user_tickets as t')
							->join('users as u', 'u.id', '=', 't.added_by')  // or t.uid
							->select(
								DB::raw('SUM(CASE WHEN t.utype = 1 THEN 1 ELSE 0 END) as ca_tickets'),
								DB::raw('SUM(CASE WHEN t.utype = 2 THEN 1 ELSE 0 END) as customer_tickets'),
								DB::raw('COUNT(*) as total_tickets')
							)
							->first();
		//echo "<pre>";print_r($ticketCustCounts);exit;
		return view('Admin.ticket-management', compact('recentTickets', 'ticketCustCounts','ticketCaCounts','ticketTotalCounts'));
    }
	
	public function getCustomerTicketStats()
	{
		$data = DB::table('user_tickets as t')
			->join('users as u', 'u.id', '=', 't.added_by')
			->select(
				DB::raw('SUM(CASE WHEN t.isActive = 0 THEN 1 ELSE 0 END) as open'),
				DB::raw('SUM(CASE WHEN t.isActive = 1 THEN 1 ELSE 0 END) as resolved'),
				DB::raw('SUM(CASE WHEN t.isActive = 2 THEN 1 ELSE 0 END) as running'),
				DB::raw('SUM(CASE WHEN t.isActive = 3 THEN 1 ELSE 0 END) as closed'),
				DB::raw('COUNT(*) as total')
			)
			->where('u.u_type', 2) // only customer users
			->first();

		return response()->json($data);
	}
	public function getCaTicketStats()
	{
		$data = DB::table('user_tickets as t')
			->join('users as u', 'u.id', '=', 't.added_by')
			->select(
				DB::raw('SUM(CASE WHEN t.isActive = 0 THEN 1 ELSE 0 END) as open'),
				DB::raw('SUM(CASE WHEN t.isActive = 1 THEN 1 ELSE 0 END) as resolved'),
				DB::raw('SUM(CASE WHEN t.isActive = 2 THEN 1 ELSE 0 END) as running'),
				DB::raw('SUM(CASE WHEN t.isActive = 3 THEN 1 ELSE 0 END) as closed'),
				DB::raw('COUNT(*) as total')
			)
			->where('u.u_type', 1) // only ca users
			->first();

		return response()->json($data);
	}
	public function getSupportTicketStats()
	{
		$data = DB::table('user_tickets as t')
			->join('users as u', 'u.id', '=', 't.added_by')
			->select(
				DB::raw('SUM(CASE WHEN t.isActive = 0 THEN 1 ELSE 0 END) as open'),
				DB::raw('SUM(CASE WHEN t.isActive = 1 THEN 1 ELSE 0 END) as resolved'),
				DB::raw('SUM(CASE WHEN t.isActive = 2 THEN 1 ELSE 0 END) as running'),
				DB::raw('SUM(CASE WHEN t.isActive = 3 THEN 1 ELSE 0 END) as closed'),
				DB::raw('COUNT(*) as total')
			)
			//->where('u.u_type', 1) // only ca users
			->first();

		return response()->json($data);
	}

	
	public function getTicketArray($utype,$userId)
	{
		$tickets = "";
		if (Auth::user()->u_type == 3 || Auth::user()->u_type == 6) { //admin
			$tickets =  DB::table('user_tickets')
							->select(DB::raw('user_tickets.*,company_profiles.comp_name,company_profiles.comp_email'))
							->leftJoin('company_profiles', 'user_tickets.added_by', '=', 'company_profiles.userId')
							->where('user_tickets.utype', '=', $utype)
							->orderBy('user_tickets.id', 'DESC')->paginate(10);
		}else if (Auth::user()->u_type == 1 || Auth::user()->u_type == 2 || Auth::user()->u_type == 4 || Auth::user()->u_type == 5) { //user
			$tickets =  DB::table('user_tickets')
							->select(DB::raw('user_tickets.*,company_profiles.comp_name,company_profiles.comp_email'))
							->leftJoin('company_profiles', 'user_tickets.added_by', '=', 'company_profiles.userId')
							->where('user_tickets.utype', '=', $utype)
							->where('user_tickets.added_by', '=', $userId)
							->orderBy('user_tickets.id', 'DESC')->paginate(10);
		}
		return $tickets;
	}
	public function getTicketList($utype,$userId)
	{
		$tickets = $this->getTicketArray($utype,$userId);
		//echo "<pre>"; print_r($tickets);exit;
		$array = array();
		foreach ($tickets as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['added_by'] = $val->added_by;
			$array[$val->id]['utype'] = $val->utype;
			$array[$val->id]['compId'] = $val->compId;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['comp_email'] = $val->comp_email;
			$array[$val->id]['due_date'] = $val->due_date;
			$array[$val->id]['msg'] = $val->msg;
			$array[$val->id]['priority'] = $val->priority;
			$array[$val->id]['isActive'] = $val->isActive;
			$array[$val->id]['created_at'] = $val->created_at;
			$array[$val->id]['updated_at'] = $val->updated_at;

			if (Auth::user()->u_type == 1) {
				$compId = $val->compId;
				$compName = DB::table('users')
					->select(DB::raw('users.name'))
					->where('id', '=', $compId)
					->get();
				$array[$val->id]['messages_by'] = $compName[0]->name;
				$array[$val->id]['messages'] = DB::table('user_ticket_chats')
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
				$array[$val->id]['messages'] = DB::table('user_ticket_chats')
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
		return $array;
	}

    public function CustomerTicket()
    {
		$title = 'Tickets';
		$userId = currentOwnerId();
		$utype = 2;
		$array = $this->getTicketList($utype,$userId);
		$tickets_pagination = $this->getTicketArray($utype,$userId);
		$tickets = json_decode(json_encode($array));
		//echo "<pre>"; print_r($tickets);exit;
		return view('Admin.customer-ticket')->with([
			'title' => $title,
			'tickets' => $tickets,
			'tickets_pagination' => $tickets_pagination,
		]);
    }
    public function CATicket()
    {
        $title = 'Tickets';
		$userId = currentOwnerId();
		$utype = 1;
		$array = $this->getTicketList($utype,$userId);
		$tickets_pagination = $this->getTicketArray($utype,$userId);
		$tickets = json_decode(json_encode($array));
		//echo "<pre>"; print_r($tickets);exit;
		return view('Admin.ca-ticket')->with([
			'title' => $title,
			'tickets' => $tickets,
			'tickets_pagination' => $tickets_pagination,
		]);
    }
	
	public function createTicket(Request $request)
    {
        $request->validate([
            'msg' => 'required|string',
            'chat_message' => 'required|string',
            'priority' => 'required'
        ]);
		$msg = array();
		$added_by = currentOwnerId();
		$utype = currentOwnerUserType();
		$chat_message = $request->chat_message;
		$msg = $request->msg;
		$due_date = Carbon::now()->addDays(3);
		
		$isClosed = User_tickets::where('added_by', $added_by)
                               ->whereIn('isActive', [0, 1, 2])
                               ->exists();
		//echo $isClosed;exit;
		if(!$isClosed){
			$ticket = User_tickets::create([
				'added_by' => $added_by,
				'utype' => $utype,
				'compId' => 1,
				'due_date' => $due_date,
				'msg' => $msg,
				'priority' => $request->priority,
				'isActive' => 0
			]);

			// Step 3: Insert into user_ticket_chats
			User_ticket_chats::create([
				'to_user_id' => 1,
				'from_user_id' => $added_by,
				'chat_message' => $chat_message,
				'c_qid'   => $ticket->id,
				'status'  => 0,
			]);

			$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'Ticket created successfully!'
					);
				
		}else{
			$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'Already you have open ticket!'
					);
		}
		return response()->json($msg);
    }

    public function TicketView()
    {
        return view('Admin.ticket-view');
    }
	
	public function getUser($added_by,$utype)
	{
		$tickets = "";
		if ($utype == 1 || $utype == 4) { //ca
			$tickets =  DB::table('users')
							->select(DB::raw('users.id,users.created_at,ca_profiles.comp_name,ca_profiles.comp_email'))
							->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
							->where('users.id', '=', $added_by)
							->where('users.u_type', '=', $utype)
							->get();
		}else if ($utype == 2 || $utype == 5) { //user-company
			$tickets =  DB::table('users')
							->select(DB::raw('users.id,users.created_at,company_profiles.comp_name,company_profiles.comp_email'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.id', '=', $added_by)
							->where('users.u_type', '=', $utype)
							->get();
		}
		return $tickets;
	}
	
	public function ticket_response($caId, $uid, $id)
	{
		$caId = base64_decode($caId);
		$uid = base64_decode($uid);
		$id = base64_decode($id);
		$title = "Ticket Response";

		$compId = $uid;
		$array['id'] = $id;
		$array['uid'] = $uid;
		
		$caIds = DB::table('user_tickets')
						->select(DB::raw('user_tickets.*,user_tickets.msg'))
						->where('id', '=', $id)
						->get()->toArray();
		$array['added_by'] = $caIds[0]->added_by;
		$array['utype'] = $caIds[0]->utype;
		$array['compId'] = $caIds[0]->compId;
		$array['msg'] = $caIds[0]->msg;
		$array['priority'] = $caIds[0]->priority;
		$array['isActive'] = $caIds[0]->isActive;
		$array['created_at'] = $caIds[0]->created_at;
		$array['updated_at'] = $caIds[0]->updated_at;
		//user get data
		$userData = $this->getUser($caIds[0]->added_by,$caIds[0]->utype);
		$userData = json_decode(json_encode($userData));
		$array['user'] = $userData[0] ?? '';
		$caId = $caIds[0]->added_by;
		
		
		$totalTickets = DB::table('user_tickets')
							->where('added_by', $caId)
							->count();
		$prevTickets = DB::table('user_tickets')
								->where('added_by', $caId)
								->where('created_at', '>=', Carbon::now()->subMonths(3))  // last 3 months
								->count();
		$array['totalTickets'] = $totalTickets;
		$array['prevTickets'] = $prevTickets;
		

		$array['caid'] = $caId;
		//Get chat message from seller
		$array['messages'] = DB::table('user_ticket_chats')
			->where('c_qid', '=', $id)
			->get();

		//chat status set 0 to 1
		$message_update = DB::table('user_ticket_chats')
			->where('c_qid', '=', $id)
			->where(function ($q) use ($caId) {
				$q->where(function ($q2) use ($caId) {
					$q2->where('to_user_id', Auth::user()->id)->Where('from_user_id', $caId);
				});
			})
			->update(
				array(
					'status' => 1,
				)
			);

		//chat status set 0 to 1
		/*$update_status = DB::table('user_tickets')
							->where('id', '=', $id)
							->update(
								array(
									'isActive' => 1,
								)
							);*/
		

		$quotes = json_decode(json_encode($array));
		//echo "<pre>";print_r($quotes);exit;

		return view('Admin.ticket-view')->with([
			'quotes' => $quotes,
			'title' => $title
		]);
	
	}
	
	protected function validator(array $data)
    {
		return Validator::make(
			$data,
			[
           'attachment_file' => 'mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg|max:2048',
        ]
		);
    }
	
	public function upload_file_ticket(Request $request)
	{
		//die("asdfas");
		/* $this->validate($request, [
			'attachment_file' => 'mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg|max:2048',

        ]); */

		 $validation = $this->validator($request->all());
		if ($validation->fails()) {
            return response()->json($validation->errors()->toArray());
		} else {

			$str = '';
			if ($file = $request->hasFile('attachment_file')) {

				$file = $request->file('attachment_file');

				$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName();
				$destinationPath1 = public_path() . '/uploads/chat/';

				$file->move($destinationPath1, $fileName1);

				$filepath = asset('public/uploads/chat/' . $fileName1);

				$ext = pathinfo($fileName1, PATHINFO_EXTENSION);
				if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
					$str .= '<div class="fileAttechmentInner relative"> <img src="' . $filepath . '" alt=""> <a href="javascript:;"><span onclick="remove_image(event)" class="remove_attachment_file">x</span></a> </div><div class="clear"></div>';
				} else {
					$str .= '<div class="fileAttechmentInnerText relative"> <a class="relative" href="' . $filepath . '" target="_blank">' . $fileName1 . ' <i class="fa fa-download" aria-hidden="true"></i><span onclick="remove_image(event)" class="remove_attachment_file">x</span></a>  </div><div class="clear"></div>';
				}
			}
			//echo $str; exit;

				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => '',
					'message' => $str,
					'filename' => $fileName1
				);
				return response()->json($msg);
		}
	}

	public function insert_chat_ticket(Request $request)
    {
		$user_id = currentOwnerId();
        $to_user_id = $request->input('to_user_id');
        $chat_message = $request->input('chat_message');
        $message_file = $request->input('message_file');
        $c_qid = $request->input('c_qid');
        $notifyCustomer = $request->input('notifyCustomer');
		
		//echo "<pre>";print_r($_POST);exit;
		if ($message_file == 'undefined') {
			$message_file = '';
		}

		$str = '';
		$insert = DB::table('user_ticket_chats')->insertGetId(
					 array(
							'to_user_id' => $to_user_id,
							'from_user_id' => $user_id,
							'chat_message' => $chat_message,
							'attached' => $message_file,
							'c_qid' => $c_qid,
							'status' => 0
						)
				);

		if ($insert) {
			$result = DB::table('user_ticket_chats')
				->where('chat_message_id', '=', $insert)
				->where('from_user_id', '=', $user_id)
				->where('to_user_id', '=', $to_user_id)
					->get()->toArray();
			//send email to user		
			if($notifyCustomer == '1'){
				$this->emailNotification($to_user_id,$c_qid,$chat_message);
			}
			
			if ($result[0]->attached != "") {
				$ext = pathinfo($result[0]->attached, PATHINFO_EXTENSION);
				$filepath = asset('uploads/chat/' . $result[0]->attached);

				if (in_array(strtolower($ext), ['jpeg', 'jpg', 'png', 'gif'])) {
					// Image attachment
					$timestamp = date('h:i A', strtotime($result[0]->timestamp));
					$str .= '<div class="message-out">
						<div class="d-flex align-items-end flex-column">
							<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($result[0]->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
							<div class="message d-flex align-items-end flex-column">
								<div class="d-flex align-items-center mb-1 chat-msg">
									<div class="flex-grow-1 ms-3">
										<div class="msg-content bg-primary">
											<div class="image-message-preview">
												<a href="' . $filepath . '" target="_blank"><img src="' . $filepath . '" alt="Image" class="img-fluid"></a>
												<div class="image-timestamp">' . $timestamp . '</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				} else if (strtolower($ext) === 'pdf') {
					// PDF document
					$str .= '<div class="message-out">
						<div class="d-flex align-items-end flex-column">
							<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($result[0]->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
							<div class="message d-flex align-items-end flex-column">
								<div class="d-flex align-items-center mb-1 chat-msg">
									<div class="flex-grow-1 ms-3">
										<div class="msg-content bg-primary">
											<div class="pdf-preview">
												<div class="pdf-info">
													<div class="pdf-icon-small">
														<i class="ti ti-file-text text-danger"></i>
													</div>
													<div class="pdf-name">' . $result[0]->attached . '</div>
													<div class="pdf-size">Adobe Acrobat Document</div>
												</div>
												<div class="pdf-actions">
													<a href="' . $filepath . '" target="_blank" class="pdf-action">Open</a>
													<a href="' . $filepath . '" download="' . $result[0]->attached . '" class="pdf-action">Save as...</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				} else {
					// Other document types
					$docType = '';
					$docIcon = '';
					$docTypeText = 'Document';
					$bgColor = '#0078D7'; // Default blue

					switch (strtolower($ext)) {
						case 'doc':
						case 'docx':
							$docType = 'doc-preview';
							$docTypeText = 'Microsoft Word Document';
							$bgColor = '#2B579A'; // Word blue
							break;
						case 'xls':
						case 'xlsx':
							$docType = 'xls-preview';
							$docTypeText = 'Microsoft Excel Document';
							$bgColor = '#217346'; // Excel green
							break;
						case 'ppt':
						case 'pptx':
							$docType = 'ppt-preview';
							$docTypeText = 'Microsoft PowerPoint Document';
							$bgColor = '#D24726'; // PowerPoint orange
							break;
						case 'zip':
						case 'rar':
							$docType = 'zip-preview';
							$docTypeText = 'Archive File';
							$bgColor = '#FFA000'; // Archive yellow
							break;
						case 'txt':
							$docType = 'txt-preview';
							$docTypeText = 'Text Document';
							$bgColor = '#4285F4'; // Text blue
							break;
						default:
							$docType = 'default-preview';
							$docTypeText = 'Document';
							$bgColor = '#607D8B'; // Default gray-blue
					}

					$str .= '<div class="message-out">
						<div class="d-flex align-items-end flex-column">
							<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($result[0]->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
							<div class="message d-flex align-items-end flex-column">
								<div class="d-flex align-items-center mb-1 chat-msg">
									<div class="flex-grow-1 ms-3">
										<div class="msg-content bg-primary">
											<div style="background-color: ' . $bgColor . '; border-radius: 12px; padding: 15px; color: white; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
												<div style="font-size: 16px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">' . $result[0]->attached . '</div>
												<div style="font-size: 13px; opacity: 0.8;">' . $docTypeText . '</div>
												<div style="display: flex; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 10px;">
													<a href="' . $filepath . '" target="_blank" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Open</a>
													<a href="' . $filepath . '" download="' . $result[0]->attached . '" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Save as...</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}

			if ($result[0]->chat_message != "") {
				$str .= '<div class="message-out">
					<div class="d-flex align-items-end flex-column">
						<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($result[0]->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
						<div class="message d-flex align-items-end flex-column">
							<div class="d-flex align-items-center mb-1 chat-msg">
								<div class="flex-grow-1 ms-3">
									<div class="msg-content bg-primary">
										<p class="mb-0">' . $result[0]->chat_message . '</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
		}

		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => '',
			'message' => $str,
		);
		return response()->json($msg);
    }

	public function fetch_user_chat_history_ticket($from_user_id, $to_user_id)
	{
		$result = DB::table('user_ticket_chats')
					->select(DB::raw('user_ticket_chats.*'))
					->where([
						['from_user_id', '=', $from_user_id],
						['to_user_id', '=', $to_user_id],
					])
					->orwhere([
						['from_user_id', '=', $to_user_id],
						['to_user_id', '=', $from_user_id],
					])
					->orderBy('timestamp', 'asc')
					->get()->toArray();

		$html = '';
		foreach ($result as $row) {
			$isCurrentUser = ($row->from_user_id == $from_user_id);
			if ($isCurrentUser) {
				// Outgoing message (You)
				if ($row->attached != "") {
					$ext = pathinfo($row->attached, PATHINFO_EXTENSION);
					$filepath = asset('uploads/chat/' . $row->attached);

					if (in_array(strtolower($ext), ['jpeg', 'jpg', 'png', 'gif'])) {
						$timestamp = date('h:i A', strtotime($row->timestamp));
						$html .= '<div class="message-out">
							<div class="d-flex align-items-end flex-column">
								<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
								<div class="message d-flex align-items-end flex-column">
									<div class="d-flex align-items-center mb-1 chat-msg">
										<div class="flex-grow-1 ms-3">
											<div class="msg-content bg-primary">
												<div class="image-message-preview">
													<a href="' . $filepath . '" target="_blank"><img src="' . $filepath . '" alt="Image" class="img-fluid"></a>
													<div class="image-timestamp">' . $timestamp . '</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
					} else if (strtolower($ext) === 'pdf') {
						$html .= '<div class="message-out">
							<div class="d-flex align-items-end flex-column">
								<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
								<div class="message d-flex align-items-end flex-column">
									<div class="d-flex align-items-center mb-1 chat-msg">
										<div class="flex-grow-1 ms-3">
											<div class="msg-content bg-primary">
												<div class="pdf-preview">
													<div class="pdf-info">
														<div class="pdf-icon-small">
															<i class="ti ti-file-text text-danger"></i>
														</div>
														<div class="pdf-name">' . $row->attached . '</div>
														<div class="pdf-size">Adobe Acrobat Document</div>
													</div>
													<div class="pdf-actions">
														<a href="' . $filepath . '" target="_blank" class="pdf-action">Open</a>
														<a href="' . $filepath . '" download="' . $row->attached . '" class="pdf-action">Save as...</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
					} else {
						$docTypeText = 'Document';
						$bgColor = '#0078D7';
						switch (strtolower($ext)) {
							case 'doc':
							case 'docx':
								$docTypeText = 'Microsoft Word Document';
								$bgColor = '#2B579A';
								break;
							case 'xls':
							case 'xlsx':
								$docTypeText = 'Microsoft Excel Document';
								$bgColor = '#217346';
								break;
							case 'ppt':
							case 'pptx':
								$docTypeText = 'Microsoft PowerPoint Document';
								$bgColor = '#D24726';
								break;
							case 'zip':
							case 'rar':
								$docTypeText = 'Archive File';
								$bgColor = '#FFA000';
								break;
							case 'txt':
								$docTypeText = 'Text Document';
								$bgColor = '#4285F4';
								break;
							default:
								$docTypeText = 'Document';
								$bgColor = '#607D8B';
						}
						$html .= '<div class="message-out">
							<div class="d-flex align-items-end flex-column">
								<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
								<div class="message d-flex align-items-end flex-column">
									<div class="d-flex align-items-center mb-1 chat-msg">
										<div class="flex-grow-1 ms-3">
											<div class="msg-content bg-primary">
												<div style="background-color: ' . $bgColor . '; border-radius: 12px; padding: 15px; color: white; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
													<div style="font-size: 16px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">' . $row->attached . '</div>
													<div style="font-size: 13px; opacity: 0.8;">' . $docTypeText . '</div>
													<div style="display: flex; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 10px;">
														<a href="' . $filepath . '" target="_blank" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Open</a>
														<a href="' . $filepath . '" download="' . $row->attached . '" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Save as...</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
					}
				}
				if ($row->chat_message != "") {
					$html .= '<div class="message-out">
						<div class="d-flex align-items-end flex-column">
							<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
							<div class="message d-flex align-items-end flex-column">
								<div class="d-flex align-items-center mb-1 chat-msg">
									<div class="flex-grow-1 ms-3">
										<div class="msg-content bg-primary">
											<p class="mb-0">' . $row->chat_message . '</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			} else {
				// Incoming message (Other user)
				if ($row->attached != "") {
					$ext = pathinfo($row->attached, PATHINFO_EXTENSION);
					$filepath = asset('uploads/chat/' . $row->attached);

					if (in_array(strtolower($ext), ['jpeg', 'jpg', 'png', 'gif'])) {
						$timestamp = date('h:i A', strtotime($row->timestamp));
						$html .= '<div class="message-in">
							<div class="d-flex">
								<div class="flex-grow-1 mx-3">
									<div class="d-flex align-items-start flex-column">
										<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small></p>
										<div class="message d-flex align-items-start flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 me-3">
													<div class="msg-content card mb-0">
														<div class="image-message-preview">
															<a href="' . $filepath . '" target="_blank"><img src="' . $filepath . '" alt="Image" class="img-fluid"></a>
															<div class="image-timestamp">' . $timestamp . '</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
					} else if (strtolower($ext) === 'pdf') {
						$html .= '<div class="message-in">
							<div class="d-flex">
								<div class="flex-grow-1 mx-3">
									<div class="d-flex align-items-start flex-column">
										<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small></p>
										<div class="message d-flex align-items-start flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 me-3">
													<div class="msg-content card mb-0">
														<div class="pdf-preview">
															<div class="pdf-info">
																<div class="pdf-icon-small">
																	<i class="ti ti-file-text text-danger"></i>
																</div>
																<div class="pdf-name">' . $row->attached . '</div>
																<div class="pdf-size">Adobe Acrobat Document</div>
															</div>
															<div class="pdf-actions">
																<a href="' . $filepath . '" target="_blank" class="pdf-action">Open</a>
																<a href="' . $filepath . '" download="' . $row->attached . '" class="pdf-action">Save as...</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
					} else {
						$docTypeText = 'Document';
						$bgColor = '#0078D7';
						switch (strtolower($ext)) {
							case 'doc':
							case 'docx':
								$docTypeText = 'Microsoft Word Document';
								$bgColor = '#2B579A';
								break;
							case 'xls':
							case 'xlsx':
								$docTypeText = 'Microsoft Excel Document';
								$bgColor = '#217346';
								break;
							case 'ppt':
							case 'pptx':
								$docTypeText = 'Microsoft PowerPoint Document';
								$bgColor = '#D24726';
								break;
							case 'zip':
							case 'rar':
								$docTypeText = 'Archive File';
								$bgColor = '#FFA000';
								break;
							case 'txt':
								$docTypeText = 'Text Document';
								$bgColor = '#4285F4';
								break;
							default:
								$docTypeText = 'Document';
								$bgColor = '#607D8B';
						}
						$html .= '<div class="message-in">
							<div class="d-flex">
								<div class="flex-grow-1 mx-3">
									<div class="d-flex align-items-start flex-column">
										<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small></p>
										<div class="message d-flex align-items-start flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 me-3">
													<div class="msg-content card mb-0">
														<div style="background-color: ' . $bgColor . '; border-radius: 12px; padding: 15px; color: white; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
															<div style="font-size: 16px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">' . $row->attached . '</div>
															<div style="font-size: 13px; opacity: 0.8;">' . $docTypeText . '</div>
															<div style="display: flex; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 10px;">
																<a href="' . $filepath . '" target="_blank" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Open</a>
																<a href="' . $filepath . '" download="' . $row->attached . '" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Save as...</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
					}
				}
				if ($row->chat_message != "") {
					$html .= '<div class="message-in">
						<div class="d-flex">
							<div class="flex-grow-1 mx-3">
								<div class="d-flex align-items-start flex-column">
									<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($row->timestamp)) . '</small></p>
									<div class="message d-flex align-items-start flex-column">
										<div class="d-flex align-items-center mb-1 chat-msg">
											<div class="flex-grow-1 me-3">
												<div class="msg-content card mb-0">
													<p class="mb-0">' . $row->chat_message . '</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}
		}
		return $html;
	}

	public function refreshMessagesTicket(Request $request)
	{
		$user_id = currentOwnerId();
		$c_qid = $request->input('c_qid');

		// Get all messages for the conversation
		$messages = DB::table('user_ticket_chats')
			->where('c_qid', $c_qid)
			->orderBy('timestamp', 'asc')
			->get();

		$html = '';

		foreach ($messages as $message) {
			// For current user's messages
			if ($message->from_user_id == $user_id) {
				if ($message->attached != "") {
					$ext = pathinfo($message->attached, PATHINFO_EXTENSION);
					$filepath = asset('uploads/chat/' . $message->attached);

					if (in_array(strtolower($ext), ['jpeg', 'jpg', 'png', 'gif'])) {
						// Image attachment
						$timestamp = date('h:i A', strtotime($message->timestamp));
						$html .= '<div class="message-out">
							<div class="d-flex align-items-end flex-column">
								<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
								<div class="message d-flex align-items-end flex-column">
									<div class="d-flex align-items-center mb-1 chat-msg">
										<div class="flex-grow-1 ms-3">
											<div class="msg-content bg-primary">
												<div class="image-message-preview">
													<a href="' . $filepath . '" target="_blank"><img src="' . $filepath . '" alt="Image" class="img-fluid"></a>
													<div class="image-timestamp">' . $timestamp . '</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					} else if (strtolower($ext) === 'pdf') {
						// PDF document
						$fileSize = ''; // You might want to fetch actual file size if available
						$html .= '<div class="message-out">
							<div class="d-flex align-items-end flex-column">
								<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
								<div class="message d-flex align-items-end flex-column">
									<div class="d-flex align-items-center mb-1 chat-msg">
										<div class="flex-grow-1 ms-3">
											<div class="msg-content bg-primary">
												<div class="pdf-preview">
													<div class="pdf-info">
														<div class="pdf-icon-small">
															<i class="ti ti-file-text text-danger"></i>
														</div>
														<div class="pdf-name">' . $message->attached . '</div>
														<div class="pdf-size">Adobe Acrobat Document</div>
													</div>
													<div class="pdf-actions">
														<a href="' . $filepath . '" target="_blank" class="pdf-action">Open</a>
														<a href="' . $filepath . '" download="' . $message->attached . '" class="pdf-action">Save as...</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					} else {
						// Other document types
						$docType = '';
						$docTypeText = 'Document';
						$bgColor = '#0078D7'; // Default blue

						switch (strtolower($ext)) {
							case 'doc':
							case 'docx':
								$docType = 'doc-preview';
								$docTypeText = 'Microsoft Word Document';
								$bgColor = '#2B579A'; // Word blue
								break;
							case 'xls':
							case 'xlsx':
								$docType = 'xls-preview';
								$docTypeText = 'Microsoft Excel Document';
								$bgColor = '#217346'; // Excel green
								break;
							case 'ppt':
							case 'pptx':
								$docType = 'ppt-preview';
								$docTypeText = 'Microsoft PowerPoint Document';
								$bgColor = '#D24726'; // PowerPoint orange
								break;
							case 'zip':
							case 'rar':
								$docType = 'zip-preview';
								$docTypeText = 'Archive File';
								$bgColor = '#FFA000'; // Archive yellow
								break;
							case 'txt':
								$docType = 'txt-preview';
								$docTypeText = 'Text Document';
								$bgColor = '#4285F4'; // Text blue
								break;
							default:
								$docType = 'default-preview';
								$docTypeText = 'Document';
								$bgColor = '#607D8B'; // Default gray-blue
						}

						$html .= '<div class="message-out">
							<div class="d-flex align-items-end flex-column">
								<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
								<div class="message d-flex align-items-end flex-column">
									<div class="d-flex align-items-center mb-1 chat-msg">
										<div class="flex-grow-1 ms-3">
											<div class="msg-content bg-primary">
												<div style="background-color: ' . $bgColor . '; border-radius: 12px; padding: 15px; color: white; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
													<div style="font-size: 16px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">' . $message->attached . '</div>
													<div style="font-size: 13px; opacity: 0.8;">' . $docTypeText . '</div>
													<div style="display: flex; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 10px;">
														<a href="' . $filepath . '" target="_blank" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Open</a>
														<a href="' . $filepath . '" download="' . $message->attached . '" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Save as...</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					}
				}

				// Text message
				if ($message->chat_message != "") {
					$html .= '<div class="message-out">
						<div class="d-flex align-items-end flex-column">
							<p class="mb-1 text-muted"><small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small> <i class="ph-duotone ph-user-circle"></i></p>
							<div class="message d-flex align-items-end flex-column">
								<div class="d-flex align-items-center mb-1 chat-msg">
									<div class="flex-grow-1 ms-3">
										<div class="msg-content bg-primary">
											<p class="mb-0">' . $message->chat_message . '</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			} else {
				// For other user's messages
				if ($message->attached != "") {
					$ext = pathinfo($message->attached, PATHINFO_EXTENSION);
					$filepath = asset('uploads/chat/' . $message->attached);

					if (in_array(strtolower($ext), ['jpeg', 'jpg', 'png', 'gif'])) {
						// Image attachment
						$timestamp = date('h:i A', strtotime($message->timestamp));
						$html .= '<div class="message-in">
							<div class="d-flex">
								<div class="flex-grow-1 mx-3">
									<div class="d-flex align-items-start flex-column">
										<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small></p>
										<div class="message d-flex align-items-start flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 me-3">
													<div class="msg-content card mb-0">
														<div class="image-message-preview">
															<a href="' . $filepath . '" target="_blank"><img src="' . $filepath . '" alt="Image" class="img-fluid"></a>
															<div class="image-timestamp">' . $timestamp . '</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					} else if (strtolower($ext) === 'pdf') {
						// PDF document
						$fileSize = ''; // You might want to fetch actual file size if available
						$html .= '<div class="message-in">
							<div class="d-flex">
								<div class="flex-grow-1 mx-3">
									<div class="d-flex align-items-start flex-column">
										<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small></p>
										<div class="message d-flex align-items-start flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 me-3">
													<div class="msg-content card mb-0">
														<div class="pdf-preview">
															<div class="pdf-info">
																<div class="pdf-icon-small">
																	<i class="ti ti-file-text text-danger"></i>
																</div>
																<div class="pdf-name">' . $message->attached . '</div>
																<div class="pdf-size">Adobe Acrobat Document</div>
															</div>
															<div class="pdf-actions">
																<a href="' . $filepath . '" target="_blank" class="pdf-action">Open</a>
																<a href="' . $filepath . '" download="' . $message->attached . '" class="pdf-action">Save as...</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					} else {
						// Other document types
						$docType = '';
						$docTypeText = 'Document';
						$bgColor = '#0078D7'; // Default blue

						switch (strtolower($ext)) {
							case 'doc':
							case 'docx':
								$docType = 'doc-preview';
								$docTypeText = 'Microsoft Word Document';
								$bgColor = '#2B579A'; // Word blue
								break;
							case 'xls':
							case 'xlsx':
								$docType = 'xls-preview';
								$docTypeText = 'Microsoft Excel Document';
								$bgColor = '#217346'; // Excel green
								break;
							case 'ppt':
							case 'pptx':
								$docType = 'ppt-preview';
								$docTypeText = 'Microsoft PowerPoint Document';
								$bgColor = '#D24726'; // PowerPoint orange
								break;
							case 'zip':
							case 'rar':
								$docType = 'zip-preview';
								$docTypeText = 'Archive File';
								$bgColor = '#FFA000'; // Archive yellow
								break;
							case 'txt':
								$docType = 'txt-preview';
								$docTypeText = 'Text Document';
								$bgColor = '#4285F4'; // Text blue
								break;
							default:
								$docType = 'default-preview';
								$docTypeText = 'Document';
								$bgColor = '#607D8B'; // Default gray-blue
						}

						$html .= '<div class="message-in">
							<div class="d-flex">
								<div class="flex-grow-1 mx-3">
									<div class="d-flex align-items-start flex-column">
										<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small></p>
										<div class="message d-flex align-items-start flex-column">
											<div class="d-flex align-items-center mb-1 chat-msg">
												<div class="flex-grow-1 me-3">
													<div class="msg-content card mb-0">
														<div style="background-color: ' . $bgColor . '; border-radius: 12px; padding: 15px; color: white; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
															<div style="font-size: 16px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">' . $message->attached . '</div>
															<div style="font-size: 13px; opacity: 0.8;">' . $docTypeText . '</div>
															<div style="display: flex; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 10px;">
																<a href="' . $filepath . '" target="_blank" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Open</a>
																<a href="' . $filepath . '" download="' . $message->attached . '" style="flex: 1; text-align: center; padding: 6px; border-radius: 4px; text-decoration: none; color: white; font-weight: 500; transition: background 0.2s;">Save as...</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					}
				}

				// Text message
				if ($message->chat_message != "") {
					$html .= '<div class="message-in">
						<div class="d-flex">
							<div class="flex-grow-1 mx-3">
								<div class="d-flex align-items-start flex-column">
									<p class="mb-1 text-muted"><i class="ph-duotone ph-user-circle-gear"></i> <small>' . date('d-m-Y h:i A', strtotime($message->timestamp)) . '</small></p>
									<div class="message d-flex align-items-start flex-column">
										<div class="d-flex align-items-center mb-1 chat-msg">
											<div class="flex-grow-1 me-3">
												<div class="msg-content card mb-0">
													<p class="mb-0">' . $message->chat_message . '</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}
		}

		return response()->json([
			'success' => true,
			'messages' => $html
		]);
	}
	
	public function resolvedTicket(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$tId = $request->c_qid;
		$to_user_id = $request->to_user_id;
		$chat_message = $request->chat_message;
		$notifyCustomer = $request->notifyCustomer;
		$isActive = 1;
		$update = DB::table('user_tickets')
					->where('id', $tId)
					->update(
						array(
							'isActive' => $isActive,
							'updated_at' => now()

						)
					);
		if($notifyCustomer == '1'){
				$this->emailNotification($to_user_id,$tId,$chat_message);
		}
		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/customer-ticket'),
			'message' => 'Ticket resolved successfully'
		);
		return response()->json($msg);
	}
	
	public function closedTicket(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$tId = $request->c_qid;
		$to_user_id = $request->to_user_id;
		$chat_message = $request->chat_message;
		$notifyCustomer = $request->notifyCustomer;
		$isActive = 3;
		$update = DB::table('user_tickets')
					->where('id', $tId)
					->update(
						array(
							'isActive' => $isActive,
							'updated_at' => now()

						)
					);
		if($notifyCustomer == '1'){
			$this->emailNotification($to_user_id,$tId,$chat_message);
		}
		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/customer-ticket'),
			'message' => 'Ticket closed successfully'
		);
		return response()->json($msg);
	}
	
	public function emailNotification($uid,$c_qid,$chat_message)
	{
		//echo "<pre>";print_r($result);exit;
		$ticketStatus = DB::table('user_tickets')
							->select(
								'user_tickets.id',
								'user_tickets.isActive',
								DB::raw("
									CASE 
										WHEN user_tickets.isActive = 0 THEN 'Pending'
										WHEN user_tickets.isActive = 1 THEN 'Resolved'
										WHEN user_tickets.isActive = 2 THEN 'In-Progress'
										WHEN user_tickets.isActive = 3 THEN 'Closed'
										ELSE 'Unknown'
									END AS status_text
								")
							)
							->where('user_tickets.id', $c_qid)
							->first();
		$ticketId = "#TKT-". sprintf("%05d",$ticketStatus->id); //ticket id
		$status_text = $ticketStatus->status_text; //ticket status
		
		$u_type = User::where('id', $uid)->value('u_type');
		if($u_type == 1 || $u_type == 5){
			$userDet =  DB::table('users')
							->select(DB::raw('users.id,users.id,ca_profiles.comp_name,ca_profiles.comp_email'))
							->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
							->where('users.id', '=', $uid)
							->get();
		}else if($u_type == 2 || $u_type == 4){
			$userDet =  DB::table('users')
							->select(DB::raw('users.id,users.id,company_profiles.comp_name,company_profiles.comp_email'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.id', '=', $uid)
							->get();
		}
		$name = $userDet[0]->comp_name;
		$email = $userDet[0]->comp_email;
		$replyMsg = $chat_message ?? '';
		//echo "<pre>";print_r($userDet);exit;
		$body = view('reply_email_template', compact('name', 'email', 'replyMsg','status_text'))->render(); 
		$data_email = ['email' => $email];
		$emailSubject = "Support Team Response — Ticket ".$ticketId;
		$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);

		return $sendMail;
	}
	
}
