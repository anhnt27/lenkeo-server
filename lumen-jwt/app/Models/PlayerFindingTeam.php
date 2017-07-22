<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerFindingTeam extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    
    protected $appends = [
        'district_name',
        'player_name',
        'position_name',
        'level_name'
    ];

    //relationships
    public function districts()
    {
        return $this->belongsToMany('App\Models\District');
    }

    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Property', 'position_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\Property', 'level_id', 'id');
    }

    // append attributes handlers
    public function getDistrictNameAttribute()
    {
        if(! $this->districts)
            return '';

        return implode('-', $this->districts->pluck('name')->toArray());
    }

    public function getPlayerNameAttribute()
    {
        if(! $this->player)
            return '';

        if( $this->player->is_admin) return $this->fb_name;

        return $this->player->name;
    }

    public function getPositionNameAttribute()
    {
        if(! $this->position)
            return '';

        return $this->position->value;
    }
    
    public function getLevelNameAttribute()
    {
        if(! $this->level)
            return '';

        return $this->level->value;
    }


}
