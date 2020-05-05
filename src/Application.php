<?php

namespace WebTheory\Zeref;

use Dotenv\Dotenv;
use League\Container\Container;
use Psr\Container\ContainerInterface;
use WebTheory\GuctilityBelt\Config;

class Application extends Container
{
    /**
     * @var Application
     */
    protected static $instance;

    /**
     * Base path of the framework.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Web root relative to basePath.
     *
     * @var string
     */
    protected $webRoot;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * __construct
     *
     * @param  mixed $basePath
     * @param  mixed $webRoot
     * @return void
     */
    public function __construct(string $basePath, string $webRoot = 'public')
    {
        parent::__construct();

        $this->webRoot = $webRoot;

        $this->setBasePath($basePath)
            ->addBaseDefinitions()
            ->addBaseServiceProviders();
    }

    /**
     * Add basic definitions to the container.
     */
    protected function addBaseDefinitions()
    {
        static::setInstance($this);

        $this->share('app', $this);
        $this->share(Application::class, $this);
        $this->share(ContainerInterface::class, $this);

        return $this;
    }

    /**
     * Add base service providers.
     */
    protected function addBaseServiceProviders()
    {
        return $this;
    }

    /**
     *
     */
    public function bootstrap()
    {
        $this
            ->loadEnvironment()
            ->bindConfiguration()
            ->addProvidersFromConfig()
            ->setWpConfig()
            ->bindAppToAccessors();

        return $this;
    }

    /**
     *
     */
    protected function loadEnvironment()
    {
        $dotenv = Dotenv::create(
            $this->basePath(),
            $this->getEnvironmentFile()
        );

        $dotenv->load();
        $dotenv->required(['WP_HOME', 'WP_SITEURL']);

        if (!env('DATABASE_URL')) {
            $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
        }

        return $this;
    }

    /**
     *
     */
    protected function bindConfiguration()
    {
        $this->share('config', new Config($this->configPath()));

        return $this;
    }

    /**
     *
     */
    protected function addProvidersFromConfig()
    {
        array_map(
            [$this, 'addServiceProvider'],
            $this->get('config')->get('app.providers')
        );

        return $this;
    }

    /**
     *
     */
    protected function setWpConfig()
    {
        /** @var Config $config */
        $config = $this->get('config');

        $values = $config->get('wp.config');
        $values = array_merge(
            $values['default'] ?? [],
            $values[env('APP_ENV')] ?? []
        );

        foreach ($values as $name => $value) {
            define($name, $value);
        }

        // Remove the wp config properties as they should not be accessed from this context beyond this point
        $config->remove('wp.config');

        return $this;
    }

    /**
     *
     */
    protected function bindAppToAccessors()
    {
        ServiceAccessor::_clearResolvedInstances();
        ServiceAccessor::_setProxyContainer($this);

        return $this;
    }

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     *
     * @return $this
     */
    protected function setBasePath($basePath)
    {
        $this->basePath = realpath($basePath);
        $this->addPathsToContainer();

        return $this;
    }

    /**
     * Bind all of the application paths in the container.
     */
    protected function addPathsToContainer()
    {
        $this->share('path', $this->path());
        $this->share('path.web', $this->webPath());
        $this->share('path.base', $this->basePath());
        $this->share('path.assets', $this->assetsPath());
        $this->share('path.config', $this->configPath());
        $this->share('path.languages', $this->langPath());
        $this->share('path.storage', $this->storagePath());
        $this->share('path.resources', $this->resourcePath());
        $this->share('path.bootstrap', $this->bootstrapPath());
    }

    /**
     * Get the base path of the installation.
     *
     * @param string $path Optional path to append to the base path.
     *
     * @return string
     */
    public function basePath($path = '')
    {
        return realpath($this->basePath . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the application directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function path($path = '')
    {
        return realpath($this->basePath('app') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the path of the web server root.
     *
     * @param string $path
     *
     * @return string
     */
    public function webPath($path = '')
    {
        return realpath($this->basePath($this->webRoot) . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the resources directory path.
     *
     * @param string $path
     *
     * @return string
     */
    public function resourcePath($path = '')
    {
        return realpath($this->basePath('resources') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the path to the resources "languages" directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function langPath($path = '')
    {
        return realpath($this->resourcePath('languages') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the path of the asset.
     *
     * @param string $path
     *
     * @return string
     */
    public function assetsPath($path = '')
    {
        return realpath($this->webPath('assets') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the main application configuration directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return realpath($this->basePath('config') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the storage directory path.
     *
     * @param string $path
     *
     * @return string
     */
    public function storagePath($path = '')
    {
        return realpath($this->basePath('storage') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the bootstrap directory path.
     *
     * @param string $path
     *
     * @return string
     */
    public function bootstrapPath($path = '')
    {
        return realpath($this->basePath('bootstrap') . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->get('config')->get('app.locale');
    }

    /**
     * Set the current application locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale($locale)
    {
        $this->get('config')->set('app.locale', $locale);
    }

    /**
     * Determine if application locale is the given locale.
     *
     * @param  string  $locale
     * @return bool
     */
    public function isLocale($locale)
    {
        return $this->getLocale() == $locale;
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @throws \League\Container\Exception\NotFoundException
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        $file = $this->wordpressPath('.maintenance');

        if (function_exists('wp_installing') && !file_exists($file)) {
            return wp_installing();
        }

        return file_exists($file);
    }

    /**
     * Get the value of instance
     *
     * @return Application
     */
    public static function getInstance(): Application
    {
        return static::$instance;
    }

    /**
     * Set the value of instance
     *
     * @param Application $instance
     *
     * @return self
     */
    protected static function setInstance(Application $instance)
    {
        static::$instance = $instance;
    }

    /**
     * Get the environment file to load during bootstrapping.
     *
     * @return string
     */
    public function getEnvironmentFile(): string
    {
        return $this->environmentFile ?: '.env';
    }

    /**
     * Set the environment file to load during bootstrapping.
     *
     * @param string $environmentFile The environment file to load during bootstrapping.
     *
     * @return self
     */
    public function setEnvironmentFile(string $environmentFile)
    {
        $this->environmentFile = $environmentFile;

        return $this;
    }
}
