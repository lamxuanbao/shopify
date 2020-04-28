<?php

namespace App\Http\Controllers;

use App\Services\Response\ResponseFacade;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $repository;

    public function response($data, $code = Response::HTTP_OK, $message = null)
    {
        return ResponseFacade::send($data, $code, $message);
    }
}
