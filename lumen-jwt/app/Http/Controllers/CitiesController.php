<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CityInterface;
use EllipseSynergie\ApiResponse\Contracts\Response;

class CitiesController extends Controller
{
    protected $cities;

    public function __construct(Response $response, CityInterface $cities)
    {
        $this->response = $response;
        $this->cities = $cities;
    }

    public function getLocation()
    {
        $cities = $this->cities->findAll();
        $results['cities'] = $cities;

        $districtsByCity = $cities->keyBy('id')->load('districts');
        $results['districts_by_city'] = $districtsByCity;

        return $this->response->withArray(['results' => $results]);
    }    
    public function getDistrictsByCity($cityId)
    {
        $city = $this->cities->findById($cityId);

        $city->load('districts');
            
        return $city->districts;
    }
}