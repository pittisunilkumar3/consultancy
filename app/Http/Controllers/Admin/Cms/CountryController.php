<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Models\FileManager;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class CountryController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $country = Country::query()->orderBy('id','DESC');
            return datatables($country)
                ->addIndexColumn()
                ->addColumn('banner_image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->banner_image) . '" alt="banner_image" class="rounded avatar-xs w-100 h-100">
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
                            <a href="'.route('admin.cms-settings.countries.edit', $data->id).'" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </a>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.countries.delete', $data->id) . '\', \'countryDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status','action','banner_image'])
                ->make(true);
        }
        $data['pageTitle'] = __("Destination Country");
        $data['countryActive'] = 'active';
        $data['showCmsSettings'] = 'show';

        return view('admin.cms.countries.index', $data);
    }

    public function create()
    {
        $data['pageTitle'] = __("Create");
        $data['pageTitleParent'] = __('Destination Country');
        $data['countryActive'] = 'active';
        $data['showCmsSettings'] = 'show';

        return view('admin.cms.countries.create', $data);
    }

    public function edit($id)
    {
        $data['countryData'] = Country::find($id);
        $data['pageTitle'] = __("Update");
        $data['pageTitleParent'] = __('Destination Country');
        $data['countryActive'] = 'active';
        $data['showCmsSettings'] = 'show';
        return view('admin.cms.countries.edit', $data);
    }


    public function store(CountryRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $country = $id ? Country::find($id) : new Country();

            if (Country::where('slug', getSlug($request->name))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->name) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->name);
            }
            // Basic data updates
            $country->name = $request->name;
            $country->slug = $slug;
            $country->details = $request->details;
            $country->core_benefits_title = $request->core_benefits_title ?? [];
            $country->status = $request->status;

            // Handling banner image
            if ($request->hasFile('banner_image')) {
                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('study-country', $request->banner_image);
                $country->banner_image = $uploadedFile->id;
            }

            // Handling core benefits icon
            $coreBenefitsIcon = $request->core_benefits_icon_id ?? []; // Keep current icons as baseline
            foreach ($request->core_benefits_icon ?? [] as $index => $icon) {
                if ($request->hasFile("core_benefits_icon.$index")) {
                    // Upload new or replacement icon
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('study-country', $icon);
                    $coreBenefitsIcon[$index] = $uploadedFile->id;
                }
            }
            $country->core_benefits_icon = array_values($coreBenefitsIcon); // Re-index array

            // Handling gallery images
            $countryGalleryImage = $country->gallery_image ?? [];
            foreach ($request->gallery_image ?? [] as $index => $icon) {
                if ($request->hasFile("gallery_image.$index")) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('study-country', $icon);
                    $countryGalleryImage[$index] = $uploadedFile->id;
                }
            }
            $country->gallery_image = array_values($countryGalleryImage);

            // Save the country
            $country->save();
            DB::commit();

            // Success message
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
            $data = Country::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
