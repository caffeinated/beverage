<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Extends Laravel's base service provider with added functionality
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Enables strict checking of provided bindings, aliases and singletons. Checks if the given items are correct. Set to false if
     * @var bool
     */
    protected $strict = true;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The src directory path
     *
     * @var string
     */
    protected $dir;


    /*
     |---------------------------------------------------------------------
     | Resources properties
     |---------------------------------------------------------------------
     |
     */

    /**
     * Path to resources directory, relative to $dir
     *
     * @var string
     */
    protected $resourcesPath = '../resources';

    /**
     * Resource destination path, relative to base_path
     *
     * @var string
     */
    protected $resourcesDestinationPath = 'resources';


    /*
     |---------------------------------------------------------------------
     | Views properties
     |---------------------------------------------------------------------
     |
     */

    /**
     * View destination path, relative to base_path
     *
     * @var string
     */
    protected $viewsDestinationPath = 'resources/views/vendor/{namespace}';

    /**
     * Package views path
     *
     * @var string
     */
    protected $viewsPath = '{resourcesPath}/{dirName}';

    /**
     * A collection of directories in this package containing views.
     * ['dirName' => 'namespace']
     *
     * @var array
     */
    protected $viewDirs = [ /* 'dirName' => 'namespace' */ ];


    /*
     |---------------------------------------------------------------------
     | Assets properties
     |---------------------------------------------------------------------
     |
     */

    /**
     * Assets destination path, relative to public_path
     *
     * @var string
     */
    protected $assetsDestinationPath = 'vendor/{namespace}';

    /**
     * Package views path
     *
     * @var string
     */
    protected $assetsPath = '{resourcesPath}/{dirName}';

    /**
     * A collection of directories in this package containing assets.
     * ['dirName' => 'namespace']
     *
     * @var array
     */
    protected $assetDirs = [ /* 'dirName' => 'namespace' */ ];


    /*
     |---------------------------------------------------------------------
     | Configuration properties
     |---------------------------------------------------------------------
     |
     */

    /**
     * Collection of configuration files.
     *
     * @var array
     */
    protected $configFiles = [ ];

    /**
     * Path to the config directory, relative to $dir
     *
     * @var string
     */
    protected $configPath = '../config';


    /*
     |---------------------------------------------------------------------
     | Database properties
     |---------------------------------------------------------------------
     |
     */

    /**
     * Path to the migration destination directory, relative to database_path
     *
     * @var string
     */
    protected $migrationDestinationPath = 'migrations';

    /**
     * Path to the seeds destination directory, relative to database_path
     *
     * @var string
     */
    protected $seedsDestinationPath = 'seeds';

    /**
     * Path to database directory, relative to $dir
     *
     * @var string
     */
    protected $databasePath = '../database';

    /**
     * Array of directory names/paths relative to $databasePath containing seed files.
     *
     * @var array
     */
    protected $seedDirs = [ /* 'dirName', */ ];

    /**
     * Array of directory names/paths relative to $databasePath containing migration files.
     *
     * @var array
     */
    protected $migrationDirs = [ /* 'dirName', */ ];


    /*
     |---------------------------------------------------------------------
     | Miscelanious properties
     |---------------------------------------------------------------------
     |
     */

    /**
     * Collection of service providers.
     *
     * @var array
     */
    protected $providers = [ ];

    protected $bindings = [ ];

    protected $singletons = [ ];


    /**
     * Collection of aliases.
     *
     * @var array
     */
    protected $aliases = [ ];

    /**
     * Collection of middleware.
     *
     * @var array
     */
    protected $middleware = [ ];

    /**
     * Collection of prepend middleware.
     *
     * @var array
     */
    protected $prependMiddleware = [ ];

    /**
     * Collection of route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [ ];

    /**
     * Collection of bound instances.
     *
     * @var array
     */
    protected $provides = [ ];

    /**
     * Collection of commands.
     *
     * @var array
     */
    protected $commands = [ ];


    /*
     |---------------------------------------------------------------------
     | Booting functions
     |---------------------------------------------------------------------
     |
     */

    /**
     * Perform the booting of the service.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function boot()
    {
        $this->bootConfigFiles();
        $this->bootViews();
        $this->bootAssets();
        $this->bootMigrations();
        $this->bootSeeds();

        return $this->app;
    }

    /**
     * Adds the config files defined in $configFiles to the publish procedure.
     * Can be overriden to adjust default functionality
     */
    protected function bootConfigFiles()
    {
        if (isset($this->dir) and isset($this->configFiles) and is_array($this->configFiles)) {
            foreach ($this->configFiles as $filename) {
                $this->publishes([ $this->getConfigFilePath($filename) => config_path($filename . '.php') ], 'config');
            }
        }
    }

    /**
     * Adds the view directories defined in $viewDirs to the publish procedure.
     * Can be overriden to adjust default functionality
     */
    protected function bootViews()
    {
        if (isset($this->dir) and isset($this->viewDirs) and is_array($this->viewDirs)) {
            foreach ($this->viewDirs as $dirName => $namespace) {
                $viewPath             = $this->getViewsPath($dirName);
                $viewsDestinationPath = Str::replace($this->viewsDestinationPath, '{namespace}', $namespace);
                $this->loadViewsFrom($viewPath, $namespace);
                $this->publishes([ $viewPath => base_path($viewsDestinationPath) ], 'views');
            }
        }
    }

    /**
     * Adds the asset directories defined in $assetDirs to the publish procedure.
     * Can be overriden to adjust default functionality
     */
    protected function bootAssets()
    {
        if (isset($this->dir) and isset($this->assetDirs) and is_array($this->assetDirs)) {
            foreach ($this->assetDirs as $dirName => $namespace) {
                $assetDestinationPath = Str::replace($this->assetsDestinationPath, '{namespace}', $namespace);
                $this->publishes([ $this->getAssetsPath($dirName) => public_path($assetDestinationPath) ], 'public');
            }
        }
    }

    /**
     * Adds the migration directories defined in $migrationDirs to the publish procedure.
     * Can be overriden to adjust default functionality
     */
    protected function bootMigrations()
    {
        if (isset($this->dir) and isset($this->migrationDirs) and is_array($this->migrationDirs)) {
            foreach ($this->migrationDirs as $dirPath) {
                $this->publishes([ $this->getDatabasePath($dirPath) => database_path($this->migrationDestinationPath) ], 'migrations');
            }
        }
    }

    /**
     * Adds the seed directories defined in $seedDirs to the publish procedure.
     * Can be overriden to adjust default functionality
     */
    protected function bootSeeds()
    {
        if (isset($this->dir) and isset($this->seedDirs) and is_array($this->seedDirs)) {
            foreach ($this->seedDirs as $dirPath) {
                $this->publishes([ $this->getDatabasePath($dirPath) => database_path($this->migrationDestinationPath) ], 'migrations');
            }
        }
    }


    /*
     |---------------------------------------------------------------------
     | Registration functions
     |---------------------------------------------------------------------
     |
     */

    /**
     * Registers the server in the container.
     *
     * @return \Illuminate\Foundation\Application
     * @throws \Exception
     */
    public function register()
    {
        // Auto register the beverage service provider
        if (! get_class($this) == BeverageServiceProvider::class) {
            $this->app->register(BeverageServiceProvider::class);
        }

        $this->viewsPath  = Str::replace($this->viewsPath, '{resourcesPath}', $this->getResourcesPath());
        $this->assetsPath = Str::replace($this->assetsPath, '{resourcesPath}', $this->getResourcesPath());

        $router = $this->app->make('router');
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');

        $this->registerConfigFiles();

        foreach ($this->prependMiddleware as $middleware) {
            $kernel->prependMiddleware($middleware);
        }

        foreach ($this->middleware as $middleware) {
            $kernel->pushMiddleware($middleware);
        }

        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->middleware($key, $middleware);
        }

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }

        foreach ($this->bindings as $binding => $class) {
            $this->app->bind($binding, $class);
        }

        foreach ($this->singletons as $binding => $class) {
            if ($this->strict && ! class_exists($class) && ! interface_exists($class)) {
                throw new \Exception(get_called_class() . ": Could not find alias class [{$class}]. This exception is only thrown when \$strict checking is enabled");
            }
            $this->app->singleton($binding, $class);
        }

        foreach ($this->aliases as $alias => $full) {
            if ($this->strict && ! class_exists($full) && ! interface_exists($full)) {
                throw new \Exception(get_called_class() . ": Could not find alias class [{$full}]. This exception is only thrown when \$strict checking is enabled");
            }
            $this->app->alias($alias, $full);
        }

        if (is_array($this->commands) and count($this->commands) > 0) {
            $this->commands($this->commands);
        }

        return $this->app;
    }

    /**
     * Merges all defined config files defined in $configFiles.
     * Can be overriden to adjust default functionality
     */
    protected function registerConfigFiles()
    {
        if (isset($this->dir) and isset($this->configFiles) and is_array($this->configFiles)) {
            foreach ($this->configFiles as $filename) {
                $this->mergeConfigFrom($this->getConfigFilePath($filename), $filename);
            }
        }
    }


    /*
     |---------------------------------------------------------------------
     | Path getter convinience functions
     |---------------------------------------------------------------------
     |
     */

    /**
     * getFilePath
     *
     * @param        $relativePath
     * @param null   $fileName
     * @param string $ext
     * @return string
     */
    public function getPath($relativePath, $fileName = null, $ext = '.php')
    {
        $path = Path::join($this->dir, $relativePath);

        return is_null($fileName) ? $path : Path::join($path, $fileName . $ext);
    }

    /**
     * getConfigFilePath
     *
     * @param null $fileName
     * @return string
     */
    public function getConfigFilePath($fileName = null)
    {
        return realpath($this->getPath($this->configPath, $fileName));
    }

    /**
     * getMigrationFilePath
     *
     * @param null $path
     * @return string
     */
    public function getDatabasePath($path = null)
    {
        return realpath($this->getPath($this->databasePath, $path, ''));
    }

    /**
     * getViewFilePath
     *
     * @param null $path
     * @return string
     */
    public function getResourcesPath($path = null)
    {
        return realpath($this->getPath($this->resourcesPath, $path, ''));
    }

    /**
     * getAssetsPath
     *
     * @param null $dirName
     * @return string
     */
    public function getAssetsPath($dirName)
    {
        return realpath(Str::replace($this->assetsPath, '{dirName}', $dirName));
    }

    /**
     * getViewsPath
     *
     * @param null $path
     * @return string
     */
    public function getViewsPath($dirName)
    {
        return realpath(Str::replace($this->viewsPath, '{dirName}', $dirName));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        $provides = [ ];

        foreach ($this->providers as $provider) {
            $instance = $this->app->resolveProviderClass($provider);

            $provides = array_merge($provides, $instance->provides());
        }

        return $provides;
    }
}
