<?php

namespace App\Repositories\Eloquent;

use App\Models\TeamFindingPlayer;
use App\Repositories\TeamFindingPlayerInterface;

class TeamFindingPlayerRepository extends AbstractRepository implements TeamFindingPlayerInterface
{
    /**
     *
     * @param \App\Models\TeamFindingPlayer $resource
     *
     * @internal param \App\Models\TeamFindingPlayer $resource
     */
    public function __construct(TeamFindingPlayer $resource)
    {
        parent::__construct($resource);
    }

    public function getFindingPlayers($districtIds, $positionIds, $levelIds)
    {
        $query = $this->model;
                    
        if($districtIds) {
            $query = $query->whereIn('district_id', $districtIds);
        }

        if($positionIds) {
            $query = $query->whereIn('position_id', $positionIds);
        }

        if($levelIds) {
            $query = $query->whereIn('level_id', $levelIds);
        }
        return $query->get();
    }
}
