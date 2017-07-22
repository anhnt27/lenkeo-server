<?php

namespace App\Http\Controllers;

use Auth;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\PlayerInterface;
use App\Notifications\NewPlayerFindingTeam;
use App\Repositories\PlayerFindingTeamInterface;

class PlayerFindingTeamsController  extends Controller
{
    protected $players;
    protected $playerFindingTeams;

    public function __construct(PlayerFindingTeamInterface $playerFindingTeams, PlayerInterface $players)
    {
        $this->players            = $players;
        $this->playerFindingTeams = $playerFindingTeams;
    }

    public function getFindingTeams($districtIds, $positionIds, $levelIds)
    {
        Log::info('getFindingTeams', compact('districtIds', 'positionIds', 'levelIds'));
        $levelIds    = $this->processFilterParams($levelIds);
        $districtIds = $this->processFilterParams($districtIds);
        $positionIds = $this->processFilterParams($positionIds);

        $playerFindingTeams =  $this->playerFindingTeams->getFindingTeams($districtIds, $positionIds, $levelIds);
        return $playerFindingTeams;
    }

    public function getFindingTeamById(int $id) 
    {
        Log::info('getFindingTeamById', compact('id'));
        $playerFindingTeam = $this->playerFindingTeams->findById($id);

        return $playerFindingTeam;
    }

    public function addFindingTeam(Request $request)
    {
        Log::info('addFindingTeam', $request->all());
        $this->validate($request, [
            'districtIds'  => 'required',
            'positionId'  => 'required|numeric',
            'levelId'     => 'required|numeric',
            'phoneNumber' => 'required|numeric',
        ]);
        try {
            $player = Auth::user();
            if(! $player) {
                Log::error('addFindingPlayer : Can not find Authenticated Player');
                return self::ERROR_RETURN;;
            }

            $params = [
                'player_id'      => $player->id,
                'by_admin'       => $player->is_admin,
                'message'        => $request->input('message'),
                'level_id'       => $request->input('levelId'),
                'date'           => $request->input('matchDate'),
                'position_id'    => $request->input('positionId'),
                'fb_name'        => $request->input('contactName'),
                'expired_date'   => $request->input('expiredDate'),
                'phone_number'   => $request->input('phoneNumber'),
            ];
            $playerFindingTeam = $this->playerFindingTeams->save($params);

            $selectedDistrictIds = $request->input('districtIds');

            if($selectedDistrictIds && is_array($selectedDistrictIds)) {
                $selectedDistrictIds = array_map('intval', $selectedDistrictIds);
            } else {
                $selectedDistrictIds = [intval($selectedDistrictIds)];
            }

            $playerFindingTeam->districts()->attach($selectedDistrictIds);

            $this->sendNotification($playerFindingTeam);
        } catch (Exception $e) {
            Log::error('addFindingPlayer', compact('e'));
            return self::ERROR_RETURN;;
        }

        return self::SUCCESS_RETURN;
    }

    public function sendNotification($playerFindingTeam)
    {
        $players = $this->players->getFindingTeamReceivers($playerFindingTeam);
        // dd($players->toArray());
        Notification::send($players, new NewPlayerFindingTeam($playerFindingTeam));
    }
   
}