<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/7/20
 * Time: 09:48
 */

namespace App\Http\Controllers;

use App\Entities\SocialPageConversation;
use App\Services\Facebook\FacebookFacade;
use App\Services\Social\SocialFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return SocialFacade::redirect($provider);
    }

    public function callback($provider)
    {
        //        try {
        SocialFacade::auth($provider);

        return redirect()->to('/');
        //        } catch (\Exception $exception) {
        //            return [
        //                'status'  => false,
        //                'message' => $exception->getMessage(),
        //            ];
        //        }
    }

    public function fbMessageHook(Request $request)
    {
        FacebookFacade::webHook($request->all());
    }
}
