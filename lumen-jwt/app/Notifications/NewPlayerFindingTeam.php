<?php

namespace App\Notifications;

use Auth;
use Illuminate\Bus\Queueable;
use App\Models\PlayerFindingTeam;
use App\Models\NotificationSetting;
use App\Notifications\BaseNotification;

/**
 * Class NewPlayerFindingTeam
 *
 * @package App\Notifications
 */
class NewPlayerFindingTeam extends BaseNotification
{
    use Queueable;

    /**
     * @var string
     */
    public  $message = 'Quan district_name - position_name : player_name dang tim doi';

    /**
     * @var \App\Models\PlayerFindingTeam
     */
    private $playerFindingTeam;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\PlayerFindingTeam $playerFindingTeam
     */
    public function __construct(PlayerFindingTeam $playerFindingTeam)
    {
        $this->playerFindingTeam = $playerFindingTeam;
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
        $msg = str_replace('district_name', $this->playerFindingTeam->district_name, $this->message);
        $msg = str_replace('position_name', $this->playerFindingTeam->position_name, $msg);
        $msg = str_replace('player_name', $this->playerFindingTeam->player_name, $msg);

        return [
            'params'   => [
                'id'   => $this->playerFindingTeam->id,
                'type' => NotificationSetting::TYPE_FINDING_TEAM,
            ],
            'template' => $msg
        ];
    }
}
