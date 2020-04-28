<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/6/20
 * Time: 15:59
 */

namespace App\Services\Facebook;

use App\Services\Facebook\Src\FacebookService;
use Illuminate\Support\Facades\Facade;

class FacebookFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FacebookService::class;
    }
}
