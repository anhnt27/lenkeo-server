<?php

namespace App\Repositories\Eloquent;

use App\Models\PlayerFindingTeam;
use App\Repositories\PlayerFindingTeamInterface;

class PlayerFindingTeamRepository extends AbstractRepository implements PlayerFindingTeamInterface
{
    /**
     *
     * @param \App\Models\PlayerFindingTeam $resource
     *
     * @internal param \App\Models\PlayerFindingTeam $resource
     */
    public function __construct(PlayerFindingTeam $resource)
    {
        parent::__construct($resource);
    }

    public function getFindingTeams($districtIds, $positionIds, $levelIds)
    {
        $query = $this->model
                ->join('district_player_finding_team', 'player_finding_teams.id', 'district_player_finding_team.player_finding_team_id');

        if($districtIds) {
            $query = $query->whereIn('district_player_finding_team.district_id', $districtIds);
        }

        if($positionIds) {
            $query = $query->whereIn('position_id', $positionIds);
        }

        if($levelIds) {
            $query = $query->whereIn('level_id', $levelIds);
        }
        
        return $query->select('player_finding_teams.*')->orderBy('player_finding_teams.created_at', 'desc')->distinct()->get();
    }


}
