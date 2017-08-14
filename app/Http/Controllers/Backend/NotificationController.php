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

//        $notifications->map(function ($notification) {
//            $notification->link = route('Staff::notification@read', [
//                $notification->id
//            ]);
//
//            return $notification;
//        });

        return ['data' => $notifications, 'metadata' => ['unreadCount' => $unreadCount]];
    }
}
