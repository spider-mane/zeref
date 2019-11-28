<?php

namespace WebTheory\Zeref\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

class SlimAppServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        App::class,
        RequestHandlerInterface::class,
        RouteCollectorProxyInterface::class
    ];

    public function register()
    {
        $container = $this->getLeagueContainer();

        $app = $this->createApp();

        foreach ($this->provides as $abstract) {
            $container->share($abstract, $app)
        }
    }

    protected function createApp() {
        //
    }
}
