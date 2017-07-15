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

    public function getJoiningTeamRequests(int $teamId)
    {
        return $this->model
            ->where('team_id', $teamId)
            ->where('type', Join::TYPE_JOIN_TEAM)
            ->get();
    }

    public function getInviteRequest(int $playerId)
    {
        return $this->model
            ->where('player_id', $playerId)
            ->where('type', Join::TYPE_INVITE_MEMBER)
            ->get();
    }
}
