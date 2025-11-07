<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotify;
use App\Models\CertificateType;
use App\Models\Country;
use App\Models\FileManager;
use App\Models\LanguageProficiencyTest;
use App\Models\StudentServiceOrder;
use App\Models\Subject;
use App\Models\University;
use App\Models\User;
use App\Models\OnboardingFormSetting;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OnboardingController extends Controller
{
    use ResponseTrait;

    public function onboardingEnable($serviceOrderId)
    {
        try {
            $serviceOrder = StudentServiceOrder::findOrFail(decodeId($serviceOrderId));

            // Determine if the status is being changed from 0 to 1
            $statusChangedToActive = $serviceOrder->onboard_status == 0;

            // Toggle onboard_status between 0 and 1
            $serviceOrder->update([
                'onboard_status' => $serviceOrder->onboard_status == 1 ? 0 : 1,
            ]);

            // Only send notifications and emails if the status changed to 1 (active)
            if ($statusChangedToActive) {
                $link = route('student.service_orders.onboarding', encodeId($serviceOrder->id));

                if (getOption('app_mail_status')) {
                    $viewData = [
                        '{{name}}' => $serviceOrder->student->name,
                        '{{email}}' => $serviceOrder->student->email,
                        '{{link}}' => $link
                    ];
                    $templateData = getEmailTemplate('onboard-request', $viewData);
                    Mail::to($serviceOrder->student->email)->send(new EmailNotify($templateData));
                }

                setCommonNotification(
                    $serviceOrder->student_id,
                    $serviceOrder->orderID . ' ' . __('Onboarding Request Pending'),
                    __('Your onboarding request has been enabled. Please complete the required information by visiting the following link:'),
                    $link
                );
            }

            return $this->success([], __('Onboard status toggled successfully'));
        } catch (\Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function onboarding($studentServiceOrderId)
    {
        $data['serviceOrder'] = StudentServiceOrder::where('id', decodeId($studentServiceOrderId))->with([
            'student_basic_info',
            'student_academic_info.certificate_type',
            'student_work_experiences',
            'student_extra_curriculum_activities',
            'student_language_proficiencies',
        ])->first();
        if (is_null($data['serviceOrder']) || (auth()->user()->role == USER_ROLE_STUDENT && $data['serviceOrder']->student_id != auth()->id())) {
            return back()->with('error', __('Invalid request! Please contact with admin'));
        }

        $data['showServiceOrder'] = 'active';
        $data['pageTitle'] = __('Onboarding');
        $data['pageTitleParent'] = $data['serviceOrder']->orderID;

        // Load form settings and user data
        $data['formSetting'] = OnboardingFormSetting::all();

        $interestedArea = $data['serviceOrder']->student_basic_info;

        // Check if $interestedArea is not null before accessing its methods
        $data['serviceOrder']->intersted_countries = $interestedArea ? $interestedArea->getCountries()->pluck('name')->implode(', ') : '';
        $data['serviceOrder']->intersted_universities = $interestedArea ? $interestedArea->getUniversities()->pluck('name')->implode(', ') : '';
        $data['serviceOrder']->intersted_subjects = $interestedArea ? $interestedArea->getSubjects()->pluck('name')->implode(', ') : '';
        $data['certificateTypes'] = CertificateType::where('status', STATUS_ACTIVE)->get();
        $data['proficiencyTests'] = LanguageProficiencyTest::where('status', STATUS_ACTIVE)->get();

        // Decode admin-defined custom fields and user custom fields
        $adminCustomFieldStructure = json_decode(getOnboardingField($data['formSetting'], 'custom_field_form', 'show'), true);
        $userCustomFields = $data['serviceOrder']->student_basic_info ? json_decode($data['serviceOrder']->student_basic_info->custom_fields, true) : [];

        // Merge custom fields with user data
        $mergedCustomFields = $this->mergeCustomFields($adminCustomFieldStructure, $userCustomFields ?? []);

        $data['customFieldData'] = json_encode($mergedCustomFields);

        return view('student.onboarding.index', $data);
    }

    private function mergeCustomFields(array $adminStructure, array $userData)
    {
        // Map user data by 'name' for quick lookup
        $userDataMap = [];
        foreach ($userData as $field) {
            $userDataMap[$field['name']] = $field['userData'];
        }

        // Merge admin structure with user data
        foreach ($adminStructure as &$field) {
            if (isset($userDataMap[$field['name']])) {
                $field['userData'] = $userDataMap[$field['name']];
            }
        }

        return $adminStructure;
    }

    public function editModal($studentServiceOrderId, $section, $id = null)
    {
        $data['formSetting'] = OnboardingFormSetting::all();

        $data['serviceOrder'] = StudentServiceOrder::where('id', decodeId($studentServiceOrderId))->with([
            'student_basic_info',
            'student_academic_info.certificate_type',
            'student_work_experiences',
            'student_extra_curriculum_activities',
            'student_language_proficiencies',
        ])->first();

        if (is_null($data['serviceOrder']) || (auth()->user()->role == USER_ROLE_STUDENT && $data['serviceOrder']->student_id != auth()->id())) {
            return back()->with('error', __('Invalid request! Please contact with admin'));
        }

        // Load the specific modal view based on section
        if ($section === 'basic') {
            $data['serviceOrder'] = StudentServiceOrder::where('id', decodeId($studentServiceOrderId))->with([
                'student_basic_info',
            ])->first();

            if (is_null($data['serviceOrder']) || (auth()->user()->role == USER_ROLE_STUDENT && $data['serviceOrder']->student_id != auth()->id())) {
                return back()->with('error', __('Invalid request! Please contact with admin'));
            }

            return view('student.onboarding.edit.basic_info', $data);
        } else if ($section === 'academic') {
            $data['serviceOrder'] = StudentServiceOrder::with([
                'student_academic_info' => function ($query) use ($id) {
                    $query->where('id', $id);
                }
            ])->findOrFail(decodeId($studentServiceOrderId));

            if (is_null($data['serviceOrder']) || (auth()->user()->role == USER_ROLE_STUDENT && $data['serviceOrder']->student_id != auth()->id())) {
                return back()->with('error', __('Invalid request! Please contact with admin'));
            }

            $data['certificateTypes'] = CertificateType::where('status', STATUS_ACTIVE)->get();
            $data['academyData'] = $data['serviceOrder']->student_academic_info->first();
            return view('student.onboarding.edit.academic_info', $data);
        } else if ($section === 'work_experience') {
            $data['serviceOrder'] = StudentServiceOrder::with([
                'student_work_experiences' => function ($query) use ($id) {
                    $query->where('id', $id);
                }
            ])->findOrFail(decodeId($studentServiceOrderId));

            if (is_null($data['serviceOrder']) || (auth()->user()->role == USER_ROLE_STUDENT && $data['serviceOrder']->student_id != auth()->id())) {
                return back()->with('error', __('Invalid request! Please contact with admin'));
            }
            $data['experienceData'] = $data['serviceOrder']->student_work_experiences->first();
            return view('student.onboarding.edit.work_experience', $data);
        } else if ($section === 'extra_curriculum_activity') {
            $data['serviceOrder'] = StudentServiceOrder::with([
                'student_extra_curriculum_activities' => function ($query) use ($id) {
                    $query->where('id', $id);
                }
            ])->findOrFail(decodeId($studentServiceOrderId));

            if (is_null($data['serviceOrder']) || (auth()->user()->role == USER_ROLE_STUDENT && $data['serviceOrder']->student_id != auth()->id())) {
                return back()->with('error', __('Invalid request! Please contact with admin'));
            }

            $data['activityData'] = $data['serviceOrder']->student_extra_curriculum_activities->first();
            return view('student.onboarding.edit.extra_curriculum_activity', $data);
        } else if ($section === 'language_proficiency') {
            $data['serviceOrder'] = StudentServiceOrder::with([
                'student_language_proficiencies' => function ($query) use ($id) {
                    $query->where('id', $id);
                }
            ])->findOrFail(decodeId($studentServiceOrderId));

            $data['proficiencyTests'] = LanguageProficiencyTest::where('status', STATUS_ACTIVE)->get();
            $data['proficiencyData'] = $data['serviceOrder']->student_language_proficiencies->first();
            return view('student.onboarding.edit.language_proficiency', $data);

        } else if ($section === 'interested_area') {
            $data['serviceOrder'] = StudentServiceOrder::with('student_basic_info')->findOrFail(decodeId($studentServiceOrderId));
            $interestedArea = $data['serviceOrder']->student_basic_info;

            // Fetch countries, universities, and subjects
            $data['countries'] = Country::where('status', STATUS_ACTIVE)->get();

            // Fetch universities based on saved countries
            $selectedCountryIds = $interestedArea->destination_country_ids ?? [];
            $data['universities'] = University::whereIn('country_id', $selectedCountryIds)
                ->where('status', STATUS_ACTIVE)->get();

            // Fetch subjects based on saved universities
            $selectedUniversityIds = $interestedArea->university_ids ?? [];
            $data['subjects'] = Subject::whereIn('university_id', $selectedUniversityIds)
                ->where('status', STATUS_ACTIVE)->get();

            return view('student.onboarding.edit.interested_area', $data);
        }
    }

    public function update(Request $request, $serviceOrderId, $section, $id = null)
    {
        $serviceOrderId = decodeId($serviceOrderId);
        // Generate validation rules based on the section
        $validationRules = $this->generateValidationRules($section);

        if ($section === 'interested_area') {
            $formSettings = OnboardingFormSetting::all();

            foreach ($formSettings as $setting) {
                if (str_starts_with($setting->field_slug, 'interested_area_')) {
                    $field = $setting->field_slug;

                    // Set validation rules dynamically based on form settings
                    if ($setting->field_required && $setting->field_show) {
                        $validationRules[$field] = 'required';
                    } else {
                        $validationRules[$field] = 'nullable';
                    }

                    // Additional specific rules for array fields
                    if (in_array($field, ['interested_area_destination_country_ids', 'interested_area_university_ids', 'interested_area_subject_ids'])) {
                        $validationRules[$field] .= '|array';
                    }

                    // String field with max length
                    if ($field === 'interested_area_admission_period') {
                        $validationRules[$field] .= '|string|max:255';
                    }
                }
            }
        } else if ($section === 'basic') {
            if ($request->has('has_basic_passport_attachment')) {
                $validationRules['basic_passport_attachment'] = 'nullable|mimes:pdf,jpg,jpeg,png';
            }
        } elseif ($section === 'academic') {
            if ($id) {
                $validationRules['academic_attachment'] = 'nullable|mimes:pdf,jpg,jpeg,png';
            }
        } elseif ($section === 'work_experience') {
            if ($id) {
                $validationRules['work_experience_attachment'] = 'nullable|mimes:pdf,jpg,jpeg,png';
            }
        } elseif ($section === 'extra_curriculum_activity') {
            if ($id) {
                $validationRules['extra_curriculum_activity_attachment'] = 'nullable|mimes:pdf,jpg,jpeg,png';
            }
        } elseif ($section === 'language_proficiency') {
            if ($id) {
                $validationRules['language_proficiency_attachment'] = 'nullable|mimes:pdf,jpg,jpeg,png';
            }
        }

        $validatedData = $request->validate($validationRules);

        DB::beginTransaction();

        try {

            $studentServiceOrder = StudentServiceOrder::with(['student_basic_info'])->where('id', $serviceOrderId)->first();

            if ($section === 'basic') {
                $studentServiceOrder = StudentServiceOrder::with(['student_basic_info'])->where('id', $serviceOrderId)->first();

                // Handle file upload for basic_passport_attachment
                $passportAttachmentId = $studentServiceOrder->student_basic_info->passport_attachment ?? null;
                if ($request->hasFile('basic_passport_attachment')) {
                    $fileManager = new FileManager();
                    $uploadedFile = $fileManager->upload('student_onboarding', $request->file('basic_passport_attachment'));

                    if ($uploadedFile) {
                        $passportAttachmentId = $uploadedFile->id;
                    } else {
                        DB::rollBack();
                        return $this->error([], __('File upload failed! Please try again.'));
                    }
                }

                // Update basic information
                $studentServiceOrder->student_basic_info()->updateOrCreate([
                    'student_service_order_id' => $studentServiceOrder->id
                ], [
                    'user_id' => $studentServiceOrder->student_id,
                    'first_name' => $validatedData['basic_first_name'] ?? $studentServiceOrder->student_basic_info?->first_name,
                    'last_name' => $validatedData['basic_last_name'] ?? $studentServiceOrder->student_basic_info?->last_name,
                    'email' => $validatedData['basic_email'] ?? $studentServiceOrder->student_basic_info?->email,
                    'mobile' => $validatedData['basic_phone'] ?? $studentServiceOrder->student_basic_info?->mobile,
                    'gender' => $validatedData['basic_gender'] ?? $studentServiceOrder->student_basic_info?->gender,
                    'passport_attachment' => $passportAttachmentId,
                    'date_of_birth' => $validatedData['basic_date_of_birth'] ?? $studentServiceOrder->student_basic_info->date_of_birth,
                    'present_address' => $validatedData['basic_present_address'] ?? $studentServiceOrder->student_basic_info->present_address,
                    'permanent_address' => $validatedData['basic_permanent_address'] ?? $studentServiceOrder->student_basic_info->permanent_address,
                    'passport_number' => $validatedData['basic_passport_number'] ?? $studentServiceOrder->student_basic_info->passport_number,
                ]);
            } elseif ($section === 'academic') {
                $serviceOrder = StudentServiceOrder::with(['student_academic_info.certificate_type'])->where('id', $serviceOrderId)->first();
                // Update basic information

                // Handle file upload for basic_passport_attachment
                $attachmentId = $request->id ? $serviceOrder->student_academic_info->find($request->id)->attachment : null;
                if ($request->hasFile('academic_attachment')) {
                    $fileManager = new FileManager();
                    $uploadedFile = $fileManager->upload('student_onboarding', $request->file('academic_attachment'));

                    if ($uploadedFile) {
                        $attachmentId = $uploadedFile->id;
                    } else {
                        DB::rollBack();
                        return $this->error([], __('File upload failed! Please try again.'));
                    }
                }

                $serviceOrder->student_academic_info()->updateOrCreate(
                    ['student_service_order_id' => $studentServiceOrder->id, 'id' => $request->id],
                    [
                        'user_id' => $studentServiceOrder->student_id,
                        'certificate_type_id' => $validatedData['academic_certificate_type_id'] ?? null,
                        'institution' => $validatedData['academic_institution'] ?? null,
                        'academic_year' => $validatedData['academic_academic_year'] ?? null,
                        'passing_year' => $validatedData['academic_passing_year'] ?? null,
                        'result' => $validatedData['academic_result'] ?? null,
                        'attachment' => $attachmentId,
                    ]);
            } elseif ($section === 'work_experience') {
                $serviceOrder = StudentServiceOrder::with(['student_work_experiences'])->where('id', $serviceOrderId)->first();

                $attachmentId = $request->id ? $serviceOrder->student_work_experiences->find($request->id)->attachment : null;

                if ($request->hasFile('work_experience_attachment')) {
                    $fileManager = new FileManager();
                    $uploadedFile = $fileManager->upload('student_onboarding', $request->file('work_experience_attachment'));

                    if ($uploadedFile) {
                        $attachmentId = $uploadedFile->id;
                    } else {
                        DB::rollBack();
                        return $this->error([], __('File upload failed! Please try again.'));
                    }
                }

                $serviceOrder->student_work_experiences()->updateOrCreate(
                    ['student_service_order_id' => $serviceOrderId, 'id' => $request->id],
                    [
                        'user_id' => $studentServiceOrder->student_id,
                        'title' => $validatedData['work_experience_title'] ?? null,
                        'company' => $validatedData['work_experience_company'] ?? null,
                        'designation' => $validatedData['work_experience_designation'] ?? null,
                        'start_date' => $validatedData['work_experience_start_date'] ?? null,
                        'end_date' => $validatedData['work_experience_end_date'] ?? null,
                        'description' => $validatedData['work_experience_description'] ?? null,
                        'attachment' => $attachmentId, // Only updates if a new file was uploaded
                    ]
                );
            } elseif ($section === 'extra_curriculum_activity') {
                $serviceOrder = StudentServiceOrder::with(['student_extra_curriculum_activities'])->where('id', $serviceOrderId)->first();

                $attachmentId = $request->id ? $serviceOrder->student_extra_curriculum_activities->find($request->id)->attachment : null;

                if ($request->hasFile('extra_curriculum_activity_attachment')) {
                    $fileManager = new FileManager();
                    $uploadedFile = $fileManager->upload('student_onboarding', $request->file('extra_curriculum_activity_attachment'));

                    if ($uploadedFile) {
                        $attachmentId = $uploadedFile->id;
                    } else {
                        DB::rollBack();
                        return $this->error([], __('File upload failed! Please try again.'));
                    }
                }

                $serviceOrder->student_extra_curriculum_activities()->updateOrCreate(
                    ['student_service_order_id' => $serviceOrderId, 'id' => $request->id],
                    [
                        'user_id' => $studentServiceOrder->student_id,
                        'title' => $validatedData['extra_curriculum_activity_title'] ?? null,
                        'description' => $validatedData['extra_curriculum_activity_description'] ?? null,
                        'attachment' => $attachmentId, // Only updates if a new file was uploaded
                    ]
                );
            } else if ($section === 'language_proficiency') {
                $serviceOrder = StudentServiceOrder::with(['student_language_proficiencies'])->where('id', $serviceOrderId)->first();

                $attachmentId = $request->id ? $serviceOrder->student_language_proficiencies->find($request->id)->attachment : null;

                if ($request->hasFile('language_proficiency_attachment')) {
                    $fileManager = new FileManager();
                    $uploadedFile = $fileManager->upload('student_onboarding', $request->file('language_proficiency_attachment'));

                    if ($uploadedFile) {
                        $attachmentId = $uploadedFile->id;
                    } else {
                        DB::rollBack();
                        return $this->error([], __('File upload failed! Please try again.'));
                    }
                }

                $serviceOrder->student_language_proficiencies()->updateOrCreate(
                    ['student_service_order_id' => $serviceOrderId, 'id' => $request->id],
                    [
                        'user_id' => $studentServiceOrder->student_id,
                        'language_proficiency_test_id' => $validatedData['language_proficiency_test_id'] ?? null,
                        'score' => $validatedData['language_proficiency_score'] ?? null,
                        'test_date' => $validatedData['language_proficiency_test_date'] ?? null,
                        'expired_date' => $validatedData['language_proficiency_expired_date'] ?? null,
                        'attachment' => $attachmentId,
                    ]
                );
            } else if ($section === 'interested_area') {
                $serviceOrder = StudentServiceOrder::with(['student_basic_info'])->where('id', $serviceOrderId)->first();

                $serviceOrder->student_basic_info()->updateOrCreate(
                    ['student_service_order_id' => $serviceOrderId],
                    [
                        'user_id' => $studentServiceOrder->student_id,
                        'destination_country_ids' => $validatedData['interested_area_destination_country_ids'],
                        'university_ids' => $validatedData['interested_area_university_ids'] ?? [],
                        'subject_ids' => $validatedData['interested_area_subject_ids'] ?? [],
                        'admission_period' => $validatedData['interested_area_admission_period'] ?? null,
                    ]
                );
            } else if ($section === 'custom_field') {

                $serviceOrder = StudentServiceOrder::with(['student_basic_info'])->where('id', $serviceOrderId)->first();

                $serviceOrder->student_basic_info()->updateOrCreate(
                    ['student_service_order_id' => $serviceOrder->id],
                    [
                        'custom_fields' => $request->custom_fields,
                    ]
                );
            }

            DB::commit();
            return $this->success([], __('Profile updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    private function generateValidationRules($section)
    {
        $formSettings = OnboardingFormSetting::all();
        $rules = [];

        foreach ($formSettings as $setting) {
            if (str_starts_with($setting->field_slug, $section . '_')) {
                if ($setting->field_required && $setting->field_show) {
                    if (str_contains($setting->field_slug, 'attachment') || $setting->is_attachment) {
                        $rules[$setting->field_slug] = 'required|mimes:pdf,jpg,jpeg,png';
                    } else {
                        $rules[$setting->field_slug] = 'required';
                    }
                }
            }
        }

        return $rules;
    }

    public function delete($serviceOrderId, $section, $id)
    {
        DB::beginTransaction();

        try {
            $serviceOrder = StudentServiceOrder::with(['student_academic_info', 'student_work_experiences', 'student_extra_curriculum_activities'])->findOrFail(decodeId($serviceOrderId));

            if ((auth()->user()->role == USER_ROLE_STUDENT && $serviceOrder->student_id != auth()->id()) || is_null($serviceOrder)) {
                return back()->with('error', __('Invalid request! Please contact with admin'));
            }


            if ($section === 'academic') {
                $serviceOrder->student_academic_info()->findOrFail($id)->delete();
            } else if ($section === 'work_experience') {
                $serviceOrder->student_work_experiences()->findOrFail($id)->delete();
            } else if ($section === 'extra_curriculum_activity') {
                $serviceOrder->student_extra_curriculum_activities()->findOrFail($id)->delete();
            }

            DB::commit();
            return $this->success([], __('Record deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    public function onboardFinish($serviceOrderId)
    {
        DB::beginTransaction();

        try {
            $serviceOrder = StudentServiceOrder::findOrFail(decodeId($serviceOrderId));

            $serviceOrder->update([
                'onboard_status' => $serviceOrder->onboard_status == 0,
            ]);

            // Prepare the link for email and notification
            $link = route('admin.service_orders.task-board.index', $serviceOrderId);

            // Send email if the application mail status is enabled
            if (getOption('app_mail_status')) {
                $viewData = [
                    '{{name}}' => $serviceOrder->student->name,
                    '{{email}}' => $serviceOrder->student->email,
                    '{{link}}' => $link,
                ];
                $templateData = getEmailTemplate('onboard-done-by-student', $viewData);
                Mail::to($serviceOrder->student->email)->send(new EmailNotify($templateData));
            }

            // Set common notification
            setCommonNotification(
                $serviceOrder->student_id,
                $serviceOrder->orderID . ' ' . __('Onboard done'),
                __('The onboard has been done . Please review that'),
                $link
            );

            DB::commit();
            return $this->success(['url' => route('student.service_orders.task-board.index', $serviceOrderId)], __('Finished the onboard.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }
}
