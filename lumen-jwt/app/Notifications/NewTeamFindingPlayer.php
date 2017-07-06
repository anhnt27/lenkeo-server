<?php

namespace App\Notifications;

use App\Models\TeamFindingPlayer;
use App\Models\NotificationSetting;
use App\Notifications\BaseNotification;
use Auth;
use Illuminate\Bus\Queueable;

/**
 * Class NewTeamFindingPlayer
 *
 * @package App\Notifications
 */
class NewTeamFindingPlayer extends BaseNotification
{
    use Queueable;

    /**
     * @var string
     */
    public  $message = 'Quan district_name - player_name dang tim position_name';
    /**
     * @var \App\Models\TeamFindingPlayer
     */
    private $teamFindingPlayer;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\TeamFindingPlayer $teamFindingPlayer
     */
    public function __construct(TeamFindingPlayer $teamFindingPlayer)
    {
        $this->teamFindingPlayer = $teamFindingPlayer;
    }

    /**
     * Insert notification to database
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->getMessageData();
    }

    public function getMessageData()
    {
        $msg = str_replace('district_name', $this->teamFindingPlayer->district_name, $this->message);
        $msg = str_replace('position_name', $this->teamFindingPlayer->position_name, $msg);
        $msg = str_replace('player_name', $this->teamFindingPlayer->player_name, $msg);
        return [
            'params'   => [
                'id'            => $this->teamFindingPlayer->id,
                'type'          => NotificationSetting::TYPE_FINDING_PLAYER,
            ],
            'template' => $msg
        ];
    }
}
