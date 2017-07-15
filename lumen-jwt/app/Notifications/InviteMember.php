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
class InviteMember extends BaseNotification
{
    use Queueable;

    /**
     * @var string
     */
    public  $message = 'team_name mời bạn gia nhập đội.';
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

    public function getMessageData()
    {
        $msg = str_replace('team_name', $this->join->team_name, $this->message);
        return [
            'params'   => [
                'id'        => $this->join->id,
                'team_id'   => $this->join->team_id,
                'player_id' => $this->join->player_id,
                'type'      => NotificationSetting::TYPE_INVITE_MEMBER,
            ],
            'template' => $msg
        ];
    }
}
