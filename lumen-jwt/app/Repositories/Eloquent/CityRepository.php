<?php

namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Repositories\CityInterface;

class CityRepository extends AbstractRepository implements CityInterface
{
    /**
     *
     * @param \App\Models\City $resource
     *
     */
    public function __construct(City $resource)
    {
        parent::__construct($resource);
    }
}
