<?php

namespace WebTheory\Zeref\Accessors;

use Swift_Mailer;
use WebTheory\Zeref\ServiceAccessor;

class SwiftMailer extends ServiceAccessor
{
    /**
     *
     */
    protected static function _getServiceToProxy()
    {
        return Swift_Mailer::class;
    }
}
