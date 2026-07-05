<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatCaConversation;
use App\Models\ChatCaMessage;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | START CHAT
    |--------------------------------------------------------------------------
    */

    public function startChat(Request $request)
    {

        $conversation = ChatCaConversation::where('ca_id', $request->ca_id)
            ->where('company_id', $request->company_id)
            ->first();

        if (!$conversation) {

            $conversation = ChatCaConversation::create([
                'ca_id' => $request->ca_id,
                'company_id' => $request->company_id,
                'last_message_at' => now()
            ]);
        }

        return response()->json([
            'status' => true,
            'conversation_id' => $conversation->id
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SEND MESSAGE
    |--------------------------------------------------------------------------
    */

    public function sendMessage(Request $request)
    {

        $request->validate([
            'conversation_id' => 'required',
            'message' => 'nullable',
            'file' => 'nullable|file|max:2048'
        ]);

        $user = Auth::user();

        $senderType = ($user->u_type == 1 || $user->u_type == 4)
            ? 'ca'
            : 'company';

        $fileName = null;
		$filePath = null;

		if ($request->hasFile('file')) {
			$file = $request->file('file');
			// Create folder if not exists
			$destinationPath = public_path('uploads/ca-comp-chat-files');
			if (!file_exists($destinationPath)) {
				mkdir($destinationPath, 0777, true);
			}
			// File name
			$fileName = time() . '_' . $file->getClientOriginalName();

			// Move file
			$file->move($destinationPath, $fileName);

			// Store relative path in DB
			$filePath = 'uploads/ca-comp-chat-files/' . $fileName;
		}

        $message = ChatCaMessage::create([

            'conversation_id' => $request->conversation_id,

            'sender_id' => $user->id,

            'sender_type' => $senderType,

            'message' => $request->message,

            'file_name' => $fileName,

            'file_path' => $filePath,

            'is_read' => 0
        ]);

        $conversation = ChatCaConversation::find($request->conversation_id);

        $conversation->last_message = $request->message;

        $conversation->last_message_at = now();

        if ($senderType == 'ca') {

            $conversation->company_unread_count += 1;

        } else {

            $conversation->ca_unread_count += 1;
        }

        $conversation->save();

        return response()->json([
            'status' => true,
            'message' => 'Message sent'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET MESSAGES
    |--------------------------------------------------------------------------
    */

    public function getMessages($conversationId)
    {

        $messages = ChatCaMessage::where(
                'conversation_id',
                $conversationId
            )
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MARK AS READ
    |--------------------------------------------------------------------------
    */

    public function markAsRead($conversationId)
    {

        $user = Auth::user();

        $senderType = ($user->u_type == 1 || $user->u_type == 4)
            ? 'ca'
            : 'company';

        ChatCaMessage::where('conversation_id', $conversationId)
            ->where('sender_type', '!=', $senderType)
            ->update([
                'is_read' => 1
            ]);

        $conversation = ChatCaConversation::find($conversationId);

        if ($senderType == 'ca') {

            $conversation->ca_unread_count = 0;

        } else {

            $conversation->company_unread_count = 0;
        }

        $conversation->save();

        return response()->json([
            'status' => true
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION COUNT
    |--------------------------------------------------------------------------
    */

    public function getUnreadCount()
    {

        $user = Auth::user();

        if ($user->u_type == 1 || $user->u_type == 4) {

            $count = ChatCaConversation::where(
                'ca_id',
                $user->id
            )->sum('ca_unread_count');

        } else {

            $count = ChatCaConversation::where(
                'company_id',
                $user->id
            )->sum('company_unread_count');
        }

        return response()->json([
            'count' => $count
        ]);
    }

}
