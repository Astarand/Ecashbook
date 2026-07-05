<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;


class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Customize the response when authentication fails.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Check if the request expects a JSON response (API request)
        if ($request->expectsJson()) {
            // Return custom response for token mismatch
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Invalid or expired token.',
                'error_code' => 'TOKEN_MISMATCH'
            ], 401);
        }

        return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Invalid or expired token.',
                'error_code' => 'TOKEN_MISMATCH'
            ], 401);
        // Redirect to login for web requests (if not an API call)
        return redirect()->guest(route('login'));
    }
}
