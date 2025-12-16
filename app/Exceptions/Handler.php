<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Handle CSRF token mismatch (419 error) for mobile users
        if ($exception instanceof TokenMismatchException) {
            // Check if it's a mobile request
            $userAgent = $request->header('User-Agent');
            $isMobile = preg_match('/(Mobile|Android|iPhone|iPad|iPod|BlackBerry|Windows Phone)/i', $userAgent);
            
            if ($isMobile || $request->ajax()) {
                // For mobile or AJAX requests, redirect back with a friendly message
                return redirect()->back()
                    ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                    ->with('error', 'Your session has expired. Please try again.');
            }
        }

        if ($exception instanceof PostTooLargeException) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'status' => 413,
                    'message' => 'Uploaded file is too large. Please upload a smaller file.'
                ], 413);
            }
        }

        return parent::render($request, $exception);
    }
}
