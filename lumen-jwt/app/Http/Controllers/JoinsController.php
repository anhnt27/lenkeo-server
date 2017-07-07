<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Join;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\JoinInterface;
use App\Repositories\PlayerInterface;

class JoinsController extends Controller
{
    protected $joins;

    public function __construct(JoinInterface $joins, PlayerInterface $players)
    {
        $this->joins   = $joins;
        $this->players = $players;
    }

    public function addJoinTeam(Request $request)    
    {
        Log::info('addJoinTeam');
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;

            $email = $request->input('email');

            $playerOfTeam = $this->players->findOneByFields(['email' => $email]);

            if(!$playerOfTeam || !$playerOfTeam->team) {
                return ['code' => self::CODE_ERROR, 'message' => 'Thành viên với email không tồn tại!'];
            }

            if(!$playerOfTeam->team->is_finding_player) {
                return ['code' => self::CODE_ERROR, 'message' => 'Đội đã đủ thành viên!'];
            }

            $params = [
                'player_id' => $playerOfTeam->id,
                'status'    => Join::STATUS_SEND,
                'type'      => Join::TYPE_JOIN_TEAM,
                'team_id'   => $playerOfTeam->team->id,
            ];

            $this->joins->save($params);

            // @TODO
            // Notify Team Lead of this team.
            // Display this data on Info Page of this team.

            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
        }

    }
}