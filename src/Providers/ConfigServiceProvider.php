<?php

namespace WebTheory\Zeref\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Noodlehaus\Config;
use WebTheory\Zeref\Application;

class ConfigServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = ['config'];

    /**
     *
     */
    public function boot()
    {
        $container = $this->getLeagueContainer();

        $container->share('config', function () use ($container) {

            /** @var Application $container */
            return new Config($this->getConfigFiles($container->configPath()));
        });
    }

    /**
     * retrieves files in a directory
     */
    protected function getConfigFiles(string $dir): array
    {
        $files = [];

        foreach (scandir($dir) as $file) {

            $file = "{$dir}/{$file}";

            if (is_dir($file)) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }
}
