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
    const SOCIAL_TYPE_GOOGLE   = 1;
    const SOCIAL_TYPE_FACEBOOK = 2;
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

    public function login(Request $request)
    {
        info('login...');
        info($request->input('socialType'));
        info($request->input('email'));
        info($request->input('name'));
        info($request->input('inputToken'));
        $this->validate($request, [
            'socialType' => 'required',
            'email'      => 'required|email|max:255',
            'inputToken' => 'required|string',
        ]);


        try {
            $email      = $request->input('email');
            $socialType = $request->input('socialType');
            $inputToken = $request->input('inputToken');
            
            $isTesting = false;
            $isTesting = true;  
            if($isTesting) {
                $player = $this->players->findOneByFields(['email' => $email]);

                // new player
                if(!$player) {
                    $name   = $request->input('name');
                    $player = $this->players->save(['email' => $email, 'name' => $name]);
                }

                $token = $this->jwt->fromUser($player);
                info('login called' . $email . '/' . $inputToken);
                info('return token: '. $token);
                return ['token' => $token, 'code' => self::CODE_SUCCESS, 'player' => $player];
            }

            $verifiedEmail = $this->verifyAccessToken($socialType, $inputToken);

            if(! $verifiedEmail) {
                info('Can not verify this token');
                return ['code' => self::CODE_ERROR, 'message' => 'Can not verify this token'];
            } 
            // if($profileResponse->getStatusCode() != 200) {
            //     return response()->json(['error_code']);
            // }

            // $returnContent = $profileResponse->getBody()->getContents();
            // $profile       = json_decode($returnContent);

            if($verifiedEmail != $email) {
                info('Can not verify this token. Emails are not matched.');
                return ['code' => self::CODE_ERROR, 'message' => 'Can not verify this token. Emails are not matched.'];
            }

            $player = $this->players->findOneByFields(['email' => $email]);

            $isNewPlayer = false;
            // new player
            if(!$player) {
                $isNewPlayer = true;
                $name   = $request->input('name');
                if(! $name) $name = ' ';
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

        return ['token' => $token, 'code' => 200, 'player' => $player, 'isNewPlayer' => $isNewPlayer];
    }

    public function verifyAccessToken(int $socialType, $accessToken)
    {
        switch ($socialType) {
            case self::SOCIAL_TYPE_GOOGLE:
                # code...
                return $this->verifyGoogleToken($accessToken);
                break;
            case self::SOCIAL_TYPE_FACEBOOK:
                return $this->verifyFacebookToken($accessToken);
                break;
            default:
                return false;
                # code...
                break;
        }
    }

    public function verifyGoogleToken($accessToken)
    {
        info('verifyGoogleTokenw');
        try {
            $client = new Client();


            $profileResponse = $client->request('GET', 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='. $accessToken);

            if($profileResponse->getStatusCode() != 200) {
                return false;
            }

            $returnContent = $profileResponse->getBody()->getContents();
            $profile       = json_decode($returnContent);

            return $profile->email;
        } catch (Exception $e) {
            info('errow when verify google Token', $e);
            return false;
        }
    }

    public function verifyFacebookToken($accessToken)
    {
        $client = new Client();

        $fields = 'id,email,first_name,last_name,link,name';

        // $res = $client->request('GET', 'https://graph.facebook.com/me?fields=id,email,first_name,last_name,link,name&access_token=EAAaR39aoHBQBALJFGWhnw1KZCZC5WIaKOvQ70PiCk6lhTTqWWlyKTmiFtdyNypjZAGdLfdHiVmWZAMbmhrbKrx3hgNTJxmTmaiT5sceEjZAZCQJhoWukFBK12Ygsfn9v1vteoqwAdutt4b7nAH5vyTLY4lSoYOyhZBER4OLC6ZBowGW1McUXI5JSKJ98d8B2m4XZBtpWKnbvtGKVkQIzSel0bJAgXaZB8mz1YZD');
        $profileResponse = $client->request('GET', 'https://graph.facebook.com/me', [
            'query' => [
                'access_token' => $accessToken,
                'fields'       => $fields
            ]
        ]);

        if($profileResponse->getStatusCode() != 200) {
            return false;
        }

        $returnContent = $profileResponse->getBody()->getContents();
        $profile       = json_decode($returnContent);

        return $profile->email;
    }

}