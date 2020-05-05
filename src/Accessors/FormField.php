<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Saveyour\Factories\FieldFactory;
use WebTheory\Zeref\ServiceAccessor;

class FormField extends ServiceAccessor
{
    /**
     *
     */
    protected static function _getServiceToProxy()
    {
        return FieldFactory::class;
    }
}
