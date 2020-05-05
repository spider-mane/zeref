<?php

use Twig\Environment;
use WebTheory\Zeref\ServiceAccessor;

class Twig extends ServiceAccessor
{
    /**
     *
     */
    protected function _getServiceToProxy()
    {
        return Environment::class;
    }
}
