<?php

namespace App\Repositories\Eloquent;

use DB;
use Auth;
use App\Models\Player;
use App\Models\NotificationSetting;
use App\Repositories\PlayerInterface;

class PlayerRepository extends AbstractRepository implements PlayerInterface
{
    /**
     *
     * @param \App\Models\Player $resource
     *
     * @internal param \App\Models\Player $resource
     */
    public function __construct(Player $resource)
    {
        parent::__construct($resource);
    }

    public function getFindingTeamReceivers($playerFindingTeam)
    {
        $logginedUserId = Auth::id();
        
        $levelId      = $playerFindingTeam->level_id;
        $positionId   = $playerFindingTeam->position_id;
        $groundTypeId = $playerFindingTeam->ground_type_id;
        $districtIds  = $playerFindingTeam->districts->pluck('id')->toArray();

        return $this->model
            ->join('notification_settings', 'notification_settings.player_id', 'players.id')
            ->join('level_notification_setting', 'level_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('position_notification_setting', 'position_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('district_notification_setting', 'district_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('ground_type_notification_setting', 'ground_type_notification_setting.notification_setting_id', 'notification_settings.id')
            ->where('players.is_receive_player_finding_team', 1)
            ->where('notification_settings.type', NotificationSetting::TYPE_FINDING_TEAM)
            ->where('level_notification_setting.level_id', $levelId)
            ->where('position_notification_setting.position_id', $positionId)
            ->where('ground_type_notification_setting.ground_type_id', $groundTypeId)
            ->whereIn('district_notification_setting.district_id', $districtIds)
            ->where('players.id', '<>', $logginedUserId)
            ->select('players.*')
            ->distinct()
            ->get();
    }

    public function getFindingMatchReceivers($teamFindingMatch)
    {
        $logginedUserId = Auth::id();

        $levelId      = $teamFindingMatch->level_id;
        $groundTypeId = $teamFindingMatch->ground_type_id;
        $districtIds  = $teamFindingMatch->districts->pluck('id')->toArray();

        // dd($levelId, $districtIds );
        $query = $this->model
            ->join('notification_settings', 'notification_settings.player_id', 'players.id')
            ->join('level_notification_setting', 'level_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('district_notification_setting', 'district_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('ground_type_notification_setting', 'ground_type_notification_setting.notification_setting_id', 'notification_settings.id')
            ->where('players.is_receive_team_finding_match', 1)
            ->where('notification_settings.type', NotificationSetting::TYPE_FINDING_MATCH)
            ->where('level_notification_setting.level_id', $levelId)
            ->where('ground_type_notification_setting.ground_type_id', $groundTypeId)
            ->whereIn('district_notification_setting.district_id', $districtIds)
            ->where('players.id', '<>', $logginedUserId)
            ->select('players.*')
            ->distinct();

        return $query->get();
    }

    public function getFindingPlayerReceivers($teamFindingPlayer)
    {
        $logginedUserId = Auth::id();

        $levelId      = $teamFindingPlayer->level_id;
        $positionId   = $teamFindingPlayer->position_id;
        $districtId   = $teamFindingPlayer->district_id;
        $groundTypeId = $teamFindingPlayer->ground_type_id;

        $query = $this->model
            ->join('notification_settings', 'notification_settings.player_id', 'players.id')
            ->join('level_notification_setting', 'level_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('position_notification_setting', 'position_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('district_notification_setting', 'district_notification_setting.notification_setting_id', 'notification_settings.id')
            ->join('ground_type_notification_setting', 'ground_type_notification_setting.notification_setting_id', 'notification_settings.id')
            ->where('players.is_receive_team_finding_player', 1)
            ->where('notification_settings.type', NotificationSetting::TYPE_FINDING_PLAYER)
            ->where('level_notification_setting.level_id', $levelId)
            ->where('position_notification_setting.position_id', $positionId)
            ->where('district_notification_setting.district_id', $districtId)
            ->where('ground_type_notification_setting.ground_type_id', $groundTypeId)
            ->where('players.id', '<>', $logginedUserId)
            ->select('players.*')
            ->distinct();

        return $query->get();
    }

    public function getLeaderOfTeam(int $teamId)
    {
        return $this->model
            ->where('team_id', $teamId)
            ->where('is_team_lead', 1)
            ->first();
    }
}

