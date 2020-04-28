<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ProductMessageRepository::class, \App\Repositories\ProductMessageRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SocialRepository::class, \App\Repositories\SocialRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SocialPageRepository::class, \App\Repositories\SocialPageRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SocialPagePostRepository::class, \App\Repositories\SocialPagePostRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SocialPagePostCommentRepository::class, \App\Repositories\SocialPagePostCommentRepositoryEloquent::class);
        //:end-bindings:
    }
}
