<?php
namespace Caffeinated\Beverage;

use Illuminate\Foundation\AliasLoader;
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
	 * Collection of configuration files.
	 *
	 * @var array
	 */
	protected $configFiles = [];

	/**
	 * @var string
	 */
	protected $dir;

	/**
	 * Path to resources directory, relative to $dir
	 *
	 * @var string
	 */
	protected $resourcesPath = '../resources';

	/**
	 * Collection of service providers.
	 *
	 * @var array
	 */
	protected $providers = [];

	/**
	 * Collection of aliases.
	 *
	 * @var array
	 */
	protected $aliases = [];

	/**
	 * Collection of middleware.
	 *
	 * @var array
	 */
	protected $middleware = [];

	/**
	 * Collection of prepend middleware.
	 *
	 * @var array
	 */
	protected $prependMiddleware = [];

	/**
	 * Collection of route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [];

	/**
	 * Collection of migration directories.
	 *
	 * @var array
	 */
	protected $migrationDirs = [];

	/**
	 * Collection of bound instances.
	 *
	 * @var array
	 */
	protected $provides = [];

	/**
	 * Collection of commands.
	 *
	 * @var array
	 */
	protected $commands = [];

	/**
	 * Perform the post-registration booting of services.
	 *
	 * @return Application
	 */
	public function boot()
	{
		if (isset($this->dir) and isset($this->configFiles) and is_array($this->configFiles)) {
			foreach ($this->configFiles as $filename) {
				$configPath = $this->dir.'/../config/'.$filename.'.php';

				$this->publishes([$configPath => config_path($filename.'.php')], 'config');
			}
		}

		return $this->app;
	}

	/**
	 * Register bindings in the container.
	 *
	 * @return Application
     * @todo fix migrations resourcespath thing, should be removed. check all other paths aswell
	 */
	public function register()
	{
		$router = $this->app->make('router');
		$kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');

		if (isset($this->dir)) {
			foreach ($this->configFiles as $filename) {
				$configPath = Path::join($this->dir, '..', 'config', $filename.'.php');

				$this->mergeConfigFrom($configPath, $filename);
			}

			foreach ($this->migrationDirs as $migrationDir) {
				$migrationPath = Path::join($this->dir, '..', 'database/migrations', $migrationDir);

				$this->publishes([$migrationPath => base_path('/database/migrations')], 'migrations');
			}
		}

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

		foreach ($this->aliases as $alias => $full) {
			$this->app->booting(function() use ($alias, $full) {
				$this->alias($alias, $full);
			});
		}

		if (is_array($this->commands) and count($this->commands) > 0) {
			$this->commands($this->commands);
		}

		return $this->app;
	}

	/**
	 * Register the given alias.
	 *
	 * @param  string  $name
	 * @param  string  $fullyQualifiedName
	 * @return void
	 */
	protected function alias($name, $fullyQualifiedName)
	{
		AliasLoader::getInstance()->alias($name, $fullyQualifiedName);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return $this->provides;
	}
}
