<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Join extends Model
{
    use SoftDeletes;
    
    const TYPE_JOIN_TEAM  = 1;
    const TYPE_INVITE_MEMBER  = 2;
    
    const STATUS_SEND     = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;

    protected $appends = [
        'player_name',
        'team_name',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    // append attributes handlers
    public function getPlayerNameAttribute()
    {
        if(! $this->player)
            return '';

        return $this->player->name;
    }
    
    public function getTeamNameAttribute()
    {
        if(! $this->team)
            return '';

        info($this->name);
        return $this->team->name;
    }
}
