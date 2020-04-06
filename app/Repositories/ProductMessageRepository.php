<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 17:40
 */

namespace App\Repositories;

use App\Entities\ProductMessage;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductMessageRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductMessage::class;
    }

    public function getList($params)
    {
        $result = $this->select('*')
            ->join(
                'products',
                function ($join) use ($params) {
                    $join->on('products.id', '=', 'product_messages.product_id')
                        ->where('products.handle', '=', $params['handle'] ?? null);
                }
            )
            ->join(
                'shops',
                function ($join) use ($params) {
                    $join->on('shops.id', '=', 'products.shop_id')
                        ->where('shops.domain', '=', $params['domain'] ?? null);
                }
            )
            ->get();

        return $result;
    }
}
