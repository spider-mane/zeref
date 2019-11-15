<?php

namespace WebTheory\Zeref\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig\Twig;

class TwigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [Twig::class];

    /**
     *
     */
    public function register()
    {
        $container = $this->getLeagueContainer();

        $container->share(Twig::class, function () use ($container) {
            //
        })->addTag('twig');
    }
}
