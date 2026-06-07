<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeHolidayController;
use App\Http\Controllers\Api\EmployeeAttendanceController;
use App\Http\Controllers\Api\EmployeeClaimsController;
use App\Http\Controllers\Api\EmployeeHrController;
use App\Http\Controllers\Api\TokenController;



// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']); // Direct route

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

    });

    // User management routes
    Route::prefix('users')->group(function () {

        Route::get('/', [UserController::class, 'index']);
        Route::get('/search', [UserController::class, 'search']);
        Route::get('/{id}', [UserController::class, 'show']);

        Route::post('/employee/details', [EmployeeController::class, 'getEmployeeDetails']);
        Route::post('/employee/punch-in', [EmployeeController::class, 'punchIn']);
        Route::post('/employee/punch-out', [EmployeeController::class, 'punchOut']);
        Route::post('/employee/lunch-in', [EmployeeController::class, 'lunchIn']);
        Route::post('/employee/lunch-out', [EmployeeController::class, 'lunchOut']);
        Route::post('/employee/break-in', [EmployeeController::class, 'breakIn']);
        Route::post('/employee/break-out', [EmployeeController::class, 'breakOut']);
        Route::post('/employee/todays-attendance', [EmployeeController::class, 'getTodaysAttendance']);



        Route::post('/company/holidays', [EmployeeController::class, 'getCompanyHolidays']);

    });


    Route::prefix('users')->group(function () {
        // Employee holiday routes
        Route::post('/employee/apply-leave', [EmployeeHolidayController::class, 'applyLeave']);
        Route::post('/employee/list-of-leave', [EmployeeHolidayController::class, 'listOfLeave']);
        Route::post('/employee/leave-details', [EmployeeHolidayController::class, 'leaveDetails']);


        //---- Today Task Route ----//
        Route::post('/task/today-task', [EmployeeHolidayController::class, 'todayTask']);
        Route::post('/task/task-status-update', [EmployeeHolidayController::class, 'taskStatusUpdate']);
        Route::post('/task/task-list', [EmployeeHolidayController::class, 'taskList']);
        Route::post('/task/task-details', [EmployeeHolidayController::class, 'taskDetails']);

        //---- Attendence Routes ----//
        Route::post('/attendance/range-summary', [EmployeeAttendanceController::class, 'attendanceRangeSummary']);
        Route::post('/attendance/daily-activity', [EmployeeAttendanceController::class, 'getDailyActivity']);

        //---- Expenditure Claims Routes ----//
        Route::post('/expenditure-claims/submit-claim', [EmployeeClaimsController::class, 'submitExpenditureClaim']);
        Route::post('/expenditure-claims/claim-list', [EmployeeClaimsController::class, 'expenditureClaimList']);
        Route::post('/expenditure-claims/claim-details', [EmployeeClaimsController::class, 'expenditureClaimDetails']);

        Route::post('/expenditure-claims/submit-Supply', [EmployeeClaimsController::class, 'submitSupplyRequisition']);
        Route::post('/expenditure-claims/supply-list', [EmployeeClaimsController::class, 'supplyRequisitionList']);
        Route::post('/expenditure-claims/supply-details', [EmployeeClaimsController::class, 'supplyRequisitionDetails']);

        ///----------- HR Letter Routes -----------///
        Route::post('/hr-letters/letter-list', [EmployeeHrController::class, 'hrLetterList']);
        Route::post('/hr-letters/letter-details', [EmployeeHrController::class, 'hrLetterDetails']);
        Route::post('/review/employee_review_list', [EmployeeHrController::class, 'employeeReviewList']);

        //------- Payslip Routes ---------//
        Route::post('/payslips/payslip-details', [EmployeeHrController::class, 'payslipDetails']);

        //--------- policy routes and terms & condination ---------//
        Route::post('/policies/policy-list', [EmployeeHrController::class, 'employeePolicyList']);
        Route::post('/policies/update-policy-read-status', [EmployeeHrController::class, 'updatePolicyReadStatus']);

    });

});

// Legacy route for backward compatibility
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Added for GST token
Route::get('/update-refresh-token', [TokenController::class, 'updateRefreshToken']);
Route::get('/send-compliance-reminder', [TokenController::class, 'sendComplianceReminderNotifications']);