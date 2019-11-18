<?php

use Twig\Environment;
use WebTheory\Zeref\ServiceAccessor;

class Twig extends ServiceAccessor
{
    protected function getServiceAccessed()
    {
        return Environment::class;
    }
}
