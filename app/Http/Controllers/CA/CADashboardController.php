<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CADashboardController extends Controller
{
    public function CADashboard()
    {
        return view('ca.dashboard');
    }
	
	public function monthlyPayments(Request $request)
	{
		$month = $request->month;
		$year = date('Y');

		$data = DB::table('ca_payment_history')
			->whereMonth('payment_date', $month)
			->whereYear('payment_date', $year)
			->selectRaw('
				SUM(gov_fees) as total_gov,
				SUM(total_amount) as total_received,
				SUM(service_fees) as total_due
			')
			->first();

		return response()->json([
			'gov' => $data->total_gov ?? 0,
			'received' => $data->total_received ?? 0,
			'due' => $data->total_due ?? 0,
		]);
	}
	
	public function getCustomerPaymentStats(Request $request)
	{
		$month = $request->month; // e.g. "April"
		$monthNumber = date('m', strtotime($month));

		$query = DB::table('ca_payment_history')
			->whereMonth('payment_date', $monthNumber);

		$total = (clone $query)->sum('total_amount');

		$received = (clone $query)
			->where('payment_type', 'credit')
			->sum('total_amount');

		$pending = (clone $query)
			->where('payment_type', 'debit')
			->sum('total_amount');

		$overdue = (clone $query)
			->where('payment_type', 'debit')
			->whereDate('payment_date', '<', now()->subDays(30))
			->sum('total_amount');

		return response()->json([
			'total' => (float)$total,
			'received' => (float)$received,
			'pending' => (float)$pending,
			'overdue' => (float)$overdue,
		]);
	}
	
	// Controller
	public function taskStatusSummary(Request $request)
	{
		$userId = currentOwnerId();
		$month = $request->month; // e.g. April

		$monthNumber = date('m', strtotime($month));
		$year = now()->year; // current year

		$tasks = DB::table('users')
					->leftJoin('task_managements', 'users.id', '=', 'task_managements.company_id')
					->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
					->leftJoin('users as emp', 'emp.id', '=', 'task_managements.emp_id')
					->leftJoin('task_category', 'task_category.id', '=', 'task_managements.task_category')
					->select(
						'users.email as email',
						'task_managements.*',
						DB::raw('COALESCE(company_profiles.comp_name, users.name) as name'),
						'emp.name as empname',
						'task_category.task_category_name'
					)
					->where('task_managements.added_by', $userId)
					->whereMonth('task_managements.task_date', $monthNumber)
					->whereYear('task_managements.task_date', $year)
					->orderBy('task_managements.due_date', 'desc')
					->limit(5)
					->get();

				
		$totalTask = DB::table('task_managements')
					->where('added_by', $userId)
					->whereMonth('task_date', $monthNumber)
					->whereYear('task_date', $year)
					->count();


		$completedTask = DB::table('task_managements')
					->where('added_by', $userId)
					->where('project_status', 3)
					->whereMonth('task_date', $monthNumber)
					->whereYear('task_date', $year)
					->count();


		$pendingTask = DB::table('task_managements')
						->where('added_by', $userId)
						->whereIn('project_status', [1, 2])
						->whereMonth('task_date', $monthNumber)
						->whereYear('task_date', $year)
						->count();


		$overdueTask = DB::table('task_managements')
					->where('added_by', $userId)
					->where('project_status', '!=', 3)
					->whereNotNull('due_date')
					->whereDate('due_date', '<', now())
					->whereMonth('task_date', $monthNumber)
					->whereYear('task_date', $year)
					->count();

		return response()->json([
			'tasks' => $tasks,
			'total' => $totalTask,
			'completed' => $completedTask,
			'pending' => $pendingTask,
			'overdue' => $overdueTask
		]);
	}

	public function taskWiseClients()
	{
		$userId = currentOwnerId();
		$data = DB::table('task_managements as tm')
			->join('task_category as tc', 'tc.id', '=', 'tm.task_category')
			->where('tc.add_by', 'admin')
			->where('tm.added_by', $userId)
			->select('tc.task_category_name', DB::raw('COUNT(tm.id) as total'))
			->groupBy('tc.id', 'tc.task_category_name')
			->get();

		return response()->json($data);
	}
	
	public function monthwiseOnboardClients(Request $request)
	{
		$month = $request->month; // January, February...
		$year = date('Y');
		$caId = currentOwnerId();

		$monthNumber = date('m', strtotime($month));
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $year);

		$data = [];

		for ($day = 1; $day <= $daysInMonth; $day++) {

			$date = "$year-$monthNumber-" . str_pad($day, 2, '0', STR_PAD_LEFT);

			$totalAssign = DB::table('ca_assigns')
				->where('ca_id', $caId)
				->where('ca_assign_status', 1)
				->where('ca_current_status', 1)
				->whereDate('created_at', $date)
				->count();

			$requestAssign = DB::table('ca_assigns')
				->where('ca_id', $caId)
				->where('ca_assign_status', 1)
				->where('ca_current_status', 0)
				->whereDate('created_at', $date)
				->count();

			$ownAssign = 0;

			$data[] = [
				'day' => $day,
				'totalAssign' => $totalAssign,
				'requestAssign' => $requestAssign,
				'ownAssign' => $ownAssign
			];
		}

		return response()->json($data);
	}




}
