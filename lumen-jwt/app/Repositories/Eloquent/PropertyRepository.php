<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\PropertyInterface;

class PropertyRepository extends AbstractRepository implements PropertyInterface
{
    /**
     *
     * @param \App\Models\Property $resource
     *
     */
    public function __construct(Property $resource)
    {
        parent::__construct($resource);
    }

    public function getPropertiesByName($name) 
    {
        return $this->model->where('name', $name)->get();
    }
}
