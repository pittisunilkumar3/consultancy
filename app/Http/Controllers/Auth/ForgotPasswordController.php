<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotify;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Str;

class ForgotPasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        try {
            if(getOption('app_mail_status')){
                $this->sendForgotMail($request->email);
            }else{
                return back()->with('success', __('Please contact with admin to enable the feature!'));
            }
            return back()->with('success', __('We have mailed your password reset link!'));
        } catch (Exception $e) {
            return back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }

    function sendForgotMail($email)
    {
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);

        $user = User::where('email', $email)->first();

        //send customer mail
        $viewData = [
            '{{name}}' => $user->name,
            '{{email}}' => $user->email,
            '{{link}}' => url(route('password.reset', ['token' => $token, 'email' => $user->email], false))
        ];
        $data = getEmailTemplate('password-reset', $viewData);
        Mail::to($user->email)->send(new EmailNotify($data));
        return true;
    }


    public function forgetVerifyForm($token, $email)
    {
        $resetPassword = DB::table('password_resets')->where('token', $token)->where('email', $email)->first();
        return view('auth.passwords.reset', compact('token', 'resetPassword'));
    }

    public function forgetVerify(Request $request, $token)
    {
        $user = DB::table('password_resets')
            ->join('users', 'users.email', '=', 'password_resets.email')
            ->where('token', $token)
            ->select('users.email', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"))
            ->first();
        if (!is_null($user)) {
            return view('auth.passwords.reset')->with(['token' => $token, 'email' => $user->email]);
        } else {
            return back()->with('error', __('Email Not Found'));
        }
    }

    public function updatePassword(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        try {
            $user = DB::table('password_resets')
                ->join('users', 'users.email', '=', 'password_resets.email')
                ->where('token', $token)
                ->select('users.email', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"))
                ->first();

            if (!is_null($user) && $user->email == $request->email) {
                User::where('email', $user->email)
                    ->update([
                        'password' => bcrypt($request->password),
                    ]);

                return redirect()->route('login')->with('success', __('Reset Successfully. Please login with new password'));
            } else {
                throw new Exception(__('Email doesn\'t match'));
            }
        } catch (Exception $e) {
            // Pass only the exception message instead of the entire exception object
            return back()->with('error', $e->getMessage());
        }
    }
}
