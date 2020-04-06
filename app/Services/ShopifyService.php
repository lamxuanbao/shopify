<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 11:30
 */

namespace App\Services;


use GuzzleHttp\Client;

class ShopifyService
{
    /**
     * @param $shop_domain
     *
     * @return string
     */
    public static function install(string $domain): string
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
    public static function verifyRequest(array $data): bool
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
    public static function getAccessToken(string $shop, string $code): array
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

    public static function postRequest(string $shop, $accessToken, $action, array $data, $version = '2020-01')
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

    public static function getRequest(string $shop, $accessToken, $action, $version = '2020-01')
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
}
