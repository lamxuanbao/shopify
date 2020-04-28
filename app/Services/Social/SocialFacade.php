<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/7/20
 * Time: 10:52
 */

namespace App\Services\Social;


use App\Services\Social\Src\SocialService;
use Illuminate\Support\Facades\Facade;

class SocialFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SocialService::class;
    }
}
