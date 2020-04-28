<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/8/20
 * Time: 09:04
 */

namespace App\Http\Composers;

use App\Repositories\SocialPageRepository;
use App\Services\Facebook\FacebookFacade;
use Illuminate\View\View;

class FacebookPageComposer
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
        $data                 = $view->getData();
        $socialPageRepository = app(SocialPageRepository::class);
        $page                 = $socialPageRepository->find($data['pageId']);
        $page->load(['conversations.users', 'conversations.detail']);
        $view->with(
            [
                'page' => $page,
            ]
        );
    }
}
