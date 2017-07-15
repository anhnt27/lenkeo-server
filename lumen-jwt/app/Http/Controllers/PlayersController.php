<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\Player;
use GuzzleHttp\Client;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\PlayerInterface;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class PlayersController extends Controller
{
    protected $jwt;
    protected $players;

    public function __construct(JWTAuth $jwt, PlayerInterface $players)
    {
        $this->jwt     = $jwt;
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
        $loginUser = Auth::user();
        info('postRegistration called'. $request->input('registrationId'). 'by User'. $loginUser->email);

        $this->validate($request, [
            'registrationId'     => 'required|string',
        ]);

        try {
            $registrationId  = $request->input('registrationId');
            $loginUser->updateRegistrationId($registrationId);

        } catch(Exception $e) {
            info($e);
        }

        return ['status' => 200, 'message' => 'RegistrationId was saved'];
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

    public function getPlayer()
    {
        $player = Auth::user();
        if(! $player) {
            return self::ERROR_RETURN;
        }
        return $player;
    }

    public function getPlayerById($id)
    {
        $player = Auth::user();
        if(! $player) {
            return self::ERROR_RETURN;
        }

        return $this->players->findById($id);
    }

    public function updatePlayer(Request $request)
    {
        $loggedInPlayer = Auth::user();

        $id = $request->input('id');

        if($loggedInPlayer->id !== $id) {
            return self::ERROR_RETURN;
        }

        $player = $this->players->save($request->all());

        return ['code' => 200];
    }
}