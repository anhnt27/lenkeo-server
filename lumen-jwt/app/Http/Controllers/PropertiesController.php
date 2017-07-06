<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Repositories\PropertyInterface;

class PropertiesController extends Controller
{
    protected $properties;

    public function __construct(PropertyInterface $properties)
    {
        $this->properties = $properties;
    }

    public function getPropertiesByName($name) 
    {
        return $this->properties->getPropertiesByName($name);
    }

    public function getAllProperties()
    {
        return $this->properties->findAll()->keyBy('id');
    }

}