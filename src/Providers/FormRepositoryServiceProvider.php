<?php

use League\Container\ServiceProvider\AbstractServiceProvider;
use WebTheory\Zeref\Forms\FormRepository;

class FormRepositoryServiceProvider extends AbstractServiceProvider
{
    protected $provides = [FormRepository::class];

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $container = $this->getLeagueContainer();

        $container->share(FormRepository::class, function () use ($container) {
            return new FormRepository;
        });
    }
}
