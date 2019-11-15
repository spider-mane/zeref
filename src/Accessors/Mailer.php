<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Zeref\ServiceAccessor;

/**
 *
 */
class Mailer extends ServiceAccessor
{
    /**
     *
     */
    protected static function getServiceAccessed()
    {
        return 'mailer';
    }
}
