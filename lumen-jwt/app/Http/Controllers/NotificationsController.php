<?php

namespace App\Http\Controllers;

use ApiResponse;
use App\Repositories\NotificationInterface;
use Auth;

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
        $user = Auth::user();

        $notifications = $user->notifications;

        return $notifications;
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
