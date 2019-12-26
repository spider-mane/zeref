<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Saveyour\Factories\FieldFactory;
use WebTheory\Zeref\ServiceAccessor;

class Field extends ServiceAccessor
{
    /**
     *
     */
    protected static function getServiceAccessed()
    {
        return FieldFactory::class;
    }
}
