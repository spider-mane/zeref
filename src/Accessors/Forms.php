<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Zeref\Forms\FormRepository;
use WebTheory\Zeref\ServiceAccessor;

class Forms extends ServiceAccessor
{
    protected static function _getServiceToProxy()
    {
        return FormRepository::class;
    }
}
