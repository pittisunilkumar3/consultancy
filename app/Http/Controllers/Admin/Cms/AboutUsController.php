<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutUsRequest;
use App\Models\FileManager;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class AboutUsController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data['pageTitle'] = __("Manage About Us");
        $data['aboutUsActive'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['aboutUsData'] = AboutUs::first();
        return view('admin.cms.about-us.index', $data);
    }

    public function store(AboutUsRequest $request)
    {
        DB::beginTransaction();
        try {
            ;
            $id = $request->id;
            if ($id) {
                $aboutUs = AboutUs::find($id);
            } else {
                $aboutUs = new AboutUs();
            }
            $aboutUs->title = $request->title;
            $aboutUs->details = $request->details;
            $aboutUs->our_mission_title = $request->our_mission_title;
            $aboutUs->our_mission_details = $request->our_mission_details;
            $aboutUs->our_vision_title = $request->our_vision_title;
            $aboutUs->our_vision_details = $request->our_vision_details;
            $aboutUs->our_goal_title = $request->our_goal_title;
            $aboutUs->our_goal_details = $request->our_goal_details;

            if ($request->hasFile('our_mission_image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('about-us', $request->our_mission_image);

                $aboutUs->our_mission_image = $uploadedFile->id;
            }
            if ($request->hasFile('our_vision_image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('about-us', $request->our_vision_image);

                $aboutUs->our_vision_image = $uploadedFile->id;
            }
            if ($request->hasFile('our_goal_image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('about-us', $request->our_goal_image);

                $aboutUs->our_goal_image = $uploadedFile->id;
            }

            $awards = [];
            if ($request->has('awards_title')) {
                foreach ($request->input('awards_title') as $key => $name) {
                    $awardsData = [
                        'name' => $name,
                    ];

                    $oldPhoto = $request->input("old_awards_image.$key");

                    if ($request->hasFile("awards_image.$key")) {
                        $fileManager = new FileManager();
                        $uploadedImage = $fileManager->upload('awards', $request->file("awards_image.$key"));
                        if (!is_null($uploadedImage)) {
                            $awardsData['image'] = $uploadedImage->id;
                        } else {
                            DB::rollBack();
                            return $this->error([], __('Something went wrong while uploading the Awards image.'));
                        }
                    } elseif ($oldPhoto) {
                        $awardsData['image'] = $oldPhoto;
                    } else {
                        $awardsData['image'] = null;
                    }

                    $awards[] = $awardsData;
                }
            }
            $aboutUs->awards = $awards;

            $ourHistory = [];
            if ($request->has('our_history_year')) {
                foreach ($request->input('our_history_year') as $key => $name) {
                    $ourHistoryData = [
                        'year' => $name,
                        'title' => $request->input('our_history_title.'.$key),
                        'description' => $request->input('our_history_description.'.$key),

                    ];

                    $oldPhoto = $request->input("old_our_history_image.$key");

                    if ($request->hasFile("our_history_image.$key")) {
                        $fileManager = new FileManager();
                        $uploadedImage = $fileManager->upload('our_history', $request->file("our_history_image.$key"));
                        if (!is_null($uploadedImage)) {
                            $ourHistoryData['image'] = $uploadedImage->id;
                        } else {
                            DB::rollBack();
                            return $this->error([], __('Something went wrong while uploading the Awards image.'));
                        }
                    } elseif ($oldPhoto) {
                        $ourHistoryData['image'] = $oldPhoto;
                    } else {
                        $ourHistoryData['image'] = null;
                    }

                    $ourHistory[] = $ourHistoryData;
                }
            }
            $aboutUs->our_history = $ourHistory;

            $aboutUsPoint = [];
            if ($request->has('about_us_point')) {
                foreach ($request->input('about_us_point') as $key => $name) {
                    $aboutUsPointData = [
                        'point' => $name,
                    ];
                    $aboutUsPoint[] = $aboutUsPointData;
                }
            }
            $aboutUs->about_us_point = $aboutUsPoint;

            $aboutUsBannerImage = $aboutUs->banner_image ?? [];
            foreach ($request->banner_image ?? [] as $index => $icon) {
                if ($request->hasFile("banner_image.$index")) {
                    $newFile = new FileManager();
                    $uploadedFile = $newFile->upload('about_us_banner', $icon);
                    $aboutUsBannerImage[$index] = $uploadedFile->id;
                }
            }
            $aboutUs->banner_image = array_values($aboutUsBannerImage);

            $aboutUs->save();
            DB::commit();

            $message = $request->id ? __('Updated successfully.') : __('Created successfully.');
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

}
