<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * Class BaseModel
 *
 * @package App\Models
 */
class BaseNotification extends Notification
{
    /**
     * Notification message for particular notification type
     *
     * @var string
     */
    protected $message = 'Missing notification message template.';

    /**
     * @var string
     */
    protected $redirectUrl = '';

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
}
