<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function teamFindingPlayers()
    {
        return $this->belongsToMany('App\Models\TeamFindingPlayer');
    }
}
