<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;

/**
 * Class Notification
 *
 * @package App\Models
 */
class Notification extends BaseModel
{
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function markAsRead()
    {
        $this->read_at = Carbon::now();
        $this->save();
    }
}
