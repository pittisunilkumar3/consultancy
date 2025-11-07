<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            if (auth()->user()->role == USER_ROLE_ADMIN || auth()->user()->role == USER_ROLE_STAFF) {
                if (auth()->user()->status == USER_STATUS_ACTIVE) {
                    return $next($request);
                } elseif (auth()->user()->status == STATUS_SUSPENDED) {
                    auth()->logout();
                    return redirect()->route('login')->with('error', __('Your account is suspended! Please contact with admin'));
                } else {
                    auth()->logout();
                    return redirect()->route('login')->with('error', __('Your account is deactivate! Please contact with admin'));
                }
            } else {
                abort('403');
            }
        } else {
            return redirect()->route('ZaiInstaller::pre-install');
        }
    }
}
