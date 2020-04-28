<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/13/20
 * Time: 17:09
 */

namespace App\Http\Composers;


use App\Entities\SocialPagePostComment;
use Illuminate\View\View;

class PostDetailComposer
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
        $data = $view->getData();
        $comments = SocialPagePostComment::where('social_page_post_id', $data['postId'])
            ->get();
        $view->with(
            [
                'comments' => $comments,
            ]
        );
    }
}
