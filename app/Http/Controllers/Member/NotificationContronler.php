<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationContronler extends Controller
{
    use ApiResponse;
    public function getUnreadCount(Request $request)
    {
        $count = $request->user()->unreadNotifications()->count();

        return $this->responseJson(true, 'Thành công', null, ['count' => $count]);
    }

    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->limit(10)->get();


        return $this->responseJson(true, 'Lấy danh sách thành công',200, $notifications,200);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return $this->responseJson(true, 'Đã đánh dấu đọc', 200,$notification, 200);
    }
}
