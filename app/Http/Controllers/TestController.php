<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/6/20
 * Time: 13:41
 */

namespace App\Http\Controllers;


use App\Services\ShopifyService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;

class TestController
{
    public function __invoke()
    {
        //        $scriptTags = [
        //            'script_tag' => [
        //                "event" => "onload",
        //                "src"   => ShopifyService::generateScript(),
        //            ],
        //        ];
        //        dd($scriptTags);
        //        $id          = '119732469819';
        //        $accessToken = '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';
        //        $client      = new Client();
        //        try {
        //            $response = $client->request(
        //                'DELETE',
        //                "https://lamxuanbao.myshopify.com/admin/api/2020-04/script_tags/{$id}.json",
        //                [
        //                    'headers' => [
        //                        'Content-Type'           => 'application/json',
        //                        'X-Shopify-Access-Token' => $accessToken,
        //                    ],
        //                ]
        //            );
        //
        //            return [
        //                'status' => true,
        //                'data'   => json_decode(
        //                    $response->getBody()
        //                        ->getContents(),
        //                    true
        //                ),
        //            ];
        //        } catch (\Exception $exception) {
        //            return [
        //                'status'  => false,
        //                'message' => $exception->getMessage(),
        //            ];
        //        }
    }
}
