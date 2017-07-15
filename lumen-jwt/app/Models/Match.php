<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    const CONFIRM_STATUS_BEGIN = -1;
    const CONFIRM_STATUS_NO    = 0;
    const CONFIRM_STATUS_YES   = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    protected $appends = [
        'match_name',
        'confirmed_number'     
    ];

    public function players()
    {
        return $this->belongsToMany('App\Models\Player')->withPivot('confirm_status');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    // append attributes handlers
    public function getMatchNameAttribute()
    {
        return substr($this->from,0,5). 'h Ngày '. $this->match_date;
    }
    public function getConfirmedNumberAttribute()
    {
        return $this->belongsToMany('App\Models\Player')->wherePivot('confirm_status', 1)->count();
    }
    public function getFromAttribute($value)
    {
        return substr($value,0,5);
    }
    public function getToAttribute($value)
    {
        return substr($value,0,5);
    }
}
