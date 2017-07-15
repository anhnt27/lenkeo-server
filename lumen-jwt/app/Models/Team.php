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
}
