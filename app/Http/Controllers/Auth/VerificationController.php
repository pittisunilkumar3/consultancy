<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verifyForm(Request $request){
        return view('auth.verify');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Ensure the hash matches the user's email hash for security
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('login')->with('error', __('Invalid verification link.'));
        }

        // Check if the user's email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', __('Your email is already verified.'));
        }

        // Mark the email as verified
        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('login')->with('success', __('Your email has been verified successfully.'));
    }

    /**
     * Resend the email verification notification.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = auth()->user();
        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent!');
    }
}
