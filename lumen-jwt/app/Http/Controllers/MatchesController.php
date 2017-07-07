<?php

namespace App\Http\Controllers;

use Auth;
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
            if(! $player) return self::ERROR_RETURN;
            if($player->team) {
                Log::info('Player can not be in 2 matches');
                return self::ERROR_RETURN;
            }

            $params = [
                'name'              => $request->input('name'),
                'message'           => $request->input('message'),
                'average_age'       => $request->input('averageAge'),
                'district_id'       => $request->input('districtId'),
                'number_of_member'  => $request->input('numberOfMember'),
                'usual_match_time'  => $request->input('usualMatchTime'),
                'is_finding_member' => $request->input('isFindingMember'),
            ];

            $team = $this->matches->save($params);

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

}