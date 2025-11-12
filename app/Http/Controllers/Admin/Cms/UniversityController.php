<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UniversityRequest;
use App\Models\Country;
use App\Models\FileManager;
use App\Models\University;
use App\Models\UniversityCriteriaField;
use App\Models\UniversityCriteriaValue;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class UniversityController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $university = University::query()->orderBy('id','DESC');
            return datatables($university)
                ->addIndexColumn()
                ->addColumn('thumbnail_image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->thumbnail_image) . '" alt="thumbnail_image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-completed">'.__("Active").'</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">'.__("Deactivate").'</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <a href="'.route('admin.cms-settings.universities.edit', $data->id).'" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </a>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.universities.delete', $data->id) . '\', \'universityDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status','action','thumbnail_image'])
                ->make(true);
        }
        $data['pageTitle'] = __("Manage University");
        $data['universityActive'] = 'active';
        $data['showCmsSettings'] = 'show';

        return view('admin.cms.universities.index', $data);
    }

    public function create()
    {
        $data['pageTitleParent'] = __("Manage University");
        $data['pageTitle'] = __('Create');
        $data['universityActive'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['countryData'] = Country::where('status',STATUS_ACTIVE)->get();
        $data['criteriaFields'] = UniversityCriteriaField::where('status', STATUS_ACTIVE)->orderBy('order')->get();

        return view('admin.cms.universities.create', $data);
    }

    public function edit($id)
    {
        $data['universityData'] = University::with('criteriaValues.criteriaField')->find($id);
        $data['pageTitleParent'] = __("Manage University");
        $data['pageTitle'] = __('Update');
        $data['universityActive'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['countryData'] = Country::where('status',STATUS_ACTIVE)->get();
        $data['criteriaFields'] = UniversityCriteriaField::where('status', STATUS_ACTIVE)->orderBy('order')->get();

        // Get existing criteria values keyed by criteria_field_id
        $data['existingCriteriaValues'] = [];
        if ($data['universityData'] && $data['universityData']->criteriaValues) {
            foreach ($data['universityData']->criteriaValues as $value) {
                $data['existingCriteriaValues'][$value->criteria_field_id] = $value->value;
            }
        }

        return view('admin.cms.universities.edit', $data);
    }


    public function store(UniversityRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $university = $id ? University::find($id) : new University();

            if (University::where('slug', getSlug($request->name))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->name) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->name);
            }

            $university->name = $request->name;
            $university->slug = $slug;
            $university->details = $request->details;
            $university->country_id = $request->country_id;
            $university->world_ranking = $request->world_ranking;
            $university->international_student = $request->international_student;
            $university->avg_cost = $request->avg_cost;
            $university->feature = $request->feature ?? STATUS_PENDING;
            $university->top_university = $request->top_university ?? STATUS_PENDING;
            $university->core_benefits_title = $request->core_benefits_title ?? [];
            $university->status = $request->status;

            if ($request->hasFile('thumbnail_image')) {
                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('study-university', $request->thumbnail_image);
                $university->thumbnail_image = $uploadedFile->id;
            }
            if ($request->hasFile('logo')) {
                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('study-university', $request->logo);
                $university->logo = $uploadedFile->id;
            }

            $coreBenefitsIcon = $request->core_benefits_icon_id ?? [];
            foreach ($request->core_benefits_icon ?? [] as $index => $icon) {
                if ($request->hasFile("core_benefits_icon.$index")) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('study-university', $icon);
                    $coreBenefitsIcon[$index] = $uploadedFile->id;
                }
            }
            $university->core_benefits_icon = array_values($coreBenefitsIcon);

            $universityGalleryImage = $university->gallery_image ?? [];
            foreach ($request->gallery_image ?? [] as $index => $icon) {
                if ($request->hasFile("gallery_image.$index")) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('study-university', $icon);
                    $universityGalleryImage[$index] = $uploadedFile->id;
                }
            }
            $university->gallery_image = array_values($universityGalleryImage);

            $university->save();

            // Save criteria values
            // Get all active criteria fields to handle unchecked checkboxes
            $allCriteriaFields = UniversityCriteriaField::where('status', STATUS_ACTIVE)
                ->with('dependsOn')
                ->orderByRaw('depends_on_criteria_field_id IS NULL DESC, `order` ASC')
                ->get();
            $submittedCriteriaValues = $request->criteria_values ?? [];
            $submittedStructuredValues = $request->criteria_structured ?? [];

            foreach ($allCriteriaFields as $criteriaField) {
                $criteriaFieldId = $criteriaField->id;

                // Handle structured JSON fields (e.g., English tests with scores)
                if ($criteriaField->type === 'json' && $criteriaField->is_structured && isset($submittedStructuredValues[$criteriaFieldId])) {
                    $structuredData = $submittedStructuredValues[$criteriaFieldId];
                    $structuredJson = [];

                    foreach ($structuredData as $option => $data) {
                        if (isset($data['enabled']) && $data['enabled'] == '1' && isset($data['value']) && !empty(trim($data['value']))) {
                            $structuredJson[$option] = (float)$data['value'];
                        }
                    }

                    if (!empty($structuredJson)) {
                        UniversityCriteriaValue::updateOrCreate(
                            [
                                'university_id' => $university->id,
                                'criteria_field_id' => $criteriaFieldId
                            ],
                            [
                                'value' => json_encode($structuredJson)
                            ]
                        );
                    } else {
                        // Remove if empty
                        UniversityCriteriaValue::where('university_id', $university->id)
                            ->where('criteria_field_id', $criteriaFieldId)
                            ->delete();
                    }
                    continue; // Skip to next field
                }

                // Check if this criteria field was submitted in the form
                if (isset($submittedCriteriaValues[$criteriaFieldId])) {
                    $value = $submittedCriteriaValues[$criteriaFieldId];

                    // For boolean type, if checkbox is checked, value will be "1"
                    if ($criteriaField->type === 'boolean') {
                        // Boolean checkbox is checked (value = "1")
                        UniversityCriteriaValue::updateOrCreate(
                            [
                                'university_id' => $university->id,
                                'criteria_field_id' => $criteriaFieldId
                            ],
                            [
                                'value' => '1'
                            ]
                        );
                    } elseif ($criteriaField->type === 'json') {
                        // JSON type - handle array input
                        if (is_array($value) && !empty($value)) {
                            // Array of values from checkboxes - encode as JSON
                            $jsonValue = json_encode(array_values($value));
                            UniversityCriteriaValue::updateOrCreate(
                                [
                                    'university_id' => $university->id,
                                    'criteria_field_id' => $criteriaFieldId
                                ],
                                [
                                    'value' => $jsonValue
                                ]
                            );
                        } elseif (is_string($value) && !empty(trim($value))) {
                            // String value - try to validate as JSON, if not valid JSON, wrap in array
                            $decoded = json_decode($value, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                // Valid JSON array
                                UniversityCriteriaValue::updateOrCreate(
                                    [
                                        'university_id' => $university->id,
                                        'criteria_field_id' => $criteriaFieldId
                                    ],
                                    [
                                        'value' => $value
                                    ]
                                );
                            } else {
                                // Not valid JSON, treat as single value and wrap in array
                                $jsonValue = json_encode([$value]);
                                UniversityCriteriaValue::updateOrCreate(
                                    [
                                        'university_id' => $university->id,
                                        'criteria_field_id' => $criteriaFieldId
                                    ],
                                    [
                                        'value' => $jsonValue
                                    ]
                                );
                            }
                        } else {
                            // Empty value - remove it
                            UniversityCriteriaValue::where('university_id', $university->id)
                                ->where('criteria_field_id', $criteriaFieldId)
                                ->delete();
                        }
                    } elseif ($value !== null && $value !== '') {
                        // Non-boolean, non-JSON field with a value
                        UniversityCriteriaValue::updateOrCreate(
                            [
                                'university_id' => $university->id,
                                'criteria_field_id' => $criteriaFieldId
                            ],
                            [
                                'value' => $value
                            ]
                        );
                    } else {
                        // Empty value for non-boolean - remove it
                        UniversityCriteriaValue::where('university_id', $university->id)
                            ->where('criteria_field_id', $criteriaFieldId)
                            ->delete();
                    }
                } else {
                    // Field not in request
                    if ($criteriaField->type === 'boolean') {
                        // Boolean checkbox is unchecked - set value to "0" (false)
                        UniversityCriteriaValue::updateOrCreate(
                            [
                                'university_id' => $university->id,
                                'criteria_field_id' => $criteriaFieldId
                            ],
                            [
                                'value' => '0'
                            ]
                        );
                    } elseif ($criteriaField->type === 'json') {
                        // JSON type field not submitted (all checkboxes unchecked) - remove it
                        UniversityCriteriaValue::where('university_id', $university->id)
                            ->where('criteria_field_id', $criteriaFieldId)
                            ->delete();
                    } else {
                        // Non-boolean field not in request - remove it (field was cleared)
                        UniversityCriteriaValue::where('university_id', $university->id)
                            ->where('criteria_field_id', $criteriaFieldId)
                            ->delete();
                    }
                }
            }

            DB::commit();

            $message = $request->id ? __('Updated successfully.') : __('Created successfully.');
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            $data = University::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
