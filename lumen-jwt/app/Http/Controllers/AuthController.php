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
        ]);

        try {
            $email  = $request->input('email');
            
            $player = $this->players->findOneByFields(['email' => $email]);

            if(!$player) {
                $name   = $request->input('name');
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