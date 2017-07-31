<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'city_name',
        'district_name',
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

    public function players()
    {
        return $this->hasMany('App\Models\Player');
    }

    public function matches()
    {
        return $this->hasMany('App\Models\Match');
    }

    public function joins()
    {
        return $this->hasMany('App\Models\Join');
    }
    
    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }
    
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
}
