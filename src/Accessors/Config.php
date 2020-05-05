<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Zeref\ServiceAccessor;

class Config extends ServiceAccessor
{
    /**
     *
     */
    protected static function _getServiceToProxy()
    {
        return 'config';
    }
}
