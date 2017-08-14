<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
class NotificationController extends AdminController
{
    public function getNotification(Request $request)
    {

        $unreadCount = Notification::where('unread', 1)->count();
        $notifications = Notification::orderBy('created_at', 'desc');

        if ($request->has('since')) {
            $notifications = $notifications->where('created_at', '>=', Carbon::createFromTimeStamp((int) $request->input('since')));
        } else {
            $notifications = $notifications->take((int) $request->input('limit', 10));
        }

        $notifications = $notifications->get();

        $notifications->map(function ($notification) {
            $notification->link = route('Admin::notification@detailNotification', [
                $notification->id
            ]);

            return $notification;
        });

        return ['data' => $notifications, 'metadata' => ['unreadCount' => $unreadCount]];
    }
    public function detailNotification(Request $request,$id) {
        $notification = Notification::findOrFail($id);
        $notification->unread= 0;
        $notification->save();
        return view('admin.notification.detail',compact('notification'));
    }
    public function getAll(){
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notification.list',compact('notifications'));
    }
}
