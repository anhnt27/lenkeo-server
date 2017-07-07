<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\SettingInterface;

class SettingRepository extends AbstractRepository implements SettingInterface
{
    /**
     *
     * @param \App\Models\Setting $resource
     *
     */
    public function __construct(Setting $resource)
    {
        parent::__construct($resource);
    }
}
