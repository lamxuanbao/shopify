<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 11:32
 */

namespace App\Http\Controllers;

use App\Repositories\ShopRepository;
use App\Services\Shopify\ShopifyFacade;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
    public function __construct(ShopRepository $shopRepository)
    {
        $this->repository = $shopRepository;
    }

    public function install(Request $request)
    {
        $params = $request->all();
        $url    = ShopifyFacade::install($params['domain'] ?? null);

        return redirect()->to($url);
    }

    public function auth(Request $request)
    {
        $this->repository->auth($request->all());

        return redirect('/');
    }
}
