<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnboardingFormSetting;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class OnboardingFormSettingController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data['pageTitle'] = __('Onboarding Form Settings');
        $data['activeSetting'] = 'active';
        $data['activeOnboardingFormSetting'] = 'active';
        $data['sections'] = config('onboarding_form.sections');
        $data['formSetting'] = OnboardingFormSetting::all();
        return view('admin.setting.onboarding_form_settings', $data);
    }

    public function update(Request $request)
    {
        try {
            $setting = OnboardingFormSetting::firstOrCreate(['field_slug' => $request->field_slug]);
            $setting->{$request->field_key} = $request->field_value; // Corrected syntax
            $setting->save();
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG)); // Removed `dd()` for cleaner error handling
        }
    }

    public function customField(Request $request)
    {
        try {
            $setting = OnboardingFormSetting::firstOrCreate(['field_slug' => 'custom_field_form']);
            $setting->field_show = $request->custom_field;
            $setting->save();
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG)); // Removed `dd()` for cleaner error handling
        }
    }

}
