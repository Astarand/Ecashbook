<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Redirect;
use DB;
use Auth;
use App\Models\User;
use App\Models\Projects;
use Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\AuditLogger;

class ProjectManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ProjectList()
    {
		//$this->middleware('auth'); 
		//return view('pages.project')->with([
				
		//]); 
		$title = 'Projects';
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		if(Auth::user()->u_type ==1){ //ca
			$projects =  DB::table('projects')
							->select(DB::raw('projects.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'projects.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'projects.added_by', '=', 'ca_assigns.comp_id')
							->where('ca_assigns.ca_id','=',$userId)
							->where('ca_assigns.ca_assign_status','=',1)
							->orderBy('id', 'DESC')->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca employee
			$projects =  DB::table('projects')
							->select(DB::raw('projects.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'projects.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'projects.added_by', '=', 'ca_assigns.comp_id')
							->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
							->where('ca_assigns.ca_assign_status','=',1)
							->orderBy('id', 'DESC')->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$projects = DB::table('projects')
						->select(DB::raw('projects.*, company_profiles.comp_name'))
						->leftJoin('company_profiles', 'projects.added_by', '=', 'company_profiles.userId')
						->where('added_by', '=', $userId)
						->orderBy('id', 'DESC')
						->get();
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$projects =  DB::table('projects')
							->select(DB::raw('projects.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'projects.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$projects_pagination = $projects;
		//echo "<pre>"; print_r($projects);exit;
		return view('User.project-list')->with([
			'title' =>$title,
			'projects'=>$projects,
			'projects_pagination' =>$projects_pagination,
		]); 
    }
    public function addproject()
    {
		//$this->middleware('auth'); 
		// return view('pages.addproject')->with([
		return view('User.add-project')->with([
				
		]); 
    }
	
	protected function validator(array $data)
	{
		return Validator::make($data, [

			// Project Info
			'proj_name'        => 'required|string|max:255',
			'proj_cat'         => 'required|string',
			'project_sub_cat'  => 'required_if:proj_cat,!=,Other',
			'other_sub_cat'    => 'required_if:proj_cat,Other',

			// Status
			'proj_status'      => 'required|in:Pending,Ongoing,Done',

			// Client Info
			'client_name'      => 'required|string|max:255',
			'client_email'     => 'required|email|max:255',
			'client_contact'   => 'required|digits_between:7,15',

			// Dates
			'proj_start_date'  => 'required|date',
			'proj_end_date'    => 'required|date|after_or_equal:proj_start_date',

			// Cost & Details
			'proj_cost'        => 'nullable|numeric|min:0',
			'proj_details'     => 'nullable|string',

		], [

			// Project Info Messages
			'proj_name.required'        => 'Project name is required.',
			'proj_cat.required'         => 'Please select a project category.',
			'project_sub_cat.required_if' => 'Please select a sub category.',
			'other_sub_cat.required_if' => 'Please enter the other category name.',

			// Status
			'proj_status.required'      => 'Please choose the project status.',
			'proj_status.in'            => 'Invalid project status selected.',

			// Client Info
			'client_name.required'      => 'Client name is required.',
			'client_email.required'     => 'Client email is required.',
			'client_email.email'        => 'Enter a valid email address.',
			'client_contact.required'   => 'Client phone number is required.',
			'client_contact.digits_between' => 'Phone number must be between 7 and 15 digits.',

			// Dates
			'proj_start_date.required'  => 'Project start date is required.',
			'proj_start_date.date'      => 'Enter a valid start date.',
			'proj_end_date.required'    => 'Project end date is required.',
			'proj_end_date.after_or_equal' => 'End date must be after or equal to start date.',

			// Cost
			'proj_cost.numeric'         => 'Project valuation must be a number.',
			'proj_cost.min'             => 'Project valuation cannot be negative.',
		]);
	}


    protected function create(array $data)
    {
		// echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
        return Projects::create([
            'added_by' => $userId,
            'proj_name' => $data['proj_name'],
            'proj_cat' => $data['proj_cat'],
            'project_sub_cat' => $data['project_sub_cat'] ?? null,
            'other_sub_cat' => $data['other_sub_cat'] ?? null,
            'proj_status' => $data['proj_status'],
            'client_name' => $data['client_name'],
            'client_contact' => $data['client_contact'],
            'client_email' => $data['client_email'],
            'proj_start_date' => $data['proj_start_date'],
            'proj_end_date' => $data['proj_end_date'],
            'proj_cost' => $data['proj_cost'],
            'proj_details' => $data['proj_details'],                      
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_add_project(Request $request)
	{
		$userId = currentOwnerId();
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json([
				'status'  => 'validation_error',
				'errors'  => $validation->errors(),
				'message' => 'Please fix the validation errors.'
			], 422);
		}

		$project = Projects::create([
			'added_by'        => $userId,
			'proj_name'       => $request->proj_name,
			'proj_cat'        => $request->proj_cat,
			'project_sub_cat' => $request->project_sub_cat,
			'other_sub_cat'   => $request->other_sub_cat,
			'proj_status'     => $request->proj_status,
			'client_name'     => $request->client_name,
			'client_contact'  => $request->client_contact,
			'client_email'    => $request->client_email,
			'proj_start_date' => $request->proj_start_date,
			'proj_end_date'   => $request->proj_end_date,
			'proj_cost'       => $request->proj_cost,
			'proj_details'    => $request->proj_details,
		]);

		return response()->json([
			'status'   => 'success',
			'redirect' => route('user.ProjectList'),
			'message'  => 'Project added successfully'
		]);
	}


	// public function save_add_project(Request $request)
	// {
	// 	$validation = $this->validator($request->all());

	// 	if ($validation->fails()) {
	// 		return response()->json([
	// 			'status'  => 'validation_error',
	// 			'errors'  => $validation->errors(),
	// 			'message' => 'Please fix the validation errors.'
	// 		], 422); // 422 = Unprocessable Entity
	// 	}

	// 	// Create project
	// 	$insertProject = $this->create($request->all());

	// 	if ($insertProject) {
	// 		return response()->json([
	// 			'status'   => 'success',
	// 			'class'    => 'succ',
	// 			'redirect' => route('user.ProjectList'),
	// 			'message'  => 'Project added successfully'
	// 		]);
	// 	}

	// 	return response()->json([
	// 		'status'   => 'error',
	// 		'class'    => 'err',
	// 		'redirect' => url('/'),
	// 		'message'  => 'Project add failed'
	// 	]);
	// }

	
	// public function save_add_project(Request $request)  {  
            
	// 	//echo "<pre>";print_r($request->file('prod_image'));exit;
	// 	//$input = Input::all();
	// 	//dd($input);
	// 	$validation = $this->validator($request->all());
    //     if ($validation->fails())  {  
    //         return response()->json($validation->errors()->toArray());
    //     }
    //     else{
	// 		$insertProject = $this->create($request->all());
	// 		$projId = DB::getPdo()->lastInsertId();
			
	// 		if ($insertProject){
	// 			$msg = array(
	// 				'status' => 'success',
	// 				'class' => 'succ',
	// 				'redirect' => route('user.ProjectList'),
	// 				'message' => 'Project added successfully'
	// 			);
	// 			return response()->json($msg);	
	// 		}else{
	// 			$msg = array(
	// 				'status' => 'error',
	// 				'class' => 'err',
	// 				'redirect' => url('/'),
	// 				'message' => 'Project add failed'
	// 			);
	// 			return response()->json($msg);	
	// 		}
				
	// 	}	
    // }
	
	public function edit_project($projId)  {  
        
		if(Auth::user()->u_type ==1){
			return redirect('/projects');
		}
		$projId = base64_decode($projId);
		
		$project = DB::table('projects')
								->where('id', '=', $projId)
								->get();
		$project = $project[0];
		//echo "<pre>";print_r($project);exit;
		return view('User.edit-project')->with([		
			'project' => $project,
			'projId' => $projId
		]); 
    }
	
	public function view_project($projId)  {  
        
		$projId = base64_decode($projId);
		
		$project = DB::table('projects')
								->where('id', '=', $projId)
								->get();
		$project = $project[0];
		// echo "<pre>";print_r($project);exit;
		return view('User.view-project')->with([		
			'project' => $project,
			'projId' => $projId
		]); 
    }
	
	// public function update_project(Request $request)  {  
            
	// 	//echo "<pre>";print_r($request->all());exit;
	// 	$projId = $request->proj_id;
		
	// 	$validation = $this->validator($request->all());
    //     if ($validation->fails())  {  
    //         return response()->json($validation->errors()->toArray());
    //     }
    //     else{
	// 		//FETCH OLD DATA
	// 		$project = DB::table('projects')->where('id', $projId)->first();
	// 		$oldData = (array) $project;
	// 		//PREPARE NEW DATA
	// 		$newData = [
	// 			'proj_name'        => $request->proj_name,
	// 			'proj_cat'         => $request->proj_cat,
	// 			'proj_status'      => $request->proj_status,
	// 			'client_name'      => $request->client_name,
	// 			'client_contact'   => $request->client_contact,
	// 			'client_email'     => $request->client_email,
	// 			'proj_start_date'  => $request->proj_start_date,
	// 			'proj_end_date'    => $request->proj_end_date,
	// 			'proj_cost'        => $request->proj_cost,
	// 			'proj_details'     => $request->proj_details
	// 		];
	// 		//start update project
	// 		$update = DB::table('projects')
	// 				->where('id', $projId)
	// 				->update(
	// 					array(
	// 							'proj_name' => $request->proj_name,
	// 							'proj_cat' => $request->proj_cat,
	// 							'proj_status' => $request->proj_status,
	// 							'client_name' => $request->client_name,
	// 							'client_contact' => $request->client_contact,
	// 							'client_email' => $request->client_email,
	// 							'proj_start_date' => $request->proj_start_date,
	// 							'proj_end_date' => $request->proj_end_date,
	// 							'proj_cost' => $request->proj_cost,
	// 							'proj_details' => $request->proj_details
	// 					 )
	// 				);
					
	// 		//DETECT CHANGES ONLY
	// 		$changedOld = [];
	// 		$changedNew = [];

	// 		foreach ($newData as $key => $value) {
	// 			if (array_key_exists($key, $oldData) && $oldData[$key] != $value) {
	// 				$changedOld[$key] = $oldData[$key];
	// 				$changedNew[$key] = $value;
	// 			}
	// 		}
	// 		// AUDIT LOG
	// 		if (!empty($changedNew)) {
	// 			$projectName = $request->proj_name ?? $oldData['proj_name'] ?? 'Unnamed Project';
	// 			AuditLogger::logEntry(
	// 				action: 'update',
	// 				module: 'Project',
	// 				description: "Project updated: {$projectName}",
	// 				oldData: $changedOld,
	// 				newData: $changedNew
	// 			);
	// 		}
	// 		$msg = array(
	// 			'status' => 'success',
	// 			'class' => 'succ',
	// 			'redirect' => url('/project-list'),
	// 			'message' => 'Record updated successfully'
	// 		);
	// 		return response()->json($msg);
	// 		//end update item
			
	// 	}	
    // }

	public function update_project(Request $request)
	{
		$projId = $request->proj_id;

		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json([
				'status'  => 'validation_error',
				'errors'  => $validation->errors(),
				'message' => 'Please fix the validation errors.'
			], 422);
		}

		$project = DB::table('projects')->where('id', $projId)->first();
		$oldData = (array) $project;

		$newData = [
			'proj_name'        => $request->proj_name,
			'proj_cat'         => $request->proj_cat,
			'project_sub_cat'  => $request->project_sub_cat,
			'other_sub_cat'    => $request->other_sub_cat,
			'proj_status'      => $request->proj_status,
			'client_name'      => $request->client_name,
			'client_contact'   => $request->client_contact,
			'client_email'     => $request->client_email,
			'proj_start_date'  => $request->proj_start_date,
			'proj_end_date'    => $request->proj_end_date,
			'proj_cost'        => $request->proj_cost,
			'proj_details'     => $request->proj_details
		];

		DB::table('projects')->where('id', $projId)->update($newData);

		// Audit log
		$changedOld = [];
		$changedNew = [];

		foreach ($newData as $key => $value) {
			if (array_key_exists($key, $oldData) && $oldData[$key] != $value) {
				$changedOld[$key] = $oldData[$key];
				$changedNew[$key] = $value;
			}
		}

		if (!empty($changedNew)) {
			AuditLogger::logEntry(
				action: 'update',
				module: 'Project',
				description: "Project updated: " . ($request->proj_name ?? $oldData['proj_name']),
				oldData: $changedOld,
				newData: $changedNew
			);
		}

		return response()->json([
			'status'   => 'success',
			'redirect' => url('/project-list'),
			'message'  => 'Record updated successfully'
		]);
	}


	
	//Activate Project
	public function changeProjectStatus(Request $request)
    {
        $user = Projects::find($request->id);
        $user->proj_status = $request->status;
        $user->save();
        //return response()->json(['success'=>'Status change successfully.']);
		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/project-list'),
			'message' => 'Status change successfully.'
		);
		return response()->json($msg);

    }
	
	public function delProject($id)
	{
		$decodedId = base64_decode($id);
		// FETCH OLD DATA
		$project = DB::table('projects')->where('id', $decodedId)->first();
		$oldData = (array) $project;

		$deleted = DB::table('projects')->where('id', $decodedId)->delete();

		if ($deleted) {
			// AUDIT LOG ENTRY
			$projectName = $project->proj_name ?? $project->proj_name ?? 'Unnamed Project';
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Project',
				description: "Project deleted: {$projectName}",
				oldData: $oldData,
				newData: null
			);
			return response()->json([
				'status' => 'success',
				'message' => 'Project deleted successfully.',
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'message' => 'Delete action failed!',
			]);
		}
	}



	public function searchProjects(Request $request)
	{
		$query = DB::table('projects');

		if ($request->filled('proj_name')) {
			$query->where('proj_name', 'LIKE', '%' . $request->proj_name . '%');
		}

		if ($request->filled('client_name')) {
			$query->where('client_name', 'LIKE', '%' . $request->client_name . '%');
		}

		if ($request->filled('proj_cat')) {
			$query->where('proj_cat', $request->proj_cat);
		}

		if ($request->filled('project_sub_cat')) {
			$query->where('project_sub_cat', $request->project_sub_cat);
		}

		// Reset functionality
		$projects = $request->has('reset') ? DB::table('projects')->get() : $query->get();

		// Define status badge colors
		$statusColors = [
			'Done' => 'bg-success',
			'Ongoing' => 'bg-warning',
			'Pending' => 'bg-danger',
		];

		$output = '';

		foreach ($projects as $key => $project) {
			$badgeClass = $statusColors[$project->proj_status] ?? 'bg-secondary';

			$output .= '
			<tr>
				<td class="text-end">' . ($key + 1) . '</td>
				<td><a class="text-muted text-hover-primary">' . htmlspecialchars($project->client_name) . '</a></td>
				<td><a class="text-muted text-hover-primary">' . htmlspecialchars($project->proj_name) . '</a></td>
				<td><a class="text-muted text-hover-primary">' . htmlspecialchars($project->proj_cat) . '</a></td>
				<td><a class="text-muted text-hover-primary">' . htmlspecialchars($project->client_contact) . '</a></td>
				<td><a class="text-muted text-hover-primary">₹' . number_format($project->proj_cost, 2) . '</a></td>
				<td><a class="text-muted text-hover-primary">' . date('d/m/Y', strtotime($project->proj_start_date)) . '</a></td>
				<td><a class="text-muted text-hover-primary">' . date('d/m/Y', strtotime($project->proj_end_date)) . '</a></td>
				<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($project->proj_status) . '</span></td>
				<td>
					<span class="action-toggle">
						<i class="ti ti-dots-vertical f-20"></i>
					</span>

					<div class="prod-action-links">
						<ul class="list-inline me-auto mb-0">
							<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="View">
								<a href="' . url('/viewProject/' . base64_encode($project->id)) . '" 
								   class="avtar avtar-xs btn-link-success btn-pc-default">
									<i class="ti ti-eye f-18"></i>
								</a>
							</li>

							<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
								<a href="' . url('/editProject/' . base64_encode($project->id)) . '" 
								   class="avtar avtar-xs btn-link-success btn-pc-default">
									<i class="ti ti-edit-circle f-18"></i>
								</a>
							</li>

							<li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
								<a href="javascript:void(0);" 
								   data-id="' . base64_encode($project->id) . '" 
								   class="avtar avtar-xs btn-link-danger btn-pc-default delete-btn"
								   data-bs-toggle="modal" 
								   data-bs-target="#delete_modal">
									<i class="ti ti-trash f-18"></i>
								</a>
							</li>
						</ul>
					</div>
				</td>
			</tr>';
		}

		return response()->json($output);
	}


	
}
