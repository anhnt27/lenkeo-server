<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
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

    public function districts()
    {
        return $this->hasMany('App\Models\District');
    }
}
