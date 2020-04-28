<?php

namespace App\Services\Shopify;

use App\Services\Shopify\Src\ShopifyService;
use Illuminate\Support\Facades\Facade;

class ShopifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ShopifyService::class;
    }
}
