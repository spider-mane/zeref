<?php

namespace WebTheory\Zeref\Accessors;

use Backalley\WordPress\PostType\Factory;
use WebTheory\Zeref\ServiceAccessor;

/**
 *  @method static array create(array $postTypes)
 */
class PostType extends ServiceAccessor
{
    protected static function getServiceAccessed()
    {
        return Factory::class;
    }
}
