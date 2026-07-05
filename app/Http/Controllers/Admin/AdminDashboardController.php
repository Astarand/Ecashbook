<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class AdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics based on month and financial year
     */
    public function getDashboardStats(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));
        $month = $request->input('month', date('F'));

        \Log::info('Dashboard Stats Request', ['year' => $year, 'month' => $month]);

        // Convert month name to number
        $monthNum = \DateTime::createFromFormat('F', $month)->format('m');
        $monthIndex = (int)$monthNum;

        // Get the first and last day of the selected month and year
        $startDate = Carbon::createFromDate($year, $monthIndex, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $monthIndex, 1)->endOfMonth();

        \Log::info('Date Range', ['start' => $startDate->toDateTimeString(), 'end' => $endDate->toDateTimeString()]);

        // Daily registration data for the month (for chart)
        $dailyRegistrations = DB::table('users')
            ->selectRaw('DATE(created_at) as date, DAY(created_at) as day, u_type')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('u_type', [1, 2])
            ->orderBy('day')
            ->get();

        \Log::info('Daily Registrations Count', ['count' => $dailyRegistrations->count(), 'data' => $dailyRegistrations]);

        // Process daily data for chart
        $daysInMonth = $startDate->daysInMonth;
        $dailyCustomers = array_fill(0, $daysInMonth, 0);
        $dailyCAs = array_fill(0, $daysInMonth, 0);

        foreach ($dailyRegistrations as $reg) {
            $dayIndex = $reg->day - 1;
            if ($reg->u_type == 2) {
                $dailyCustomers[$dayIndex]++;
            } elseif ($reg->u_type == 1) {
                $dailyCAs[$dayIndex]++;
            }
        }

        // Total and new users for the month
        $totalCustomers = DB::table('users')
            ->where('u_type', 2)
            ->count();

        $totalCAs = DB::table('users')
            ->where('u_type', 1)
            ->count();

        $newCustomersThisMonth = DB::table('users')
            ->where('u_type', 2)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $newCAsThisMonth = DB::table('users')
            ->where('u_type', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Verification status (status = 1 is verified)
        $pendingVerification = DB::table('users')
            ->whereIn('u_type', [1, 2])
            ->where('status', '!=', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $verifiedUsers = DB::table('users')
            ->whereIn('u_type', [1, 2])
            ->where('status', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        \Log::info('Dashboard Stats Final', [
            'dailyCustomersLength' => count($dailyCustomers),
            'dailyCAsLength' => count($dailyCAs),
            'totalCustomers' => $totalCustomers,
            'totalCAs' => $totalCAs,
            'newCustomers' => $newCustomersThisMonth,
            'newCAs' => $newCAsThisMonth
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'dailyCustomers' => array_values($dailyCustomers),
                'dailyCAs' => array_values($dailyCAs),
                'totalCustomers' => $totalCustomers,
                'totalCAs' => $totalCAs,
                'newCustomers' => $newCustomersThisMonth,
                'newCAs' => $newCAsThisMonth,
                'pendingVerification' => $pendingVerification,
                'verifiedUsers' => $verifiedUsers,
            ]
        ]);
    }
}
