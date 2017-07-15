<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use ApiResponse;
use Illuminate\Http\Request;
use App\Repositories\NotificationInterface;

class NotificationsController extends Controller
{
    /**
     * @var \App\Repositories\NotificationInterface
     */
    protected $notifications;

    /**
     * @param NotificationInterface $notificationInterface
     */
    public function __construct(
        NotificationInterface $notificationInterface
    )
    {
        $this->notifications = $notificationInterface;
    }

    public function getNotifications() 
    {
        $player = Auth::user();

        $notifications = $player->notifications;

        return $notifications;
    }

    public function countUnreadNotifications() 
    {
        $player = Auth::user();
        
        if(!$player) return 0;

        $count = $this->notifications->countUnreadNotifications($player->id);

        return $count;
    }

    public function updateNotificationIsRead(Request $request)
    {
        $id = $request->input('id');
        $notification = $this->notifications->findById($id);

        // return $notification;
        $notification->markAsRead();
        return self::SUCCESS_RETURN;
    }

    /**
     * Get all notifications belongs to User
     *
     * @return \Response
     */
    public function getUnread()
    {
        $user = Auth::user();

        $notifications = $this->notifications->getNotifications($user);

        return $notifications;
    }

    /**
     * Mark a particular notification as has been read
     *
     * @return \Response
     */
    public function markAsRead()
    {
        $notificationId = $this->notifications->getRequest()->getInputData()['id'];

        $user = Auth::user();
        if (!$user) {
            $this->response->errorWrongArgs('Invalid user');
        }

        $result = $this->notificationRepository->markAsRead($notificationId, $user);

        if (!$result) {
            $this->response->errorWrongArgs('Notification cannot be marked as read');
        }

        $notifications = $this->notificationRepository->getNotifications($user);

        return $this->response->setStatusCode(200)->withArray($notifications);
    }
}
