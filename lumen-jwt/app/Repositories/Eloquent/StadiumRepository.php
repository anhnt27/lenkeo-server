<?php

namespace App\Repositories\Eloquent;

use App\Models\Stadium;
use App\Repositories\StadiumInterface;

class StadiumRepository extends AbstractRepository implements StadiumInterface
{
    /**
     *
     * @param \App\Models\Stadium $resource
     *
     * @internal param \App\Models\Stadium $resource
     */
    public function __construct(Stadium $resource)
    {
        parent::__construct($resource);
    }

    public function getStadiumsByDistrict($districtId)
    {
        return $this->model
                ->where('district_id', $districtId)
                ->get();
    }
}
