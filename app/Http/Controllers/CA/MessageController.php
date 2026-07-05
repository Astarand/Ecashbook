<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;;

use Auth;
use Validator;
use App\Models\User;
use App\Models\Chat_messages;


use DB;
use Helper;
use Image;

class MessageController extends Controller
{
	public function __construct()
	{
		//$this->middleware('auth');
		if (!Auth::user()) {
			return redirect('/');
		}
	}

	public function index(Request $request) {}
	protected function validator(array $data)
    {
		return Validator::make(
			$data,
			[
           'attachment_file' => 'mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg|max:2048',
        ]
		);
    }

	public function upload_file(Request $request)
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

	public function insert_chat(Request $request)
    {
		$user_id = currentOwnerId();
        $to_user_id = $request->input('to_user_id');
        $chat_message = $request->input('chat_message');
        $message_file = $request->input('message_file');
        $c_qid = $request->input('c_qid');
		if ($message_file == 'undefined') {
			$message_file = '';
		}

		$str = '';
		$insert = DB::table('chat_messages')->insertGetId(
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
			$result = DB::table('chat_messages')
				->where('chat_message_id', '=', $insert)
				->where('from_user_id', '=', $user_id)
				->where('to_user_id', '=', $to_user_id)
					->get()->toArray();

			if ($result[0]->attached != "") {
				$ext = pathinfo($result[0]->attached, PATHINFO_EXTENSION);
				$filepath = asset('public/uploads/chat/' . $result[0]->attached);

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
												<img src="' . $filepath . '" alt="Image" class="img-fluid">
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

	public function fetch_user_chat_history($from_user_id, $to_user_id)
	{
		$result = DB::table('chat_messages')
					->select(DB::raw('chat_messages.*'))
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
					$filepath = asset('public/uploads/chat/' . $row->attached);

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
													<img src="' . $filepath . '" alt="Image" class="img-fluid">
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
					$filepath = asset('public/uploads/chat/' . $row->attached);

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
															<img src="' . $filepath . '" alt="Image" class="img-fluid">
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

	public function refreshMessages(Request $request)
	{
		$user_id = currentOwnerId();
		$c_qid = $request->input('c_qid');

		// Get all messages for the conversation
		$messages = DB::table('chat_messages')
			->where('c_qid', $c_qid)
			->orderBy('timestamp', 'asc')
			->get();

		$html = '';

		foreach ($messages as $message) {
			// For current user's messages
			if ($message->from_user_id == $user_id) {
				if ($message->attached != "") {
					$ext = pathinfo($message->attached, PATHINFO_EXTENSION);
					$filepath = asset('/uploads/chat/' . $message->attached);

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
													<img src="' . $filepath . '" alt="Image" class="img-fluid">
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
					$filepath = asset('/uploads/chat/' . $message->attached);

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
															<img src="' . $filepath . '" alt="Image" class="img-fluid">
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
}
