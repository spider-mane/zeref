<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Leonidas\Fields\Field;
use WebTheory\Zeref\ServiceAccessor;

class AdminField extends ServiceAccessor
{
    /**
     *
     */
    protected static function getServiceAccessed()
    {
        return Field::class;
    }
}
