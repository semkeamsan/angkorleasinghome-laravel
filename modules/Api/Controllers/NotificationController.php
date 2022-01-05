<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Models\NotificationPush;


class NotificationController extends Controller
{
    public function index()
    {
        $checkNotify = NotificationPush::query();
        if (is_admin()) {
            $checkNotify->where(function ($query) {
                $query->where('data', 'LIKE', '%"for_admin":1%');
                $query->orWhere('notifiable_id', auth()->id());
            });
        } else {
            $checkNotify->where('data', 'LIKE', '%"for_admin":0%');
            $checkNotify->where('notifiable_id', auth()->id());
        }
        $notifications = $checkNotify->orderBy('created_at', 'desc')->limit(5)->get();
        $countUnread = $checkNotify->where('read_at', null)->count();
        return $this->sendSuccess([
            'unread_notification' => $countUnread,
            'notification' => $notifications
        ]);
    }

    public function allNotification()
    {
        $checkNotify = NotificationPush::query();
        if (is_admin()) {
            $checkNotify->where(function ($query) {
                $query->where('data', 'LIKE', '%"for_admin":1%');
                $query->orWhere('notifiable_id', auth()->id());
            });
        } else {
            $checkNotify->where('data', 'LIKE', '%"for_admin":0%');
            $checkNotify->where('notifiable_id', auth()->id());
        }
        $notifications = $checkNotify->orderBy('created_at', 'desc')->get();
        return $this->sendSuccess([
            'notification' => $notifications
        ]);
    }
}
