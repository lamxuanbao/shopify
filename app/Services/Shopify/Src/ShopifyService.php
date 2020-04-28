<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 11:30
 */

namespace App\Services\Shopify\Src;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;

class ShopifyService
{
    /**
     * @param $shop_domain
     *
     * @return string
     */
    public function install(string $domain): string
    {
        $clientId    = config('shopify.client_id');
        $scopes      = implode(
            ',',
            config('shopify.scopes')
        );
        $redirectUri = config('shopify.redirect_url');

        return "https://{$domain}.myshopify.com/admin/oauth/authorize?client_id={$clientId}&scope={$scopes}&redirect_uri={$redirectUri}";
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function verifyRequest(array $data): bool
    {
        $tmp = [];
        if (is_string($data)) {
            $each = explode(
                '&',
                $data
            );
            foreach ($each as $e) {
                list($key, $val) = explode(
                    '=',
                    $e
                );
                $tmp[$key] = $val;
            }
        } elseif (is_array($data)) {
            $tmp = $data;
        } else {
            return false;
        }

        // Timestamp check; 1 hour tolerance
        if (($tmp['timestamp'] - time() > 3600)) {
            return false;
        }


        if (array_key_exists(
            'hmac',
            $tmp
        )) {
            // HMAC Validation
            $queryString = http_build_query(
                [
                    'code'      => $tmp['code'],
                    'shop'      => $tmp['shop'],
                    'timestamp' => $tmp['timestamp'],
                ]
            );
            $match       = $tmp['hmac'];
            $calculated  = hash_hmac(
                'sha256',
                $queryString,
                config('shopify.client_secret')
            );

            return $calculated === $match;
        }

        return false;
    }

    /**
     * @param $code
     *
     * @return array
     */
    public function getAccessToken(string $shop, string $code): array
    {
        $client = new Client();
        try {
            $response = $client->request(
                'POST',
                "https://{$shop}/admin/oauth/access_token.json",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body'    => json_encode(
                        [
                            'code'          => $code,
                            'client_id'     => config('shopify.client_id'),
                            'client_secret' => config('shopify.client_secret'),
                        ]
                    ),
                ]
            );

            return [
                'status' => true,
                'data'   => json_decode(
                    $response->getBody()
                        ->getContents(),
                    true
                ),
            ];
        } catch (\Exception $exception) {
            return [
                'status'  => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function postRequest(string $shop, $accessToken, $action, array $data, $version = '2020-01')
    {
        $client = new Client();
        try {
            $response = $client->request(
                'POST',
                "https://{$shop}/admin/api/{$version}/{$action}.json",
                [
                    'headers' => [
                        'Content-Type'           => 'application/json',
                        'X-Shopify-Access-Token' => $accessToken,
                    ],
                    'body'    => json_encode($data),
                ]
            );

            return [
                'status' => true,
                'data'   => json_decode(
                    $response->getBody()
                        ->getContents(),
                    true
                ),
            ];
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    public function getRequest(string $shop, $accessToken, $action, $version = '2020-01')
    {
        $client = new Client();
        try {
            $response = $client->request(
                'GET',
                "https://{$shop}/admin/api/{$version}/{$action}.json",
                [
                    'headers' => [
                        'Content-Type'           => 'application/json',
                        'X-Shopify-Access-Token' => $accessToken,
                    ],
                ]
            );

            return [
                'status' => true,
                'data'   => json_decode(
                    $response->getBody()
                        ->getContents(),
                    true
                ),
            ];
        } catch (\Exception $exception) {
            return [
                'status'  => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function generateScript()
    {
        $data = "jQuery(document).ready(function () {
    try {
        var url = '".env('APP_URL')."api/message';
        var
        domain = window.location.host;
        var
        pathArray = window.location.pathname.split('/');
        if (pathArray[pathArray.length - 2] == 'products') {
            var
            handle = pathArray[pathArray.length - 1];
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                'domain': domain,
                    'handle': handle
                },
                success: function (result) {
                const data = result.data;
                data.forEach(element => $(\"<div style='padding: 10px;background-color: blue;color: white;margin-bottom: 10px;'>\" + element.message + \"</div>\").insertBefore(\".shopify-payment-button__button--unbranded\"));
                }
            })
        }
    } catch (e) {

    }
});";
        $url  = 'js/shopify.js';
        File::put(public_path($url), $data);

        return env('APP_URL').$url;
    }
}
