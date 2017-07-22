<?php

namespace App\Notifications;

use Auth;
use App\Models\Join;
use Illuminate\Bus\Queueable;
use App\Models\NotificationSetting;
use App\Notifications\BaseNotification;

/**
 *
 * @package App\Notifications
 */
class JoinTeam extends BaseNotification
{
    use Queueable;

    /**
     * @var string
     */
    public  $message = 'player_name muốn gia nhập đội.';
    /**
     * @var \App\Models\join
     */
    private $join;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Join $join
     */
    public function __construct(Join $join)
    {
        $this->join = $join;
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
        $msg = str_replace('player_name', $this->join->player_name, $this->message);
        return $msg;
    }

    public function getMessageData()
    {
        $msg = $this->getReplacedMessage();

        return [
            'params'   => [
                'id'        => $this->join->id,
                'team_id'   => $this->join->team_id,
                'player_id' => $this->join->player_id,
                'type'      => NotificationSetting::TYPE_JOIN_TEAM,
            ],
            'template' => $msg
        ];
    }
}
