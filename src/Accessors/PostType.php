<?php

namespace WebTheory\Zeref\Accessors;

use WebTheory\Leonidas\PostType\Factory;
use WebTheory\Zeref\ServiceAccessor;

/**
 * @method static array create(array $postTypes)
 */
class PostType extends ServiceAccessor
{
    /**
     *
     */
    protected static function _getServiceToProxy()
    {
        return Factory::class;
    }
}
