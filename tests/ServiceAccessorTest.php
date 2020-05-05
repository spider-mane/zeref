<?php

namespace WebTheory\Zeref\Tests;

use PHPUnit\Framework\TestCase;
use WebTheory\Zeref\Application;
use WebTheory\Zeref\ServiceAccessor;

class ServiceAccessorTest extends TestCase
{
    public function testAccessorsCanGetDefinedService()
    {
        $this->expectNotice();

        $app = new Application(APP_ROOT_DIR);
        $app->bootstrap();

        $appAccessor = new class extends ServiceAccessor
        {
            public static function _getServiceToProxy()
            {
                return 'app';
            }
        };

        $configAccessor = new class extends ServiceAccessor
        {
            public static function _getServiceToProxy()
            {
                return 'config';
            }
        };

        ServiceAccessor::_clearResolvedInstances();
        ServiceAccessor::_setProxyContainer($app);

        $this->assertEquals($app, $appAccessor::get('app'));
        $this->assertEquals(
            $app->get('config')->get('app.test'),
            $configAccessor::get('app.test')
        );
    }
}
