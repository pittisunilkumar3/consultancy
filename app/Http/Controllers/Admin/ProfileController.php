<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Models\Designation;
use App\Models\FileManager;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA;

class   ProfileController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data['pageTitle'] = __('Profile');
        $data['activeProfile'] = 'active';
        $data['user'] = auth()->user();
        $data['designations'] = Designation::where('status', STATUS_ACTIVE)->get();
        $google2fa = new Google2FA();
        $data['qr_code'] = $google2fa->getQRCodeInline(
            getOption('app_name'),
            $data['user']->email,
            $data['user']->google2fa_secret
        );

        return view('admin.profile.index', $data);
    }

    public function update(ProfileRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            if ($request->image) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('User', $request->image);
                $user->image = $uploadedFile->id;
            }
            $user->designation_id = $request->designation_id;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->save();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function password()
    {
        $data['title'] = __('Profile');
        $data['pageTitle'] = __('Change Password');
        $data['activeProfile'] = 'active';
        return view('admin.profile.password', $data);
    }

    public function passwordUpdate(PasswordChangeRequest $request)
    {
        try {
            $user = User::find(auth()->id());
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
