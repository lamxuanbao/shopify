<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 17:12
 */

namespace App\Http\Composers;


use App\Repositories\ProductRepository;
use Illuminate\View\View;

class ProductComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $productRepository = app(ProductRepository::class);
        $products          = $productRepository->all();
        $view->with(
            [
                'products' => $products,
            ]
        );
    }
}
