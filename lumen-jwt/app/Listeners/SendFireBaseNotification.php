<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NewTeamFindingPlayer;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Notifications\Events\NotificationSent;

class SendFireBaseNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        info('send firebase event is being handled');
        $notifiable = $event->notifiable;
        $notification = $event->notification;

        if(! $notifiable  || !$notification) {  
            info('notifiable or notification are not exist');
            return;  
        }

        if(! $notifiable->registration_id) {
            info('notifiable don not have registration id');
            return ;
        }

        $title = 'Len Keo';
        $msg = $notification->getReplacedMessage();

        $this->send($title, $msg, $notifiable->registration_id);

    }

    public function send($title, $message, $registrationId) 
    {
        info('sending fire base notification to: '. $registrationId);
        $client = new Client(['headers' => ['Content-Type' => 'application/json', 'Authorization' => 'key=AIzaSyAAdhPer0nGT0tVIpOqWY2s5f56x_hXUa0']]);

        $params = [
            'to' => $registrationId,
            'notification' => [
                'title' => $title,
                'text' =>  $message
            ]
        ];

        $result = $client->post('https://fcm.googleapis.com/fcm/send', ['json' => $params]);
    }
}
