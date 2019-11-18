<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Leonidas\Taxonomy\Factory;
use WebTheory\Zeref\ServiceAccessor;

/**
 * @method static array create(array $taxonomies)
 */
class Taxonomy extends ServiceAccessor
{
    protected static function getServiceAccessed()
    {
        return Factory::class;
    }
}
