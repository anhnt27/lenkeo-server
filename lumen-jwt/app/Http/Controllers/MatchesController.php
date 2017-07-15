<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\MatchInterface;
use App\Repositories\PlayerInterface;

class MatchesController extends Controller
{
    protected $matches;

    public function __construct(MatchInterface $matches, PlayerInterface $players)
    {
        $this->matches = $matches;
        $this->players = $players;
    }

    public function create(Request $request)
    {
        Log::info('create Match');
        try {
            $player = Auth::user();

            if(! $player)               return self::ERROR_RETURN;
            if(! $player->team)         return self::ERROR_RETURN;

            $params = [
                'team_id'    => $player->team->id,
                'to'         => $request->input('to'),
                'from'       => $request->input('from'),
                'message'    => $request->input('message'),
                'address'    => $request->input('address'),
                'match_date' => $request->input('matchDate'),
            ];
            $match = $this->matches->save($params);

            if(! $match) return self::ERROR_RETURN;
            
            // init match_player table
            $playerIds = $player->team->players->pluck('id');

            $match->players()->sync($playerIds);

            // Handle notification to team member

            // Handle team member response (first time yes/no,  later : toggle)


            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function getMatches()
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            
            $team = $player->team;
            if(! $team) return self::ERROR_RETURN;

            return $this->matches->getMatches($team->id);

            return $team->matches->sortByDesc('created_at');
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function getMatchById($id)
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            
            $match             = $this->matches->findById($id);
            $players           = $this->matches->getPlayers($match->id);
            $confirmStatusData = $this->matches->confirmStatus($match->id, $player->id);

            return ['match' => $match, 'players' => $players, 'confirmStatusData' => $confirmStatusData];
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function confirmJoinMatch(Request $request)
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;

            $matchId       = $request->input('matchId');
            $confirmStatus = $request->input('confirmStatus');

            $player->matches()->updateExistingPivot($matchId, ['confirm_status' => $confirmStatus]);
            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }
}