<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            [
                'partial/_product_list',
            ],
            'App\Http\Composers\ProductComposer'
        );
        View::composer(
            [
                'partial/_social',
            ],
            'App\Http\Composers\SocialComposer'
        );
        View::composer(
            [
                'facebook_page',
            ],
            'App\Http\Composers\FacebookPageComposer'
        );
        View::composer(
            [
                'partial/_posts',
            ],
            'App\Http\Composers\PostsComposer'
        );
        View::composer(
            [
                'post_detail',
            ],
            'App\Http\Composers\PostDetailComposer'
        );
    }
}
