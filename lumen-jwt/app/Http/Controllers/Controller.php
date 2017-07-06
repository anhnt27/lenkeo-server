<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const ERROR_RETURN = ['code' => 500];
    public function convertStringToIntArray($inputArray) 
    {
        return array_map('intval', explode(',', $inputArray));
    }
    
    public function processFilterParams($param) {
    if( $param == '0') {
        return [];
    }

    return array_map('intval', explode(',', $param));
    }
}
