<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 17:25
 */

namespace App\Http\Controllers;


use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function detail($slug)
    {
        $product = $this->repository->findByField('handle', $slug)
            ->first();
        if (!$product) {
            abort(404);
        }

        return view('detail', compact('product'));
    }
}

