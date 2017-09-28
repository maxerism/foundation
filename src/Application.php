<?php

namespace Orchestra\Foundation;

use Illuminate\Log\LogServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Orchestra\Routing\RoutingServiceProvider;
use Illuminate\Foundation\Application as BaseApplication;
use Orchestra\Contracts\Foundation\Application as ApplicationContract;

class Application extends BaseApplication implements ApplicationContract
{
    /**
     * The custom vendor path defined by the developer.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));

        $this->register(new RoutingServiceProvider($this));

        $this->register(new LogServiceProvider($this));
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        parent::bindPathsInContainer();

        $this->instance('path.vendor', $this->vendorPath());
    }

    /**
     * Mark the given provider as registered.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     *
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this['events']->fire(get_class($provider), [$provider]);

        parent::markAsRegistered($provider);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->resourcesPath('config'.($path ? DIRECTORY_SEPARATOR.$path : ''));
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath($path = '')
    {
        return ($this->databasePath ?: $this->resourcesPath('database')).($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the application resource files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function resourcesPath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    /**
     * Get the path to the vendor directory.
     *
     * @return string
     */
    public function vendorPath()
    {
        return $this->vendorPath ?: $this->basePath.DIRECTORY_SEPARATOR.'vendor';
    }

    /**
     * Set the vendor directory.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function useVendorPath($path)
    {
        $this->vendorPath = $path;

        $this->instance('path.vendor', $path);

        return $this;
    }

    /**
     * Get the path to the cached extension.json file.
     *
     * @return string
     */
    public function getCachedExtensionServicesPath()
    {
        return $this->basePath('bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'extension.php');
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        $this->booted = false;
        $this->hasBeenBootstrapped = false;

        $this->bootingCallbacks = [];
        $this->bootedCallbacks = [];
        $this->reboundCallbacks = [];
        $this->resolvingCallbacks = [];
        $this->terminatingCallbacks = [];
        $this->afterResolvingCallbacks = [];
        $this->globalResolvingCallbacks = [];

        $this->serviceProviders = [];
        $this->deferredServices = [];
        $this->buildStack = [];
    }
}
