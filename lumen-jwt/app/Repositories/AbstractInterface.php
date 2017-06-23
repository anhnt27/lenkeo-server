<?php 
namespace App\Repositories;

interface AbstractInterface
{
    /**
     * @param array $attributes
     */
    public function getNew(array $attributes = []);
}