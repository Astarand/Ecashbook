<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and create token
     *
     * @param Request $request
     * @return JsonResponse
     */
    // public function login(Request $request): JsonResponse
    // {
    //     try {
    //         // Validate user credentials
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //             'password' => 'required|string|min:6',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Validation error',
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $credentials = $request->only('email', 'password');
    //         $user = User::where('email', $credentials['email'])->first();

    //         if (!$user) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid credentials'
    //             ], 401);
    //         }

    //         if (!Auth::attempt($credentials)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid credentials'
    //             ], 401);
    //         }

    //         // Check if the user already has an active session
    //         $existingSession = \DB::table('user_sessions')->where('user_id', $user->id)->first();

    //         if ($existingSession) {
    //             // Revoke the previous session
    //             \DB::table('user_sessions')->where('user_id', $user->id)->delete();

    //             // Revoke all the previous tokens
    //             $user->tokens->each(function ($token) {
    //                 $token->delete();
    //             });
    //         }

    //         $user = Auth::user();
    //         $token = $user->createToken('auth_token')->plainTextToken;

    //         // Store the new token in the user_sessions table
    //         \DB::table('user_sessions')->insert([
    //             'user_id' => $user->id,
    //             'token' => $token,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         $employee = Employees::where('empId', $user->id)->first();

    //         if (!$employee) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Employee not found for the logged-in user'
    //             ], 404);
    //         }

    //         $employeeId = $employee->employee_id;
    //         $secureKey = hash('sha256', $employeeId . config('app.key'));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Login successful',
    //             'data' => [
    //                 'user' => [
    //                     'id' => $user->id,
    //                     'added_by' => $employee->added_by,
    //                     'employeeId' => $employeeId,
    //                     'name' => $user->name,
    //                     'email' => $user->email,
    //                     'email_verified_at' => ($user->status == 1) ? true : false,
    //                     'created_at' => $user->created_at,
    //                     'updated_at' => $user->updated_at,
    //                 ],
    //                 'token' => $token,
    //                 'token_type' => 'Bearer',
    //                 'secure' => $secureKey, // Add the secure key to the response
    //             ]
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Login failed',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function login(Request $request): JsonResponse
{
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'device_token' => 'nullable|string',
            'device_type'  => 'nullable|string', // e.g. android, ios, web
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Clear old sessions/tokens
        \DB::table('user_sessions')->where('user_id', $user->id)->delete();
        $user->tokens()->delete();

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Store new session
        \DB::table('user_sessions')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ Save device info in user_devices (history)
        if ($request->filled('device_token')) {
            \DB::table('user_devices')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                ],
                [
                    'device_type' => $request->device_type ?? null,
                    'updated_at'  => now(),
                ]
            );

            // ✅ Also update users table (latest device)
            $user->device_token = $request->device_token;
            $user->device_type  = $request->device_type ?? null;
            $user->save();
        }

        $employee = Employees::where('empId', $user->id)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found for the logged-in user'
            ], 404);
        }

        $employeeId = $employee->employee_id;
        $secureKey = hash('sha256', $employeeId . config('app.key'));

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'added_by' => $employee->added_by,
                    'employeeId' => $employeeId,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => ($user->status == 1) ? true : false,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'secure' => $secureKey,
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Login failed',
            'error' => $e->getMessage()
        ], 500);
    }
}




    /**
     * Logout user (Revoke token)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {   
        
        try {
            $request->user()->currentAccessToken()->delete();

            // Delete the user's session from the database
            \DB::table('user_sessions')->where('user_id', $request->user()->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
