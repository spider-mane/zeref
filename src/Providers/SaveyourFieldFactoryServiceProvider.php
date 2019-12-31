<?php

namespace WebTheory\Zeref\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use WebTheory\Leonidas\Fields\Factory as FormFieldFactory;
use WebTheory\Leonidas\Fields\Managers\Factory as DataManagerFactory;
use WebTheory\Saveyour\Contracts\FieldDataManagerResolverFactoryInterface;
use WebTheory\Saveyour\Contracts\FormFieldResolverFactoryInterface;
use WebTheory\Saveyour\Factories\FieldFactory;
use WebTheory\Zeref\Config;

class SaveyourFieldFactoryServiceProvider extends AbstractServiceProvider
{
    protected $provides = [FieldFactory::class];

    /**
     *
     */
    public function register()
    {
        $container = $this->getLeagueContainer();

        $container->share(FieldFactory::class, function () use ($container) {
            $config = $container->get('config');

            return new FieldFactory(
                $this->createFormFieldFactory($config),
                $this->createDataManagerFactory($config),
                $config->get('wp.option_handlers.field_controller', null)
            );
        });
    }

    /**
     *
     */
    protected function createFormFieldFactory(Config $config): FormFieldResolverFactoryInterface
    {
        $namespaces = [
            'webtheory.acnologia' => 'App\\Forms\\Fields',
            // 'webtheory.zeref' => 'WebTheory\\Zeref\\Forms\\Fields',
        ];

        $factories = $config->get('wp.option_handlers.form_fields', []);

        return new FormFieldFactory($namespaces, $factories);
    }

    /**
     *
     */
    protected function createDataManagerFactory(Config $config): FieldDataManagerResolverFactoryInterface
    {
        $namespaces = [
            'webtheory.acnologia' => 'App\\Forms\\Managers',
            // 'webtheory.zeref' => 'WebTheory\\Zeref\\Forms\\Managers',
        ];

        $factories = $config->get('wp.option_handlers.data_managers', []);

        return new DataManagerFactory($namespaces, $factories);
    }
}
