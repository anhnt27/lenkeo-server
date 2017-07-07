<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Repositories\PlayerInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    protected $players;

    public function __construct(JWTAuth $jwt, PlayerInterface $players)
    {
        $this->jwt     = $jwt;
        $this->players = $players;
    }

    public function loginPost(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'name'     => 'required|string|max:255',
            'inputToken'     => 'required|string|max:255',
        ]);


        try {
            $email      = $request->input('email');
            $inputToken = $request->input('inputToken');
                
            $profileResponse = $this->verifyAccessToken($inputToken);

            if($profileResponse->getStatusCode() != 200) {
                return response()->json(['error_code']);
            }

            $returnContent = $profileResponse->getBody()->getContents();
            $profile       = json_decode($returnContent);

            if($profile->email != $email) {
                return response()->json(['invalid_email']);
            }

            $player = $this->players->findOneByFields(['email' => $email]);

            // new player
            if(!$player) {
                $name   = $request->input('name');
                $player = $this->players->save(['email' => $email, 'name' => $name]);
            }

            // return token
            $token = $this->jwt->fromUser($player);
            info('login called' . $email . '/' . $inputToken);
            info('return token: '. $token);

        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], $e->getStatusCode());
        } 

        return ['token' => $token, 'code' => 200, 'player' => $player];
    }
    public function verifyAccessToken($accessToken)
    {
        $fields = 'id,email,first_name,last_name,link,name';

        $client = new Client();

        $res = $client->request('GET', 'https://graph.facebook.com/me?fields=id,email,first_name,last_name,link,name&access_token=EAAaR39aoHBQBALJFGWhnw1KZCZC5WIaKOvQ70PiCk6lhTTqWWlyKTmiFtdyNypjZAGdLfdHiVmWZAMbmhrbKrx3hgNTJxmTmaiT5sceEjZAZCQJhoWukFBK12Ygsfn9v1vteoqwAdutt4b7nAH5vyTLY4lSoYOyhZBER4OLC6ZBowGW1McUXI5JSKJ98d8B2m4XZBtpWKnbvtGKVkQIzSel0bJAgXaZB8mz1YZD');
        $profileResponse = $client->request('GET', 'https://graph.facebook.com/me', [
            'query' => [
                'access_token' => $accessToken,
                'fields'       => $fields
            ]
        ]);

        return $profileResponse;
    }

    public function login($email, $name)
    {
        info('caleed'.$email. $name);
        try {
            $player = $this->players->findOneByFields(['email' => $email]);

            if(!$player) {
                $player = $this->players->save(['email' => $email, 'name' => $name]);
            }

            $token = $this->jwt->fromUser($player);

        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], $e->getStatusCode());
        } 

        return response()->json(compact('token'));
    }
}