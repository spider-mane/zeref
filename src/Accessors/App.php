<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Zeref\Application;
use WebTheory\Zeref\ServiceAccessor;

class App extends ServiceAccessor
{
    /**
     *
     */
    protected static function _getServiceToProxy()
    {
        return Application::class;
    }
}
