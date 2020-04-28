<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/8/20
 * Time: 09:04
 */

namespace App\Http\Composers;

use App\Repositories\SocialPagePostRepository;
use App\Repositories\SocialPageRepository;
use App\Services\Facebook\FacebookFacade;
use Illuminate\View\View;

class PostsComposer
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
        $data                     = $view->getData();
        $socialPagePostRepository = app(SocialPagePostRepository::class);
        $posts                    = $socialPagePostRepository->findWhere(
            [
                'social_page_id' => $data['pageId'],
            ]
        );
        $view->with(
            [
                'posts' => $posts,
            ]
        );
    }
}
