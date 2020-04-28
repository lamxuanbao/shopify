<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/8/20
 * Time: 09:04
 */

namespace App\Http\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SocialComposer
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
        $user          = Auth::user();
        $facebook      = $user->social()
            ->provider('facebook')
            ->first();
        $facebookPages = $facebook->pages ?? [];
        $view->with(
            [
                'user'          => $user,
                'facebookPages' => $facebookPages,
            ]
        );
    }
}
