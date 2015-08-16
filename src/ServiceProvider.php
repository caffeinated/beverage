<?php
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
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The src directory path
     * @var string
     */
    protected $dir;


    # RESOURCES

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


    # VIEWS

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


    # ASSETS

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


    # CONFIG

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


    # DATABASE

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


    # MISC

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


    # Booting functions

    /**
     * Perform the booting of the service.
     *
     * @return Application
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
                $viewPath             = Str::replace($this->viewsPath, '{dirName}', $dirName);
                $viewsDestinationPath = Str::replace($this->viewsDestinationPath, '{namespace}', $namespace);
                $this->loadViewsFrom($viewPath, $namespace);
                $this->publishes([ $viewPath => base_path($viewsDestinationPath) ]);
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
                $assetPath            = Str::replace($this->assetsPath, '{dirName}', $dirName);
                $assetDestinationPath = Str::replace($this->assetsDestinationPath, '{namespace}', $namespace);
                $this->publishes([ $assetPath => public_path($assetDestinationPath) ], 'public');
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


    # Registration functions

    /**
     * Registers the server in the container.
     *
     * @return Application
     */
    public function register()
    {
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
            $this->app->singleton($binding, $class);
        }

        foreach ($this->aliases as $alias => $full) {
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


    # Path getter convinience functions

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
        return $this->getPath($this->configPath, $fileName);
    }

    /**
     * getMigrationFilePath
     *
     * @param null $path
     * @return string
     */
    public function getDatabasePath($path = null)
    {
        return $this->getPath($this->databasePath, $path, '');
    }

    /**
     * getViewFilePath
     *
     * @param null $path
     * @return string
     */
    public function getResourcesPath($path = null)
    {
        return $this->getPath($this->resourcesPath, $path, '');
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
