<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 10:59
 */

namespace App\Services;

use Illuminate\Http\Response as ResponseSys;

class ResponseService
{
    public static function send($data, $code = ResponseSys::HTTP_OK, $message = null)
    {
        $result = [
            'status'  => false,
            'message' => null,
            'data'    => [],
            'errors'  => [],
        ];

        if ($code == ResponseSys::HTTP_OK) {
            $result['status'] = true;
        }

        if ($code == ResponseSys::HTTP_OK) {
            if (is_string($data)) {
                $result['message'] = $data;
            } else {
                $result['data']    = $data;
                $result['message'] = $message;
            }
            unset($result['errors']);
        } else {
            $code = empty($code) ? ResponseSys::HTTP_INTERNAL_SERVER_ERROR : $code;
            if ($data instanceof \Exception) {
                $result['message'] = $data->getMessage();
            } else {
                if (is_string($data)) {
                    $result['message'] = $data;
                } else {
                    $result['errors']  = $data;
                    $result['message'] = $message;
                }
            }
            unset($result['data']);
        }

        return response()->json($result, $code);
    }
}
