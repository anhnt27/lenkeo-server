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

    public function getPlayers($matchId)
    {
        return $this->model
            ->join('match_player', '.match_player.match_id', 'matches.id')
            ->join('players', '.players.id', 'match_player.player_id')
            ->where('matches.id', $matchId)
            ->select('players.name', 'match_player.confirm_status')
            ->orderBy('confirm_status', 'desc')
            ->get();
    }

    public function confirmStatus($matchId, $playerId)
    {
        return $this->model
            ->join('match_player', '.match_player.match_id', 'matches.id')
            ->join('players', '.players.id', 'match_player.player_id')
            ->where('matches.id', $matchId)
            ->where('players.id', $playerId)
            ->select('players.name', 'match_player.confirm_status')
            ->orderBy('confirm_status', 'desc')
            ->first();
    }

    public function getMatches(int $teamId)
    {
        return $this->model
                ->where('team_id', $teamId)
                ->orderBy('created_at', 'desc')
                ->get();
    }
}
