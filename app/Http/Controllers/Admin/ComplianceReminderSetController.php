<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Compliance_forms;
use Validator;
use Redirect;
use DB;
use Auth;
use Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;

class ComplianceReminderSetController extends Controller
{
   
    public function ComplianceReminderSetList()
    {
         $userId = Auth::user()->id;
         //$complianceReminderSetList = Compliance_forms::all();
         $complianceReminderSetList = DB::table('compliance_forms as cf')
									->join('compliances as c', 'c.id', '=', 'cf.compliance_id')
									->select(
										'cf.*',
										'c.name as compliance'
									)
									->get();
		//echo "<pre>";print_r($complianceReminderSetList);exit;
        return view('Admin.compliance-reminder-set-list', compact('complianceReminderSetList'));
    }
    public function AddComplianceReminderSet()
    {
		$compliances = DB::table('compliances')->where('status', 'active')->get();
        return view('Admin.compliance-reminder-set-add', compact('compliances'));
    }
    public function save_compliance_reminder(Request $request)
    {
        // Validate and save the compliance reminder data
        $request->validate([
            'compliance_id' => 'required',
            'form_name' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'due_year_type' => 'required|string|max:255',
            'reminder_year_type' => 'required|string|max:255',
            'reminderStatus' => 'required|in:1,0',
        ]);

        // Save the compliance reminder data
        $complianceReminder = new Compliance_forms();
        $complianceReminder->compliance_id = $request->compliance_id;
        $complianceReminder->form_name = $request->form_name;
        $complianceReminder->frequency = $request->frequency;
        $complianceReminder->due_day = $request->due_day;
        $complianceReminder->due_month = $request->due_month;
        $complianceReminder->due_year_type = $request->due_year_type;
        
		$complianceReminder->reminder_day = $request->reminder_day;
        $complianceReminder->reminder_month = $request->reminder_month;
        $complianceReminder->reminder_year_type = $request->reminder_year_type;
        $complianceReminder->reminderStatus = $request->reminderStatus;
        $complianceReminder->created_at = now();
        $complianceReminder->updated_at = now();
        $complianceReminder->save();

        return redirect()->route('admin.compliance-reminder-list')->with('success', 'Compliance Reminder saved successfully.');
    }

    public function ComplianceReminderSetDetails($id)
    {
		$compliances = DB::table('compliances')->where('status', 'active')->get();
        $complianceReminder = Compliance_forms::findOrFail($id);
        return view('Admin.compliance-reminder-set-details', compact('complianceReminder','compliances'));
    }

    private function validateRcs($request)
    {
        return Validator::make($request->all(), [
			'compliance_id' => 'required',
            'form_name' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'due_year_type' => 'required|string|max:255',
            'reminder_year_type' => 'required|string|max:255',
            'reminderStatus' => 'required|in:1,0',
        ]);
    }

    public function update_compliance_reminder(Request $request, $id)
    {
        //print_r($request->all());exit;
       $validation = $this->validateRcs($request);
        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }
        
        // Update the compliance reminder data
        $complianceReminder = Compliance_forms::findOrFail($id);
		$complianceReminder->compliance_id = $request->compliance_id;
        $complianceReminder->form_name = $request->form_name;
        $complianceReminder->frequency = $request->frequency;
        $complianceReminder->due_day = $request->due_day;
        $complianceReminder->due_month = $request->due_month;
        $complianceReminder->due_year_type = $request->due_year_type;
        
		$complianceReminder->reminder_day = $request->reminder_day;
        $complianceReminder->reminder_month = $request->reminder_month;
        $complianceReminder->reminder_year_type = $request->reminder_year_type;
        $complianceReminder->reminderStatus = $request->reminderStatus;
        $complianceReminder->save();
        return response()->json([
                'status' => 'success',
                'class' => 'succ',
                'redirect' => url('/compliance-reminder-list'),
                'message' => 'Compliance Reminder updated successfully'
            ]);

    }

    public function delete_compliance_reminder(Request $request)
    {
       $del = DB::table('compliance_forms')->where('id', $request->id)->delete();
		if($del){
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				//'redirect' => url('/tds-tax-slab-list'),
				'message' => 'Record deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				//'redirect' => url('/tds-tax-slab-list'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }
}

