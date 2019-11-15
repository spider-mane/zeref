<?php

namespace WebTheory\Zeref\Tests;

use PHPUnit\Framework\TestCase;
use WebTheory\Zeref\Application;

class ApplicationTest extends TestCase
{
    public function canGetAndSetBasePath()
    {
        $app = new Application();
        $dir = dirname(__DIR__);

        $app->setBasePath($dir);

        $this->assertEquals($dir, $app->basePath());
    }
}
