<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    CONST TYPE_FINDING_PLAYER = 1;
    CONST TYPE_FINDING_TEAM   = 2;
    CONST TYPE_FINDING_MATCH  = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function districts()
    {
        return $this->belongsToMany('App\Models\District');
    }

    public function positions()
    {
        return $this->belongsToMany('App\Models\Property','position_notification_setting', 'notification_setting_id', 'position_id');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Models\Property','level_notification_setting', 'notification_setting_id', 'level_id');
    }
}
