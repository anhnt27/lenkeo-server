<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\TeamInterface;

class TeamRepository extends AbstractRepository implements TeamInterface
{
    /**
     *
     * @param \App\Models\Team $resource
     *
     */
    public function __construct(Team $resource)
    {
        parent::__construct($resource);
    }
}
