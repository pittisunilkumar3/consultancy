<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (getOption('email_verification_status', 0) == 1 && $user && !$user->hasVerifiedEmail() && $request->route()->getName() !== 'verification.notice') {
            // Check if email system is enabled
            if (getOption('app_mail_status', 0) == 1) {

                // Send custom verification email
                $user->sendEmailVerificationNotification();

                return redirect()->route('verification.notice')
                    ->with('error', __('Please verify your email address to access this feature.'));
            } else {
                // Redirect with message if email system is disabled
                return redirect()->back()
                    ->with('error', __('Please contact with admin to enable the feature!'));
            }
        }

        return $next($request);
    }
}
