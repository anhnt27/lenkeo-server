<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class FirebaseController extends Controller
{
    public function __construct()
    {
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
}