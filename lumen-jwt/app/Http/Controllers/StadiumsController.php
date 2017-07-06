<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\StadiumInterface;

class StadiumsController extends Controller
{
    protected $stadiums;

    public function __construct(StadiumInterface $stadiums)
    {
        $this->stadiums = $stadiums;
    }

    public function getStadiumsByDistrict(int $districtId)
    {
        return $this->stadiums->findAll();
    }
}