<?php

namespace App\Repositories;

use App\Services\ShopifyService;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Entities\Shop;

class ShopRepository extends BaseRepository
{
    protected $productRepository;
    protected $userRepository;

    public function __construct(Application $app, ProductRepository $productRepository, UserRepository $userRepository)
    {
        parent::__construct($app);
        $this->productRepository = $productRepository;
        $this->userRepository    = $userRepository;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Shop::class;
    }

    public function auth($params)
    {
        $verify_request = ShopifyService::verifyRequest($params);

        if (!$verify_request) {
            throw new \Exception('Verify request fail');
        }
        $domain      = $params['shop'] ?? null;
        $code        = $params['code'] ?? null;
        $accessToken = ShopifyService::getAccessToken($domain, $code);
        if (!$accessToken['status']) {
            throw new \Exception($accessToken['message']);
        }
        $data = $accessToken['data'];

        $accessToken = $data['access_token'];

        $info = ShopifyService::getRequest($domain, $accessToken, 'shop');
        if (!$info['status']) {
            throw new \Exception($info['message']);
        }

        $shop   = $info['data']['shop'] ?? [];
        $shopId = $shop['id'];
        unset($shop['id']);
        $scriptTags = [
            'script_tag' => [
                "event" => "onload",
                "src"   => env('APP_URL')."/js/shopify.js",
            ],
        ];
        ShopifyService::postRequest(
            $domain,
            $accessToken,
            'script_tags',
            $scriptTags,
            '2020-04'
        );
        $result = $this->updateOrCreate(
            [
                'shop_id' => $shopId,
            ],
            array_merge(
                $shop,
                [
                    'shop_id'      => $shopId,
                    'access_token' => $accessToken,
                ]
            )
        );

        $user = $this->userRepository->updateOrCreate(
            [
                'email' => $shop['email'],
            ],
            [
                'name'  => $shop['name'],
                'email' => $shop['email'],
            ]
        );
        Auth::loginUsingId($user->id, true);


        $products = ShopifyService::getRequest(
            $domain,
            $accessToken,
            'products',
            '2020-04'
        );
        if (!$products['status']) {
            throw new \Exception($products['message']);
        }
        $products = $products['data']['products'];
        foreach ($products as $product) {
            $product['images']           = $product['images'];
            $product['main_image']       = $product['image'];
            $product['price']            = $product['variants'][0]['price'];
            $product['compare_at_price'] = $product['variants'][0]['compare_at_price'];
            $product['shop_id']          = $result->id;
            $this->productRepository->updateOrCreate(
                [
                    'product_id' => $product['id'],
                    'shop_id'    => $product['shop_id'],
                ],
                array_merge(
                    $product,
                    [
                        'product_id' => $product['id'],
                    ]
                )
            );
        }
    }
}
