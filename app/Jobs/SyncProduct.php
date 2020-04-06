<?php

namespace App\Jobs;

use App\Services\ShopifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $domain;
    protected $accessToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain, $accessToken)
    {
        $this->domain      = $domain;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = ShopifyService::getProducts($this->domain, $this->accessToken);
        if (!$products['status']) {
            throw new \Exception($products['message']);
        }
        $products = $products['data']['products'];
        foreach ($products as $product) {

            dd($product);
            $this->productRepository->updateOrCreate(
                [
                    'product_id' => $product['id'],
                ],
                array_merge(
                    $product,
                    [
                        'product_id' => $product['id'],
                    ]
                )
            );
        }
        dd($products);
        //
    }
}
