<?php

namespace WebTheory\Zeref\Accessors;

use Slim\Interfaces\RouteCollectorProxyInterface;
use WebTheory\Zeref\ServiceAccessor;

class Route extends ServiceAccessor
{
    /**
     *
     */
    protected static function getServiceAccessed()
    {
        return RouteCollectorProxyInterface::class;
    }
}
