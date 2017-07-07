<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CityInterface;

class CitiesController extends Controller
{
    protected $cities;

    public function __construct(CityInterface $cities)
    {
        $this->cities = $cities;
    }

    public function getLocation()
    {
        $cities = $this->cities->findAll();
        $results['cities'] = $cities;

        $districtsByCity = $cities->keyBy('id')->load('districts');
        $results['districts_by_city'] = $districtsByCity;

        return ['results' => $results];
    }    
    public function getDistrictsByCity($cityId)
    {
        $city = $this->cities->findById($cityId);

        $city->load('districts');
            
        return $city->districts;
    }
}