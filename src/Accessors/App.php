<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Zeref\ServiceAccessor;

class App extends ServiceAccessor
{
    /**
     *
     */
    protected static function getServiceAccessed()
    {
        return 'app';
    }
}
