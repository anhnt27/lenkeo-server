<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\TeamInterface;
use App\Repositories\PlayerInterface;

class TeamsController extends Controller
{
    protected $teams;
    protected $players;

    public function __construct(TeamInterface $teams, PlayerInterface $players)
    {
        $this->teams = $teams;
        $this->players = $players;
    }

    public function create(Request $request)
    {
        Log::info('create Team');
        try {
            $player = Auth::user();

            if(! $player) return self::ERROR_RETURN;
            
            if($player->team) {
                Log::info('Player can not be in 2 teams');
                return self::ERROR_RETURN;
            }

            $params = [
                'name'              => $request->input('name'),
                'city_id'           => $request->input('cityId'),
                'message'           => $request->input('message'),
                'average_age'       => $request->input('averageAge'),
                'district_id'       => $request->input('districtId'),
                'number_of_member'  => $request->input('numberOfMember'),
                'usual_match_time'  => $request->input('usualMatchTime'),
                'is_finding_member' => $request->input('isFindingMember'),
            ];

            $team = $this->teams->save($params);

            if(! $team) return self::ERROR_RETURN;
            
            $team->players()->save($player);

            $playerParams = [
                'id' => $player->id,
                'is_belong_team' => 1,
                'is_team_lead' => 1,
            ];
            $this->players->save($playerParams);

            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function update(Request $request)
    {
        Log::info('update Team');
        try {
            $player = Auth::user();

            if(! $player) return self::ERROR_RETURN;

            $params = [
                'id'                => $request->input('id'),
                'name'              => $request->input('name'),
                'city_id'           => $request->input('cityId'),
                'message'           => $request->input('message'),
                'average_age'       => $request->input('averageAge'),
                'district_id'       => $request->input('districtId'),
                'number_of_member'  => $request->input('numberOfMember'),
                'usual_match_time'  => $request->input('usualMatchTime'),
                'is_finding_member' => $request->input('isFindingMember'),
            ];

            $team = $this->teams->save($params);

            if(! $team) return self::ERROR_RETURN;
            
            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }

    }

    public function getTeam()
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;

            return $player->team;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }  

    public function getTeamById($id)
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;

            return $this->teams->findById($id);
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function getTeamPlayers()
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;

            $team =  $player->team;
            if(! $team) return self::ERROR_RETURN;

            return $team->players;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }

    }

    public function removeMember(Request $request)
    {
        try {
            $player = Auth::user();

            if(! $player) return self::ERROR_RETURN;
            
            if(!$player->team) {
                return self::ERROR_RETURN;
            }

            $beingRemovedPlayer = $this->players->findById($request->input('id'));
            if(!$beingRemovedPlayer) {
                return self::ERROR_RETURN; 
            }

            $beingRemovedPlayer->team()->dissociate();
            $beingRemovedPlayer->save();

            // NOTIFICATION

            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }

    }


}