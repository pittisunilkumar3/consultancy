<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSeen;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notificationView($id)
    {
        $data['pageTitle'] = __('Notification Details');
        $data['notification'] = Notification::find($id);

        if($data['notification'] !=null){
            $dataArray = [
                'user_id'=> $data['notification']->user_id,
                'notification_id'=> $data['notification']->id,
            ];
            NotificationSeen::firstOrCreate($dataArray);
            $data['notification']->update(['view_status' => 1]);
        }
        return view('admin.notifications.single', $data);
    }

    public function allNotification(){
        $data['pageTitle'] = __('All Notification');
        $data['activeNotification'] = 'active';
        $data['notifications'] = Notification::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(5);
        return view('admin.notifications.all', $data);
    }

    public function notificationMarkAsRead($id){
        DB::beginTransaction();
        try {
            $notificationData = Notification::where('id','=',$id)->first();
            $notificationData->update(['view_status' => 1]);
            $dataArray = [
                'user_id'=> $notificationData->user_id,
                'notification_id'=> $notificationData->id,
            ];

            NotificationSeen::firstOrCreate($dataArray);

            DB::commit();
            return redirect()->back()->with('success', UPDATED_SUCCESSFULLY);
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);

        }

    }
}
