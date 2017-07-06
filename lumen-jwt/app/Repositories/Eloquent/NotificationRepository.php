<?php

namespace App\Repositories\Eloquent;

use Auth;
use App\Models\Notification;
use App\Repositories\NotificationInterface;
// use Notification as NotificationSender;

/**
 * Class NotificationRepository
 *
 * @package App\Repositories\Eloquent
 */
class NotificationRepository extends AbstractRepository implements NotificationInterface
{
    /**
     * Create a new Db NotificationRepository instance.
     *
     * @param \App\Models\Notification $resource
     *
     * @internal param \App\Models\Notification $resource
     */
    public function __construct(Notification $resource)
    {
        parent::__construct($resource);
    }

    public function getNotifications($user) 
    {
        // return this-
    }
}
