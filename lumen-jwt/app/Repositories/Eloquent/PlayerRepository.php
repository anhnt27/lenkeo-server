<?php

namespace App\Repositories\Eloquent;

use App\Models\Player;
use App\Repositories\PlayerInterface;

class PlayerRepository extends AbstractRepository implements PlayerInterface
{
    /**
     *
     * @param \App\Models\Player $resource
     *
     * @internal param \App\Models\Player $resource
     */
    public function __construct(Player $resource)
    {
        parent::__construct($resource);
    }
}
