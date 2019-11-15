<?php

namespace App\Providers;

use App\App;
use Backalley\WordPress\PostType\Factory;
use Illuminate\Support\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        /** @param App $app */
        $this->app->singleton(Factory::class, function ($app) {

            return new Factory($app->get('config')->get('wp.option_handlers.post_type'));
        });
    }
}
