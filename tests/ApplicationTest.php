<?php

namespace WebTheory\Zeref\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use WebTheory\Zeref\Application;
use WebTheory\Zeref\ServiceAccessor;

class ApplicationTest extends TestCase
{
    public function testCanGetAndSetBasePath()
    {
        $app = new Application();
        $dir = APP_ROOT_DIR;

        $app->setBasePath($dir);

        $this->assertEquals($dir, $app->basePath());
    }

    public function testPathsAreAccurate()
    {
        $base = APP_ROOT_DIR;
        $app = new Application($base);

        $bootstrap = realpath($base . '/bootstrap');
        $config = realpath($base . '/config');
        $path = realpath($base . '/app');
        $resources = realpath($base . '/resources');
        $storage = realpath($base . '/storage');
        $web = realpath($base . '/public');

        $assets = realpath($base . '/public/assets');
        $languages = realpath($base . '/resources/languages');

        $content = realpath($base . '/public/app');
        $wordpress = realpath($base . '/public/wp');

        $muPlugins = realpath($base . '/public/app/mu-plugins');
        $plugins = realpath($base . '/public/app/plugins');
        $themes = realpath($base . '/public/app/themes');

        // begin tests
        $this->assertEquals($base, $app->basePath());
        $this->assertEquals($bootstrap, $app->bootstrapPath());
        $this->assertEquals($config, $app->configPath());
        $this->assertEquals($path, $app->path());
        $this->assertEquals($resources, $app->resourcePath());
        $this->assertEquals($storage, $app->storagePath());
        $this->assertEquals($web, $app->webPath());

        $this->assertEquals($assets, $app->assetsPath());
        $this->assertEquals($languages, $app->langPath());

        $this->assertEquals($content, $app->contentPath());
        $this->assertEquals($wordpress, $app->wordpressPath());

        $this->assertEquals($muPlugins, $app->muPluginsPath());
        $this->assertEquals($plugins, $app->pluginsPath());
        $this->assertEquals($plugins, $app->pluginsPath());
        $this->assertEquals($themes, $app->themesPath());
    }

    public function testBootstrapsSuccessfully()
    {
        $app = new Application(APP_ROOT_DIR);
        $app->bootstrap();
        $services = [\Twig\Twig::class, \Swift_Mailer::class];

        $envTest = 'http://example.test';
        $configTest = 'value';
        $providerTest = array_map(function ($service) use ($app) {
            return $app->has($service) ? 1 : 0;
        }, $services);
        $accessorTest = ServiceAccessor::getServiceAccessorApplication();

        $this->assertEquals($envTest, env('WP_HOME'));
        $this->assertEquals($configTest, $app->get('config')->get('app.test'));
        $this->assertCount(count($services), array_filter($providerTest));
        $this->assertEquals($app, $accessorTest);
    }
}
