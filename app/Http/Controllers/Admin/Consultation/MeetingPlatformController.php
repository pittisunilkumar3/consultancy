<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Admin\Consultation\GoogleMeetController;
use App\Http\Controllers\Controller;
use App\Models\MeetingPlatform;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingPlatformController extends Controller
{
    use ResponseTrait;

    public function index()
    {

        $data = [
            'showManageConsultation' => 'show',
            'activeMeetingPlatform' => 'active',
            'pageTitle' => __('Meeting Platform'),
            'platforms' => MeetingPlatform::get(),
        ];

        return view('admin.consultations.meeting-platform.index', $data);
    }

    public function edit($id)
    {
        $data = [
            'platform' => $this->getInfo($id) ,
        ];

        if ($data['platform']->type == MEETING_PLATFORMS_ZOOM) {
            return view('admin.consultations.meeting-platform.partials.zoom-edit', $data);
        } elseif ($data['platform']->type == MEETING_PLATFORMS_MEET) {
            return view('admin.consultations.meeting-platform.partials.google-meet-edit', $data);
        } elseif ($data['platform']->type == MEETING_PLATFORMS_PERSON) {
            return view('admin.consultations.meeting-platform.partials.personal-meeting-edit', $data);
        }
    }

    public function update(Request $request, $id)
    {
        if($request->type == MEETING_PLATFORMS_MEET){
            return $this->updateGoogleMeet($request, $id);
        }
        return $this->updatePlatform($request, $id);
    }

    public function updatePlatform($request, $id)
    {
        try {
            DB::beginTransaction();

            $platform = $this->getInfo($id);
            if($platform->type == MEETING_PLATFORMS_PERSON){
                $updateData = [
                    'address' => $request->address,
                ];
            }elseif($platform->type == MEETING_PLATFORMS_ZOOM){
                $updateData = [
                    'account_id' => $request->account_id,
                    'key' => $request->client_id,
                    'secret' => $request->client_secret,
                    'timezone' => $request->timezone,
                    'host_video' => $request->host_video,
                    'participant_video' => $request->participant_video,
                    'waiting_room' => $request->waiting_room,
                    'status' => $request->status,
                ];
            }

            $platform->update($updateData);

            DB::commit();

            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function updateGoogleMeet($request, $id)
    {
        try {
            DB::beginTransaction();

            $platform = $this->getInfo($id);

            $updateData = [
                'key' => $request->client_id,
                'secret' => $request->client_secret,
                'timezone' => $request->timezone,
                'calender_id' => $request->calender_id,
            ];

            if($request->status == STATUS_DEACTIVATE){
                $updateData['status'] = STATUS_DEACTIVATE;
            }

            $platform->update($updateData);

            DB::commit();

            if($request->status == STATUS_ACTIVE){
                $googleMeetController = new GoogleMeetController();
                return $googleMeetController->getClientToken($platform->key, $platform->secret);
            }
            return redirect()->route('admin.consultations.meeting_platforms.index')->with('success', getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('admin.consultations.meeting_platforms.index')->with('error', getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function getInfo($id)
    {
        return MeetingPlatform::where(['id' => $id])->first();
    }
}
