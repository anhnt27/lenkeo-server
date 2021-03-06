<?php

namespace App\Http\Controllers;

use Auth;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\PlayerInterface;
use App\Notifications\NewTeamFindingPlayer;
use App\Repositories\TeamFindingPlayerInterface;

class TeamFindingPlayersController  extends Controller
{
    protected $players;
    protected $teamFindingPlayers;

    public function __construct(TeamFindingPlayerInterface $teamFindingPlayers, PlayerInterface $players)
    {
        $this->players            = $players;
        $this->teamFindingPlayers = $teamFindingPlayers;
    }

    public function getFindingPlayers($districtIds, $positionIds, $levelIds)
    {
        Log::info('getFindingPlayers', compact('districtIds', 'positionIds', 'levelIds'));
        $levelIds    = $this->processFilterParams($levelIds);
        $districtIds = $this->processFilterParams($districtIds);
        $positionIds = $this->processFilterParams($positionIds);

        $teamFindingPlayers =  $this->teamFindingPlayers->getFindingPlayers($districtIds, $positionIds, $levelIds);
        return $teamFindingPlayers;
    }

    public function getFindingPlayerById(int $id) 
    {
        Log::info('getFindingPlayerById', compact('id'));
        $teamFindingPlayer = $this->teamFindingPlayers->findById($id);
        return $teamFindingPlayer;
    }


    public function addFindingPlayer(Request $request)
    {
        Log::info('addFindingPlayer', $request->all());
        $this->validate($request, [
                'districtId'  => 'required|numeric',
                'positionId'  => 'required|numeric',
                'levelId'     => 'required|numeric',
                'phoneNumber' => 'required|numeric',
            ]);
        try {
            $player = Auth::user();
            if( ! $player) {
                Log::error('addFindingPlayer : Can not find Authenticated Player');
                return self::ERROR_RETURN;
            }

            $params = [
                'player_id'      => $player->id,
                'by_admin'       => $player->is_admin,
                'message'        => $request->input('message'),
                'address'        => $request->input('address'),
                'level_id'       => $request->input('levelId'),
                'district_id'    => $request->input('districtId'),
                'position_id'    => $request->input('positionId'),
                'fb_name'        => $request->input('contactName'),
                'phone_number'   => $request->input('phoneNumber'),
                'ground_type_id' => $request->input('groundTypeId'),
                'needing_number' => $request->input('needingNumber'),
                'date'           => $request->input('matchDate'),
                'expired_date'   => $request->input('expiredDate'),
                'time'           => $request->input('matchHour'),
            ];

            $teamFindingPlayer = $this->teamFindingPlayers->save($params);

            $this->sendNotification($teamFindingPlayer);
        } catch (Exception $e) {
            Log::error('addFindingPlayer', compact('e'));
            return self::ERROR_RETURN;
        }

        return self::SUCCESS_RETURN;
    }

    public function sendNotification($teamFindingPlayer)
    {
        $players = $this->players->getFindingPlayerReceivers($teamFindingPlayer);
        Notification::send($players, new NewTeamFindingPlayer($teamFindingPlayer));
    }
   
}