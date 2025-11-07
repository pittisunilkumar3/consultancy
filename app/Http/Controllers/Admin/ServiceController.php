<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\FileManager;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;
use function NunoMaduro\Collision\Exceptions\getPrevious;

class ServiceController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $query = Service::query();

        if($request->has('search_key')){
            $query->where('title','like','%'.$request->search_key.'%')
                   ->orWhere('price','like','%'.$request->search_key.'%');
        }
        if(auth()->user()->role != USER_ROLE_STUDENT){
            $data['service'] = $query->orderBy('id', 'DESC')->paginate(12);
        }else{
            $data['service'] = $query->orderBy('id', 'DESC')->where('status',STATUS_ACTIVE)->paginate(12);
        }

        if ($request->ajax()) {
            return view('admin.services.partials.list', $data)->render();
        }
        $data['pageTitle'] = __("Manage Service");
        $data['activeService'] = 'active';

        return view(getPrefix().'.services.index', $data);
    }

    public function create()
    {
        $data['pageTitle'] = __("Create Service");
        $data['activeService'] = 'active';
        $data['pageTitleParent'] = __("Manage Service");
        return view('admin.services.create', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __("Update Service");
        $data['activeService'] = 'active';
        $data['service'] = Service::find(decodeId($id));
        $data['pageTitleParent'] = __("Manage Service");
        return view('admin.services.edit', $data);
    }

    public function details($id)
    {
        $data['pageTitle'] = __("Service Details");
        $data['activeService'] = 'active';
        $data['serviceData'] = Service::find(decodeId($id));
        $data['pageTitleParent'] = __("Manage Service");
        return view(getPrefix().'.services.details', $data);
    }

    public function store(ServiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            if ($id) {
                $services = Service::find($id);
            } else {
                $services = new Service();
            }
            if (Service::where('slug', getSlug($request->title))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->title) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->title);
            }

            $services->title = $request->title;
            $services->price = $request->price;
            $services->slug = $slug;
            $services->description = $request->description;
            $services->status = $request->status;

            $features = [];
            if ($request->has('feature_name')) {
                foreach ($request->input('feature_name') as $key => $name) {
                    $features[] = [
                        'name' => $name,
                        'value' => $request->input('feature_value.' . $key),
                    ];
                }
            }
            $services->feature = $features;

            if ($request->hasFile('image')) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('service', $request->image);
                $services->image = $uploadedFile->id;
            }
            if ($request->hasFile('icon')) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('service', $request->icon);
                $services->icon = $uploadedFile->id;
            }
            $services->save();
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            $data = Service::find(decodeId($id));
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
