<?php

namespace App\Repositories\Eloquent;

use App\Models\Match;
use App\Repositories\MatchInterface;

class MatchRepository extends AbstractRepository implements MatchInterface
{
    /**
     *
     * @param \App\Models\Match $resource
     *
     */
    public function __construct(Match $resource)
    {
        parent::__construct($resource);
    }
}
