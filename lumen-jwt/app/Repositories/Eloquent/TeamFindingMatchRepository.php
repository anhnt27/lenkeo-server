<?php

namespace App\Repositories\Eloquent;

use App\Models\TeamFindingMatch;
use App\Repositories\TeamFindingMatchInterface;

class TeamFindingMatchRepository extends AbstractRepository implements TeamFindingMatchInterface
{
    /**
     *
     * @param \App\Models\TeamFindingMatch $resource
     *
     * @internal param \App\Models\TeamFindingMatch $resource
     */
    public function __construct(TeamFindingMatch $resource)
    {
        parent::__construct($resource);
    }

    public function getFindingMatchs($districtIds, $levelIds)
    {
        $query = $this->model
                ->join('district_team_finding_match', 'team_finding_matches.id', 'district_team_finding_match.team_finding_match_id');

        if($districtIds) {
            $query = $query->whereIn('district_team_finding_match.district_id', $districtIds);
        }

        if($levelIds) {
            $query = $query->whereIn('level_id', $levelIds);
        }
        
        return $query->select('team_finding_matches.*')->distinct()->get();
    }
}
