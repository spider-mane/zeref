<?php

namespace WebTheory\Zeref;

use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

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
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     *
     */
    public function __construct(string $basePath)
    {
        parent::__construct();

        $this
            ->setBasePath($basePath)
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
    protected function bindAppToAccessors()
    {
        ServiceAccessor::clearResolvedInstances();
        ServiceAccessor::setServiceAccessorContainer($this);

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
        $this->share('path.themes', $this->themesPath());
        $this->share('path.languages', $this->langPath());
        $this->share('path.content', $this->contentPath());
        $this->share('path.plugins', $this->pluginsPath());
        $this->share('path.storage', $this->storagePath());
        $this->share('path.resources', $this->resourcePath());
        $this->share('path.bootstrap', $this->bootstrapPath());
        $this->share('path.muplugins', $this->muPluginsPath());
        $this->share('path.wordpress', $this->wordpressPath());
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
        return realpath($this->basePath . DS . $path);
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
        return realpath($this->basePath('app') . DS . $path);
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
        return realpath($this->basePath('resources') . DS . $path);
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
        return realpath($this->resourcePath('languages') . DS . $path);
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
        return realpath($this->basePath(WEB_ROOT_DIRNAME) . DS . $path);
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
        return realpath($this->webPath('assets') . DS . $path);
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
        return realpath($this->basePath('config') . DS . $path);
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
        return realpath($this->basePath('storage') . DS . $path);
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
        return realpath($this->basePath('bootstrap') . DS . $path);
    }

    /**
     * Get the WordPress directory path.
     *
     * @param string $path
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     *
     * @return string
     */
    public function wordpressPath($path = '')
    {
        return realpath($this->webPath(WP_CORE_DIRNAME) . DS . $path);
    }

    /**
     * Get the WordPress "content" directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function contentPath($path = '')
    {
        return realpath(WP_CONTENT_DIR . DS . $path);
    }

    /**
     * Get the WordPress "mu-plugins" directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function muPluginsPath($path = '')
    {
        return realpath($this->contentPath('mu-plugins') . DS . $path);
    }

    /**
     * Get the WordPress "plugins" directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function pluginsPath($path = '')
    {
        return realpath($this->contentPath('plugins') . DS . $path);
    }

    /**
     * Get the WordPress "themes" directory.
     *
     * @param string $path
     *
     * @return string
     */
    public function themesPath($path = '')
    {
        return realpath($this->contentPath('themes') . DS . $path);
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
     * Determine if we are running in the console.
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() == 'cli' || php_sapi_name() == 'phpdbg';
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
