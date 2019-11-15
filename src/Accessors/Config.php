<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Zeref\ServiceAccessor;

class Config extends ServiceAccessor
{
    protected static function getServiceAccessed()
    {
        return 'config';
    }
}
