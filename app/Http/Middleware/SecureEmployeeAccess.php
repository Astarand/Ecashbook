<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecureEmployeeAccess
{
    /**
     * Handle an incoming request for secure employee data access
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log the access attempt
        Log::info('Employee data access attempt', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        // Rate limiting check (basic implementation)
        $cacheKey = 'employee_access_' . Auth::id() . '_' . $request->ip();
        $attempts = cache()->get($cacheKey, 0);
        
        if ($attempts >= 10) { // Max 10 requests per minute
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'error_code' => 'RATE_LIMIT_EXCEEDED'
            ], 429);
        }

        cache()->put($cacheKey, $attempts + 1, 60); // Cache for 1 minute

        // Additional security headers
        $response = $next($request);
        
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        return $response;
    }
}