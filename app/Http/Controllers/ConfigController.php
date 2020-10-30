<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Models\AppConfig;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function show()
    {
        $res = new ApiMessage();
        $config = AppConfig::getConfig();

        return $res->setData($config)->send();
    }
}
