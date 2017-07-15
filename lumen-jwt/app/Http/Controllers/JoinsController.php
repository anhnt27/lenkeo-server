<?php

namespace App\Http\Controllers;

use Auth;
use Notification;
use App\Models\Join;
use Illuminate\Http\Request;
use App\Notifications\JoinTeam;
use Illuminate\Support\Facades\Log;
use App\Repositories\JoinInterface;
use App\Notifications\InviteMember;
use App\Repositories\PlayerInterface;

class JoinsController extends Controller
{
    protected $joins;

    public function __construct(JoinInterface $joins, PlayerInterface $players)
    {
        $this->joins   = $joins;
        $this->players = $players;
    }

    public function addJoinTeam(Request $request)    
    {
        Log::info('addJoinTeam');
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;

            $email = $request->input('email');

            $playerOfTeam = $this->players->findOneByFields(['email' => $email]);

            if(!$playerOfTeam || !$playerOfTeam->team) {
                return ['code' => self::CODE_ERROR, 'message' => 'Thành viên với email không tồn tại!'];
            }

            $team = $playerOfTeam->team;
            if(!$team->is_finding_player) {
                return ['code' => self::CODE_ERROR, 'message' => 'Đội đã đủ thành viên!'];
            }

            if($this->joins->findOneByFields(['player_id' => $player->id, 'team_id' => $team->id]))
                return ['code' => self::CODE_ERROR, 'message' => 'Đã gửi lời mời!'];

            $params = [
                'player_id' => $player->id,
                'status'    => Join::STATUS_SEND,
                'type'      => Join::TYPE_JOIN_TEAM,
                'team_id'   => $team->id,
            ];

            $join = $this->joins->save($params);

            $teamLead = $this->players->getLeaderOfTeam($team->id);
            $this->sendJoinTeamNotification($teamLead, $join);

            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function updateJoinTeam(Request $request)
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            if(! $player->is_team_lead) return self::ERROR_RETURN;
            
            $id   = $request->input('id');
            $join = $this->joins->findById($id);

            if(! $join) return self::ERROR_RETURN;

            $isAccept = $request->input('isAccept');
            $status   = $isAccept ? Join::STATUS_ACCEPTED : Join::STATUS_REJECTED;

            $this->joins->save(compact('id', 'status'));
            $this->joins->delete($id);
            $joiningPlayer = $join->player;
            if(! $joiningPlayer) return self::ERROR_RETURN;

            $joiningTeam = $join->team;
            if(! $joiningTeam) return self::ERROR_RETURN;

            if($isAccept) {
                $joiningPlayer->team()->associate($joiningTeam);
                $joiningPlayer->save();
            }

            // mark join as read and 
            // @TODO Notification

            // only lead can do this.
            // check status 
            // update status
            // delete join request

            // if accept : update team_id , number of player
            // if reject :              
                // send notification to player / team lead


            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function getJoiningTeamRequests()
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            
            $team = $player->team;
            if(! $team) return self::ERROR_RETURN;

            $joiningTeam = $this->joins->getJoiningTeamRequests($team->id);
            return $joiningTeam;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    } 

    public function getInviteRequest()
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            
            $inviteMembers = $this->joins->getInviteRequest($player->id);

            return $inviteMembers;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }
    
    public function addInviteMember(Request $request)    
    {
        Log::info('addInviteMember');
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            if(! $player->team) return self::ERROR_RETURN;
            if(! $player->is_team_lead) return self::ERROR_RETURN;

            $email = $request->input('email');

            $invitedPlayer = $this->players->findOneByFields(['email' => $email]);

            if(!$invitedPlayer || $invitedPlayer->team) {
                return ['code' => self::CODE_ERROR, 'message' => 'Thành viên với email không tồn tại hoặc đã gia nhập đội khác'];
            }

            if($this->joins->findOneByFields(['player_id' => $invitedPlayer->id, 'team_id' => $player->team->id]))
                return ['code' => self::CODE_ERROR, 'message' => 'Đã gửi lời mời!'];

            $params = [
                'player_id' => $invitedPlayer->id,
                'status'    => Join::STATUS_SEND,
                'type'      => Join::TYPE_INVITE_MEMBER,
                'team_id'   => $player->team->id,
            ];

            $join = $this->joins->save($params);

            // @TODO
            // Notify invited Player
            $this->sendInviteNotification($invitedPlayer, $join);
            // Display this data on Info Page of Player

            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function updateInviteMember(Request $request)
    {
        try {
            $player = Auth::user();
            if(! $player) return self::ERROR_RETURN;
            
            $id   = $request->input('id');
            $join = $this->joins->findById($id);

            if(! $join) return self::ERROR_RETURN;

            $isAccept = $request->input('isAccept');
            $status   = $isAccept ? Join::STATUS_ACCEPTED : Join::STATUS_REJECTED;

            $this->joins->save(compact('id', 'status'));
            $this->joins->delete($id);
            $joiningPlayer = $join->player;
            if(! $joiningPlayer) return self::ERROR_RETURN;

            $joiningTeam = $join->team;
            if(! $joiningTeam) return self::ERROR_RETURN;

            if($isAccept) {
                $joiningPlayer->team()->associate($joiningTeam);
                $joiningPlayer->save();
            }
            // @TODO Notification

            // only lead can do this.
            // check status 
            // update status
            // delete join request

            // if accept : update team_id , number of player
            // if reject :              
                // send notification to player / team lead


            return self::SUCCESS_RETURN;
        } catch (Exception $e) {
            Log::error($e);
            return self::ERROR_RETURN;
        }
    }

    public function sendInviteNotification($invitedPlayer, $join)
    {
        Notification::send($invitedPlayer, new InviteMember($join));
    }

    public function sendJoinTeamNotification($teamLead, $join)
    {
        Notification::send($teamLead, new JoinTeam($join));
    }
}