<?php

namespace WebTheory\Zeref;

use Psr\Container\ContainerInterface;

class ServiceAccessor
{
    /**
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * The resolved object instance.
     *
     * @var object
     */
    protected static $resolvedInstance;

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getServiceAccessorRoot()
    {
        return static::resolveServiceAccessorInstance(static::getServiceAccessed());
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getServiceAccessed()
    {
        throw new \RuntimeException(__CLASS__ . ' does not implement ' . __METHOD__ . ' method.');
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param  object|string  $name
     * @return mixed
     */
    protected static function resolveServiceAccessorInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        if (static::$container) {
            return static::$resolvedInstance[$name] = static::$container->get($name);
        }
    }

    /**
     * Clear a resolved facade instance.
     *
     * @param  string  $name
     * @return void
     */
    public static function clearResolvedInstance($name)
    {
        unset(static::$resolvedInstance[$name]);
    }

    /**
     * Clear all of the resolved instances.
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    /**
     * Get the application instance behind the facade.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public static function getServiceAccessorContainer()
    {
        return static::$container;
    }

    /**
     * Set the application instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $container
     * @return void
     */
    public static function setServiceAccessorContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getServiceAccessorRoot();

        if (!$instance) {
            throw new RuntimeException('A service has not been defined.');
        }

        return $instance->$method(...$args);
    }
}
