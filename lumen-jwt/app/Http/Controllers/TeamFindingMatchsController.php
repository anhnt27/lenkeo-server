<?php

namespace App\Http\Controllers;

use Auth;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\PlayerInterface;
use App\Notifications\NewTeamFindingMatch;
use App\Repositories\TeamFindingMatchInterface;

class TeamFindingMatchsController  extends Controller
{
    protected $players;
    protected $teamFindingMatchs;

    public function __construct(TeamFindingMatchInterface $teamFindingMatchs, PlayerInterface $players)
    {
        $this->players           = $players;
        $this->teamFindingMatchs = $teamFindingMatchs;
    }

    public function getFindingMatchs($districtIds, $levelIds)
    {
        Log::info('getFindingMatchs', compact('districtIds', 'levelIds'));
        $levelIds    = $this->processFilterParams($levelIds);
        $districtIds = $this->processFilterParams($districtIds);

        $teamFindingMatchs =  $this->teamFindingMatchs->getFindingMatchs($districtIds, $levelIds);
        return $teamFindingMatchs;
    }

    public function getFindingMatchById(int $id) 
    {
        Log::info('getFindingMatchById', compact('id'));
        $teamFindingMatch = $this->teamFindingMatchs->findById($id);
        return $teamFindingMatch;
    }


    public function addFindingMatch(Request $request)
    {
        Log::info('addFindingMatch', $request->all());
        $this->validate($request, [
                'levelId'     => 'required|numeric',
                'phoneNumber' => 'required|numeric',
            ]);
        try {
            $player = Auth::user();
            if( ! $player) {
                Log::error('addFindingMatch : Can not find Authenticated Player');
                return self::ERROR_RETURN;;
            }

            $params = [
                'player_id'      => $player->id,
                'by_admin'       => $player->is_admin,
                'message'        => $request->input('message'),
                'address'        => $request->input('address'),
                'level_id'       => $request->input('levelId'),
                'date'           => $request->input('matchDate'),
                'time'           => $request->input('matchHour'),
                'fb_name'        => $request->input('contactName'),
                'phone_number'   => $request->input('phoneNumber'),
                'expired_date'   => $request->input('expiredDate'),
                'ground_type_id' => $request->input('groundTypeId'),
            ];

            $teamFindingMatch = $this->teamFindingMatchs->save($params);

            $selectedDistrictIds = $request->input('districtIds');

            if($selectedDistrictIds && is_array($selectedDistrictIds)) {
                $selectedDistrictIds = array_map('intval', $selectedDistrictIds);
            } else {
                $selectedDistrictIds = [intval($selectedDistrictIds)];
            }

            $teamFindingMatch->districts()->attach($selectedDistrictIds);

            $this->sendNotification($teamFindingMatch);
        } catch (Exception $e) {
            Log::error('addFindingMatch', compact('e'));
            return self::ERROR_RETURN;
        }

        return self::SUCCESS_RETURN;
    }

    public function sendNotification($teamFindingMatch)
    {
        $players = $this->players->getFindingMatchReceivers($teamFindingMatch);
        info('sending notification. TeamFindingMatch, number of receiver:' . count($players));
        // dd($players);
        Notification::send($players, new NewTeamFindingMatch($teamFindingMatch));
    }
   
}