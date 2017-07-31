<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Player extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];
    
    protected $appends = [
        'city_name',
        'level_name',
        'position_name',
        'district_name',
        'ground_type_name',
    ];

    // appends attribute
    public function getCityNameAttribute()
    {
        if(! $this->city)
            return '';

        return $this->city->name;
    }
    public function getDistrictNameAttribute()
    {
        if(! $this->district)
            return '';
        return $this->district->name;
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
    public function getGroundTypeNameAttribute()
    {
        if(! $this->groundType)
            return '';

        return $this->groundType->value;
    }


    public function updateRegistrationId($registrationId)
    {
        $this->registration_id = $registrationId;
        $this->save();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // relationships
    public function teamFindingPlayers()
    {
        return $this->hasMany('App\Models\TeamFindingPlayer');
    }

    public function notificationSettings()
    {
        return $this->hasMany('App\Models\NotificationSetting');
    }

    public function setting()
    {
        return $this->hasOne('App\Models\Setting');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function matches()
    {
        return $this->belongsToMany('App\Models\Match')->withPivot('confirm_status');
    }

    public function joins()
    {
        return $this->hasOne('App\Models\Join');
    }
    
    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }
    
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Property', 'position_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\Property', 'level_id', 'id');
    }

    public function groundType()
    {
        return $this->belongsTo('App\Models\Property', 'ground_type_id', 'id');
    }
}
