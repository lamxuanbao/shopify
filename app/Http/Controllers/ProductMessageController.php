<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 17:48
 */

namespace App\Http\Controllers;


use App\Repositories\ProductMessageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductMessageController extends Controller
{
    public function __construct(ProductMessageRepository $productMessageRepository)
    {
        $this->repository = $productMessageRepository;
    }

    public function index(Request $request)
    {
        try {
            $result = $this->repository->getList($request->all());

            return $this->response($result);
        } catch (\Exception $e) {
            return $this->response(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
        $url = redirect()
            ->getUrlGenerator()
            ->previous();

        return redirect($url);
    }
}


