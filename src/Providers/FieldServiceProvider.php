<?php

namespace WebTheory\Zeref\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use WebTheory\Leonidas\Fields\Factory as FormFieldFactory;
use WebTheory\Leonidas\Fields\Managers\Factory as DataManagerFactory;
use WebTheory\Leonidas\Fields\WpAdminField;
use WebTheory\Saveyour\FieldFactory;

class FieldServiceProvider extends AbstractServiceProvider
{
    protected $provides = [FieldFactory::class];

    public function register()
    {
        $container = $this->getLeagueContainer();

        $container->share(FieldFactory::class, function () use ($container) {

            $config = $container->get('config');

            $managers = $config->get('wp.option_handlers.data_managers') ?? [];
            $fields = $config->get('wp.option_handlers.form_fields') ?? [];
            $controller = $config->get('wp.option_handlers.controller') ?? WpAdminField::class;

            $formFieldFactory = new FormFieldFactory([], $fields);
            $dataManagerFactory = new DataManagerFactory([], $managers);

            return new FieldFactory($formFieldFactory, $dataManagerFactory, $controller);
        })->addTag('field');
    }
}
