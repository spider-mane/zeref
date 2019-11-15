<?php

namespace App\Providers;

use Backalley\WordPress\PostType\Factory;
use League\Container\ServiceProvider\AbstractServiceProvider;

class PostTypeServiceProvider extends AbstractServiceProvider
{
    protected $provides = [Factory::class];

    /**
     *
     */
    public function register()
    {
        $container = $this->getLeagueContainer();

        $container->share(Factory::class, function () use ($container) {

            return new Factory($container->get('config')->get('wp.option_handlers.post_type'));
        })->addTag('post_type');
    }
}
