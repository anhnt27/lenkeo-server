<?php

namespace App\Repositories\Eloquent;

use App\Models\NotificationSetting;
use App\Repositories\NotificationSettingInterface;

class NotificationSettingRepository extends AbstractRepository implements NotificationSettingInterface
{
    /**
     *
     * @param \App\Models\NotificationSetting $resource
     *
     */
    public function __construct(NotificationSetting $resource)
    {
        parent::__construct($resource);
    }
}
