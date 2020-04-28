<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class FacebookVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hubMod      = $request->input("hub_mode");
        $verifyToken = $request->input("hub_verify_token");
        $localToken  = env('FACEBOOK_VERIFY_TOKEN');
        if ($verifyToken === $localToken) {
            return response($request->input("hub_challenge"), 200);
        }

        return $next($request);
    }
}
