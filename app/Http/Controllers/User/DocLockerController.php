<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\DocumentLockers;
use App\Models\UserDocuments;
use App\Models\DocumentAccesses;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocLockerController extends Controller
{
    public function DocLockerSetup()
    {
        return view('User.doc-locker-setup');
    }
	
	public function DocLocker()
	{
		
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		checkCoreAccess('Secure Doc Locker');
		//$userId = (in_array($userType, [1, 2, 3])) ? Auth::user()->id : Auth::user()->user_add_by;
		$userId = currentOwnerId();

		$locker = DocumentLockers::where('user_id', $userId)->first();
		$lockerSecured = $locker && !empty($locker->passcode_hash);

		/*
		|--------------------------------------------------------------------------
		| USER / USER EMPLOYEE (OWNER)
		|--------------------------------------------------------------------------
		*/
		// if (in_array($userType, [2,5])) {

		// 	$documents = UserDocuments::where('user_documents.user_id', $userId)
		// 		->leftJoin('document_accesses', function ($join) use ($userId) {
		// 			$join->on('user_documents.document_type', '=', 'document_accesses.documentType')
		// 				->where('document_accesses.granted_by', $userId);
		// 		})
		// 		->leftJoin('users as ca', 'ca.id', '=', 'document_accesses.granted_to')
		// 		->select([
		// 			'user_documents.*',
		// 			'document_accesses.doc_permission',
		// 			'document_accesses.created_at as granted_at',
		// 			DB::raw('ca.name as granted_to_name')
		// 		])
		// 		->orderBy('user_documents.id','desc')
		// 		->paginate(10);
		// }

		if (in_array($userType, [2,5])) {

			$documents = UserDocuments::where('user_documents.user_id', $userId)
				->leftJoin('document_accesses', function ($join) use ($userId) {
					$join->on('user_documents.document_type', '=', 'document_accesses.documentType')
						->where('document_accesses.granted_by', $userId);
				})
				->leftJoin('users as ca', 'ca.id', '=', 'document_accesses.granted_to')

				// NEW JOIN
				->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'user_documents.proprietorship_id')

				->select([
					'user_documents.*',
					'document_accesses.doc_permission',
					'document_accesses.created_at as granted_at',
					DB::raw('ca.name as granted_to_name'),

					// proprietorship name
					DB::raw('pp.comp_name as comp_name')
				])

				->orderBy('user_documents.id','desc')
				// ->paginate(10);
				->get();
		}

		/*
		|--------------------------------------------------------------------------
		| CA / CA EMPLOYEE  → DOCUMENT TYPE BASED ACCESS
		|--------------------------------------------------------------------------
		*/
		elseif (in_array($userType, [1,4])) {

			$documents = UserDocuments::join('ca_assigns', function ($join) use ($userId) {
					$join->on('ca_assigns.comp_id','=','user_documents.user_id')
						 ->where('ca_assigns.ca_id',$userId)
						 ->where('ca_assigns.ca_assign_status',1)
						 ->where('ca_assigns.ca_current_status',1);
				})
				->join('document_accesses', function ($join) use ($userId) {
					$join->on('user_documents.document_type','=','document_accesses.documentType')
						 ->where('document_accesses.granted_to',$userId);
				})
				->join('users as owner','owner.id','=','user_documents.user_id')
				->select([
					'user_documents.*',
					'document_accesses.doc_permission',
					'document_accesses.created_at as granted_at',
					'owner.name as granted_to_name'
				])
				->orderBy('user_documents.id','desc')
				->paginate(10);
		}

		/*
		|--------------------------------------------------------------------------
		| ADMIN / ADMIN EMPLOYEE
		|--------------------------------------------------------------------------
		*/
		else {

			$documents = UserDocuments::leftJoin('users as owner','owner.id','=','user_documents.user_id')
				->leftJoin('document_accesses','user_documents.document_type','=','document_accesses.documentType')
				->leftJoin('users as ca','ca.id','=','document_accesses.granted_to')
				->select([
					'user_documents.*',
					'document_accesses.doc_permission',
					'document_accesses.created_at as granted_at',
					DB::raw('COALESCE(ca.name, owner.name) as granted_to_name')
				])
				->orderBy('user_documents.id','desc')
				->paginate(10);
		}

		// echo "<pre>"; print_r($documents->toArray()); echo "</pre>"; exit;

		return view('User.doc-locker', compact('documents','lockerSecured'));
	}
	
	public function setPasscode(Request $request)
    {
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
        $request->validate([
            'passcode' => 'required|digits:6'
        ]);

        DocumentLockers::updateOrCreate(
            ['user_id' => $userId],
            ['passcode_hash' => Hash::make($request->passcode)]
        );

        return response()->json(['status' => true]);
    }

    public function verifyPasscode(Request $request)
    {
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
        $request->validate([
            'passcode' => 'required|digits:6'
        ]);

        $locker = DocumentLockers::where('user_id', $userId)->first();

        if (!$locker || !Hash::check($request->passcode, $locker->passcode_hash)) {
            return response()->json(['status' => false], 401);
        }

        session(['locker_verified' => true]);

        return response()->json(['status' => true]);
    }
	
	//documents section
	
	public function upload(Request $request)
	{
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
		$request->validate([
			'document_type' => 'required',
			'file_type' => 'required',
			//'document_name' => 'required|string|max:255',
			'document_file' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
		]);

		$file = $request->file('document_file');

		// Original filename (safe)
		$originalName = $file->getClientOriginalName();

		// User specific folder
		$folderPath = public_path("uploads/document-locker/{$userId}");

		// Create folder if not exists
		if (!File::exists($folderPath)) {
			File::makeDirectory($folderPath, 0755, true);
		}

		// Move file
		$file->move($folderPath, $originalName);

		// DB path
		$path = "uploads/document-locker/{$userId}/{$originalName}";

		UserDocuments::create([
			'user_id'       => $userId,
			'document_type' => $request->document_type,
			'file_type' 	=> $request->file_type,
			'document_name' => $request->document_name,
			'file_name'     => $originalName,
			'file_path'     => $path,
		]);

		return response()->json([
			'status'  => true,
			'message' => 'Document uploaded successfully'
		]);
	}

	// Common functon to upload file 
	public static function saveToLocker($userId, $file, $documentType, $fileType, $documentName, $proprietorship_id = null)
	{
		$originalName = time() . '_' . $file->getClientOriginalName();

		// Base folder
		$folderPath = public_path("uploads/document-locker/{$userId}");

		// Add proprietorship folder if exists
		if ($proprietorship_id) {
			$folderPath .= "/{$proprietorship_id}";
		}

		// Create directory if not exists
		if (!File::exists($folderPath)) {
			File::makeDirectory($folderPath, 0755, true);
		}

		// Move file
		$file->move($folderPath, $originalName);

		// File path for database
		$path = "uploads/document-locker/{$userId}";
		if ($proprietorship_id) {
			$path .= "/{$proprietorship_id}";
		}
		$path .= "/{$originalName}";

		// Save record
		UserDocuments::create([
			'user_id'           => $userId,
			'proprietorship_id' => $proprietorship_id,
			'document_type'     => $documentType,
			'file_type'         => $fileType,
			'document_name'     => $documentName,
			'file_name'         => $originalName,
			'file_path'         => $path,
		]);
	}
	
	private function checkPasscode(UserDocuments $doc)
	{		
		$user = Auth::user();
		$userType = $user->u_type;

		// ADMIN & ADMIN EMPLOYEE → Always allow
		if (in_array($userType, [3, 6])) {
			return true;
		}
		// CA & CA EMPLOYEE → allow ONLY if document_type permission exists
		if (in_array($userType, [1, 4])) {

			$hasPermission = DocumentAccesses::where('granted_to', $user->id)
				->where('documentType', $doc->document_type)
				->exists();

			if (!$hasPermission) {
				abort(403, 'You do not have permission to access this document type');
			}

			return true;
		}

		// USER / USER EMPLOYEE → Must pass passcode
		if (!session('locker_verified')) {
			abort(403, 'Passcode verification required');
		}

		return true;
	}

    public function view($id)
	{
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();

		$doc = UserDocuments::findOrFail($id);
		$this->checkPasscode($doc);
		
		$fullPath = public_path($doc->file_path);

		if (!File::exists($fullPath)) {
			abort(404, 'File not found');
		}
		
		$ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

		$mimeTypes = [
			'pdf'  => 'application/pdf',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png'  => 'image/png',
			'doc'  => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		];

		return response()->file($fullPath, [
			'Content-Type'        => $mimeTypes[$ext] ?? 'application/octet-stream',
			'Content-Disposition' => 'inline; filename="' . basename($doc->file_name) . '"',
			'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
			'Pragma'              => 'no-cache',
			'Expires'             => '0',
		]);
	}

    public function download($id)
    {
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
		
		$doc = UserDocuments::findOrFail($id);
		$this->checkPasscode($doc);
		$fullPath = public_path($doc->file_path);

		if (!File::exists($fullPath)) {
			abort(404, 'File not found');
		}

		return response()->download(
			$fullPath,
			$doc->file_name
		);
    }
	
	public function ca_list()
    {
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
		
        return User::leftJoin('ca_assigns', function ($join) use ($userId) {
            $join->on('users.id', '=', 'ca_assigns.ca_id')
                 ->where('ca_assigns.comp_id', $userId);
        })
        ->where('users.u_type', 1) // CA
		->where('ca_assigns.ca_assign_status','=', 1)
        ->where('ca_assigns.ca_current_status','=', 1)
        ->select(
            'users.id',
            'users.name',
            'users.email'
        )
        ->get();
    }
	
	public function giveAccess(Request $request)
	{
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
		
		$request->validate([
			//'document_id'   => 'required|exists:user_documents,id',
			'ca_id'         => 'required|exists:users,id',
			'documentType' => 'required',
			'doc_permission'=> 'required|string'
		]);

		DocumentAccesses::updateOrCreate(
			[
				//'document_id' => $request->document_id,
				'granted_to' => $request->ca_id,
				'documentType' => $request->documentType,
			],
			[
				'granted_by' => $userId,
				'doc_permission' => $request->doc_permission,
			]
		);

		return response()->json([
			'success' => true,
			'message' => 'Permission updated successfully'
		]);
	}


    public function destroy($id)
    {
		$authUser = auth()->user();
		$userType = $authUser->u_type;
		$userId = currentOwnerId();
		
		// Passcode must be verified
		if (!session('locker_verified')) {
			abort(403, 'Passcode verification required');
		}
		// Fetch only owner's document
		$doc = UserDocuments::where('id', $id)
					->where('user_id', $userId)
					->firstOrFail();

		$fullPath = public_path($doc->file_path);

		// Delete physical file
		if (File::exists($fullPath)) {
			File::delete($fullPath);
		}

		// Delete document record
		$doc->delete();

		//DO NOT delete document_accesses (permissions stay)
		return response()->json([
			'status' => true,
			'message' => 'Document deleted successfully'
		]);
    }
}
