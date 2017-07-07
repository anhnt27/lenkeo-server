<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SettingInterface;

class SettingsController extends Controller
{
    protected $settings;

    public function __construct(Response $response, SettingInterface $settings)
    {
        $this->settings = $settings;
    }
}