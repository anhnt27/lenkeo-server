<?php

namespace App\Notifications;

use Auth;
use App\Models\TeamFindingMatch;
use App\Models\NotificationSetting;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;

/**
 * Class NewTeamFindingMatch
 *
 * @package App\Notifications
 */
class NewTeamFindingMatch extends BaseNotification
{
    use Queueable;

    /**
     * @var string
     */
    public  $message = '[Quận district_name] - player_name đang tìm kèo';
    /**
     * @var \App\Models\TeamFindingMatch
     */
    private $teamFindingMatch;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\TeamFindingMatch $teamFindingMatch
     */
    public function __construct(TeamFindingMatch $teamFindingMatch)
    {
        $this->teamFindingMatch = $teamFindingMatch;
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

    public function getReplacedMessage()
    {
        $msg = str_replace('district_name', $this->teamFindingMatch->district_name, $this->message);
        $msg = str_replace('player_name', $this->teamFindingMatch->player_name, $msg);
        return $msg;
    }
    
    public function getMessageData()
    {
        $msg = $this->getReplacedMessage();
        
        return [
            'params'   => [
                'id'            => $this->teamFindingMatch->id,
                'type'          => NotificationSetting::TYPE_FINDING_MATCH,
            ],
            'template' => $msg
        ];
    }
}
