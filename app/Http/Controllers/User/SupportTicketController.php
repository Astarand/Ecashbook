<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Helpers\AuditLogger;

use App\Models\SupportTickets;
use App\Models\SupportTicketMessages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SupportTicketController extends Controller
{
 
	public function GSTComplianceSupport()
    {
		
		$userType = Auth::user()->u_type;
		checkCoreAccess('Tax Filing & Returns');
		$query = SupportTickets::orderBy('created_at', 'desc');
		$userId = null;
		if (!in_array($userType, [3, 6])) {
			$userId = (in_array($userType, [2])) ? Auth::user()->id : Auth::user()->user_add_by;
			$query->where('user_id', $userId);
		}
		$tickets = $query->paginate(10);
		return view('User.gst-compliance-support', compact('tickets'));
    }

    /* =========================
       CREATE TICKET
    ========================== */
    public function store(Request $request)
	{
		$userType = Auth::user()->u_type;
		$userId = null;
		if (!in_array($userType, [3, 6])) {
			$userId = (in_array($userType, [2])) ? Auth::user()->id : Auth::user()->user_add_by;
		}
		//if user already has open ticket
		$hasOpenTicket = SupportTickets::where('user_id', $userId)
			->where('status', '!=', 'closed')
			->exists();

		if ($hasOpenTicket) {
			return response()->json([
				'status'  => false,
				'message' => 'You already have an open support ticket. Please wait until it is closed.'
			], 422);
		}

		//Validation
		$request->validate([
			'created_at' => 'required|date',
			'query_type' => 'required',
			'message'    => 'required',
			'attachment' => 'nullable|file|max:5120',
		]);

		//Create ticket
		$ticket = SupportTickets::create([
			'ticket_no'   => 'GST-' . date('Y') . '-' . rand(100000, 999999),
			'user_id'     => $userId,
			'query_type'  => $request->query_type,
			'other_query' => $request->query_type === 'other' ? $request->other_query : null,
			'status'      => 'open',
			'created_at'  => $request->created_at ?? now(),
		]);

		//Attachment
		$attachment = null;
		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
			$destination = public_path('uploads/support_tickets');
			if (!file_exists($destination)) {
				mkdir($destination, 0777, true);
			}
			$file->move($destination, $filename);
			$attachment = 'uploads/support_tickets/' . $filename;
		}


		//First message
		SupportTicketMessages::create([
			'ticket_id'    => $ticket->id,
			'sender_id'    => $userId,
			'sender_utype' => 2,
			'message'      => $request->message,
			'attachment'   => $attachment,
		]);

		return response()->json([
			'status'  => true,
			'message' => 'Support ticket created successfully'
		]);
	}

    /* =========================
       LOAD CHAT
    ========================== */
    public function messages($id)
    {
        $messages = SupportTicketMessages::where('ticket_id', $id)
                    ->with('sender')
                    ->orderBy('id')
                    ->get();

        return response()->json($messages);
    }

    /* =========================
       SEND MESSAGE
    ========================== */
    public function sendMessage(Request $request)
    {
		$userType = Auth::user()->u_type;
		$userId = null;
		if (!in_array($userType, [3, 6])) {
			$userId = (in_array($userType, [2])) ? Auth::user()->id : Auth::user()->user_add_by;
		}else{
			$userId = (in_array($userType, [3])) ? Auth::user()->id : Auth::user()->admin_add_by;
		}
        $request->validate([
            'ticket_id' => 'required',
            'message'    => 'nullable|string',
			'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);
		
		// If no attachment, message is required
		if (!$request->hasFile('attachment') && trim($request->message) === '') {
			return response()->json([
				'status' => false,
				'errors' => [
					'message' => ['Message is required when no file is attached']
				]
			], 422);
		}

		$path = null;
		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
			$destination = public_path('uploads/support_tickets');
			if (!file_exists($destination)) {
				mkdir($destination, 0777, true);
			}
			$file->move($destination, $filename);
			$path = 'uploads/support_tickets/' . $filename;
		}
        SupportTicketMessages::create([
            'ticket_id'   => $request->ticket_id,
            'sender_id'   => $userId,
            'sender_utype'=> $userType,
            'message'     => $request->message,
			'attachment'=> $path
        ]);

        return response()->json(['status' => true]);
    }
	
	public function resolve(SupportTickets $ticket)
    {

        if ($ticket->status !== 'open') {
            return response()->json([
                'status' => false,
                'message' => 'Ticket cannot be resolved'
            ], 422);
        }

        $ticket->update(['status' => 'resolved']);

        return response()->json([
            'status' => true,
            'message' => 'Ticket resolved successfully'
        ]);
    }

    public function close(SupportTickets $ticket)
    {
        if ($ticket->status === 'closed') {
            return response()->json([
                'status' => false,
                'message' => 'Ticket already closed'
            ], 422);
        }

        $ticket->update(['status' => 'closed']);

        return response()->json([
            'status' => true,
            'message' => 'Ticket closed successfully'
        ]);
    }
 
}
