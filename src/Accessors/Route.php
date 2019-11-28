<?php

use Slim\Interfaces\RouteCollectorProxyInterface;
use WebTheory\Zeref\ServiceAccessor;

class Route extends ServiceAccessor
{
    public function getServiceAccessorRoot()
    {
        return RouteCollectorProxyInterface::class;
    }
}
