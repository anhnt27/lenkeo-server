<?php

namespace App\Repositories\Eloquent;

use App\Models\Join;
use App\Repositories\JoinInterface;

class JoinRepository extends AbstractRepository implements JoinInterface
{
    /**
     *
     * @param \App\Models\Join $resource
     *
     */
    public function __construct(Join $resource)
    {
        parent::__construct($resource);
    }
}
