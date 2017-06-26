<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Player;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Repositories\PlayerInterface;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class PlayerController extends Controller
{
    protected $players;

    public function __construct(PlayerInterface $players)
    {
        $this->players = $players;
    }

    public function fireNotification()
    {
        $client = new Client(['headers' => ['Content-Type' => 'application/json', 'Authorization' => 'key=AIzaSyAAdhPer0nGT0tVIpOqWY2s5f56x_hXUa0']]); //GuzzleHttp\Client

        $params = [
                'to' => 'dqYXFHOghv4:APA91bGpE1T5kyi9ppaobIrtjAMz3_tiCqGatBMRmZK1BrOGjoXk4MTC9jGc4aEdSI34qvWoRHUTwA7bnxrk-QgyStSKVlkMN4j97BrIvNkQLNn9rstaoB4_n1FEz1J6bECVuXBv-Fr-',
                'notification' => [
                    'title' => 'Looking for Match',
                    'text' => 'ManU are looking for a match .from lumen'
                ]
            ];

            // dd(json_encode($params));
        $result = $client->post('https://fcm.googleapis.com/fcm/send', ['json' => $params]);
        dd($result);

    }

    public function postRegistration(Request $request)
    {
        info('was called');
        return json_encode('dam');
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'registration_id'     => 'required|string',
        ]);

        try {
            $email  = $request->input('email');
            $player = $this->players->findOneByFields(['email' => $email]);

            if(!$player) {
                return response()->json(['invalid_user']);
            }

            // update player gistration id;
            $registrationId  = $request->input('registration_id');
            $player->updateRegistrationId($registrationId);
        } catch(Exception $e) {
            info($e);
        }

        return response()->json('fuck yeah!');
    } 


    public function registration($email, $registrationId)
    {
        try {
            $player = $this->players->findOneByFields(['email' => $email]);

            if(!$player) {
                return response()->json(['invalid_user']);
            }

            // update player gistration id;
            $player->updateRegistrationId($registrationId);
        } catch(Exception $e) {
            info($e);
        }

        return response()->json('fuck yeah!');
    }
}