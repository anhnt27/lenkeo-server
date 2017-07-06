<?php

namespace App\Models;
s
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $table = 'stadiums';
    
    protected $appends = [
        'district_name',
    ];

    //relationships
    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    // append attributes handlers
    public function getDistrictNameAttribute()
    {
        if(! $this->district)
            return '';

        return $this->district->name;
    }


}
