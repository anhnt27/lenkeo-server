<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Class BaseModel
 *
 * @package App\Models
 */
class BaseModel extends \Illuminate\Database\Eloquent\Model
{

    /**
     * Indicates if all mass assignment is enabled.
     * @var bool
     */
    protected static $unguarded = true;

    
    /**
     * @param $encryptedValue
     *
     * @return bool
     */
    public function isEncrypted($encryptedValue)
    {
        try {
            Crypt::decrypt($encryptedValue);

            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }
}
